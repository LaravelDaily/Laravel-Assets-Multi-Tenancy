<?php

namespace App\Http\Controllers;

use App\User;

class TenantController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return void
     */
    public function show(User $user)
    {
        return redirect()->to('/home');
    }

    /**
     * Tenant invitation
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function invitation(User $user)
    {
        if (!request()->hasValidSignature() || $user->password) {
            abort(401);
        }

        auth()->login($user);

        return redirect()->route('home');
    }
}
