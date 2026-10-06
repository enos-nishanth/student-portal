<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $studentCount = Student::count();

        return response()->json([
            'message' => 'Admin dashboard data retrieved successfully.',
            'data' => [
                'student_count' => $studentCount,
            ],
        ], 200);
    }
}
