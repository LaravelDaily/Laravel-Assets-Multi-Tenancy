<?php

namespace App\Http\Controllers\Admin;

use App\AssetGroup;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetGroupRequest;
use App\Http\Requests\UpdateAssetGroupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AssetGroupController extends Controller
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
        abort_if(Gate::denies('asset_group_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AssetGroup::query()
                ->select(sprintf('%s.*', (new AssetGroup)->getTable()))
                ->with('parent');
            $table = DataTables::of($query);

            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $crudRoutePart    = 'asset-groups';
                $permissionPrefix = 'asset_group_management_';

                return view('partials.datatableActions', compact('crudRoutePart', 'row', 'permissionPrefix'));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('name', function ($row) {
                $field = "";
                if ($row->parent) {
                    $field = $row->parent->name . ' <i class="fas fa-arrow-right"></i> ';
                }
                $field .= $row->name ? $row->name : "";
                return $field;
            });

            $table->rawColumns(['name', 'actions']);

            return $table->make(true);
        }

        return view('admin.assetGroups.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('asset_group_management_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parentGroups = AssetGroup::whereNull('parent_id')->pluck('name', 'id');

        return view('admin.assetGroups.create', compact('parentGroups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAssetGroupRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAssetGroupRequest $request)
    {
        $assetGroup = AssetGroup::create($request->validated());

        return redirect()->route('admin.asset-groups.index')->withMessage('Asset group has been created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AssetGroup  $assetGroup
     * @return \Illuminate\Http\Response
     */
    public function show(AssetGroup $assetGroup)
    {
        abort_if(Gate::denies('asset_group_management_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $assetGroup->load('parent');

        return view('admin.assetGroups.show', compact('assetGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AssetGroup  $assetGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(AssetGroup $assetGroup)
    {
        abort_if(Gate::denies('asset_group_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parentGroups = AssetGroup::whereNull('parent_id')
            ->where('id', '!=', $assetGroup->id)
            ->pluck('name', 'id');

        return view('admin.assetGroups.edit', compact('assetGroup', 'parentGroups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAssetGroupRequest $request
     * @param \App\AssetGroup $assetGroup
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssetGroupRequest $request, AssetGroup $assetGroup)
    {
        $assetGroup->update($request->validated());

        return redirect()->route('admin.asset-groups.index')->withMessage('Asset group has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AssetGroup  $assetGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssetGroup $assetGroup)
    {
        abort_if(Gate::denies('asset_group_management_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $assetGroup->delete();

        return redirect()->back()->withMessage('Asset group has been deleted successfully');
    }
}
