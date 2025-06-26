<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\Beach;
use Illuminate\Support\Facades\Auth;

class TourController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Tour::with(['beach', 'detail']);
        if ($user && $user->role === 'ceo') {
            $query->where('ceo_id', $user->id);
        }
        $tours = $query->get();
        return view('ceo.tours.index', compact('tours'));
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
            'original_price' => 'nullable|numeric',
            'capacity' => 'required|integer',
            'duration_days' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'departure_time' => 'required',
            'return_time' => 'required',
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
            'price' => $validated['price'],
            'original_price' => $validated['original_price'] ?? null,
            'capacity' => $validated['capacity'],
            'duration_days' => $validated['duration_days'],
            'status' => $validated['status'],
        ]);
        $tour->detail()->create([
            'departure_time' => $validated['departure_time'],
            'return_time' => $validated['return_time'],
            'included_services' => $request->filled('included_services') ? json_encode(preg_split('/\r?\n/', $validated['included_services'])) : null,
            'excluded_services' => $request->filled('excluded_services') ? json_encode(preg_split('/\r?\n/', $validated['excluded_services'])) : null,
            'highlights' => $request->filled('highlights') ? json_encode(preg_split('/\r?\n/', $validated['highlights'])) : null,
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
            'original_price' => 'nullable|numeric',
            'capacity' => 'required|integer',
            'duration_days' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'departure_time' => 'required',
            'return_time' => 'required',
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
            'price' => $validated['price'],
            'original_price' => $validated['original_price'] ?? null,
            'capacity' => $validated['capacity'],
            'duration_days' => $validated['duration_days'],
            'status' => $validated['status'],
        ]);
        $tour->detail()->updateOrCreate(
            ['tour_id' => $tour->id],
            [
                'departure_time' => $validated['departure_time'],
                'return_time' => $validated['return_time'],
                'included_services' => $request->filled('included_services') ? json_encode(preg_split('/\r?\n/', $validated['included_services'])) : null,
                'excluded_services' => $request->filled('excluded_services') ? json_encode(preg_split('/\r?\n/', $validated['excluded_services'])) : null,
                'highlights' => $request->filled('highlights') ? json_encode(preg_split('/\r?\n/', $validated['highlights'])) : null,
            ]
        );
        return redirect()->route('ceo.tours.index')->with('success', 'Cập nhật tour thành công!');
    }

    
}
