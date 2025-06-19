<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BeachController;
use App\Models\Beach;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $beaches = Beach::all();
        return view('dashboard', compact('beaches'));
    })->name('dashboard');

    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store']);
});

// Điều hướng của các pages
Route::view('/about', 'pages.about')->name('about');
Route::view('/gallery', 'pages.gallery')->name('gallery');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/explore', 'pages.explore')->name('explore');
Route::view('/queries', 'pages.queries')->name('queries');



Route::get('/api/beaches', [BeachController::class, 'getBeaches']);
Route::get('/beach/{id}', [BeachController::class, 'show'])->name('beach.show');


// CRUD của admin về beaches
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/beaches', [BeachController::class, 'index'])->name('admin.beaches.index');
    Route::get('/admin/beaches/create', [BeachController::class, 'create'])->name('admin.beaches.create');
    Route::post('/admin/beaches', [BeachController::class, 'store'])->name('admin.beaches.store');
    Route::get('/admin/beaches/{beach}/edit', [BeachController::class, 'edit'])->name('admin.beaches.edit');
    Route::put('/admin/beaches/{beach}', [BeachController::class, 'update'])->name('admin.beaches.update');
    Route::delete('/admin/beaches/{beach}', [BeachController::class, 'destroy'])->name('admin.beaches.destroy');
});