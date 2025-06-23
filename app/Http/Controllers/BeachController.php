<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beach;

class BeachController extends Controller
{
    public function getBeaches()
    {
        $beaches = Beach::with('detail')->get()->map(function ($beach) {
            return [
                'id' => $beach->id,
                'region' => $beach->region,
                'image' => $beach->image,
                'title' => $beach->title,
                'shortDescription' => $beach->short_description,
                'longDescription' => $beach->detail->long_description ?? null,
                'longDescription2' => $beach->detail->long_description2 ?? null,
                'highlightQuote' => $beach->detail->highlight_quote ?? null,
                'tags' => $beach->detail ? json_decode($beach->detail->tags, true) : [],
            ];
        });
        return response()->json($beaches);
    }
    public function show(Beach $beach)
    {
        $detail = $beach->detail;
        if ($detail) {
            $beach->long_description = $detail->long_description;
            $beach->highlight_quote = $detail->highlight_quote;
            $beach->long_description_2 = $detail->long_description2;
            $beach->tags = $detail->tags;
        } else {
            $beach->long_description = null;
            $beach->highlight_quote = null;
            $beach->long_description_2 = null;
            $beach->tags = json_encode([]);
        }
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
            'long_description2' => 'nullable|string',
            'highlight_quote' => 'nullable|string',
            'tags' => 'nullable',
        ]);
        $beach = Beach::create($validated);
        $beach->detail()->create([
            'long_description' => $validated['long_description'] ?? null,
            'long_description2' => $validated['long_description2'] ?? null,
            'highlight_quote' => $validated['highlight_quote'] ?? null,
            'tags' => isset($validated['tags']) ? json_encode($validated['tags']) : null,
        ]);
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
            'long_description2' => 'nullable|string',
            'highlight_quote' => 'nullable|string',
            'tags' => 'nullable',
        ]);
        $beach = Beach::findOrFail($id);
        $beach->update($validated);
        $beach->detail()->updateOrCreate(
            ['beach_id' => $beach->id],
            [
                'long_description' => $validated['long_description'] ?? null,
                'long_description2' => $validated['long_description2'] ?? null,
                'highlight_quote' => $validated['highlight_quote'] ?? null,
                'tags' => isset($validated['tags']) ? json_encode($validated['tags']) : null,
            ]
        );
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
