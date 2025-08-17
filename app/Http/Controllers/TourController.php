<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\Beach;
use App\Models\TourBooking;
use Illuminate\Support\Facades\Auth;

class TourController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Tour::with(['beach', 'detail', 'ceo']);
        if ($user && $user->role === 'ceo') {
            $query->where('ceo_id', $user->id);
        }
        
        // Cập nhật trạng thái 'cancelled' nếu tất cả departure_dates đã qua
        $toursToUpdate = $query->get();
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
        
        // Paginate với 10 tours mỗi trang
        $tours = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Thống kê từ toàn bộ dữ liệu
        $stats = [
            'total' => $query->count(),
            'pending' => $query->where('status', 'pending')->count(),
            'confirmed' => $query->where('status', 'confirmed')->count(),
            'cancelled' => $query->where('status', 'cancelled')->count(),
        ];
        
        return view('ceo.tours.index', compact('tours', 'stats'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'capacity' => 'required|integer',
            'duration_days' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,cancelled',
            'departure_dates' => 'required|array|min:1',
            'departure_dates.*' => 'required|date',
            'return_time' => 'required|date',
            'included_services' => 'nullable',
            'excluded_services' => 'nullable',
            'highlights' => 'nullable',
        ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('tours', 'public');
            $validated['image'] = $imagePath;
        }
        $tour = Tour::create([
            'beach_id' => $validated['beach_id'],
            'ceo_id' => Auth::id(),
            'title' => $validated['title'],
            'image' => $validated['image'] ?? null,
            'capacity' => $validated['capacity'],
            'duration_days' => $validated['duration_days'],
            'status' => $validated['status'],
        ]);
        $tour->prices()->create([
            'price' => $validated['price'],
            'discount' => $validated['discount'] ?? null,
        ]);
        $tour->detail()->create([
            'departure_dates' => $validated['departure_dates'],
            'return_time' => $validated['return_time'],
            'included_services' => $request->filled('included_services') ? preg_split('/\r?\n/', $validated['included_services']) : [],
            'excluded_services' => $request->filled('excluded_services') ? preg_split('/\r?\n/', $validated['excluded_services']) : [],
            'highlights' => $request->filled('highlights') ? preg_split('/\r?\n/', $validated['highlights']) : [],
        ]);
        return redirect()->route('ceo.tours.index')->with('success', 'Thêm tour thành công!');
    }

    public function edit($id)
    {
        $tour = Tour::with('detail')->findOrFail($id);
        $beaches = Beach::all();
        return view('ceo.tours.edit', compact('tour', 'beaches'));
    }

    public function update(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);
        $validated = $request->validate([
            'beach_id' => 'required|exists:beaches,id',
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'capacity' => 'required|integer',
            'duration_days' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'departure_dates' => 'required|array|min:1',
            'departure_dates.*' => 'required|date',
            'return_time' => 'required|date',
            'included_services' => 'nullable',
            'excluded_services' => 'nullable',
            'highlights' => 'nullable',
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('tours', 'public');
            $validated['image'] = $imagePath;
        } else {
            $validated['image'] = $tour->image;
        }
        $tour->update([
            'beach_id' => $validated['beach_id'],
            'ceo_id' => $tour->ceo_id ?? Auth::id(),
            'title' => $validated['title'],
            'image' => $validated['image'],
            'capacity' => $validated['capacity'],
            'duration_days' => $validated['duration_days'],
            'status' => $validated['status'],
        ]);
        // Update or create price (for simplicity, just create new)
        $tour->prices()->create([
            'price' => $validated['price'],
            'discount' => $validated['discount'] ?? null,
        ]);
        $tour->detail()->updateOrCreate(
            ['tour_id' => $tour->id],
            [
                'departure_dates' => $validated['departure_dates'],
                'return_time' => $validated['return_time'],
                'included_services' => $request->filled('included_services') ? preg_split('/\r?\n/', $validated['included_services']) : [],
                'excluded_services' => $request->filled('excluded_services') ? preg_split('/\r?\n/', $validated['excluded_services']) : [],
                'highlights' => $request->filled('highlights') ? preg_split('/\r?\n/', $validated['highlights']) : [],
            ]
        );
        return redirect()->route('ceo.tours.index')->with('success', 'Cập nhật tour thành công!');
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

    
}
