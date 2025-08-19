<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beach;

class BeachController extends Controller
{
    // Hàm xử lý dữ liệu bãi biển, dùng cho view
    protected function getBeachesData()
    {
        return Beach::with(['detail', 'region'])->get()->map(function ($beach) {
            return (object) [
                'id' => $beach->id,
                'region_id' => $beach->region_id,
                'region' => $beach->region_name,
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
    public function index(Request $request)
    {
                // Eager loading với images để check fallback
        $query = Beach::with(['detail', 'region', 'images' => function($query) {
            $query->orderBy('is_primary', 'desc')->orderBy('sort_order');
        }]);
        
        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('short_description', 'like', "%{$searchTerm}%")
                  ->orWhereHas('detail', function($detailQuery) use ($searchTerm) {
                      $detailQuery->where('long_description', 'like', "%{$searchTerm}%")
                                  ->orWhere('long_description2', 'like', "%{$searchTerm}%");
                  });
            });
        }
        
        // Lọc theo vùng
        if ($request->filled('region') && $request->region != 'all') {
            $query->where('region_id', $request->region);
        }
        
        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        switch ($sortBy) {
            case 'title':
                $query->orderBy('title', $sortOrder);
                break;
            case 'region':
                $query->join('regions', 'beaches.region_id', '=', 'regions.id')
                      ->orderBy('regions.name', $sortOrder)
                      ->select('beaches.*');
                break;
            case 'created_at':
            default:
                $query->orderBy('created_at', $sortOrder);
                break;
        }
        
        // Phân trang
        $beaches = $query->paginate(10)->withQueryString();
        
        // Lấy danh sách regions cho filter
        $regions = \App\Models\Region::all();
        
        return view('admin.beaches.index', compact('beaches', 'regions'));
    }

    // Hiển thị form thêm mới bãi biển
    public function create()
    {
        $regions = \App\Models\Region::all();
        return view('admin.beaches.create', compact('regions'));
    }

    // Lưu bãi biển mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'primary_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'nullable|string',
            'long_description2' => 'nullable|string',
            'highlight_quote' => 'nullable|string',
            'tags' => 'nullable',
        ]);

        // Xử lý upload ảnh chính (chỉ lưu vào trường image của beaches table)
        $primaryImagePath = null;
        if ($request->hasFile('primary_image')) {
            $image = $request->file('primary_image');
            $primaryImagePath = $image->store('beaches', 'public');
        }

        // Xử lý tags
        $tags = $validated['tags'] ?? null;
        if ($tags) {
            $tags = trim($tags);
            if ($tags[0] === '[') {
                $tagsArray = json_decode($tags, true);
            } else {
                $tagsArray = array_map('trim', explode(',', $tags));
            }
            if (!is_array($tagsArray)) {
                $tagsArray = [];
            }
        } else {
            $tagsArray = [];
        }
        
        // Tạo bãi biển với ảnh chính
        $beach = Beach::create([
            'region_id' => $validated['region_id'],
            'image' => $primaryImagePath,
            'title' => $validated['title'],
            'short_description' => $validated['short_description'],
        ]);

        // Xử lý ảnh bổ sung (lưu vào beach_images)
        if ($request->hasFile('additional_images')) {
            $sortOrder = 1;
            foreach ($request->file('additional_images') as $image) {
                $imagePath = $image->store('beaches', 'public');
                \App\Models\BeachImage::create([
                    'beach_id' => $beach->id,
                    'image_url' => $imagePath,
                    'alt_text' => $beach->title,
                    'caption' => 'Ảnh bổ sung của ' . $beach->title,
                    'is_primary' => false,
                    'sort_order' => $sortOrder,
                    'image_type' => 'additional'
                ]);
                $sortOrder++;
            }
        }

        $detailData = [
            'long_description' => $validated['long_description'] ?? null,
            'long_description2' => $validated['long_description2'] ?? null,
            'highlight_quote' => $validated['highlight_quote'] ?? null,
            'tags' => json_encode($tagsArray),
        ];
        
        $beachDetail = $beach->detail()->create($detailData);
        return redirect()->route('admin.beaches.index')->with('success', 'Thêm bãi biển thành công!');
    }

    // Hiển thị form sửa bãi biển
    public function edit($id)
    {
        $beach = Beach::with(['detail', 'region', 'images' => function($query) {
            $query->orderBy('sort_order');
        }])->findOrFail($id);
        $regions = \App\Models\Region::all();
        return view('admin.beaches.edit', compact('beach', 'regions'));
    }

    // Cập nhật bãi biển
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'primary_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'long_description' => 'nullable|string',
            'long_description2' => 'nullable|string',
            'highlight_quote' => 'nullable|string',
            'tags' => 'nullable',
            'deleted_images' => 'nullable|string',
        ]);

        $beach = Beach::findOrFail($id);

        // Xử lý ảnh chính mới (chỉ update field image trong beaches table)
        if ($request->hasFile('primary_image')) {
            $primaryImagePath = $request->file('primary_image')->store('beaches', 'public');
            $beach->update(['image' => $primaryImagePath]);
        }

        // Xử lý xóa ảnh bổ sung (nếu có)
        if ($request->filled('deleted_images')) {
            $deletedImageIds = explode(',', $request->deleted_images);
            \App\Models\BeachImage::whereIn('id', $deletedImageIds)->delete();
        }

        // Xử lý ảnh bổ sung mới (vẫn lưu vào beach_images nếu cần)
        if ($request->hasFile('additional_images')) {
            $maxSortOrder = $beach->images()->where('is_primary', false)->max('sort_order') ?: 0;
            $sortOrder = $maxSortOrder + 1;
            
            foreach ($request->file('additional_images') as $image) {
                $imagePath = $image->store('beaches', 'public');
                \App\Models\BeachImage::create([
                    'beach_id' => $beach->id,
                    'image_url' => $imagePath,
                    'alt_text' => $beach->title,
                    'caption' => 'Ảnh bổ sung của ' . $beach->title,
                    'is_primary' => false,
                    'sort_order' => $sortOrder,
                    'image_type' => 'additional'
                ]);
                $sortOrder++;
            }
        }

        $tags = $validated['tags'] ?? null;
        if ($tags) {
            $tags = trim($tags);
            if ($tags[0] === '[') {
                $tagsArray = json_decode($tags, true);
            } else {
                $tagsArray = array_map('trim', explode(',', $tags));
            }
            if (!is_array($tagsArray)) {
                $tagsArray = [];
            }
        } else {
            $tagsArray = [];
        }

        $beach->update([
            'region_id' => $validated['region_id'],
            'title' => $validated['title'],
            'short_description' => $validated['short_description'],
        ]);
        
        $detailData = [
            'long_description' => $validated['long_description'] ?? null,
            'long_description2' => $validated['long_description2'] ?? null,
            'highlight_quote' => $validated['highlight_quote'] ?? null,
            'tags' => json_encode($tagsArray),
        ];
        
        $beachDetail = $beach->detail()->updateOrCreate(
            ['beach_id' => $beach->id],
            $detailData
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
