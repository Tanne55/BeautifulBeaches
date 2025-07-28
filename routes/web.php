<?php

use App\Models\Tour;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BeachController;
use App\Http\Controllers\UserController;
use App\Models\Beach;
use App\Models\ReviewBeach;
use App\Models\ReviewTour;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsCeo;
use App\Http\Middleware\IsUser;
use App\Http\Controllers\ReviewBeachController;
use App\Http\Controllers\ReviewTourController;
use App\Http\Controllers\TourBookingController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\CeoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SupportRequestController;




Route::get('/', function () {
    $beaches = Beach::with(['detail', 'region']) // nạp quan hệ 1-1 và region
        ->select('id', 'region_id', 'image', 'title') // chỉ lấy cột cần thiết
        ->take(4)
        ->get()
        ->map(function ($beach) {
            return [
                'id' => $beach->id,
                'region' => $beach->region_name,
                'image' => $beach->image,
                'title' => $beach->title,
                'short_description' => $beach->short_description,
            ];
        });

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
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/create', [AdminController::class, 'create'])->name('create');
        Route::post('/', [AdminController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [AdminController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminController::class, 'update'])->name('update');
        Route::delete('/{user}', [AdminController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/ban', [AdminController::class, 'ban'])->name('ban');
    });
    // CRUD của admin về support requests
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [SupportRequestController::class, 'index'])->name('index');
        Route::get('/{supportRequest}', [SupportRequestController::class, 'show'])->name('show');
        Route::patch('/{supportRequest}/status', [SupportRequestController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/{supportRequest}', [SupportRequestController::class, 'destroy'])->name('destroy');
    });
});

// Dashboard & CRUD Ceo
Route::prefix('ceo')->middleware(['auth', IsCeo::class])->name('ceo.')->group(function () {
    Route::get('/cancellation-requests', [CeoController::class, 'cancellationRequests'])->name('cancellation_requests.index');
    Route::post('/cancellation-requests/{id}/update', [\App\Http\Controllers\CancellationRequestController::class, 'update'])->name('cancellation_requests.update');
    Route::get('/dashboard', [CeoController::class, 'dashboard'])->name('dashboard');
    // CRUD của CEO về tours
    Route::prefix('tours')->name('tours.')->group(function () {
        Route::get('/', [TourController::class, 'index'])->name('index');
        Route::get('/create', [TourController::class, 'create'])->name('create');
        Route::post('/', [TourController::class, 'store'])->name('store');
        Route::get('/{tour}/edit', [TourController::class, 'edit'])->name('edit');
        Route::put('/{tour}', [TourController::class, 'update'])->name('update');
    });
    // CRUD của CEO về tickets
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');
        Route::get('/{ticket}/edit', [TicketController::class, 'edit'])->name('edit');
        Route::put('/{ticket}', [TicketController::class, 'update'])->name('update');
        Route::patch('/{ticket}/status', [TicketController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/{ticket}', [TicketController::class, 'destroy'])->name('destroy');
    });
    Route::get('/bookings', [CeoController::class, 'bookings'])->name('bookings.index');
    Route::get('/bookings/groups', [CeoController::class, 'bookingGroups'])->name('bookings.groups');
    Route::patch('/bookings/{booking}/status', [CeoController::class, 'updateBookingStatus'])->name('bookings.updateStatus');
    Route::post('/bookings/create-group', [CeoController::class, 'createBookingGroup'])->name('bookings.createGroup');
    Route::post('/bookings/groups/{group}/generate-tickets', [CeoController::class, 'generateTicketsForGroup'])->name('bookings.generateTicketsForGroup');
    Route::post('/bookings/{tourBooking}/tickets', [TicketController::class, 'generateTickets'])->name('bookings.generateTickets');
    Route::get('/reports', [CeoController::class, 'reports'])->name('reports.index');
    Route::patch('/bookings/{booking}/confirm', [CeoController::class, 'confirmBooking'])->name('bookings.confirm');
});

// Dashboard & CRUD User
Route::prefix('user')->middleware(['auth', IsUser::class])->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/history', [UserController::class, 'history'])->name('history');
    Route::get('/cancellation-requests', [\App\Http\Controllers\CancellationRequestController::class, 'myRequests'])->name('cancellation_requests');
    Route::get('/booking/{booking}/cancel', [\App\Http\Controllers\CancellationRequestController::class, 'showCancelForm'])->name('booking.cancel.form');
    Route::post('/booking/{booking}/cancel', [\App\Http\Controllers\CancellationRequestController::class, 'store'])->name('booking.cancel.submit');
});

// Điều hướng của các pages
Route::view('/about', 'pages.about')->name('about');
Route::view('/gallery', 'pages.gallery')->name('gallery');
Route::view('/contact', 'pages.contact')->name('contact');
// Route::view('/explore', 'pages.explore')->name('explore');
Route::get('/explore', function () {
    $beaches = Beach::with(['detail', 'region'])->get()->map(function ($beach) {
        return [
            'id' => $beach->id,
            'region_name' => $beach->region_name,
            'image' => $beach->image,
            'title' => $beach->title,
            'short_description' => $beach->short_description,
            'tags' => $beach->detail ? json_decode($beach->detail->tags, true) : [],
        ];
    });
    $regions = \App\Models\Region::all();
    return view('pages.explore', compact('beaches', 'regions'));
})->name('explore');

Route::get('/tour', function () {
    $tours = Tour::with(['beach', 'prices'])->where('status', 'confirmed')->get();
    return view('pages.tour', compact('tours'));
})->name('tour');
Route::view('/queries', 'pages.queries')->name('queries');
Route::view('/detail', 'pages.detail')->name('detail');



Route::get('/api/beaches', [BeachController::class, 'getBeaches']);
Route::get('/beaches/{beach}', [BeachController::class, 'show'])->name('beaches.show');

Route::get('/tour/{id}', function ($id) {
    $tour = Tour::with(['beach.detail', 'detail'])->findOrFail($id);
    $image_url = null;
    if ($tour->image) {
        $image_url = asset($tour->image);
    } elseif ($tour->beach && $tour->beach->image) {
        $image_url = asset($tour->beach->image);
    } else {
        $image_url = 'https://via.placeholder.com/600x400?text=No+Image';
    }
    $reviews = ReviewTour::with('user')->where('tour_id', $tour->id)->latest()->get();
    return view('pages.tourdetail', compact('tour', 'image_url', 'reviews'));
})->name('tour.show');

// Route cho review/comment (cho phép khách comment)
Route::post('/beaches/{beach}/review', [ReviewBeachController::class, 'store'])->name('beaches.review');
Route::post('/tour/{tour}/review', [ReviewTourController::class, 'store'])->name('tour.review');

// Route cho booking (cho phép không đăng nhập)
Route::get('/tour/{id}/booking', [TourBookingController::class, 'showBookingForm'])->name('tour.booking.form');
Route::post('/tour/{id}/booking', [TourBookingController::class, 'storeBooking'])->name('tour.booking.store');
Route::get('/bookings/track', [TourBookingController::class, 'trackBooking'])->name('bookings.track');
Route::get('/bookings/result', [TourBookingController::class, 'showBookingResult'])->name('bookings.result');

// Public support request route
Route::post('/support', [SupportRequestController::class, 'store'])->name('support.store');

