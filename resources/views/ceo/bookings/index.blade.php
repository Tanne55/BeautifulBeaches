@php
    use Carbon\Carbon;
    use App\Models\CancellationRequest;
@endphp
@extends('layouts.auth')

@section('content')
    <div class="container py-4 container-custom">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class=" border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-1 text-primary fw-bold">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    Quản lý Booking Tours
                                </h2>
                                <p class="text-muted mb-0">
                                    Tháng
                                    {{ Carbon::createFromFormat('Y-m', request('month', now()->format('Y-m')))->format('m/Y') }}
                                </p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('ceo.bookings.groups') }}" class="btn btn-info">
                                    <i class="fas fa-layer-group me-1"></i>
                                    Nhóm Bookings
                                </a>
                                @php
                                    $pendingCancellationCount = CancellationRequest::where('status', 'pending')->count();
                                @endphp
                                <a href="{{ route('ceo.cancellation_requests.index') }}"
                                    class="btn btn-warning position-relative">
                                    <i class="fas fa-ban me-1"></i>
                                    Yêu cầu hủy
                                    @if($pendingCancellationCount > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $pendingCancellationCount }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class=" border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-filter me-2"></i>
                            Bộ lọc tìm kiếm
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Tháng/Năm</label>
                                <input type="month" name="month" class="form-control"
                                    value="{{ request('month', now()->format('Y-m')) }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Tên Tour</label>
                                <input type="text" name="tour" class="form-control" placeholder="Nhập tên tour..."
                                    value="{{ request('tour') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Người đặt</label>
                                <input type="text" name="user" class="form-control" placeholder="Tên người đặt..."
                                    value="{{ request('user') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Ngày khởi hành</label>
                                <select name="departure_date" class="form-select">
                                    <option value="">Tất cả ngày</option>
                                    @if(!empty($availableDepartureDates) && is_array($availableDepartureDates))
                                        @foreach($availableDepartureDates as $date)
                                            @if(is_array($date) && isset($date['value']) && isset($date['label']))
                                                <option value="{{ $date['value'] }}" {{ request('departure_date') == $date['value'] ? 'selected' : '' }}>
                                                    {{ $date['label'] }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý
                                    </option>
                                    <option value="grouped" {{ request('status') == 'grouped' ? 'selected' : '' }}>Đã nhóm
                                    </option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác
                                        nhận</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <div class="btn-group" role="group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>
                                        Lọc
                                    </button>
                                    <a href="{{ route('ceo.bookings.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Xóa
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        @if(isset($groupedBookings) && count($groupedBookings) > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class=" border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                Tổng quan nhanh
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                @php
                                    $totalBookings = 0;
                                    $totalPeople = 0;
                                    $totalRevenue = 0;
                                    $pendingCount = 0;
                                    $groupedCount = 0;
                                    $confirmedCount = 0;

                                    foreach ($groupedBookings as $group) {
                                        if ($group && method_exists($group, 'count')) {
                                            $totalBookings += $group->count();
                                            $totalPeople += $group->sum('number_of_people');
                                            // Chỉ tính doanh thu từ booking không bị hủy
                                            $totalRevenue += $group->where('status', '!=', 'cancelled')->sum('total_amount');
                                            $pendingCount += $group->where('status', 'pending')->count();
                                            $groupedCount += $group->where('status', 'grouped')->count();
                                            $confirmedCount += $group->where('status', 'confirmed')->count();
                                        }
                                    }
                                @endphp
                                <div class="col-md-2">
                                    <div class="p-3 border rounded">
                                        <h4 class="text-primary mb-1">{{ $totalBookings }}</h4>
                                        <small class="text-muted">Tổng Bookings</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="p-3 border rounded">
                                        <h4 class="text-info mb-1">{{ $totalPeople }}</h4>
                                        <small class="text-muted">Tổng Khách</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="p-3 border rounded">
                                        <h4 class="text-success mb-1">{{ number_format($totalRevenue, 0, ',', '.') }} TrVND</h4>
                                        <small class="text-muted">Tổng Doanh Thu</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="p-3 border rounded">
                                        <h4 class="text-warning mb-1">{{ $pendingCount }}</h4>
                                        <small class="text-muted">Chờ Xử Lý</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="p-3 border rounded">
                                        <h4 class="text-info mb-1">{{ $groupedCount }}</h4>
                                        <small class="text-muted">Đã Nhóm</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="p-3 border rounded">
                                        <h4 class="text-success mb-1">{{ $confirmedCount }}</h4>
                                        <small class="text-muted">Đã Xác Nhận</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Main Content -->
        @if($bookings->isEmpty())
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có booking nào</h5>
                            <p class="text-muted">Không có booking nào cho các tour của bạn trong tháng này.</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Tour Groups -->
            @if(!empty($groupedBookings) && count($groupedBookings) > 0)
                @foreach($groupedBookings as $groupKey => $bookingGroup)
                    @php
                        if (empty($bookingGroup) || !is_object($bookingGroup) || !method_exists($bookingGroup, 'first')) {
                            continue;
                        }

                        $firstBooking = $bookingGroup->first();
                        if (!$firstBooking) {
                            continue;
                        }

                        $tour = $firstBooking->tour;
                        $departureDate = $firstBooking->selected_departure_date;

                        $filterMonth = request('month', now()->format('Y-m'));

                        $filteredBookings = collect();
                        $pendingBookings = collect();
                        $groupedStatusBookings = collect();
                        $confirmedBookings = collect();
                        $cancelledBookings = collect();

                        if ($bookingGroup && method_exists($bookingGroup, 'filter')) {
                            $filteredBookings = $bookingGroup->filter(function ($booking) use ($filterMonth) {
                                // Filter by departure date instead of booking date
                                if ($booking->selected_departure_date) {
                                    return Carbon::parse($booking->selected_departure_date)->format('Y-m') === $filterMonth;
                                }
                                return false;
                            });

                            if ($filteredBookings && method_exists($filteredBookings, 'where')) {
                                $pendingBookings = $filteredBookings->where('status', 'pending');
                                $groupedStatusBookings = $filteredBookings->where('status', 'grouped');
                                $confirmedBookings = $filteredBookings->where('status', 'confirmed');
                                $cancelledBookings = $filteredBookings->where('status', 'cancelled');
                            }
                        }
                    @endphp

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class=" border-0 shadow-sm">
                                <div class="card-header bg-gradient-primary text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="mx-5 ">
                                            <h5 class="mb-1 fw-bold">
                                                <i class="fas fa-map-marked-alt me-2"></i>
                                                {{ $tour->title }}
                                            </h5>
                                            <div class="row text-white-50">
                                                <div class="col-md-12">
                                                    <small>
                                                        <i class="fas fa-plane-departure me-1"></i>
                                                        Ngày khởi hành:
                                                        @if($departureDate)
                                                            {{ Carbon::parse($departureDate)->format('d/m/Y H:i') }}
                                                        @else
                                                            <span class="text-warning">Chưa chọn ngày khởi hành</span>
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mx-4">
                                            <span class="badge bg-light text-dark fs-6">
                                                {{ $filteredBookings->count() }} bookings
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <!-- Quick Stats for this tour -->
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                                                <h5 class="text-warning mb-1">{{ $pendingBookings ? $pendingBookings->count() : 0 }}
                                                </h5>
                                                <small class="text-muted">Chờ xử lý</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                                                <h5 class="text-info mb-1">
                                                    {{ $groupedStatusBookings ? $groupedStatusBookings->count() : 0 }}
                                                </h5>
                                                <small class="text-muted">Đã nhóm</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                                <h5 class="text-success mb-1">{{ $confirmedBookings ? $confirmedBookings->count() : 0 }}
                                                </h5>
                                                <small class="text-muted">Đã xác nhận</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center p-3 bg-danger bg-opacity-10 rounded">
                                                <h5 class="text-danger mb-1">{{ $cancelledBookings ? $cancelledBookings->count() : 0 }}
                                                </h5>
                                                <small class="text-muted">Đã hủy</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Group Management Section -->
                                    @if($pendingBookings && method_exists($pendingBookings, 'count') && $pendingBookings->count() > 0)
                                        @php
                                            $departureDates = collect();
                                            if ($pendingBookings && method_exists($pendingBookings, 'pluck')) {
                                                $departureDates = $pendingBookings->pluck('selected_departure_date')->filter()->unique();
                                            }
                                            $sameDepartureDate = $departureDates->count() <= 1;
                                        @endphp

                                        <div class=" bg-light mb-4">
                                            <div class="card-header">
                                                <h6 class="mb-0 text-primary">
                                                    <i class="fas fa-layer-group me-2"></i>
                                                    Gom nhóm bookings
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                @if(!$sameDepartureDate)
                                                    <div class="alert alert-warning">
                                                        <h6 class="mb-2">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            Lưu ý:
                                                        </h6>
                                                        <p class="mb-0">Các booking có ngày khởi hành khác nhau. Chỉ có thể gom nhóm các booking
                                                            có cùng ngày khởi hành.</p>
                                                    </div>
                                                @else
                                                    <div class="alert alert-info">
                                                        <h6 class="mb-2">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Hướng dẫn:
                                                        </h6>
                                                        <ul class="mb-0 small">
                                                            <li>Chọn các bookings bạn muốn gom thành 1 nhóm</li>
                                                            <li>Có thể tạo nhiều nhóm khác nhau cho cùng 1 tour và ngày khởi hành</li>
                                                            <li>Chỉ có thể gom các bookings có trạng thái "Chờ xử lý" và cùng ngày khởi hành
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @endif

                                                <form action="{{ route('ceo.bookings.createGroup') }}" method="POST"
                                                    id="groupForm_{{ $loop->index }}">
                                                    @csrf
                                                    <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                                                    <input type="hidden" name="selected_departure_date" value="{{ $departureDate }}">

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="btn-group" role="group">
                                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                                    onclick="selectAllBookings({{ $loop->index }})">
                                                                    <i class="fas fa-check-square me-1"></i>
                                                                    Chọn tất cả
                                                                </button>
                                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                                    onclick="deselectAllBookings({{ $loop->index }})">
                                                                    <i class="fas fa-square me-1"></i>
                                                                    Bỏ chọn
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 text-end">
                                                            <span class="text-muted">
                                                                Đã chọn: <span id="selectedCount_{{ $loop->index }}">0</span> /
                                                                {{ $pendingBookings ? $pendingBookings->count() : 0 }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        @if($pendingBookings && (is_array($pendingBookings) || is_object($pendingBookings)))
                                                            @foreach($pendingBookings as $booking)
                                                                <div class="col-md-6 mb-2">
                                                                    <div class=" border">
                                                                        <div class="card-body p-3">
                                                                            <div class="form-check">
                                                                                <input
                                                                                    class="form-check-input booking-checkbox-{{ $loop->parent->index }}"
                                                                                    type="checkbox" name="booking_ids[]" value="{{ $booking->id }}"
                                                                                    id="booking_{{ $booking->id }}"
                                                                                    onchange="updateSelectedCount({{ $loop->parent->index }})">
                                                                                <label class="form-check-label" for="booking_{{ $booking->id }}">
                                                                                    <div class="d-flex justify-content-between">
                                                                                        <div>
                                                                                            <strong>{{ $booking->full_name }}</strong>
                                                                                            <br>
                                                                                            <small class="text-muted">
                                                                                                {{ $booking->number_of_people }} người -
                                                                                                {{ number_format($booking->total_amount, 0, ',', '.') }}
                                                                                                TrVND
                                                                                            </small>
                                                                                        </div>
                                                                                        <div class="text-end">
                                                                                            <span class="badge bg-warning">Pending</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>

                                                    <div class="text-center">
                                                        <button type="submit" class="btn btn-primary" id="groupBtn_{{ $loop->index }}"
                                                            disabled onclick="return confirm('Gom các bookings đã chọn thành 1 nhóm?')">
                                                            <i class="fas fa-layer-group me-1"></i>
                                                            Gom nhóm (<span id="selectedCountBtn_{{ $loop->index }}">0</span> bookings)
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Revenue Summary -->
                                    @php
                                        $totalPendingAmount = $pendingBookings && method_exists($pendingBookings, 'sum') ? $pendingBookings->sum('total_amount') : 0;
                                        // Chỉ tính doanh thu từ booking không bị hủy
                                        $totalGroupAmount = $filteredBookings && method_exists($filteredBookings, 'where') ?
                                            $filteredBookings->where('status', '!=', 'cancelled')->sum('total_amount') : 0;
                                    @endphp
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class=" bg-warning bg-opacity-10 border-warning">
                                                <div class="card-body text-center">
                                                    <h6 class="text-warning mb-1">
                                                        <i class="fas fa-coins me-1"></i>
                                                        Tổng tiền chờ xử lý
                                                    </h6>
                                                    <h4 class="text-warning mb-0">{{ number_format($totalPendingAmount, 0, ',', '.') }}
                                                        TrVND
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class=" bg-info bg-opacity-10 border-info">
                                                <div class="card-body text-center">
                                                    <h6 class="text-info mb-1">
                                                        <i class="fas fa-chart-line me-1"></i>
                                                        Tổng doanh thu tour
                                                    </h6>
                                                    <h4 class="text-info mb-0">{{ number_format($totalGroupAmount, 0, ',', '.') }} TrVND
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bookings Table -->
                                    <div class="table-responsive  overflow-hidden">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-secondary">
                                                <tr class="text-center">
                                                    <th width="50">#</th>
                                                    <th>Người đặt</th>
                                                    <th>Email</th>
                                                    <th>Điện thoại</th>
                                                    <th width="100">Số người</th>
                                                    <th>Ngày khởi hành</th>
                                                    <th width="120">Tổng tiền</th>
                                                    <th width="100">Trạng thái</th>
                                                    <th width="80">Vé</th>
                                                    <th width="120">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($filteredBookings && (is_array($filteredBookings) || is_object($filteredBookings)))
                                                    @foreach($filteredBookings as $i => $booking)
                                                        <tr class="text-center">
                                                            <td>{{ $i + 1 }}</td>
                                                            <td>
                                                                <strong>{{ $booking->full_name }}</strong>
                                                                @if($booking->note)
                                                                    <br><small class="text-muted">{{ Str::limit($booking->note, 30) }}</small>
                                                                @endif
                                                            </td>
                                                            <td>{{ $booking->contact_email }}</td>
                                                            <td>{{ $booking->contact_phone }}</td>
                                                            <td>
                                                                <span class="badge bg-primary">{{ $booking->number_of_people }}</span>
                                                            </td>
                                                            <td>
                                                                @if($booking->selected_departure_date)
                                                                    <span class="badge bg-info">
                                                                        {{ Carbon::parse($booking->selected_departure_date)->format('d/m/Y H:i') }}
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-warning text-dark">Chưa chọn</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <strong class="text-success">
                                                                    {{ number_format($booking->total_amount, 0, ',', '.') }} TrVND
                                                                </strong>
                                                            </td>
                                                            <td>
                                                                @if($booking->status === 'pending')
                                                                    <span class="badge bg-warning">Chờ xử lý</span>
                                                                @elseif($booking->status === 'grouped')
                                                                    <span class="badge bg-info">Đã nhóm</span>
                                                                @elseif($booking->status === 'confirmed')
                                                                    <span class="badge bg-success">Đã xác nhận</span>
                                                                @elseif($booking->status === 'cancelled')
                                                                    <span class="badge bg-danger">Đã hủy</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $ticketCount = $booking->tickets ? $booking->tickets->count() : 0;
                                                                @endphp
                                                                @if($ticketCount > 0)
                                                                    <span class="badge bg-success">{{ $ticketCount }}</span>
                                                                @else
                                                                    <span class="badge bg-secondary">0</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="btn-group" role="group">
                                                                    @if($ticketCount > 0)
                                                                        <button type="button" class="btn btn-sm btn-outline-info"
                                                                            data-bs-toggle="offcanvas"
                                                                            data-bs-target="#ticketsOffcanvas{{ $booking->id }}" title="Xem vé">
                                                                            <i class="fas fa-ticket-alt"></i>
                                                                        </button>
                                                                    @elseif($booking->status === 'confirmed' && $ticketCount == 0)
                                                                        <form action="{{ route('ceo.bookings.generateTickets', $booking) }}"
                                                                            method="POST" class="d-inline">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-sm btn-outline-primary"
                                                                                onclick="return confirm('Sinh {{ $booking->number_of_people }} vé cho booking này?')"
                                                                                title="Sinh vé">
                                                                                <i class="fas fa-plus"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif

                                                                    @if($booking->status === 'pending')
                                                                        <form action="{{ route('ceo.bookings.confirm', $booking->id) }}"
                                                                            method="POST" class="d-inline">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <button type="submit" class="btn btn-sm btn-outline-success"
                                                                                onclick="return confirm('Xác nhận booking này?')" title="Xác nhận">
                                                                                <i class="fas fa-check"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        @endif

        <!-- Pagination -->
        @if(isset($bookings) && method_exists($bookings, 'withQueryString'))
            <div class="d-flex justify-content-center mt-4">
                {{ $bookings->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>

    <!-- Offcanvas for Tickets -->
    @if(isset($bookings) && count($bookings) > 0)
        @foreach($bookings as $booking)
            @php
                $uniqueNames = $booking->tickets->pluck('full_name')->unique();
                $showNameCol = $uniqueNames->count() > 1;
            @endphp
            <div class="offcanvas offcanvas-end" tabindex="-1" id="ticketsOffcanvas{{ $booking->id }}"
                aria-labelledby="ticketsOffcanvasLabel{{ $booking->id }}" style="width: 700px;">
                <div class="offcanvas-header bg-primary text-white">
                    <h5 class="offcanvas-title" id="ticketsOffcanvasLabel{{ $booking->id }}">
                        <i class="fas fa-ticket-alt me-2"></i>
                        Danh sách vé - Booking #{{ $booking->id }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                <strong>Người đặt:</strong> {{ $booking->full_name }}
                            </div>
                            <div class="col-6">
                                <strong>Số người:</strong> {{ $booking->number_of_people }}
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Mã vé</th>
                                    @if($showNameCol)
                                        <th>Họ tên</th>
                                    @endif
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th width="120">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->tickets as $i => $ticket)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td><code>{{ $ticket->ticket_code }}</code></td>
                                        @if($showNameCol)
                                            <td>{{ $ticket->full_name }}</td>
                                        @endif
                                        <td>
                                            @if($ticket->status === 'valid')
                                                <span class="badge bg-success">Valid</span>
                                            @elseif($ticket->status === 'used')
                                                <span class="badge bg-warning text-dark">Used</span>
                                            @else
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('ceo.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-info"
                                                    title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('ceo.tickets.edit', $ticket) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <script>
        function updateFilterSummary() {
            const status = document.getElementById('status').value;
            const ticketStatus = document.getElementById('ticket_status').value;
            const month = document.getElementById('month').value;

            let filterText = [];
            if (status && status !== 'all') filterText.push(`Trạng thái: ${status}`);
            if (ticketStatus && ticketStatus !== 'all') filterText.push(`Vé: ${ticketStatus}`);
            if (month) filterText.push(`Tháng: ${month}`);

            const summaryElement = document.getElementById('filter-summary');
            if (filterText.length > 0) {
                summaryElement.innerHTML = `<i class="fas fa-filter me-1"></i>Đang lọc: ${filterText.join(', ')}`;
                summaryElement.style.display = 'block';
            } else {
                summaryElement.style.display = 'none';
            }
        }

        function clearFilters() {
            document.getElementById('status').value = 'all';
            document.getElementById('ticket_status').value = 'all';
            document.getElementById('month').value = '';
            updateFilterSummary();
            document.getElementById('filterForm').submit();
        }

        // Initialize filter summary on page load
        document.addEventListener('DOMContentLoaded', function () {
            updateFilterSummary();
        });

        // Booking group selection functions for each group
        function selectAllBookings(groupIndex) {
            document.querySelectorAll(`.booking-checkbox-${groupIndex}`).forEach(checkbox => {
                checkbox.checked = true;
            });
            updateSelectedCount(groupIndex);
        }

        function deselectAllBookings(groupIndex) {
            document.querySelectorAll(`.booking-checkbox-${groupIndex}`).forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectedCount(groupIndex);
        }

        function updateSelectedCount(groupIndex) {
            const checkboxes = document.querySelectorAll(`.booking-checkbox-${groupIndex}:checked`);
            const count = checkboxes.length;

            document.getElementById(`selectedCount_${groupIndex}`).textContent = count;
            document.getElementById(`selectedCountBtn_${groupIndex}`).textContent = count;

            const groupBtn = document.getElementById(`groupBtn_${groupIndex}`);
            if (count > 0) {
                groupBtn.disabled = false;
                groupBtn.classList.remove('btn-secondary');
                groupBtn.classList.add('btn-primary');
            } else {
                groupBtn.disabled = true;
                groupBtn.classList.remove('btn-primary');
                groupBtn.classList.add('btn-secondary');
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            // Update all selection counters for booking groups
            const forms = document.querySelectorAll('[id^="groupForm_"]');
            forms.forEach((form, index) => {
                updateSelectedCount(index);
            });
        });
    </script>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .badge {
            font-size: 0.8em;
        }

        .offcanvas {
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
        }

        .btn-group .btn {
            border-radius: 0.375rem;
            margin-right: 2px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        .stats-card {
            border-left: 4px solid;
            background: linear-gradient(45deg, #f8f9fa, #ffffff);
        }

        .stats-card.pending {
            border-left-color: #ffc107;
        }

        .stats-card.grouped {
            border-left-color: #17a2b8;
        }

        .stats-card.confirmed {
            border-left-color: #28a745;
        }

        .stats-card.cancelled {
            border-left-color: #dc3545;
        }

        .filter-card {
            border: 1px solid #e9ecef;
            background: linear-gradient(45deg, #ffffff, #f8f9fa);
        }

        .group-card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .group-card:hover {
            border-color: #007bff;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
        }

        #filter-summary {
            font-size: 0.9em;
            padding: 0.5rem 1rem;
            background: linear-gradient(45deg, #e3f2fd, #f3e5f5);
            border: 1px solid #2196f3;
            border-radius: 0.375rem;
            color: #1976d2;
        }
    </style>
@endsection