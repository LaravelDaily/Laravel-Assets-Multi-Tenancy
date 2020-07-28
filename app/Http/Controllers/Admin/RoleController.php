<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
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
        abort_if(Gate::denies('role_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Role::query()->select(sprintf('%s.*', (new Role)->getTable()));
            $table = DataTables::of($query);

            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $crudRoutePart    = 'roles';
                $permissionPrefix = 'role_management_';

                return view('partials.datatableActions', compact('crudRoutePart', 'row', 'permissionPrefix'));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : "";
            });

            $table->rawColumns(['actions']);

            return $table->make(true);
        }

        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('role_management_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Role::withoutGlobalScopes()
            ->findOrFail(3)
            ->permissions()
            ->pluck('title', 'id');

        return view('admin.roles.create', compact('permissions'));
    }

    /**w
     * Store a newly created resource in storage.
     *
     * @param StoreRoleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::create($request->only([
            'title',
        ]));

        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index')->withMessage('Role has been created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        abort_if(Gate::denies('role_management_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->load('permissions');

        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        abort_if(Gate::denies('role_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->load('permissions');
        $permissions = Role::withoutGlobalScopes()
            ->findOrFail(3)
            ->permissions()
            ->pluck('title', 'id');

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRoleRequest $request
     * @param \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->load('permissions');

        $role->update($request->only([
            'title',
        ]));

        if ($role->permissions->pluck('id')->toArray() != $request->input('permissions', [])) {
            $role->permissions()->sync($request->input('permissions', []));
        }

        return redirect()->route('admin.roles.index')->withMessage('Role has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        abort_if(Gate::denies('role_management_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->delete();

        return redirect()->back()->withMessage('Role has been deleted successfully');
    }
}
