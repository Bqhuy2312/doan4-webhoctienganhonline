<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
}) -> name('admin.dashboard');
Route::get('/login', function () {
    return view('admin.auth.login');
});
Route::get('/courses', function () {
    return view('admin.courses.index');
}) -> name('admin.courses.index');
Route::get('/students', function () {
    return view('admin.students.index');
}) -> name('admin.students.index');
Route::get('/quiz', function () {
    return view('admin.quiz.index');
}) -> name('admin.quiz.index');