<?php

use App\Models\Tour;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BeachController;
use App\Http\Controllers\UserController;
use App\Models\Beach;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsCeo;
use App\Http\Middleware\IsUser;



Route::get('/', function () {
    $beaches = Beach::with('detail') // nạp quan hệ 1-1
        ->select('id', 'region', 'image', 'title') // chỉ lấy cột cần thiết
        ->take(4)
        ->get();

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
     // CRUD của admin về users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/ban', [UserController::class, 'ban'])->name('ban');
    });
});

// Dashboard & CRUD Ceo
Route::prefix('ceo')->middleware(['auth', IsCeo::class])->name('ceo.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\CeoController::class, 'dashboard'])->name('dashboard');
    // CRUD của admin về users
    Route::prefix('tours')->name('tours.')->group(function () {
        Route::get('/', [App\Http\Controllers\TourController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\TourController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\TourController::class, 'store'])->name('store');
        Route::get('/{tour}/edit', [App\Http\Controllers\TourController::class, 'edit'])->name('edit');
        Route::put('/{tour}', [App\Http\Controllers\TourController::class, 'update'])->name('update');
    });
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
Route::get('/tour', function () {
    $tours = Tour::with('beach')->get()->map(function ($tour) {
        return [
            'id' => $tour->id,
            'title' => $tour->title,
            'image' => $tour->image,
            'price' => $tour->price,
            'original_price' => $tour->original_price,
            'capacity' => $tour->capacity,
            'duration_days' => $tour->duration_days,
            'status' => $tour->status,

            'beach_id' => $tour->beach_id,
            'beach_title' => $tour->beach?->title,
            'beach_region' => $tour->beach?->region,
            'beach_image' => $tour->beach?->image,
            'beach_description' => $tour->beach?->short_description,
        ];
    });

    return view('pages.tour', compact('tours'));
})->name('tour');
Route::view('/queries', 'pages.queries')->name('queries');
Route::view('/detail', 'pages.detail')->name('detail');



Route::get('/api/beaches', [BeachController::class, 'getBeaches']);
Route::get('/beaches/{beach}', [BeachController::class, 'show'])->name('beaches.show');

Route::get('/tour/{id}', function ($id) {
    $tour = Tour::with(['beach', 'detail'])->findOrFail($id);

    // Ưu tiên ảnh tour, fallback sang ảnh beach, cuối cùng là placeholder
    $image_url = null;
    if ($tour->image) {
        $image_url = asset($tour->image);
    } elseif ($tour->beach && $tour->beach->image) {
        $image_url = asset($tour->beach->image);
    } else {
        $image_url = 'https://via.placeholder.com/600x400?text=No+Image';
    }

    return view('pages.tourdetail', compact('tour', 'image_url'));
})->name('tour.show');

