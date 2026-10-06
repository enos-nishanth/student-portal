<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $studentCount = Student::count();

        return view('admin.dashboard', compact('studentCount'));
    }
}
 