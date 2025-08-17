<?php

namespace App\Services;

use App\Models\Beach;
use App\Models\Tour;
use App\Models\BeachImage;
use App\Models\TourImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Lấy tất cả ảnh của beach theo loại
     */
    public function getBeachImages($beachId, $type = null, $limit = null)
    {
        $query = BeachImage::where('beach_id', $beachId);
        
        if ($type) {
            $query->where('image_type', $type);
        }
        
        $query->ordered();
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }

    /**
     * Lấy tất cả ảnh của tour theo loại
     */
    public function getTourImages($tourId, $type = null, $limit = null)
    {
        $query = TourImage::where('tour_id', $tourId);
        
        if ($type) {
            $query->where('image_type', $type);
        }
        
        $query->ordered();
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }

    /**
     * Upload và lưu ảnh cho beach
     */
    public function uploadBeachImage(Request $request, $beachId, $imageType = 'gallery', $isPrimary = false)
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        $file = $request->file('image');
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('beaches/' . $beachId, $filename, 'public');

        // Nếu đây là ảnh chính, set các ảnh chính khác thành false
        if ($isPrimary) {
            BeachImage::where('beach_id', $beachId)->update(['is_primary' => false]);
        }

        // Lấy sort_order cao nhất
        $maxOrder = BeachImage::where('beach_id', $beachId)->max('sort_order') ?? 0;

        return BeachImage::create([
            'beach_id' => $beachId,
            'image_url' => $path,
            'alt_text' => $request->input('alt_text', ''),
            'caption' => $request->input('caption', ''),
            'is_primary' => $isPrimary,
            'sort_order' => $maxOrder + 1,
            'image_type' => $imageType,
        ]);
    }

    /**
     * Upload và lưu ảnh cho tour
     */
    public function uploadTourImage(Request $request, $tourId, $imageType = 'gallery', $isPrimary = false)
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        $file = $request->file('image');
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('tours/' . $tourId, $filename, 'public');

        // Nếu đây là ảnh chính, set các ảnh chính khác thành false
        if ($isPrimary) {
            TourImage::where('tour_id', $tourId)->update(['is_primary' => false]);
        }

        // Lấy sort_order cao nhất
        $maxOrder = TourImage::where('tour_id', $tourId)->max('sort_order') ?? 0;

        return TourImage::create([
            'tour_id' => $tourId,
            'image_url' => $path,
            'alt_text' => $request->input('alt_text', ''),
            'caption' => $request->input('caption', ''),
            'is_primary' => $isPrimary,
            'sort_order' => $maxOrder + 1,
            'image_type' => $imageType,
        ]);
    }

    /**
     * Xóa ảnh beach
     */
    public function deleteBeachImage($imageId)
    {
        $image = BeachImage::find($imageId);
        if (!$image) {
            return false;
        }

        // Xóa file khỏi storage nếu không phải URL
        if (!filter_var($image->image_url, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($image->image_url);
        }

        return $image->delete();
    }

    /**
     * Xóa ảnh tour
     */
    public function deleteTourImage($imageId)
    {
        $image = TourImage::find($imageId);
        if (!$image) {
            return false;
        }

        // Xóa file khỏi storage nếu không phải URL
        if (!filter_var($image->image_url, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($image->image_url);
        }

        return $image->delete();
    }

    /**
     * Cập nhật thứ tự ảnh beach
     */
    public function updateBeachImageOrder($beachId, array $imageOrders)
    {
        foreach ($imageOrders as $order => $imageId) {
            BeachImage::where('id', $imageId)
                ->where('beach_id', $beachId)
                ->update(['sort_order' => $order]);
        }
        return true;
    }

    /**
     * Cập nhật thứ tự ảnh tour
     */
    public function updateTourImageOrder($tourId, array $imageOrders)
    {
        foreach ($imageOrders as $order => $imageId) {
            TourImage::where('id', $imageId)
                ->where('tour_id', $tourId)
                ->update(['sort_order' => $order]);
        }
        return true;
    }

    /**
     * Set ảnh chính cho beach
     */
    public function setBeachPrimaryImage($beachId, $imageId)
    {
        // Reset tất cả ảnh về không phải primary
        BeachImage::where('beach_id', $beachId)->update(['is_primary' => false]);
        
        // Set ảnh được chọn thành primary
        return BeachImage::where('id', $imageId)
            ->where('beach_id', $beachId)
            ->update(['is_primary' => true]);
    }

    /**
     * Set ảnh chính cho tour
     */
    public function setTourPrimaryImage($tourId, $imageId)
    {
        // Reset tất cả ảnh về không phải primary
        TourImage::where('tour_id', $tourId)->update(['is_primary' => false]);
        
        // Set ảnh được chọn thành primary
        return TourImage::where('id', $imageId)
            ->where('tour_id', $tourId)
            ->update(['is_primary' => true]);
    }

    /**
     * Lấy ảnh responsive với các kích thước khác nhau
     */
    public function getResponsiveImageUrl($imageUrl, $size = 'medium')
    {
        // Nếu là URL Unsplash, có thể thêm parameters
        if (strpos($imageUrl, 'unsplash.com') !== false) {
            $sizes = [
                'small' => '?w=400&h=300&fit=crop',
                'medium' => '?w=800&h=600&fit=crop',
                'large' => '?w=1200&h=800&fit=crop',
                'xlarge' => '?w=1600&h=900&fit=crop',
            ];
            
            return $imageUrl . ($sizes[$size] ?? $sizes['medium']);
        }
        
        return $imageUrl;
    }
}
