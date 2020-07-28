<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = auth()->user();

        return view('admin.profile', compact('user'));
    }

    /**
     * Update profile.
     *
     * @param UpdateProfileRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request)
    {
        $data = [
            'name' => $request->input('name'),
        ];

        if ($request->input('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        auth()->user()->update($data);

        return redirect()->back()->withMessage('Profile has been updated successfully');
    }
}
