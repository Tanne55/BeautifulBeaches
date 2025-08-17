<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beach;
use App\Models\Tour;

class GalleryController extends Controller
{
    /**
     * Get gallery images for a beach
     */
    public function getBeachGallery($id)
    {
        $beach = Beach::with(['images' => function($query) {
            $query->where('image_type', '!=', 'hero')->ordered();
        }])->find($id);
        
        if (!$beach) {
            return response()->json(['success' => false, 'message' => 'Beach not found'], 404);
        }
        
        $images = [];
        
        // Chỉ thêm ảnh gallery (loại trừ ảnh hero/main)
        foreach ($beach->images as $image) {
            $images[] = [
                'id' => $image->id,
                'image_url' => $image->getFullImageUrlAttribute(),
                'alt_text' => $image->alt_text,
                'caption' => $image->caption,
                'image_type' => $image->image_type,
                'is_primary' => $image->is_primary,
            ];
        }
        
        return response()->json([
            'success' => true,
            'images' => $images
        ]);
    }
    
    /**
     * Get gallery images for a tour
     */
    public function getTourGallery($id)
    {
        $tour = Tour::with(['images' => function($query) {
            $query->where('image_type', '!=', 'hero')->ordered();
        }])->find($id);
        
        if (!$tour) {
            return response()->json(['success' => false, 'message' => 'Tour not found'], 404);
        }
        
        $images = [];
        
        // Chỉ thêm ảnh gallery (loại trừ ảnh hero/main)
        foreach ($tour->images as $image) {
            $images[] = [
                'id' => $image->id,
                'image_url' => $image->getFullImageUrlAttribute(),
                'alt_text' => $image->alt_text,
                'caption' => $image->caption,
                'image_type' => $image->image_type,
                'is_primary' => $image->is_primary,
            ];
        }
        
        return response()->json([
            'success' => true,
            'images' => $images
        ]);
    }
}
