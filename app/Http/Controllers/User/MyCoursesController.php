<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyCoursesController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $enrollments = $user->enrollments()
                            ->with('course')
                            ->latest()
                            ->get();

        return view('user.my_courses', compact('enrollments'));
    }
}