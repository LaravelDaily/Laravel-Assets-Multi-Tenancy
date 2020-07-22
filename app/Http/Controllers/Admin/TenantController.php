<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use App\Notifications\TenantInvitation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;
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
        abort_if(Gate::denies('tenant_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = User::query()
                ->select(sprintf('%s.*', (new User)->getTable()))
                ->whereNotNull('domain');
            $table = DataTables::of($query);

            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $crudRoutePart    = 'tenants';
                $permissionPrefix = 'tenant_management_';

                return view('partials.datatableActions', compact('crudRoutePart', 'row', 'permissionPrefix'));
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
                return $row->domain ? route('tenant.show', $row) : "";
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
        abort_if(Gate::denies('tenant_management_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        $user = User::create($request->only([
            'name', 'email', 'domain',
        ]));

        $user->roles()->attach(2);

        $url = URL::signedRoute('invitation', $user);

        $user->notify(new TenantInvitation($url));

        return redirect()->route('admin.tenants.index')->withMessage('Tenant has been created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $tenant
     * @return \Illuminate\Http\Response
     */
    public function show($tenant)
    {
        abort_if(Gate::denies('tenant_management_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tenant = User::whereNotNull('domain')->findOrFail($tenant);

        return view('admin.tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $tenant
     * @return \Illuminate\Http\Response
     */
    public function edit($tenant)
    {
        abort_if(Gate::denies('tenant_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tenant = User::whereNotNull('domain')->findOrFail($tenant);

        return view('admin.tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTenantRequest $request
     * @param int $tenant
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTenantRequest $request, $tenant)
    {
        $tenant = User::whereNotNull('domain')->findOrFail($tenant);

        $tenant->update($request->only([
            'name', 'email', 'domain'
        ]));

        return redirect()->route('admin.tenants.index')->withMessage('Tenant has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $tenant
     * @return \Illuminate\Http\Response
     */
    public function destroy($tenant)
    {
        abort_if(Gate::denies('tenant_management_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        User::whereNotNull('domain')
            ->findOrFail($tenant)
            ->delete();

        return redirect()->back()->withMessage('Tenant has been deleted successfully');
    }

    public function suspend($tenant)
    {
        abort_if(Gate::denies('tenant_management_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tenant = User::whereNotNull('domain')->findOrFail($tenant);

        $tenant->update([
            'is_suspended' => !$tenant->is_suspended
        ]);

        return redirect()->back()->withMessage('Tenant has been suspended successfully');
    }
}
