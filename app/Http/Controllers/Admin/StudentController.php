<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateStudentRequest;

class StudentController extends Controller
{

    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $students = Student::with('user')
            ->when($search !== '', function ($query) use ($search) {

                $query->where(function ($query) use ($search) {

                    // Search student name, email, and phone
                    $query->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })

                    // Search academic details
                    ->orWhere('college', 'like', "%{$search}%")
                    ->orWhere('qualification', 'like', "%{$search}%");

                });

            })
            ->latest('students.id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.students.index', compact('students', 'search'));
    }


    public function show(Student $student)
    {
        $student->load('user');

        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $student->load('user');

        return view('admin.students.edit', compact('student'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $validated = $request->validated();

        $student->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        $student->update([
            'dob' => $validated['dob'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'pincode' => $validated['pincode'],
            'qualification' => $validated['qualification'],
            'college' => $validated['college'],
            'graduation_year' => $validated['graduation_year'],
            'skills' => $validated['skills'] ?? null,
        ]);

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {

            $user = $student->user;

            $student->delete();

            $user->delete();
        });

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}   