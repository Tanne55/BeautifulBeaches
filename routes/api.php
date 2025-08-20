<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\TourBookingController;

Route::middleware('api')->get('/ping', function (Request $request) {
    return response()->json(['message' => 'pong']);
});

// Guest booking tickets API
Route::get('/bookings/tickets', [TourBookingController::class, 'getBookingTickets']);

Route::get('/gallery/beach/{id}', [GalleryController::class, 'getBeachGallery']);
Route::get('/gallery/tour/{id}', [GalleryController::class, 'getTourGallery']);
