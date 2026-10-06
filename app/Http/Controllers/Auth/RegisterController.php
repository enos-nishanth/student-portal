<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterStudentRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(RegisterStudentRequest $request)
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

        auth()->login($user);

        return redirect()->route('dashboard');
    }
}