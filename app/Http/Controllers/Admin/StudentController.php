<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateStudentRequest;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('user')->get();

        return view('admin.students.index', compact('students'));
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