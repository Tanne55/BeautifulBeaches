<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BeachController;
use App\Models\Beach;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsCeo;
use App\Http\Middleware\IsUser;



Route::get('/', function () {
    $beaches = Beach::all();
    return view('welcome', compact('beaches'));
})->name('home');






// Authentication Routes
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
// Route::middleware('auth')->group(function () {
//     Route::get('/dashboard', function () {
//         $beaches = Beach::all();
//         return view('dashboard', compact('beaches'));
//     })->name('dashboard');
// ;
// });

// Dashboard & CRUD Admin
Route::prefix('admin')->middleware(['auth', IsAdmin::class])->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    // CRUD của admin về beaches
    Route::prefix('beaches')->name('beaches.')->group(function () {
        Route::get('/', [BeachController::class, 'index'])->name('index');
        Route::get('/create', [BeachController::class, 'create'])->name('create');
        Route::post('/', [BeachController::class, 'store'])->name('store');
        Route::get('/{beach}/edit', [BeachController::class, 'edit'])->name('edit');
        Route::put('/{beach}', [BeachController::class, 'update'])->name('update');
        Route::delete('/{beach}', [BeachController::class, 'destroy'])->name('destroy');
    });
});

// Dashboard & CRUD Ceo
Route::prefix('ceo')->middleware(['auth', IsCeo::class])->name('ceo.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\CeoController::class, 'dashboard'])->name('dashboard');
    
});

// Dashboard & CRUD User
Route::prefix('user')->middleware(['auth', IsUser::class])->name('user.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\UserController::class, 'dashboard'])->name('dashboard');
    
});

// Điều hướng của các pages
Route::view('/about', 'pages.about')->name('about');
Route::view('/gallery', 'pages.gallery')->name('gallery');
Route::view('/contact', 'pages.contact')->name('contact');
// Route::view('/explore', 'pages.explore')->name('explore');
Route::get('/explore', function () {
    $beaches = Beach::with('detail')->get()->map(function ($beach) {
        return [
            'id' => $beach->id,
            'region' => $beach->region,
            'image' => $beach->image,
            'title' => $beach->title,
            'short_description' => $beach->short_description,
            'tags' => $beach->detail ? json_decode($beach->detail->tags, true) : [],
        ];
    });
    return view('pages.explore', compact('beaches'));
})->name('explore');
Route::view('/queries', 'pages.queries')->name('queries');
Route::view('/detail', 'pages.detail')->name('detail');



Route::get('/api/beaches', [BeachController::class, 'getBeaches']);
Route::get('/beaches/{beach}', [BeachController::class, 'show'])->name('beaches.show');
