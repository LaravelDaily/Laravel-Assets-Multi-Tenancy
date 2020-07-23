<?php

namespace App\Http\Controllers\Admin;

use App\Asset;
use App\AssetGroup;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AssetController extends Controller
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
        abort_if(Gate::denies('asset_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Asset::query()
                ->select(sprintf('%s.*', (new Asset)->getTable()))
                ->with('subGroup.parent');
            $table = DataTables::of($query);

            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $crudRoutePart    = 'assets';
                $permissionPrefix = 'asset_management_';

                return view('partials.datatableActions', compact('crudRoutePart', 'row', 'permissionPrefix'));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });
            $table->editColumn('serial_number', function ($row) {
                return $row->serial_number ? $row->serial_number : "";
            });
            $table->editColumn('price', function ($row) {
                return $row->price ? $row->price : "";
            });
            $table->editColumn('warranty_expiry_date', function ($row) {
                return $row->warranty_expiry_date ? $row->warranty_expiry_date : "";
            });
            $table->editColumn('group', function ($row) {
                return optional($row->subGroup)->parent ? $row->subGroup->parent->name : "";
            });
            $table->editColumn('sub_group', function ($row) {
                return $row->subGroup ? $row->subGroup->name : "";
            });

            $table->rawColumns(['actions']);

            return $table->make(true);
        }

        return view('admin.assets.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('asset_management_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parentGroups = AssetGroup::whereNull('parent_id')->with('children')->get();
        $subGroups    = $parentGroups->mapWithKeys(function ($item) {
            return [
                $item->id => $item->children->pluck('name', 'id')
            ];
        });
        $parentGroups = $parentGroups->pluck('name', 'id');

        return view('admin.assets.create', compact('parentGroups', 'subGroups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAssetRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAssetRequest $request)
    {
        abort_if(Gate::denies('asset_management_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Asset::create($request->validated());

        return redirect()->route('admin.assets.index')->withMessage('Asset has been created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function show(Asset $asset)
    {
        abort_if(Gate::denies('asset_management_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $asset->load('subGroup.parent');

        return view('admin.assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function edit(Asset $asset)
    {
        abort_if(Gate::denies('asset_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $asset->load('subGroup');
        $parentGroups = AssetGroup::whereNull('parent_id')->with('children')->get();
        $subGroups    = $parentGroups->mapWithKeys(function ($item) {
            return [
                $item->id => $item->children->pluck('name', 'id')
            ];
        });
        $parentGroups = $parentGroups->pluck('name', 'id');

        return view('admin.assets.edit', compact('asset', 'parentGroups', 'subGroups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAssetRequest $request
     * @param \App\Asset $asset
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $asset->update($request->validated());

        return redirect()->route('admin.assets.index')->withMessage('Asset has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asset $asset)
    {
        abort_if(Gate::denies('asset_management_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $asset->delete();

        return redirect()->back()->withMessage('Asset has been deleted successfully');
    }
}
