@extends('layouts.auth')

@section('content')
<div class="container-fluid py-4" style="padding-left: 80px "> 
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class=" border-0 shadow-sm">
                <div class="card-body"> 
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 text-primary fw-bold">
                                <i class="fas fa-layer-group me-2"></i>
                                Quản lý Nhóm Bookings   
                            </h2>
                            <p class="text-muted mb-0">
                                Tháng {{ Carbon\Carbon::createFromFormat('Y-m', request('month', now()->format('Y-m')))->format('m/Y') }}
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('ceo.bookings.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-1"></i> 
                                Quay lại Bookings
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
            <div class="  border-0 shadow-sm">
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
                            <input type="month" 
                                   name="month" 
                                   class="form-control" 
                                   value="{{ request('month', now()->format('Y-m')) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Tên Tour</label>
                            <input type="text" 
                                   name="tour" 
                                   class="form-control" 
                                   placeholder="Nhập tên tour..." 
                                   value="{{ request('tour') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Người đặt</label>
                            <input type="text" 
                                   name="user" 
                                   class="form-control" 
                                   placeholder="Tên người đặt..." 
                                   value="{{ request('user') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Ngày khởi hành</label>
                            <select name="departure_date" class="form-select">
                                <option value="">Tất cả ngày</option>
                                @if(isset($availableDepartureDates) && is_array($availableDepartureDates))
                                    @foreach($availableDepartureDates as $date)
                                        <option value="{{ $date['value'] }}" 
                                                {{ request('departure_date') == $date['value'] ? 'selected' : '' }}>
                                            {{ $date['label'] }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Trạng thái vé</label>
                            <select name="ticket_status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="not_generated" {{ request('ticket_status') == 'not_generated' ? 'selected' : '' }}>Chưa sinh vé</option>
                                <option value="generated" {{ request('ticket_status') == 'generated' ? 'selected' : '' }}>Đã sinh vé</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex flex-column justify-content-end">
                            <div class="btn-group" role="group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>
                                    Lọc
                                </button>
                                <a href="{{ route('ceo.bookings.groups') }}" 
                                   class="btn btn-outline-secondary">
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

    <!-- Quick Stats -->
    @if($bookingGroups->isNotEmpty())
        <div class="row mb-4">
            <div class="col-12">
                <div class=" border-0 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-pie me-2"></i>
                            Tổng quan nhóm bookings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            @php
                                $totalGroups = $bookingGroups->count();
                                
                                // Tính toán an toàn cho tổng people, tickets và amount
                                $totalPeople = 0;
                                $totalTickets = 0;
                                $totalAmount = 0;
                                $groupsWithTickets = 0;
                                
                                foreach($bookingGroups as $statsGroup) {
                                    try {
                                        // Lấy booking IDs
                                        $statsBookingIds = $statsGroup->booking_ids;
                                        if (is_string($statsBookingIds)) {
                                            $statsBookingIds = json_decode($statsBookingIds, true) ?? [];
                                        }
                                        if (!is_array($statsBookingIds)) {
                                            $statsBookingIds = [];
                                        }
                                        
                                        // Lấy bookings và tính toán
                                        $statsBookings = \App\Models\TourBooking::whereIn('id', $statsBookingIds)->get();
                                        $groupPeople = $statsBookings->sum('number_of_people');
                                        $groupTickets = $statsBookings->sum(function($booking) {
                                            return $booking->tickets()->count();
                                        });
                                        $groupAmount = $statsBookings->sum('total_amount');
                                        
                                        $totalPeople += $groupPeople;
                                        $totalTickets += $groupTickets;
                                        $totalAmount += $groupAmount;
                                        
                                        if ($groupTickets > 0) {
                                            $groupsWithTickets++;
                                        }
                                    } catch (\Exception $e) {
                                        // Skip this group if error
                                        continue;
                                    }
                                }
                                
                                $groupsWithoutTickets = $totalGroups - $groupsWithTickets;
                            @endphp
                            <div class="col-md-2">
                                <div class="p-3 border rounded">
                                    <h4 class="text-primary mb-1">{{ $totalGroups }}</h4>
                                    <small class="text-muted">Tổng Nhóm</small>
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
                                    <h4 class="text-success mb-1">{{ number_format($totalAmount, 0, ',', '.') }}đ</h4>
                                    <small class="text-muted">Tổng Doanh Thu</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="p-3 border rounded">
                                    <h4 class="text-success mb-1">{{ $groupsWithTickets }}</h4>
                                    <small class="text-muted">Đã Sinh Vé</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="p-3 border rounded">
                                    <h4 class="text-warning mb-1">{{ $groupsWithoutTickets }}</h4>
                                    <small class="text-muted">Chưa Sinh Vé</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="p-3 border rounded">
                                    <h4 class="text-info mb-1">{{ $totalTickets }}</h4>
                                    <small class="text-muted">Tổng Vé</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Groups List -->
    @if($bookingGroups->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có nhóm booking nào</h5>
                        <p class="text-muted">Chưa có nhóm booking nào được tạo trong tháng này.</p>
                        <a href="{{ route('ceo.bookings.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Tạo nhóm booking mới
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Groups Table -->
        <div class="row">
            <div class="col-12">
                <div class="  border-0 shadow-sm">  
                    <div class="card-header bg-gradient-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Danh sách nhóm bookings
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Tour & Mã nhóm</th>
                                        <th width="120">Ngày khởi hành</th>
                                        <th width="100">Bookings</th>
                                        <th width="100">Tổng người</th>
                                        <th width="150">Tổng tiền</th>
                                        <th width="100">Vé</th>
                                        <th width="100">Trạng thái</th>
                                        <th width="150">Thao tác</th>
                                        <th width="100">Ngày tạo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookingGroups as $index => $group)
                                        @php
                                            // Tính toán dữ liệu cho group hiện tại
                                            try {
                                                // Lấy danh sách booking IDs từ group
                                                $groupBookingIds = $group->booking_ids;
                                                
                                                // Nếu $groupBookingIds là string (JSON), decode nó
                                                if (is_string($groupBookingIds)) {
                                                    $groupBookingIds = json_decode($groupBookingIds, true) ?? [];
                                                }
                                                
                                                // Đảm bảo $groupBookingIds là array
                                                if (!is_array($groupBookingIds)) {
                                                    $groupBookingIds = [];
                                                }
                                                
                                                // Lấy các booking objects từ database
                                                $groupBookings = \App\Models\TourBooking::whereIn('id', $groupBookingIds)->get();
                                                
                                                // Tính toán metrics
                                                $groupTotalPeople = $groupBookings->sum('number_of_people');
                                                $groupTotalTickets = $groupBookings->sum(function($booking) {
                                                    return $booking->tickets()->count();
                                                });
                                                $groupTotalAmount = $groupBookings->sum('total_amount');
                                            } catch (\Exception $e) {
                                                $groupBookings = collect();
                                                $groupTotalPeople = 0;
                                                $groupTotalTickets = 0;
                                                $groupTotalAmount = 0;
                                            }
                                        @endphp
                                        
                                        <tr class="group-row">
                                            <td>
                                                <span class="badge bg-primary">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-1 text-primary fw-bold">
                                                        <i class="fas fa-map-marked-alt me-1"></i>
                                                        {{ $group->tour->title }}
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-code me-1"></i>
                                                        {{ $group->group_code }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($group->selected_departure_date)
                                                    <span class="badge bg-info">
                                                        {{ \Carbon\Carbon::parse($group->selected_departure_date)->format('d/m/Y') }}
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($group->selected_departure_date)->format('H:i') }}
                                                    </small>
                                                @else
                                                    <span class="badge bg-warning text-dark">Chưa chọn</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="stat-circle bg-info bg-opacity-10 text-info">
                                                    <strong>{{ $groupBookings->count() }}</strong>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="stat-circle bg-primary bg-opacity-10 text-primary">
                                                    <strong>{{ $groupTotalPeople }}</strong>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-success fw-bold fs-6">
                                                    {{ number_format($groupTotalAmount, 0, ',', '.') }}đ
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $groupTotalTickets > 0 ? 'bg-success' : 'bg-secondary' }} fs-6">
                                                    {{ $groupTotalTickets }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($groupTotalTickets > 0)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>
                                                        Đã sinh vé
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Chưa sinh vé
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if($groupTotalTickets == 0)
                                                        <form action="{{ route('ceo.bookings.generateTicketsForGroup', $group->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="btn btn-success btn-sm" 
                                                                    onclick="return confirm('Sinh vé cho tất cả bookings trong nhóm này?')"
                                                                    title="Sinh {{ $groupTotalPeople }} vé">
                                                                <i class="fas fa-ticket-alt"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-outline-success btn-sm" disabled title="Đã sinh vé">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    <button class="btn btn-info btn-sm toggle-details" 
                                                            data-group-id="{{ $group->id }}"
                                                            title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $group->created_at->format('d/m/Y') }}
                                                    <br>
                                                    {{ $group->created_at->format('H:i') }}
                                                </small>
                                            </td>
                                        </tr>
                                        
                                        <!-- Collapsible Details Row -->
                                        <tr style="display: none;" data-target="groupDetails{{ $group->id }}">
                                            <td colspan="10" class="p-0 border-0">
                                                <div class="details-content p-4 bg-light border-start border-5 border-primary">
                                                    <h6 class="text-primary mb-3">
                                                        <i class="fas fa-users me-1"></i>
                                                        Chi tiết bookings trong nhóm
                                                    </h6>
                                                    
                                                    @if($groupBookings->isNotEmpty())
                                                        <div class="row">
                                                            @foreach($groupBookings as $booking)
                                                                <div class="col-md-6 col-lg-4 mb-3">
                                                                    <div class="booking-detail-card p-3 bg-white rounded border">
                                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                                            <h6 class="mb-0 text-dark">{{ $booking->full_name }}</h6>
                                                                            <span class="badge bg-primary">{{ $booking->number_of_people }} người</span>
                                                                        </div>
                                                                        
                                                                        <div class="small text-muted mb-2">
                                                                            <div><i class="fas fa-envelope me-1"></i>{{ $booking->contact_email }}</div>
                                                                            <div><i class="fas fa-phone me-1"></i>{{ $booking->contact_phone }}</div>
                                                                        </div>
                                                                        
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <span class="text-success fw-bold">
                                                                                {{ number_format($booking->total_amount ?? 0, 0, ',', '.') }}đ
                                                                            </span>
                                                                            @if($booking->status === 'grouped')
                                                                                <span class="badge bg-info">Đã nhóm</span>
                                                                            @elseif($booking->status === 'confirmed')
                                                                                <span class="badge bg-success">Đã xác nhận</span>
                                                                            @endif
                                                                        </div>
                                                                        
                                                                        @if($booking->note)
                                                                            <div class="mt-2 small text-muted">
                                                                                <i class="fas fa-sticky-note me-1"></i>
                                                                                {{ Str::limit($booking->note, 60) }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="text-center text-muted py-4">
                                                            <i class="fas fa-inbox fa-2x mb-3"></i>
                                                            <h6 class="text-muted">Chưa có booking nào</h6>
                                                            <p class="mb-0 small">Chưa có booking nào trong nhóm này</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    @if($bookingGroups->hasPages())
                        <div class="card-footer bg-light">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <p class="mb-0 text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Hiển thị {{ $bookingGroups->firstItem() }}-{{ $bookingGroups->lastItem() }} 
                                        trong tổng {{ $bookingGroups->total() }} nhóm booking
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end">
                                        {{ $bookingGroups->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.table-borderless tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
}

.badge {
    font-size: 0.75em;
}

.btn-group .btn {
    border-radius: 0.375rem;
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

/* Pagination Styles */
.pagination {
    margin-bottom: 0;
}

.pagination .page-item .page-link {
    border-radius: 50%;
    margin: 0 2px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #dee2e6;
    color: #007bff;
    transition: all 0.3s ease;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border-color: #007bff;
    color: white;
    box-shadow: 0 4px 8px rgba(0,123,255,0.3);
}

.pagination .page-item .page-link:hover {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.25);
}

.pagination .page-item.disabled .page-link {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #6c757d;
}

.card-footer {
    border-top: 1px solid rgba(0,0,0,0.1);
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

/* New Table Styles */
.group-row {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.group-row:hover {
    background-color: rgba(0,123,255,0.05);
    border-left-color: #007bff;
    transform: translateX(2px);
}

.stat-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    margin: 0 auto;
}

.booking-detail-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef !important;
}

.booking-detail-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,123,255,0.15);
    border-color: #007bff !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,0.03);
}

.table thead th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.collapse.show {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .stat-circle {
        width: 40px;
        height: 40px;
        font-size: 0.8rem;
    }
    
    .booking-detail-card {
        margin-bottom: 1rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
}

/* Smooth scrolling for collapsed content */
.details-section {
    transition: all 0.4s ease;
    overflow: hidden;
    max-height: 0;
    opacity: 0;
}

.details-section.show {
    max-height: 2000px;
    opacity: 1;
    animation: slideDown 0.4s ease-out;
}

.collapse-content {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 0 0 8px 8px;
}

@keyframes slideDown {
    from { 
        opacity: 0; 
        transform: translateY(-20px);
    }
    to { 
        opacity: 1; 
        transform: translateY(0);
    }
}

/* Custom badge styles */
.badge.fs-6 {
    font-size: 0.9rem !important;
    padding: 0.375rem 0.75rem;
}

/* Enhanced hover effects */
.booking-detail-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent, rgba(0,123,255,0.05), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.booking-detail-card:hover::before {
    opacity: 1;
}
</style>

<script>
// Very simple toggle without Bootstrap collapse
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-details').forEach(button => {
        button.addEventListener('click', function() {
            const groupId = this.getAttribute('data-group-id');
            const targetRow = document.querySelector(`tr[data-target="groupDetails${groupId}"]`);
            const icon = this.querySelector('i');
            
            if (targetRow) {
                if (targetRow.style.display === 'none' || targetRow.style.display === '') {
                    // Show
                    targetRow.style.display = 'table-row';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                    this.title = 'Ẩn chi tiết';
                } else {
                    // Hide
                    targetRow.style.display = 'none';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                    this.title = 'Xem chi tiết';
                }
            }
        });
    });
});
</script>
@endsection 