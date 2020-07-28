<?php

namespace App\Http\Controllers\Admin;

use App\Asset;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('note_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Note::query()
                ->select(sprintf('%s.*', (new Note)->getTable()))
                ->with('asset')
                ->when($request->input('asset_id'), function ($query) use ($request) {
                    $query->where('asset_id', $request->input('asset_id'));
                });
            $table = DataTables::of($query);

            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $crudRoutePart    = 'notes';
                $permissionPrefix = 'note_management_';

                return view('partials.datatableActions', compact('crudRoutePart', 'row', 'permissionPrefix'));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('asset', function ($row) {
                return $row->asset ? $row->asset->name : "";
            });
            $table->editColumn('text', function ($row) {
                return $row->text ? Str::limit($row->text, 50) : "";
            });

            $table->rawColumns(['actions']);

            return $table->make(true);
        }

        return view('admin.notes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('note_management_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $assets = Asset::all()->pluck('name', 'id');

        return view('admin.notes.create', compact('assets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNoteRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNoteRequest $request)
    {
        $note = Note::create($request->validated());

        return redirect()->route('admin.notes.index')->withMessage('Note has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        $note->load('asset');

        return view('admin.notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        abort_if(Gate::denies('note_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $assets = Asset::all()->pluck('name', 'id');

        return view('admin.notes.edit', compact('note', 'assets'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateNoteRequest $request
     * @param \App\Note $note
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        $note->update($request->validated());

        return redirect()->route('admin.notes.index')->withMessage('Note has been edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        abort_if(Gate::denies('note_management_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $note->delete();

        return redirect()->back()->withMessage('Note has been deleted successfully');
    }
}
