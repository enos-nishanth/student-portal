<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterAdminRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminRegisterController extends Controller
{
    public function store(RegisterAdminRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ]);

        auth()->login($user);

        return redirect()->route('admin.dashboard');
    }
}