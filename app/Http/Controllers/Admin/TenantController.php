<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::query()->select(sprintf('%s.*', (new User)->getTable()));
            $table = DataTables::of($query);

            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $crudRoutePart = 'tenants';

                return view('partials.datatableActions', compact('crudRoutePart', 'row'));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : "";
            });
            $table->editColumn('domain', function ($row) {
                return $row->domain ? route('tenant', $row) : "";
            });

            $table->rawColumns(['actions']);

            return $table->make(true);
        }

        return view('admin.tenants.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTenantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTenantRequest $request)
    {
        User::create($request->only([
            'name', 'email', 'domain',
        ]));

        return redirect()->route('admin.tenants.index')->withMessage('Tenant has been created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $tenant
     * @return \Illuminate\Http\Response
     */
    public function show(User $tenant)
    {
        return view('admin.tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $tenant
     * @return \Illuminate\Http\Response
     */
    public function edit(User $tenant)
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTenantRequest $request
     * @param \App\User $tenant
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTenantRequest $request, User $tenant)
    {
        $tenant->update([
            'name', 'email', 'domain'
        ]);

        return redirect()->route('admin.tenants.index')->withMessage('Tenant has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $tenant
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $tenant)
    {
        $tenant->delete();

        return redirect()->back()->withMessage('Tenant has been deleted successfully');
    }
}
