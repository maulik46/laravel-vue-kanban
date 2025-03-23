<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
