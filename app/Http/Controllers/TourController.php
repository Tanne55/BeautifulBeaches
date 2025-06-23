<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tour;

class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::with('detail')->get();
        return response()->json($tours);
    }

    public function show($id)
    {
        $tour = Tour::with('detail')->findOrFail($id);
        return response()->json($tour);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'beach_id' => 'required|exists:beaches,id',
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'original_price' => 'nullable|numeric',
            'capacity' => 'required|integer',
            'duration' => 'required|string',
            'status' => 'required|in:active,inactive',
            // chi tiết
            'departure_time' => 'nullable',
            'return_time' => 'nullable',
            'included_services' => 'nullable',
            'excluded_services' => 'nullable',
            'highlights' => 'nullable',
        ]);
        $tour = Tour::create($validated);
        $tour->detail()->create([
            'departure_time' => $validated['departure_time'] ?? null,
            'return_time' => $validated['return_time'] ?? null,
            'included_services' => isset($validated['included_services']) ? json_encode($validated['included_services']) : null,
            'excluded_services' => isset($validated['excluded_services']) ? json_encode($validated['excluded_services']) : null,
            'highlights' => isset($validated['highlights']) ? json_encode($validated['highlights']) : null,
        ]);
        return response()->json($tour->load('detail'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'beach_id' => 'required|exists:beaches,id',
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'original_price' => 'nullable|numeric',
            'capacity' => 'required|integer',
            'duration' => 'required|string',
            'status' => 'required|in:active,inactive',
            'departure_time' => 'nullable',
            'return_time' => 'nullable',
            'included_services' => 'nullable',
            'excluded_services' => 'nullable',
            'highlights' => 'nullable',
        ]);
        $tour = Tour::findOrFail($id);
        $tour->update($validated);
        $tour->detail()->updateOrCreate(
            ['tour_id' => $tour->id],
            [
                'departure_time' => $validated['departure_time'] ?? null,
                'return_time' => $validated['return_time'] ?? null,
                'included_services' => isset($validated['included_services']) ? json_encode($validated['included_services']) : null,
                'excluded_services' => isset($validated['excluded_services']) ? json_encode($validated['excluded_services']) : null,
                'highlights' => isset($validated['highlights']) ? json_encode($validated['highlights']) : null,
            ]
        );
        return response()->json($tour->load('detail'));
    }

    public function destroy($id)
    {
        $tour = Tour::findOrFail($id);
        $tour->delete();
        return response()->noContent();
    }
}
