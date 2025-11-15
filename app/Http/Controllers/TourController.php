<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourImage;
use App\Models\Beach;
use App\Models\TourBooking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Tour::with(['beach', 'detail', 'ceo', 'prices']);
        
        if ($user && $user->role === 'ceo') {
            $query->where('ceo_id', $user->id);
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('beach', function($beachQuery) use ($searchTerm) {
                      $beachQuery->where('title', 'like', '%' . $searchTerm . '%');
                  });
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Beach filter
        if ($request->filled('beach_id')) {
            $query->where('beach_id', $request->beach_id);
        }
        
        // Clone query for stats before applying sorting and pagination
        $statsQuery = clone $query;
        
        // Cập nhật trạng thái 'cancelled' nếu tất cả departure_dates đã qua
        $toursToUpdate = $statsQuery->get();
        foreach ($toursToUpdate as $tour) {
            if ($tour->detail && $tour->detail->departure_dates && $tour->status !== 'cancelled') {
                $allDatesExpired = true;
                foreach ($tour->detail->departure_dates as $departureDate) {
                    if (now()->lt(\Carbon\Carbon::parse($departureDate))) {
                        $allDatesExpired = false;
                        break;
                    }
                }
                
                if ($allDatesExpired) {
                    $tour->status = 'cancelled';
                    $tour->save();
                }
            }
        }
        
        // Sorting
        $sort = $request->get('sort', 'id_desc');
        switch($sort) {
            case 'id_asc':
                $query->orderBy('id', 'asc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'price_asc':
                $query->leftJoin('tour_prices', 'tours.id', '=', 'tour_prices.tour_id')
                      ->orderBy('tour_prices.price', 'asc')
                      ->select('tours.*');
                break;
            case 'price_desc':
                $query->leftJoin('tour_prices', 'tours.id', '=', 'tour_prices.tour_id')
                      ->orderBy('tour_prices.price', 'desc')
                      ->select('tours.*');
                break;
            case 'id_desc':
            default:
                $query->orderBy('id', 'desc');
                break;
        }
        
        // Paginate với 10 tours mỗi trang
        $tours = $query->paginate(10)->appends($request->query());
        
        // Thống kê từ toàn bộ dữ liệu
        $stats = [
            'total' => $statsQuery->count(),
            'pending' => $statsQuery->where('status', 'pending')->count(),
            'confirmed' => $statsQuery->where('status', 'confirmed')->count(),
            'cancelled' => $statsQuery->where('status', 'cancelled')->count(),
        ];
        
        // Lấy danh sách beaches cho dropdown filter
        $beaches = Beach::orderBy('title', 'asc')->get();
        
        return view('ceo.tours.index', compact('tours', 'stats', 'beaches'));
    }

    public function create()
    {
        $beaches = Beach::all();
        return view('ceo.tours.create', compact('beaches'));
    }

    public function store(Request $request)
    {
                $validated = $request->validate([
            'beach_id' => 'required|exists:beaches,id',
            'title' => 'required|string|max:255',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'capacity' => 'required|integer',
            'duration_days' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,cancelled',
            'departure_dates' => 'required|array|min:1',
            'departure_dates.*' => 'required|date',
            'included_services' => 'nullable',
            'excluded_services' => 'nullable',
            'highlights' => 'nullable',
        ]);

        // Xử lý ảnh chính
        $mainImagePath = null;
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('tours', 'public');
        }

        // Tạo tour với ảnh chính
        $tour = Tour::create([
            'beach_id' => $validated['beach_id'],
            'ceo_id' => Auth::id(),
            'title' => $validated['title'],
            'image' => $mainImagePath, // Ảnh chính lưu trong trường image
            'capacity' => $validated['capacity'],
            'duration_days' => $validated['duration_days'],
            'status' => $validated['status'],
        ]);

        // Xử lý ảnh phụ (gallery)
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $imageFile) {
                $imagePath = $imageFile->store('tours', 'public');
                
                $tour->images()->create([
                    'image_url' => $imagePath,
                    'is_primary' => false, // Tất cả ảnh gallery đều không phải ảnh chính
                    'sort_order' => $index + 1,
                    'image_type' => 'gallery',
                    'alt_text' => $tour->title . ' - Ảnh phụ ' . ($index + 1),
                ]);
            }
        }

        // Tạo giá với discount
        $priceData = [
            'tour_id' => $tour->id,
            'price' => $validated['price'],
            'discount' => $validated['discount'] ?? 0,
            'start_date' => $validated['start_date'] ?? now(),
            'end_date' => $validated['end_date'] ?? now()->addYear(),
        ];

        $tour->prices()->create($priceData);

        // Tạo chi tiết tour
        $tour->detail()->create([
            'departure_dates' => $validated['departure_dates'],
            'included_services' => $request->filled('included_services') ? preg_split('/\r?\n/', $validated['included_services']) : [],
            'excluded_services' => $request->filled('excluded_services') ? preg_split('/\r?\n/', $validated['excluded_services']) : [],
            'highlights' => $request->filled('highlights') ? preg_split('/\r?\n/', $validated['highlights']) : [],
        ]);

        return redirect()->route('ceo.tours.index')->with('success', 'Thêm tour thành công!');
    }

    public function edit($id)
    {
        $tour = Tour::with(['detail', 'prices', 'images'])->findOrFail($id);
        $beaches = Beach::all();
        return view('ceo.tours.edit', compact('tour', 'beaches'));
    }

    public function update(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);
        $validated = $request->validate([
            'beach_id' => 'required|exists:beaches,id',
            'title' => 'required|string|max:255',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_images' => 'nullable|array|max:9',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'remove_gallery_images' => 'nullable|array',
            'remove_gallery_images.*' => 'integer|exists:tour_images,id',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'capacity' => 'required|integer',
            'duration_days' => 'required|integer|min:1',
            'status' => 'required|in:draft,pending,confirmed,cancelled',
            'departure_dates' => 'required|array|min:1',
            'departure_dates.*' => 'required|date',
            'included_services' => 'nullable',
            'excluded_services' => 'nullable',
            'highlights' => 'nullable',
        ]);

        // Xử lý ảnh chính
        $updateData = [
            'beach_id' => $validated['beach_id'],
            'title' => $validated['title'],
            'capacity' => $validated['capacity'],
            'duration_days' => $validated['duration_days'],
            'status' => $validated['status'],
        ];

        if ($request->hasFile('main_image')) {
            // Xóa ảnh cũ nếu có
            if ($tour->image && Storage::disk('public')->exists($tour->image)) {
                Storage::disk('public')->delete($tour->image);
            }
            $updateData['image'] = $request->file('main_image')->store('tours', 'public');
        }

        // Cập nhật thông tin tour
        $tour->update($updateData);

        // Xóa ảnh gallery được chọn
        if (!empty($validated['remove_gallery_images'])) {
            $imagesToDelete = $tour->images()->whereIn('id', $validated['remove_gallery_images'])->get();
            foreach ($imagesToDelete as $image) {
                if (Storage::disk('public')->exists($image->image_url)) {
                    Storage::disk('public')->delete($image->image_url);
                }
                $image->delete();
            }
        }

        // Thêm ảnh gallery mới
        if ($request->hasFile('gallery_images')) {
            $currentMaxOrder = $tour->images()->max('sort_order') ?? 0;
            
            foreach ($request->file('gallery_images') as $index => $imageFile) {
                $imagePath = $imageFile->store('tours', 'public');
                
                $tour->images()->create([
                    'image_url' => $imagePath,
                    'is_primary' => false, // Ảnh gallery không bao giờ là ảnh chính
                    'sort_order' => $currentMaxOrder + $index + 1,
                    'image_type' => 'gallery',
                    'alt_text' => $tour->title . ' - Ảnh phụ ' . ($currentMaxOrder + $index + 1),
                ]);
            }
        }

        // Cập nhật giá với discount (tạo mới hoặc cập nhật giá hiện tại)
        $latestPrice = $tour->prices()->latest()->first();
        
        if ($latestPrice && 
            $latestPrice->price == $validated['price'] && 
            $latestPrice->discount == ($validated['discount'] ?? 0)) {
            // Không thay đổi gì về giá
        } else {
            // Tạo bản ghi giá mới
            $tour->prices()->create([
                'price' => $validated['price'],
                'discount' => $validated['discount'] ?? 0,
                'start_date' => $validated['start_date'] ?? now(),
                'end_date' => $validated['end_date'] ?? now()->addYear(),
            ]);
        }

        // Cập nhật chi tiết tour
        $tour->detail()->updateOrCreate(
            ['tour_id' => $tour->id],
            [
                'departure_dates' => $validated['departure_dates'],
                'included_services' => $request->filled('included_services') ? preg_split('/\r?\n/', $validated['included_services']) : [],
                'excluded_services' => $request->filled('excluded_services') ? preg_split('/\r?\n/', $validated['excluded_services']) : [],
                'highlights' => $request->filled('highlights') ? preg_split('/\r?\n/', $validated['highlights']) : [],
            ]
        );

        return redirect()->route('ceo.tours.index')->with('success', 'Tour đã được cập nhật thành công!');
    }

    /**
     * Xóa ảnh gallery
     */
    public function deleteImage(Tour $tour, TourImage $image)
    {
        try {
            // Xóa file ảnh khỏi storage nếu tồn tại
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Xóa record khỏi database
            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ảnh đã được xóa thành công!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa ảnh: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check tour availability for a specific date
     */
    public function checkAvailability(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);
        $date = $request->get('date');
        
        if (!$date) {
            return response()->json(['error' => 'Thiếu ngày khởi hành'], 400);
        }
        
        // Kiểm tra ngày có trong danh sách departure_dates không
        $tourDetail = $tour->detail;
        if ($tourDetail && !empty($tourDetail->departure_dates)) {
            $availableDates = array_map(function($datetime) {
                return \Carbon\Carbon::parse($datetime)->format('Y-m-d');
            }, $tourDetail->departure_dates);
            
            if (!in_array($date, $availableDates)) {
                return response()->json(['available' => false, 'message' => 'Ngày này không có tour']);
            }
        }
        
        // Tính số chỗ đã đặt
        $bookedPeople = TourBooking::where('tour_id', $tour->id)
            ->where('selected_departure_date', $date)
            ->whereIn('status', ['pending', 'confirmed', 'grouped'])
            ->sum('number_of_people');
            
        $remainingCapacity = $tour->capacity - $bookedPeople;
        
        return response()->json([
            'available' => $remainingCapacity > 0,
            'capacity' => $tour->capacity,
            'booked' => $bookedPeople,
            'remaining' => $remainingCapacity,
            'message' => $remainingCapacity > 0 ? "Còn lại {$remainingCapacity} chỗ" : 'Đã hết chỗ'
        ]);
    }

    /**
     * Hiển thị danh sách tours công khai
     */
    public function publicIndex()
    {
        $tours = Tour::with(['beach', 'prices'])->where('status', 'confirmed')->get();
        return view('pages.tour', compact('tours'));
    }

    /**
     * Hiển thị chi tiết tour công khai
     */
    public function publicShow($id)
    {
        $tour = Tour::with(['beach.detail', 'beach.region', 'detail', 'prices', 'ceo'])->findOrFail($id);
        $image_url = null;
        if ($tour->image) {
            $image_url = asset($tour->image);
        } elseif ($tour->beach && $tour->beach->image) {
            $image_url = asset($tour->beach->image);
        } else {
            $image_url = 'https://via.placeholder.com/600x400?text=No+Image';
        }
        $reviews = \App\Models\ReviewTour::with('user')->where('tour_id', $tour->id)->latest()->get();
        return view('pages.tourdetail', compact('tour', 'image_url', 'reviews'));
    }
}
