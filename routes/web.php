<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TaskTwoController;

Route::get('/', [StudentController::class, 'index']);
Route::post('/import', [StudentController::class, 'import']);

Route::get('/task2', [TaskTwoController::class, 'index'])->name('task2.index');
Route::post('/task2', [TaskTwoController::class, 'store'])->name('task2.store');