<?php
namespace App\Http\Controllers;

use App\Models\CancellationRequest;
use App\Models\TourBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CancellationRequestController extends Controller
{
    // Hiển thị form hủy booking cho user
    public function showCancelForm($bookingId)
    {
        $booking = TourBooking::findOrFail($bookingId);
        // Chỉ cho phép user là chủ booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        return view('user.history.cancel', compact('booking'));
    }
    // Gửi yêu cầu hủy
    public function store(Request $request, $bookingId)
    {
        $booking = TourBooking::findOrFail($bookingId);
        // Kiểm tra quyền user
        if ($booking->user_id !== Auth::id()) {
            return back()->with('error', 'Bạn không có quyền hủy đơn này.');
        }
        // Kiểm tra trạng thái booking (ví dụ: không cho hủy nếu đã khởi hành)
        if ($booking->status === 'cancelled' || $booking->isPastTour()) {
            return back()->with('error', 'Không thể hủy đơn này.');
        }
        // Kiểm tra nếu đã có yêu cầu hủy full cho booking này
        $existingFull = CancellationRequest::where('booking_id', $booking->id)
            ->where('cancellation_type', 'full')
            ->where('status', 'pending')
            ->first();
        if ($existingFull) {
            return back()->with('error', 'Bạn đã gửi yêu cầu hủy toàn bộ cho booking này.');
        }
        // Kiểm tra tổng số slot đã yêu cầu hủy (pending)
        $pendingRequests = CancellationRequest::where('booking_id', $booking->id)
            ->where('status', 'pending')
            ->get();
        $pendingSlots = $pendingRequests->sum('cancelled_slots');
        if ($pendingSlots >= $booking->number_of_people) {
            return back()->with('error', 'Bạn đã yêu cầu hủy hết số lượng người trong booking này.');
        }
        $validated = $request->validate([
            'cancellation_type' => 'required|in:full,partial',
            'cancelled_slots' => 'nullable|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);
        // Nếu partial thì phải có số lượng
        if ($validated['cancellation_type'] === 'partial' && empty($validated['cancelled_slots'])) {
            return back()->with('error', 'Vui lòng nhập số lượng muốn hủy.');
        }
        // Nếu full thì cancelled_slots = số người đã đặt
        $cancelledSlots = $validated['cancellation_type'] === 'full' ? $booking->number_of_people : $validated['cancelled_slots'];
        $cancellation = CancellationRequest::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'cancellation_type' => $validated['cancellation_type'],
            'cancelled_slots' => $cancelledSlots,
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);
        return redirect('user/history')->with('success', 'Yêu cầu hủy đã được gửi.Đã gửi thông tin hủy
                cho CEO trực thuộc, vui lòng chờ
                duyệt.');
    }

    // ceo duyệt/từ chối yêu cầu
    public function update(Request $request, $id)
    {
        $cancellation = CancellationRequest::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'reject_reason' => 'nullable|string|max:255',
        ]);
        DB::transaction(function () use ($cancellation, $validated) {
            $cancellation->status = $validated['status'];
            if ($validated['status'] === 'rejected') {
                $cancellation->reject_reason = $validated['reject_reason'] ?? null;
            }
            $cancellation->save();
            if ($validated['status'] === 'approved') {
                $booking = $cancellation->booking;
                $tickets = $booking->tickets()->where('status', 'valid')->get();
                if ($cancellation->cancellation_type === 'full') {
                    $booking->status = 'cancelled';
                    $booking->save();
                    // Hủy tất cả vé
                    foreach ($tickets as $ticket) {
                        $ticket->status = 'cancelled';
                        $ticket->save();
                    }
                } elseif ($cancellation->cancellation_type === 'partial' && $cancellation->cancelled_slots) {
                    $booking->number_of_people -= $cancellation->cancelled_slots;
                    if ($booking->number_of_people <= 0) {
                        $booking->status = 'cancelled';
                    }
                    $booking->save();
                    // Hủy số vé tương ứng
                    $cancelCount = $cancellation->cancelled_slots;
                    foreach ($tickets as $ticket) {
                        if ($cancelCount <= 0)
                            break;
                        $ticket->status = 'cancelled';
                        $ticket->save();
                        $cancelCount--;
                    }
                }
                // TODO: hoàn tiền, cập nhật inventory nếu cần
            }
        });
        return back()->with('success', 'Yêu cầu đã được xử lý.');
    }

    // Hiển thị danh sách yêu cầu hủy cho admin
    public function index()
    {
        $requests = CancellationRequest::with('booking', 'user')->orderByDesc('created_at')->get();
        return view('ceo.cancellation_requests.index', compact('requests'));
    }

    // Hiển thị yêu cầu hủy của user
    public function myRequests()
    {
        $requests = CancellationRequest::where('user_id', Auth::id())->with('booking')->orderByDesc('created_at')->get();
        return view('user.cancellation_requests.index', compact('requests'));
    }
}
