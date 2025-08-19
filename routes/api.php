<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;

Route::middleware('api')->get('/ping', function (Request $request) {
    return response()->json(['message' => 'pong']);
});

Route::get('/gallery/beach/{id}', [GalleryController::class, 'getBeachGallery']);
Route::get('/gallery/tour/{id}', [GalleryController::class, 'getTourGallery']);
