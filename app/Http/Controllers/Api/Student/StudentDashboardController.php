<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'message' => 'Welcome to the student dashboard.',
            'student_name' => $user->name,
        ]);
    }
}