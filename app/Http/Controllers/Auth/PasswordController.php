<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePasswordRequest;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for setting a password.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->password) {
            return redirect()->route('home');
        }

        return view('auth.password');
    }

    /**
     * Store a password of user.
     *
     * @param StorePasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePasswordRequest $request)
    {
        $redirect = redirect()->route('home');
        $user = auth()->user();

        if (!$user->password) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            $redirect->withMessage('Password has been set successfully');
        }

        return $redirect;
    }
}
