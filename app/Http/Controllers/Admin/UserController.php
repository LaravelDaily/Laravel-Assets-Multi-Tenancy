<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Notifications\UserInvitation;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
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
        abort_if(Gate::denies('user_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = User::query()
                        ->select(sprintf('%s.*', (new User)->getTable()))
                        ->where('tenant_id', auth()->id());
            $table = DataTables::of($query);

            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $crudRoutePart    = 'users';
                $permissionPrefix = 'user_management_';

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
            $table->editColumn('role', function ($row) {
                return $row->roles->first() ? $row->roles->first()->title : "";
            });

            $table->rawColumns(['actions']);

            return $table->make(true);
        }

        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('user_management_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->only([
            'name', 'email',
        ]));

        $user->roles()->attach($request->input('role_id'));

        $url = URL::signedRoute('invitation', $user);

        $user->notify(new UserInvitation($url));

        return redirect()->route('admin.users.index')->withMessage('User has been created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $user
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        abort_if(Gate::denies('user_management_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::where('tenant_id', auth()->id())->findOrFail($user);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $user
     * @return \Illuminate\Http\Response
     */
    public function edit($user)
    {
        abort_if(Gate::denies('user_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::where('tenant_id', auth()->id())->findOrFail($user);

        $roles = Role::pluck('title', 'id');

        $user->load('roles');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param int $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $user)
    {
        $user = User::where('tenant_id', auth()->id())->findOrFail($user);

        $user->update($request->only([
            'name', 'email'
        ]));

        if (optional($user->roles->first())->id !== $request->input('role_id', 3)) {
            $user->roles()->sync($request->input('role_id', 3));
        }

        return redirect()->route('admin.users.index')->withMessage('User has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($user)
    {
        abort_if(Gate::denies('user_management_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        User::where('tenant_id', auth()->id())
            ->findOrFail($user)
            ->delete();

        return redirect()->back()->withMessage('User has been deleted successfully');
    }
}
