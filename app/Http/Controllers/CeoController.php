<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\TourBooking;
use App\Models\TourBookingGroup;
use App\Models\Ticket;
use Illuminate\Support\Arr;

class CeoController extends Controller
{
    public function cancellationRequests(Request $request)
    {
        $ceoId = Auth::id();
        $now = now();
        $month = $request->input('month', $now->format('Y-m'));
        $viewMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->format('m/Y');
        $startOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $requestsQuery = \App\Models\CancellationRequest::with(['booking.tour', 'user'])
            ->whereHas('booking.tour', fn($q) => $q->where('ceo_id', $ceoId))
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);

        // Lọc theo tour
        if ($request->filled('tour')) {
            $requestsQuery->whereHas('booking.tour', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->input('tour') . '%');
            });
        }
        // Lọc theo người đặt
        if ($request->filled('user')) {
            $requestsQuery->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('user') . '%');
            });
        }
        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $requestsQuery->where('status', $request->input('status'));
        }
        // Lọc theo khoảng ngày gửi
        if ($request->filled('from')) {
            $requestsQuery->whereDate('created_at', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $requestsQuery->whereDate('created_at', '<=', $request->input('to'));
        }

        $requests = $requestsQuery->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('ceo.cancellation_requests.index', [
            'requests' => $requests,
            'viewMonth' => $viewMonth,
        ]);
    }
    public function dashboard()
    {
        // Lấy thông tin CEO hiện tại và profile của họ
        $user = Auth::user();
        $profile = \App\Models\UserProfile::where('user_id', $user->id)->first();
        
        // Lấy số lượng người dùng (CEO có quyền xem)
        $userCount = \App\Models\User::count();
        
        // Lấy số lượng bãi biển
        $beachCount = \App\Models\Beach::count();
        
        // Lấy số lượng booking cho các tour của CEO này
        $bookingCount = TourBooking::whereHas('tour', function($query) use ($user) {
            $query->where('ceo_id', $user->id);
        })->count();
        
        // Lấy tổng doanh thu từ các vé tour của CEO này
        $totalRevenue = Ticket::whereHas('tourBooking.tour', function($query) use ($user) {
            $query->where('ceo_id', $user->id);
        })
        ->whereIn('status', ['valid', 'used']) // Tính cả vé hợp lệ và đã sử dụng
        ->sum('unit_price');
        
        return view('ceo.dashboard', compact('user', 'profile', 'userCount', 'beachCount', 'bookingCount', 'totalRevenue'));
    }

    public function bookings(Request $request)
    {
        $ceoId = Auth::id();
        $now = now();
        $month = $request->input('month', $now->format('Y-m'));
        $startOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $bookingsQuery = TourBooking::with(['tour', 'tickets'])
            ->whereHas('tour', function ($q) use ($ceoId) {
                $q->where('ceo_id', $ceoId);
            })
            ->whereBetween('booking_date', [$startOfMonth, $endOfMonth]);

        // Lọc theo tour
        if ($request->filled('tour')) {
            $bookingsQuery->whereHas('tour', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->input('tour') . '%');
            });
        }
        // Lọc theo người đặt
        if ($request->filled('user')) {
            $bookingsQuery->where('full_name', 'like', '%' . $request->input('user') . '%');
        }
        // Lọc theo ngày khởi hành
        if ($request->filled('departure_date')) {
            $bookingsQuery->where('selected_departure_date', $request->input('departure_date'));
        }
        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $bookingsQuery->where('status', $request->input('status'));
        }

        $bookings = $bookingsQuery->orderByDesc('booking_date')->paginate(15)->withQueryString();

        // Nhóm bookings theo tour và ngày khởi hành được chọn
        // Đảm bảo luôn có collection an toàn
        $groupedBookingsList = collect();
        try {
            if ($bookings && $bookings->count() > 0) {
                $groupedBookingsList = $bookings->getCollection()->groupBy(function ($booking) {
                    $departureDate = 'no-departure';
                    if ($booking->selected_departure_date) {
                        try {
                            $departureDate = $booking->selected_departure_date->format('Y-m-d');
                        } catch (\Exception $e) {
                            $departureDate = 'no-departure';
                        }
                    }
                    // Group by tour_id và departure date thay vì booking_date
                    return $booking->tour_id . '_' . $departureDate;
                });
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error grouping bookings: ' . $e->getMessage());
            $groupedBookingsList = collect();
        }

        // Tính tổng tiền cho từng nhóm booking (an toàn)
        $groupedTotalAmounts = [];
        try {
            foreach ($groupedBookingsList as $key => $group) {
                if ($group && is_object($group) && method_exists($group, 'sum')) {
                    $groupedTotalAmounts[$key] = $group->sum('total_amount');
                } else {
                    $groupedTotalAmounts[$key] = 0;
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error calculating group totals: ' . $e->getMessage());
            $groupedTotalAmounts = [];
        }

        // Lấy thông tin các nhóm đã tạo (an toàn)
        $bookingGroupsList = collect();
        try {
            $bookingGroupsList = TourBookingGroup::with(['tour'])
                ->whereHas('tour', function ($q) use ($ceoId) {
                    $q->where('ceo_id', $ceoId);
                })
                ->get()
                ->groupBy(function ($group) {
                    // Lấy ngày khởi hành đầu tiên từ bookings trong nhóm để group
                    $departureDate = 'no-departure';
                    try {
                        $firstBooking = TourBooking::whereIn('id', $group->booking_ids ?? [])->first();
                        if ($firstBooking && $firstBooking->selected_departure_date) {
                            $departureDate = $firstBooking->selected_departure_date->format('Y-m-d');
                        }
                    } catch (\Exception $e) {
                        $departureDate = 'no-departure';
                    }
                    // Group by tour_id và departure date thay vì created_at
                    return $group->tour_id . '_' . $departureDate;
                });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error getting booking groups: ' . $e->getMessage());
            $bookingGroupsList = collect();
        }

        // Lấy danh sách ngày khởi hành available cho filter
        $availableDepartureDates = $this->getAvailableDepartureDates($ceoId, $month);

        // Đảm bảo tất cả biến đều an toàn trước khi truyền vào view
        return view('ceo.bookings.index', compact(
            'bookings', 
            'availableDepartureDates'
        ), [
            'groupedBookings' => $groupedBookingsList,
            'bookingGroups' => $bookingGroupsList,
            'groupedTotalAmounts' => $groupedTotalAmounts
        ]);
    }

    /**
     * Lấy danh sách ngày khởi hành có sẵn cho filter
     */
    private function getAvailableDepartureDates($ceoId, $month = null)
    {
        try {
            $query = TourBooking::select('selected_departure_date')
                ->whereHas('tour', function ($q) use ($ceoId) {
                    $q->where('ceo_id', $ceoId);
                })
                ->whereNotNull('selected_departure_date')
                ->distinct();

            if ($month) {
                $startOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
                $endOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();
                $query->whereBetween('booking_date', [$startOfMonth, $endOfMonth]);
            }

            $result = $query->orderBy('selected_departure_date')
                ->pluck('selected_departure_date')
                ->map(function ($date) {
                    if (!$date) return null;
                    return [
                        'value' => $date->format('Y-m-d'),
                        'label' => $date->format('d/m/Y')
                    ];
                })
                ->filter() // Loại bỏ null values
                ->values() // Reset keys
                ->toArray();
                
            return $result ?? [];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in getAvailableDepartureDates: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy thống kê booking theo ngày khởi hành
     */
    public function departureStats(Request $request)
    {
        $ceoId = Auth::id();
        $now = now();
        $month = $request->input('month', $now->format('Y-m'));
        $startOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $stats = TourBooking::select('selected_departure_date')
            ->selectRaw('COUNT(*) as total_bookings')
            ->selectRaw('SUM(number_of_people) as total_people')
            ->selectRaw('SUM(total_amount) as total_revenue')
            ->whereHas('tour', function ($q) use ($ceoId) {
                $q->where('ceo_id', $ceoId);
            })
            ->whereBetween('booking_date', [$startOfMonth, $endOfMonth])
            ->whereNotNull('selected_departure_date')
            ->where('status', '!=', 'cancelled')
            ->groupBy('selected_departure_date')
            ->orderBy('selected_departure_date')
            ->get()
            ->map(function ($stat) {
                if (!$stat->selected_departure_date) {
                    return null;
                }
                return [
                    'departure_date' => $stat->selected_departure_date->format('d/m/Y'),
                    'departure_date_raw' => $stat->selected_departure_date->format('Y-m-d'),
                    'total_bookings' => $stat->total_bookings,
                    'total_people' => $stat->total_people,
                    'total_revenue' => number_format($stat->total_revenue, 0, ',', '.'),
                ];
            })
            ->filter(); // Loại bỏ null values

        return response()->json($stats);
    }

    public function bookingGroups(Request $request)
    {
        $ceoId = Auth::id();
        $now = now();
        $month = $request->input('month', $now->format('Y-m'));
        $startOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endOfMonth = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $bookingGroupsQuery = \App\Models\TourBookingGroup::with(['tour', 'bookings'])
            ->whereHas('tour', function ($q) use ($ceoId) {
                $q->where('ceo_id', $ceoId);
            })
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);

        // Lọc theo tour
        if ($request->filled('tour')) {
            $bookingGroupsQuery->whereHas('tour', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->input('tour') . '%');
            });
        }
        // Lọc theo người đặt (lọc các nhóm có ít nhất 1 booking với tên người đặt phù hợp)
        if ($request->filled('user')) {
            $bookingGroupsQuery->whereHas('bookings', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->input('user') . '%');
            });
        }
        // Lọc theo ngày khởi hành
        if ($request->filled('departure_date')) {
            $bookingGroupsQuery->where('selected_departure_date', $request->input('departure_date'));
        }
        // Lọc theo trạng thái vé - không thể dùng where với accessor, phải filter sau
        $bookingGroups = $bookingGroupsQuery->orderByDesc('created_at')->get();
        
        $bookingGroups = $bookingGroupsQuery->orderByDesc('created_at')->paginate(10)->withQueryString();
        
        // Filter theo ticket status sau khi get data (với pagination)
        if ($request->filled('ticket_status')) {
            $filteredGroups = collect();
            foreach ($bookingGroups as $group) {
                $shouldInclude = false;
                if ($request->input('ticket_status') === 'not_generated') {
                    $shouldInclude = $group->total_tickets == 0;
                } elseif ($request->input('ticket_status') === 'generated') {
                    $shouldInclude = $group->total_tickets > 0;
                }
                
                if ($shouldInclude) {
                    $filteredGroups->push($group);
                }
            }
            
            // Tạo paginator mới với filtered results
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
            $perPage = 10;
            $currentItems = $filteredGroups->forPage($currentPage, $perPage);
            
            $bookingGroups = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentItems,
                $filteredGroups->count(),
                $perPage,
                $currentPage,
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );
            $bookingGroups->withQueryString();
        }

        // Lấy danh sách ngày khởi hành available cho filter  
        $availableDepartureDates = $this->getAvailableDepartureDates($ceoId, $month);

        return view('ceo.bookings.groups', compact('bookingGroups', 'availableDepartureDates'));
    }

    public function updateBookingStatus(Request $request, $bookingId)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $booking = TourBooking::with('tour')->findOrFail($bookingId);
        if ($booking->tour->ceo_id !== Auth::id()) {
            abort(403);
        }

        $booking->status = $request->status;
        $booking->save();

        return redirect()->route('ceo.bookings.index')->with('success', 'Cập nhật trạng thái booking thành công!');
    }

    public function createBookingGroup(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'selected_departure_date' => 'required|date',
            'booking_ids' => 'required|array|min:1',
            'booking_ids.*' => 'exists:tour_bookings,id',
        ]);

        $ceoId = Auth::id();

        // Kiểm tra quyền sở hữu tour
        $tour = \App\Models\Tour::where('id', $request->tour_id)
            ->where('ceo_id', $ceoId)
            ->firstOrFail();

        // Làm phẳng mảng booking_ids để tránh nested array
        $bookingIds = Arr::flatten($request->booking_ids ?? []);
        
        // Đảm bảo tất cả booking IDs là số nguyên hợp lệ
        $bookingIds = array_filter($bookingIds, function($id) {
            return is_numeric($id) && $id > 0;
        });
        $bookingIds = array_map('intval', $bookingIds);
        
        if (empty($bookingIds)) {
            return back()->with('error', 'Không có booking ID hợp lệ!');
        }
        
        // Kiểm tra tất cả bookings thuộc về CEO này và cùng tour, ngày khởi hành
        $bookings = TourBooking::whereIn('id', $bookingIds)
            ->where('tour_id', $request->tour_id)
            ->where('selected_departure_date', $request->selected_departure_date)
            ->where('status', 'pending')
            ->get();

        if ($bookings->count() !== count($bookingIds)) {
            return back()->with('error', 'Có booking không hợp lệ, không cùng ngày khởi hành hoặc đã được xử lý!');
        }

        // Kiểm tra xem ngày khởi hành có trong danh sách cho phép của tour không
        $tourDetail = $tour->detail;
        if ($tourDetail && !$tourDetail->isDateAvailable($request->selected_departure_date)) {
            return back()->with('error', 'Ngày khởi hành không có trong lịch trình của tour!');
        }

        // Kiểm tra xem có booking nào đã được gom nhóm chưa
        $existingGroupedBookings = TourBooking::whereIn('id', $bookingIds)
            ->where('status', 'grouped')
            ->count();

        if ($existingGroupedBookings > 0) {
            return back()->with('error', 'Có booking đã được gom nhóm trước đó!');
        }

        // Tạo booking group
        $groupCode = 'GRP-' . strtoupper(Str::random(8));
        $totalPeople = $bookings->sum('number_of_people');
        // Lấy đúng giá trị từ DB, không dùng accessor
        $totalAmount = $bookings->sum(function ($b) {
            return $b->getRawOriginal('total_amount');
        });

        $bookingGroup = TourBookingGroup::create([
            'tour_id' => $request->tour_id,
            'group_code' => $groupCode,
            'total_people' => $totalPeople,
            'booking_ids' => $bookingIds,
            'total_amount' => $totalAmount,
            'selected_departure_date' => $request->selected_departure_date, // Thêm ngày khởi hành vào nhóm
        ]);

        // Cập nhật status của các bookings thành grouped
        TourBooking::whereIn('id', $bookingIds)->update(['status' => 'grouped']);

        $bookingCount = count($bookingIds);
        $departureDate = \Carbon\Carbon::parse($request->selected_departure_date)->format('d/m/Y');
        return redirect()->route('ceo.bookings.index')
            ->with('success', "Đã tạo nhóm booking thành công! Mã nhóm: {$groupCode} ({$bookingCount} bookings, {$totalPeople} người, khởi hành: {$departureDate})");
    }

    public function generateTicketsForGroup(Request $request, $groupId)
    {
        $bookingGroup = TourBookingGroup::with(['tour'])->findOrFail($groupId);

        // Kiểm tra quyền sở hữu
        if ($bookingGroup->tour->ceo_id !== Auth::id()) {
            abort(403);
        }

        // Kiểm tra xem đã sinh vé chưa
        $bookingIds = Arr::flatten($bookingGroup->booking_ids ?? []);
        $bookings = TourBooking::whereIn('id', $bookingIds)->get();
        $totalTickets = $bookings->sum(function ($booking) {
            return $booking->tickets()->count();
        });

        if ($totalTickets > 0) {
            return back()->with('error', 'Đã sinh vé cho nhóm này rồi!');
        }

        // Sinh vé cho từng booking trong nhóm
        foreach ($bookings as $booking) {
            $unitPrice = $this->getTicketUnitPrice($booking);
            for ($i = 0; $i < $booking->number_of_people; $i++) {
                do {
                    $ticketCode = 'TIX-' . strtoupper(Str::random(8));
                } while (Ticket::where('ticket_code', $ticketCode)->exists());

                Ticket::create([
                    'tour_booking_id' => $booking->id,
                    'ticket_code' => $ticketCode,
                    'full_name' => $booking->full_name,
                    'email' => $booking->contact_email,
                    'phone' => $booking->contact_phone,
                    'status' => 'valid',
                    'unit_price' => $unitPrice, // Lưu giá vé đơn vị
                ]);
            }
            // Cập nhật total_amount dựa trên số vé hợp lệ
            $this->updateBookingTotalAmount($booking->id);
        }

        // Cập nhật status của bookings thành confirmed
        TourBooking::whereIn('id', $bookingIds)->update(['status' => 'confirmed']);

        return redirect()->route('ceo.bookings.index')
            ->with('success', "Đã sinh vé thành công cho nhóm {$bookingGroup->group_code}!");
    }

    public function reports(Request $request)
    {
        $ceoId = Auth::id();
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $viewMonth = $now->format('m/Y');

        // Tổng quan - tính doanh thu dựa trên giá của từng vé hợp lệ và đã sử dụng
        $totalRevenue = Ticket::whereIn('status', ['valid', 'used'])
            ->whereHas('tourBooking.tour', fn($q) => $q->where('ceo_id', $ceoId))
            ->whereHas('tourBooking', fn($q) => $q->whereBetween('booking_date', [$startOfMonth, $endOfMonth]))
            ->sum('unit_price');

        $totalBookings = TourBooking::whereHas('tour', fn($q) => $q->where('ceo_id', $ceoId))
            ->where('status', 'confirmed')
            ->whereBetween('booking_date', [$startOfMonth, $endOfMonth])
            ->count();

        $totalTickets = Ticket::whereHas('tourBooking.tour', fn($q) => $q->where('ceo_id', $ceoId))
            ->whereHas('tourBooking', fn($q) => $q->whereBetween('booking_date', [$startOfMonth, $endOfMonth]))
            ->count();

        $arpu = $totalTickets > 0 ? round($totalRevenue / $totalTickets, 2) : 0;

        // Tăng trưởng doanh thu so với tháng trước
        $lastMonth = $now->copy()->subMonth();
        $startOfLastMonth = $lastMonth->copy()->startOfMonth();
        $endOfLastMonth = $lastMonth->copy()->endOfMonth();
        $lastMonthRevenue = Ticket::whereIn('status', ['valid', 'used'])
            ->whereHas('tourBooking.tour', fn($q) => $q->where('ceo_id', $ceoId))
            ->whereHas('tourBooking', fn($q) => $q->whereBetween('booking_date', [$startOfLastMonth, $endOfLastMonth]))
            ->sum('unit_price');
        $growthRevenue = $lastMonthRevenue > 0 ? round((($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2) : null;

        // Top tour doanh thu cao/thấp
        $tourRevenue = Ticket::whereIn('status', ['valid', 'used'])
            ->whereHas('tourBooking.tour', fn($q) => $q->where('ceo_id', $ceoId))
            ->whereHas('tourBooking', fn($q) => $q->whereBetween('booking_date', [$startOfMonth, $endOfMonth]))
            ->with('tourBooking.tour')
            ->get()
            ->groupBy(fn($ticket) => $ticket->tourBooking->tour->title ?? 'N/A')
            ->map(function ($tickets) {
                return $tickets->sum('unit_price');
            });

        $topTours = $tourRevenue->sortDesc()->take(5)->toArray() ?: [];
        $bottomTours = $tourRevenue->sort()->take(5)->toArray() ?: [];

        // Biểu đồ doanh thu theo tháng (12 tháng gần nhất)
        $monthlyRevenue = collect();
        for ($i = 0; $i < 12; $i++) {
            $month = $now->copy()->subMonths($i);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();
            $revenue = Ticket::whereIn('status', ['valid', 'used'])
                ->whereHas('tourBooking.tour', fn($q) => $q->where('ceo_id', $ceoId))
                ->whereHas('tourBooking', fn($q) => $q->whereBetween('booking_date', [$start, $end]))
                ->sum('unit_price');
            $monthlyRevenue->prepend([
                'month' => $month->format('Y-m'),
                'revenue' => $revenue
            ]);
        }

        // Filter parameters
        $from = $request->input('from');
        $to = $request->input('to');
        $tour = $request->input('tour');
        $people = $request->input('people');

        // Individual Bookings Query
        $bookingsQuery = TourBooking::with(['tour', 'tickets'])
            ->whereHas('tour', fn($q) => $q->where('ceo_id', $ceoId))
            ->where('status', 'confirmed')
            ->whereBetween('booking_date', [$startOfMonth, $endOfMonth]);

        if ($from) {
            $bookingsQuery->whereDate('booking_date', '>=', $from);
        }
        if ($to) {
            $bookingsQuery->whereDate('booking_date', '<=', $to);
        }
        if ($tour) {
            $bookingsQuery->whereHas('tour', function ($q) use ($tour) {
                $q->where('title', 'like', '%' . $tour . '%');
            });
        }
        if ($people === '>=4') {
            $bookingsQuery->where('number_of_people', '>=', 4);
        } elseif ($people === '<4') {
            $bookingsQuery->where('number_of_people', '<', 4);
        }

        $bookings = $bookingsQuery->orderByDesc('booking_date')->paginate(5)->withQueryString();

        // Group Bookings Query
        $groupFrom = $request->input('group_from');
        $groupTo = $request->input('group_to');
        $groupTour = $request->input('group_tour');
        $ticketStatus = $request->input('ticket_status');

        $bookingGroupsQuery = TourBookingGroup::with(['tour', 'bookings'])
            ->whereHas('tour', fn($q) => $q->where('ceo_id', $ceoId))
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);

        if ($groupFrom) {
            $bookingGroupsQuery->whereDate('created_at', '>=', $groupFrom);
        }
        if ($groupTo) {
            $bookingGroupsQuery->whereDate('created_at', '<=', $groupTo);
        }
        if ($groupTour) {
            $bookingGroupsQuery->whereHas('tour', function ($q) use ($groupTour) {
                $q->where('title', 'like', '%' . $groupTour . '%');
            });
        }

        // Get all groups first, then filter by ticket status
        $allBookingGroups = $bookingGroupsQuery->orderByDesc('created_at')->get();
        
        if ($ticketStatus) {
            $allBookingGroups = $allBookingGroups->filter(function ($group) use ($ticketStatus) {
                if ($ticketStatus === 'not_generated') {
                    return $group->total_tickets == 0;
                } elseif ($ticketStatus === 'generated') {
                    return $group->total_tickets > 0;
                }
                return true;
            });
        }

        // Manual pagination for filtered results
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage('groups_page');
        $perPage = 5;
        $currentItems = $allBookingGroups->forPage($currentPage, $perPage);
        
        $bookingGroups = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $allBookingGroups->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'groups_page']
        );
        $bookingGroups->withQueryString();

        return view('ceo.reports.index', [
            'now' => $now,
            'totalRevenue' => $totalRevenue,
            'totalBookings' => $totalBookings,
            'totalTickets' => $totalTickets,
            'arpu' => $arpu,
            'growthRevenue' => $growthRevenue,
            'topTours' => $topTours,
            'bottomTours' => $bottomTours,
            'monthlyRevenue' => $monthlyRevenue,
            'bookings' => $bookings,
            'bookingGroups' => $bookingGroups,
            'viewMonth' => $viewMonth,
        ]);
    }

    public function confirmBooking(Request $request, $bookingId)
    {
        $booking = TourBooking::with(['tour', 'tickets'])->findOrFail($bookingId);
        if ($booking->tour->ceo_id !== Auth::id()) {
            abort(403);
        }
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Booking đã được xử lý!');
        }

        // Sinh vé nếu chưa có vé nào
        if ($booking->tickets->count() === 0) {
            $unitPrice = $this->getTicketUnitPrice($booking);
            for ($i = 0; $i < $booking->number_of_people; $i++) {
                do {
                    $code = 'TIX-' . strtoupper(Str::random(8));
                } while (Ticket::where('ticket_code', $code)->exists());

                Ticket::create([
                    'tour_booking_id' => $booking->id,
                    'ticket_code' => $code,
                    'full_name' => $booking->full_name,
                    'email' => $booking->contact_email,
                    'phone' => $booking->contact_phone,
                    'status' => 'valid',
                    'unit_price' => $unitPrice, // Lưu giá vé đơn vị
                ]);
            }
            
            // Cập nhật total_amount dựa trên số vé hợp lệ
            $this->updateBookingTotalAmount($booking->id);
        }

        $booking->status = 'confirmed';
        $booking->save();

        return redirect()->route('ceo.bookings.index')->with('success', 'Đã xác nhận và sinh vé cho booking!');
    }
    
    /**
     * Lấy giá đơn vị cho mỗi vé dựa trên tour và giá hiện tại
     */
    private function getTicketUnitPrice($booking)
    {
        $tour = $booking->tour;
        if (!$tour) {
            return 0;
        }
        
        // Lấy giá hiện tại dựa trên ngày khởi hành
        $departureDate = $booking->selected_departure_date ? 
            $booking->selected_departure_date->format('Y-m-d') : 
            now()->format('Y-m-d');
            
        $price = $tour->prices()->where('start_date', '<=', $departureDate)
                             ->where('end_date', '>=', $departureDate)
                             ->orderByDesc('start_date')
                             ->first();
                             
        if (!$price) {
            // Nếu không tìm thấy price trong range, lấy price gần nhất
            $price = $tour->prices()->orderByDesc('created_at')->first();
        }
        
        if ($price) {
            return $price->discount && $price->discount > 0 ? $price->final_price : $price->price;
        }
        
        // Fallback về tour price cũ nếu không có price records
        return $tour->price ?? 0;
    }
    
    /**
     * Cập nhật tổng số tiền booking dựa trên trạng thái của vé
     */
    private function updateBookingTotalAmount($bookingId)
    {
        $booking = TourBooking::with(['tickets'])->findOrFail($bookingId);
        $validTickets = $booking->tickets->whereIn('status', ['valid', 'used']); // Chỉ tính vé hợp lệ và đã sử dụng
        
        // Tính tổng số tiền từ các vé hợp lệ
        $totalAmount = 0;
        foreach ($validTickets as $ticket) {
            $totalAmount += $ticket->unit_price ?? 0;
        }
        
        // Cập nhật total_amount cho booking
        $booking->total_amount = $totalAmount;
        $booking->save();
        
        return $totalAmount;
    }
    
    /**
     * Cập nhật trạng thái vé và tính toán lại tổng số tiền booking
     */
    public function updateTicketStatus(Request $request, $ticketId)
    {
        $request->validate([
            'status' => 'required|in:valid,used,cancelled',
        ]);
        
        $ticket = Ticket::with('tourBooking.tour')->findOrFail($ticketId);
        
        // Kiểm tra quyền sở hữu
        if ($ticket->tourBooking->tour->ceo_id !== Auth::id()) {
            abort(403);
        }
        
        // Cập nhật trạng thái vé
        $ticket->status = $request->status;
        $ticket->save();
        
        // Cập nhật tổng số tiền booking
        $this->updateBookingTotalAmount($ticket->tour_booking_id);
        
        return redirect()->back()->with('success', 'Cập nhật trạng thái vé thành công!');
    }
}
