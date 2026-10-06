<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Http\Requests\UpdateStudentApiRequest;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()// list all the student through user
    {
        $students = Student::with('user')->get();

        return response()->json([
            'message' => 'Students retrieved successfully.',
            'data' => $students,
        ], 200);
    }
    public function show(Student $student) //view student by id || Route model binding (new concept)
    {
        $student->load('user');

        if (!$student) {
            return response()->json([
                'message' => 'Student not found.'
            ], 404);
        }

        return response()->json([
            'message' => 'Student retrieved successfully.',
            'data' => $student,
        ], 200);
    }

    public function update(UpdateStudentApiRequest $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'message' => 'Student not found.'
            ], 404);
        }

        $validated = $request->validated();

        DB::transaction(function () use ($student, $validated) {

            // Update users table
            $student->user->update([
                'name' => $validated['name'] ?? $student->user->name,
                'email' => $validated['email'] ?? $student->user->email,
                'phone' => $validated['phone'] ?? $student->user->phone,
                ]);

            // Student fields
            $studentData = collect($validated)->except([
                'name',
                'email',
                'phone',
            ])->toArray();

            $student->update($studentData);
        });

        return response()->json([
            'message' => 'Student updated successfully.',
            'data' => $student->load('user'),
        ], 200);
    }

    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'message' => 'Student not found.'
            ], 404);
        }

        DB::transaction(function () use ($student) {
            $user = $student->user;

            $student->delete();
            $user->delete();
        });

        return response()->json([
            'message' => 'Student deleted successfully.'
        ], 200);
    }
}
