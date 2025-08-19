<?php

namespace App\Http\Controllers;

use App\Models\SupportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportRequest::with('user');
        
        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }
        
        // Lọc theo status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Sắp xếp với ưu tiên: pending > in_progress > resolved > rejected
        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'priority':
                    // Sắp xếp theo độ ưu tiên
                    $query->orderByRaw("
                        CASE status 
                            WHEN 'pending' THEN 1 
                            WHEN 'in_progress' THEN 2 
                            WHEN 'resolved' THEN 3 
                            WHEN 'rejected' THEN 4 
                        END
                    ");
                    break;
                case 'name':
                    $order = $request->get('sort_order', 'asc');
                    $query->orderBy('name', $order);
                    break;
                case 'email':
                    $order = $request->get('sort_order', 'asc');
                    $query->orderBy('email', $order);
                    break;
                case 'created_at':
                    $order = $request->get('sort_order', 'desc');
                    $query->orderBy('created_at', $order);
                    break;
                default:
                    // Mặc định sắp xếp theo ưu tiên và ngày tạo
                    $query->orderByRaw("
                        CASE status 
                            WHEN 'pending' THEN 1 
                            WHEN 'in_progress' THEN 2 
                            WHEN 'resolved' THEN 3 
                            WHEN 'rejected' THEN 4 
                        END
                    ")->orderBy('created_at', 'desc');
            }
        } else {
            // Mặc định sắp xếp theo ưu tiên và ngày tạo
            $query->orderByRaw("
                CASE status 
                    WHEN 'pending' THEN 1 
                    WHEN 'in_progress' THEN 2 
                    WHEN 'resolved' THEN 3 
                    WHEN 'rejected' THEN 4 
                END
            ")->orderBy('created_at', 'desc');
        }
        
        $supportRequests = $query->paginate(5)->withQueryString();
        
        return view('admin.support.index', compact('supportRequests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        SupportRequest::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'title' => $request->title,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Support request submitted successfully!');
    }

    public function show(SupportRequest $supportRequest)
    {
        $supportRequest->load('user');
        return view('admin.support.show', compact('supportRequest'));
    }

    public function updateStatus(Request $request, SupportRequest $supportRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,rejected',
        ]);

        $supportRequest->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Support request status updated successfully!');
    }

    public function destroy(SupportRequest $supportRequest)
    {
        $supportRequest->delete();
        return redirect()->back()->with('success', 'Support request deleted successfully!');
    }
} 