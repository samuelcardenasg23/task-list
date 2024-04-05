<?php

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::get('/tasks', function () {
    return view('index', [
        'tasks' => Task::latest()->get()
    ]);
})->name('tasks.index');

Route::view('/tasks/create', 'create')->name('tasks.create');

Route::get('/tasks/{id}', function ($id) {
    return view('show', [
        'task' => Task::findOrFail($id)
    ]);
})->name('tasks.show');

Route::get('/tasks/{id}/edit', function ($id) {
    return view('edit', [
        'task' => Task::findOrFail($id)
    ]);
})->name('tasks.edit');

Route::post('/tasks', function (Request $request) {
    // Validation
    $data = $request->validate([
        'title' => 'required|max:255',
        'description'=> 'required',
        'long_description'=> 'required',
    ]);

    // Create Model
    $task = new Task;
    $task->title = $data['title'];
    $task->description = $data['description'];
    $task->long_description = $data['long_description'];

    // Save changes to DB
    $task->save();

    // Redirect to the recent added task
    return redirect()->route('tasks.show', ['id'=> $task->id])
        ->with('success','Task created successfully!');
})->name('tasks.store');

Route::put('/tasks/{id}', function ($id, Request $request) {
    // Validation
    $data = $request->validate([
        'title' => 'required|max:255',
        'description'=> 'required',
        'long_description'=> 'required',
    ]);

    // Create Model
    $task = Task::findOrFail($id);
    $task->title = $data['title'];
    $task->description = $data['description'];
    $task->long_description = $data['long_description'];

    // Save changes to DB
    $task->save();

    // Redirect to the recent added task
    return redirect()->route('tasks.show', ['id'=> $task->id])
        ->with('success','Task updated successfully!');
})->name('tasks.update');

Route::fallback(function () {
    return 'Still got somewhere!';
});
