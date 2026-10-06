<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterStudentRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterStudentRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, &$user) {

            $profileImage = $request->file('profile_image')
                ->store('profile-images', 'public');

            $resume = $request->file('resume')
                ->store('resumes', 'public');

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'], 
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'student',
            ]);

            Student::create([
                'user_id' => $user->id,
                'dob' => $validated['dob'],
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'pincode' => $validated['pincode'],
                'qualification' => $validated['qualification'],
                'college' => $validated['college'],
                'graduation_year' => $validated['graduation_year'],
                'skills' => $validated['skills'] ?? null,
                'profile_image' => $profileImage,
                'resume' => $resume,
            ]);
        });

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful.',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = $credentials['login'];

        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

        $user = User::where($field, $login)->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'The email/phone or password is incorrect.'
            ], 401);
        }

        $isAdmin = $user->adminAccess()->exists();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'token_type' => 'Bearer',
            'is_admin' => $isAdmin,
            'user' => $user,
        ], 200);
    }
}