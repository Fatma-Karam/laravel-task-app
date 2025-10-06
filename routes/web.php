<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
 return redirect()->route('tasks.index');
});
// Route::resource('tasks', TaskController::class)->except(['create','show']);


//show all tasks
Route::get('/tasks', [TaskController::class,'index'])->name('tasks.index');

//save new task
Route::post('/tasks', [TaskController::class,'store'])->name('tasks.store');

//show form of edit task
Route::get('/tasks/{task}/edit', [TaskController::class,'edit'])->name('tasks.edit');

//update task
Route::put('/tasks/{task}', [TaskController::class,'update'])->name('tasks.update');

//delete task
Route::delete('/tasks/{task}', [TaskController::class,'destroy'])->name('tasks.destroy');

