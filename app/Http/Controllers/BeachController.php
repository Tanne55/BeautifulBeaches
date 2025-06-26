<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beach;

class BeachController extends Controller
{
    // Hàm xử lý dữ liệu bãi biển, dùng cho view
    protected function getBeachesData()
    {
        return Beach::with('detail')->get()->map(function ($beach) {
            return (object) [
                'id' => $beach->id,
                'region' => $beach->region,
                'image' => $beach->image,
                'title' => $beach->title,
                'short_description' => $beach->short_description,
                'long_description' => $beach->detail->long_description ?? null,
                'long_description_2' => $beach->detail->long_description2 ?? null,
                'highlight_quote' => $beach->detail->highlight_quote ?? null,
                'tags' => $beach->detail ? json_decode($beach->detail->tags, true) : [],
            ];
        });
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
        $reviews = \App\Models\ReviewBeach::with('user')->where('beach_id', $beach->id)->latest()->get();
        return view('pages.detail', compact('beach', 'reviews'));
    }


    // Hiển thị danh sách bãi biển cho admin
    public function index()
    {
        $beaches = $this->getBeachesData();
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'nullable|string',
            'long_description2' => 'nullable|string',
            'highlight_quote' => 'nullable|string',
            'tags' => 'nullable',
        ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagepath = $image->store('beaches', 'public');
            $validated['image'] = $imagepath;
        }
        $beach = Beach::create([
            'region' => $validated['region'],
            'image' => $imagePath,
            'title' => $validated['title'],
            'short_description' => $validated['short_description'],
        ]);
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
        $beaches = $this->getBeachesData();
        $beach = $beaches->firstWhere('id', $id);
        return view('admin.beaches.edit', compact('beach'));
    }

    // Cập nhật bãi biển
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'region' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'nullable|string',
            'long_description2' => 'nullable|string',
            'highlight_quote' => 'nullable|string',
            'tags' => 'nullable',
        ]);
        $beach = Beach::findOrFail($id);
        // Xử lý upload ảnh nếu có file mới
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('beaches', 'public');
            $validated['image'] = $imagePath;
        } else {
            // Nếu không upload ảnh mới, giữ nguyên ảnh cũ
            $validated['image'] = $beach->image;
        }
        $beach->update([
            'region' => $validated['region'],
            'image' => $validated['image'],
            'title' => $validated['title'],
            'short_description' => $validated['short_description'],
        ]);
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
