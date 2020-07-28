<?php

namespace App\Http\Controllers\Admin;

use App\Asset;
use App\Document;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DocumentController extends Controller
{
    use MediaUploadingTrait;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('document_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Document::query()
                ->select(sprintf('%s.*', (new Document)->getTable()))
                ->with('asset')
                ->when($request->input('asset_id'), function ($query) use ($request) {
                    $query->where('asset_id', $request->input('asset_id'));
                });
            $table = DataTables::of($query);

            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $crudRoutePart    = 'documents';
                $permissionPrefix = 'document_management_';

                return view('partials.datatableActions', compact('crudRoutePart', 'row', 'permissionPrefix'));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('asset', function ($row) {
                return $row->asset ? $row->asset->name : "";
            });
            $table->editColumn('document', function ($row) {
                return $row->document ? '<a href="' . $row->document->getUrl() . '" target="_blank">Download File</a>' : '';
            });

            $table->rawColumns(['actions', 'document']);

            return $table->make(true);
        }

        return view('admin.documents.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('document_management_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $assets = Asset::all()->pluck('name', 'id');

        return view('admin.documents.create', compact('assets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDocumentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDocumentRequest $request)
    {
        $document = Document::create($request->validated());

        if ($request->input('document', false)) {
            $document->addMedia(storage_path('tmp/uploads/' . $request->input('document')))->toMediaCollection('document');
        }

        return redirect()->route('admin.documents.index')->withMessage('Document has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        $document->load('asset');

        return view('admin.documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        abort_if(Gate::denies('document_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $assets = Asset::all()->pluck('name', 'id');

        return view('admin.documents.edit', compact('document', 'assets'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDocumentRequest $request
     * @param \App\Document $document
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        $document->update($request->validated());

        if ($request->input('document', false)) {
            if (!$document->document || $request->input('document') !== $document->document->file_name) {
                if ($document->document) {
                    $document->document->delete();
                }

                $document->addMedia(storage_path('tmp/uploads/' . $request->input('document')))->toMediaCollection('document');
            }
        } elseif ($document->document) {
            $document->document->delete();
        }

        return redirect()->route('admin.documents.index')->withMessage('Document has been edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        abort_if(Gate::denies('document_management_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $document->delete();

        return redirect()->back()->withMessage('Document has been deleted successfully');
    }
}
