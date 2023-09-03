<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('project.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::controller(ProjectController::class)->group(function () {
    Route::get('/projects', 'index')->name('project.index');
    Route::get('/project/info/{project_id}', 'info')->name('project.info');
    Route::get('/add-project', 'create')->name('project.create');
    Route::post('/add-project', 'store')->name('project.store');
    Route::get('/project/edit/{project_id}', 'edit')->name('project.edit');
    Route::put('/project/edit/{project_id}', 'update')->name('project.update');
    Route::delete('/project/delete/{project_id}', 'destroy')->name('project.delete');
    Route::get('/download-pdf/{filename}', 'downloadPDF')->name('download.pdf');
});
