<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Product;
use App\Models\Order;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $studentCount = Student::count();

        $productCount = Product::count();

        $orderCount = Order::count();

        return view('admin.dashboard', compact(
            'studentCount',
            'productCount',
            'orderCount'
        ));
    }
}
