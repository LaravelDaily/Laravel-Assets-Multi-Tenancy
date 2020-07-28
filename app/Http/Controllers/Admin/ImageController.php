<?php

namespace App\Http\Controllers\Admin;

use App\Asset;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ImageController extends Controller
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
        abort_if(Gate::denies('image_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Image::query()
                ->select(sprintf('%s.*', (new Image)->getTable()))
                ->with('asset')
                ->when($request->input('asset_id'), function ($query) use ($request) {
                    $query->where('asset_id', $request->input('asset_id'));
                });
            $table = DataTables::of($query);

            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $crudRoutePart    = 'images';
                $permissionPrefix = 'image_management_';

                return view('partials.datatableActions', compact('crudRoutePart', 'row', 'permissionPrefix'));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('asset', function ($row) {
                return $row->asset ? $row->asset->name : "";
            });
            $table->editColumn('image', function ($row) {
                if ($photo = $row->image) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }

                return '';
            });

            $table->rawColumns(['actions', 'image']);

            return $table->make(true);
        }

        return view('admin.images.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('image_management_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $assets = Asset::all()->pluck('name', 'id');

        return view('admin.images.create', compact('assets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreImageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImageRequest $request)
    {
        $image = Image::create($request->validated());

        if ($request->input('image', false)) {
            $image->addMedia(storage_path('tmp/uploads/' . $request->input('image')))->toMediaCollection('image');
        }

        return redirect()->route('admin.images.index')->withMessage('Image has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        $image->load('asset');

        return view('admin.images.show', compact('image'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        abort_if(Gate::denies('image_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $assets = Asset::all()->pluck('name', 'id');

        return view('admin.images.edit', compact('image', 'assets'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateImageRequest $request
     * @param \App\Image $image
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateImageRequest $request, Image $image)
    {
        $image->update($request->validated());

        if ($request->input('image', false)) {
            if (!$image->image || $request->input('image') !== $image->image->file_name) {
                if ($image->image) {
                    $image->image->delete();
                }

                $image->addMedia(storage_path('tmp/uploads/' . $request->input('image')))->toMediaCollection('image');
            }
        } elseif ($image->image) {
            $image->image->delete();
        }

        return redirect()->route('admin.images.index')->withMessage('Image has been edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        abort_if(Gate::denies('image_management_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $image->delete();

        return redirect()->back()->withMessage('Image has been deleted successfully');
    }
}
