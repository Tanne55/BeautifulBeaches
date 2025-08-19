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
            $query->ordered();
        }])->find($id);
        
        if (!$beach) {
            return response()->json(['success' => false, 'message' => 'Beach not found'], 404);
        }
        
        $images = [];
        
        // Ưu tiên 1: Thêm field image từ bảng beaches nếu có
        if ($beach->image) {
            $isAsset = str_starts_with($beach->image, 'http') || str_starts_with($beach->image, '/assets');
            $imageUrl = $isAsset ? $beach->image : asset('storage/' . $beach->image);
            
            $images[] = [
                'id' => 'main_' . $beach->id,
                'image_url' => $imageUrl,
                'alt_text' => $beach->title,
                'caption' => 'Ảnh chính của ' . $beach->title,
                'image_type' => 'main',
                'is_primary' => true,
            ];
        }
        
        // Ưu tiên 2 & 3: Thêm primary image trước, sau đó các ảnh còn lại
        $sortedImages = $beach->images->sortByDesc('is_primary');
        foreach ($sortedImages as $image) {
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
            $query->ordered();
        }])->find($id);
        
        if (!$tour) {
            return response()->json(['success' => false, 'message' => 'Tour not found'], 404);
        }
        
        $images = [];
        
        // Ưu tiên 1: Field image trong bảng tours (nếu có)
        if ($tour->image) {
            $isAsset = str_starts_with($tour->image, 'http') || str_starts_with($tour->image, '/assets');
            $imageUrl = $isAsset ? $tour->image : asset('storage/' . (str_starts_with($tour->image, 'tours/') ? $tour->image : 'tours/' . $tour->image));
            
            $images[] = [
                'id' => 'tour_image_field',
                'image_url' => $imageUrl,
                'alt_text' => $tour->title,
                'caption' => 'Ảnh chính của tour',
                'image_type' => 'main',
                'is_primary' => true,
            ];
        }
        
        // Ưu tiên 2: Primary image từ tour_images trước
        $primaryImage = $tour->images->where('is_primary', true)->first();
        if ($primaryImage) {
            $images[] = [
                'id' => $primaryImage->id,
                'image_url' => $primaryImage->getFullImageUrlAttribute(),
                'alt_text' => $primaryImage->alt_text,
                'caption' => $primaryImage->caption,
                'image_type' => $primaryImage->image_type,
                'is_primary' => $primaryImage->is_primary,
            ];
        }
        
        // Ưu tiên 3: Các ảnh còn lại (không phải primary)
        foreach ($tour->images->where('is_primary', false) as $image) {
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
