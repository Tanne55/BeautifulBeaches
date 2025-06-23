<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beach;

class BeachController extends Controller
{
    public function getBeaches()
    {
        $beaches = Beach::all()->map(function ($beach) {
            return [
                'id' => $beach->id,
                'region' => $beach->region,
                'image' => $beach->image,
                'title' => $beach->title,
                'shortDescription' => $beach->short_description,
                'longDescription' => $beach->long_description,
                'longDescription2' => $beach->long_description_2,
                'highlightQuote' => $beach->highlight_quote,
                'tags' => json_decode($beach->tags, true),
                'price' => $beach->price,
                'originalPrice' => $beach->original_price,
                'capacity' => $beach->capacity,
                'duration' => $beach->duration,
                'rating' => $beach->rating,
                'reviews' => $beach->reviews,
            ];
        });

        return response()->json($beaches);
    }
    public function show(Beach $beach)
    {

        return view('pages.detail', compact('beach'));
    }

    // Hiển thị danh sách bãi biển cho admin
    public function index()
    {
        $beaches = Beach::all();
        return view('admin.beaches.index', compact('beaches'));
    }

    // Hiển thị form thêm mới bãi biển
    public function create()
    {
        return view('admin.beaches.create');
    }

    // Lưu bãi biển mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'region' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'nullable|string',
            'long_description_2' => 'nullable|string',
            'highlight_quote' => 'nullable|string',
            'tags' => 'nullable|string',
            'price' => 'nullable|numeric',
            'original_price' => 'nullable|numeric',
            'capacity' => 'nullable|integer',
            'duration' => 'nullable|string',
            'rating' => 'nullable|numeric',
            'reviews' => 'nullable|integer',
        ]);
        $beach = Beach::create($validated);
        return redirect()->route('admin.beaches.index')->with('success', 'Thêm bãi biển thành công!');
    }

    // Hiển thị form sửa bãi biển
    public function edit($id)
    {
        $beach = Beach::findOrFail($id);
        return view('admin.beaches.edit', compact('beach'));
    }

    // Cập nhật bãi biển
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'region' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'nullable|string',
            'long_description_2' => 'nullable|string',
            'highlight_quote' => 'nullable|string',
            'tags' => 'nullable|string',
            'price' => 'nullable|numeric',
            'original_price' => 'nullable|numeric',
            'capacity' => 'nullable|integer',
            'duration' => 'nullable|string',
            'rating' => 'nullable|numeric',
            'reviews' => 'nullable|integer',
        ]);
        $beach = Beach::findOrFail($id);
        $beach->update($validated);
        return redirect()->route('admin.beaches.index')->with('success', 'Cập nhật bãi biển thành công!');
    }

    // Xóa bãi biển
    public function destroy($id)
    {
        $beach = Beach::findOrFail($id);
        $beach->delete();
        return redirect()->route('admin.beaches.index')->with('success', 'Xóa bãi biển thành công!');
    }
}
