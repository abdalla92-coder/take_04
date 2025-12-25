<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Testcontroller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Route::get('/test/{id}', [Testcontroller::class, 'getUsers']);
// Route::get('/count',[Testcontroller::class,'countusers']);

Route::get('/test', [TestController::class, 'getPage']);
Route::post(    '/testdata',[Testcontroller::class,'formdata'])->name('postdata');


// Route::resource('/categories', [CategoryController::class]);

Route::resource('categories', CategoryController::class);


Route::middleware(['auth', 'role:administrator'])->group(function () {
    Route::get('/dash', function() {
        return view('layouts.master.main');
    });
});

