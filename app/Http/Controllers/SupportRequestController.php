<?php

namespace App\Http\Controllers;

use App\Models\SupportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportRequestController extends Controller
{
    public function index()
    {
        $supportRequests = SupportRequest::with('user')->paginate(10);
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