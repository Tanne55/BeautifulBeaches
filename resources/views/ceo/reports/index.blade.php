
@extends('layouts.auth')

@section('content')
<div class="container my-5 container-custom">
    <h2 class="mb-4 text-center">Thống kê tổng quan tháng {{ $now->format('m/Y') }}</h2>

    <!-- KPI Widgets -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <div class="fs-6 text-muted">Doanh thu tổng</div>
                    <div class="fs-3 fw-bold text-success">{{ number_format($totalRevenue, 0, ',', '.') }} tr vnđ</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <div class="fs-6 text-muted">Số vé đã bán</div>
                    <div class="fs-3 fw-bold text-primary">
                        {{ isset($totalTickets) ? number_format($totalTickets) : 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <div class="fs-6 text-muted">ARPU</div>
                    <div class="fs-3 fw-bold text-info">
                        {{ isset($arpu) ? number_format($arpu, 0, ',', '.') . 'tr vnđ' : 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <div class="fs-6 text-muted">Số booking</div>
                    <div class="fs-3 fw-bold text-warning">
                        {{ isset($totalBookings) ? $totalBookings : 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ doanh thu 12 tháng -->
    <div class=" mb-4">
        <div class="card-body">
            <h5 class="card-title">Biểu đồ doanh thu 12 tháng gần nhất</h5>
            <canvas id="monthlyRevenueChart" height="80"></canvas>
        </div>
    </div>

    <!-- Top tour doanh thu cao/thấp -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class=" h-100">
                <div class="card-body">
                    <h5 class="card-title">Top 5 tour doanh thu cao nhất</h5>
                    <ul class="list-group">
                        @if(isset($topTours) && count($topTours) > 0)
                            @foreach($topTours as $tour => $revenue)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $tour }}</span>
                                    <span class="fw-bold text-success">{{ number_format($revenue, 0, ',', '.') }} tr vnđ</span>
                                </li>
                            @endforeach
                        @else
                            <li class="list-group-item">Không có dữ liệu</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class=" h-100">
                <div class="card-body">
                    <h5 class="card-title">Top 5 tour doanh thu thấp nhất</h5>
                    <ul class="list-group">
                        @if(isset($bottomTours) && count($bottomTours) > 0)
                            @foreach($bottomTours as $tour => $revenue)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $tour }}</span>
                                    <span class="fw-bold text-danger">{{ number_format($revenue, 0, ',', '.') }} tr vnđ</span>
                                </li>
                            @endforeach
                        @else
                            <li class="list-group-item">Không có dữ liệu</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="mb-4">
        <div class="">
            <div class="card-body" >
                <style>
                    /* Khi tab đang active */
                    .nav-tabs .nav-link.active {
                      color: #000 !important;       /* chữ đen */
                      background-color: #e9ecef;    /* nền xám nhạt */
                      border-color: #dee2e6 #dee2e6 #fff;
                    }
                  </style>
                <h5 class="card-title">Chi tiết booking tháng {{ $viewMonth }}</h5>
                
                <!-- Tabs -->
                <ul class="nav nav-tabs" id="bookingTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button  class="nav-link active text-black" id="individual-tab" data-bs-toggle="tab" data-bs-target="#individual" type="button" role="tab">
                            Booking cá nhân
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-black" id="groups-tab" data-bs-toggle="tab" data-bs-target="#groups" type="button" role="tab">
                            Booking nhóm
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3" id="bookingTabContent">
                    <!-- Individual Bookings Tab -->
                    <div class="tab-pane fade show active" id="individual" role="tabpanel">
                        <!-- Bộ lọc -->
                        <form id="bookingFilterForm" class="row g-2 mb-3 align-items-end" method="GET">
                            <input type="hidden" name="tab" value="individual">
                            <div class="col-md-4">
                                <label class="form-label mb-1">Tìm kiếm tour</label>
                                <input type="text" name="tour" class="form-control" placeholder="Nhập tên tour..." value="{{ request('tour') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-1">Số vé</label>
                                <select name="people" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value=">=4" {{ request('people') == '>=4' ? 'selected' : '' }}>Từ 4 trở lên</option>
                                    <option value="<4" {{ request('people') == '<4' ? 'selected' : '' }}>Dưới 4</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-1">Từ ngày</label>
                                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-1">Đến ngày</label>
                                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Lọc</button>
                            </div>
                        </form>

                        <div class="table-responsive overflow-hidden">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Tên tour</th>
                                        <th>Giá vé</th>
                                        <th>Số vé đặt</th>
                                        <th>Vé hợp lệ/Đã hủy</th>
                                        <th>Tổng tiền</th>
                                        <th>Ngày đặt</th>
                                        <th>Người đặt</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $i => $booking)
                                        <tr>
                                            <td>{{ ($bookings->currentPage() - 1) * $bookings->perPage() + $i + 1 }}</td>
                                            <td>{{ $booking->tour->title ?? 'N/A' }}</td>
                                            <td class="text-info fw-semibold">{{ number_format($booking->tickets->first()->unit_price ?? 0, 0, ',', '.') }} tr vnđ</td>
                                            <td>
                                                @if($booking->number_of_people >= 4)
                                                    <span class="badge bg-warning text-dark">{{ $booking->number_of_people }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $booking->number_of_people }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $booking->tickets->whereIn('status', ['valid', 'used'])->count() }}</span>
                                                /
                                                <span class="badge bg-danger">{{ $booking->tickets->where('status', 'cancelled')->count() }}</span>
                                            </td>
                                            <td class="text-success fw-bold">
                                                {{ number_format($booking->total_amount, 0, ',', '.') }} tr vnđ
                                            </td>
                                            <td>{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') : '' }}</td>
                                            <td>{{ $booking->full_name }}</td>
                                            <td>{{ $booking->contact_email }}</td>
                                            <td>{{ $booking->contact_phone }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                                    <p>Không có booking nào trong tháng này</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            @if(isset($bookings) && $bookings->hasPages())
                                {{ $bookings->appends(['tab' => 'individual'])->withQueryString()->links('pagination::bootstrap-4') }}
                            @endif
                        </div>
                    </div>

                    <!-- Group Bookings Tab -->
                    <div class="tab-pane fade" id="groups" role="tabpanel">
                        @isset($bookingGroups)
                        <!-- Bộ lọc nhóm -->
                        <form id="groupFilterForm" class="row g-2 mb-3 align-items-end" method="GET">
                            <input type="hidden" name="tab" value="groups">
                            <div class="col-md-4">
                                <label class="form-label mb-1">Tìm kiếm tour</label>
                                <input type="text" name="group_tour" class="form-control" placeholder="Nhập tên tour..." value="{{ request('group_tour') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1">Trạng thái vé</label>
                                <select name="ticket_status" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value="not_generated" {{ request('ticket_status') == 'not_generated' ? 'selected' : '' }}>Chưa sinh vé</option>
                                    <option value="generated" {{ request('ticket_status') == 'generated' ? 'selected' : '' }}>Đã sinh vé</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-1">Từ ngày</label>
                                <input type="date" name="group_from" class="form-control" value="{{ request('group_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-1">Đến ngày</label>
                                <input type="date" name="group_to" class="form-control" value="{{ request('group_to') }}">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">Lọc</button>
                            </div>
                        </form>

                        <div class="table-responsive overflow-hidden">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Mã nhóm</th>
                                        <th>Tên tour</th>
                                        <th>Giá/vé</th>
                                        <th>Số booking</th>
                                        <th>Tổng vé</th>
                                        <th>Đã sinh vé</th>
                                        <th>Tổng tiền</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($bookingGroups) && count($bookingGroups) > 0)
                                        @foreach($bookingGroups as $i => $group)
                                            <tr>
                                                <td>{{ $loop->iteration + (($bookingGroups->currentPage() - 1) * $bookingGroups->perPage()) }}</td>
                                                <td class="fw-bold text-primary">{{ $group->group_code ?? 'N/A' }}</td>
                                                <td>{{ $group->tour->title ?? 'N/A' }}</td>
                                                <td class="text-info fw-semibold">
                                                    @php
                                                        // Lấy giá vé từ booking đầu tiên trong group
                                                        $firstBooking = null;
                                                        if(isset($group->booking_ids) && !empty($group->booking_ids)) {
                                                            $firstBooking = \App\Models\TourBooking::with('tickets')->whereIn('id', $group->booking_ids)->first();
                                                        }
                                                        $unitPrice = 0;
                                                        if($firstBooking && $firstBooking->tickets->isNotEmpty()) {
                                                            $unitPrice = $firstBooking->tickets->first()->unit_price;
                                                        }
                                                    @endphp
                                                    {{ number_format($unitPrice ?? 0, 0, ',', '.') }} tr vnđ
                                                </td>
                                                <td><span class="badge bg-info">
                                                    @php
                                                        // Tính số booking trong group
                                                        $totalBookingsInGroup = 0;
                                                        if(isset($group->booking_ids) && !empty($group->booking_ids)) {
                                                            $totalBookingsInGroup = count($group->booking_ids);
                                                        }
                                                    @endphp
                                                    {{ $totalBookingsInGroup }}
                                                </span></td>
                                                <td><span class="badge bg-secondary">{{ $group->total_people ?? 0 }}</span></td>
                                                <td>
                                                    @php
                                                        // Tính tổng vé đã sinh
                                                        $totalTickets = 0;
                                                        if(isset($group->booking_ids) && !empty($group->booking_ids)) {
                                                            $groupBookings = \App\Models\TourBooking::whereIn('id', $group->booking_ids)->get();
                                                            $totalTickets = $groupBookings->sum(function($booking) {
                                                                return $booking->tickets()->count();
                                                            });
                                                        }
                                                    @endphp
                                                    @if($totalTickets > 0)
                                                        <span class="badge bg-success">{{ $totalTickets }}</span>
                                                    @else
                                                        <span class="badge bg-warning">0</span>
                                                    @endif
                                                </td>
                                                <td class="text-success fw-bold">{{ number_format($group->total_amount ?? 0, 0, ',', '.') }} tr vnđ</td>
                                                <td>{{ $group->created_at ? \Carbon\Carbon::parse($group->created_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="toggleGroupDetails({{ $group->id }})">
                                                        <i class="fas fa-eye"></i> Chi tiết
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="group-details-{{ $group->id }}" style="display: none;">
                                                <td colspan="10">
                                                    <div class="p-3 bg-light">
                                                        <h6 class="mb-3">Chi tiết booking trong nhóm:</h6>
                                                        <div class="table-responsive overflow-hidden">
                                                            <table class="table table-sm table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Booking ID</th>
                                                                        <th>Người đặt</th>
                                                                        <th>Email</th>
                                                                        <th>SĐT</th>
                                                                        <th>Số vé</th>
                                                                        <th>Ngày đặt</th>
                                                                        <th>Trạng thái</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php
                                                                        // Lấy danh sách booking từ booking_ids
                                                                        $groupBookings = collect();
                                                                        if(isset($group->booking_ids) && !empty($group->booking_ids)) {
                                                                            $groupBookings = \App\Models\TourBooking::whereIn('id', $group->booking_ids)->get();
                                                                        }
                                                                    @endphp
                                                                    @if($groupBookings->isNotEmpty())
                                                                        @foreach($groupBookings as $booking)
                                                                            <tr>
                                                                                <td class="fw-bold">#{{ $booking->id }}</td>
                                                                                <td>{{ $booking->full_name ?? 'N/A' }}</td>
                                                                                <td>{{ $booking->contact_email ?? 'N/A' }}</td>
                                                                                <td>{{ $booking->contact_phone ?? 'N/A' }}</td>
                                                                                <td><span class="badge bg-secondary">{{ $booking->number_of_people ?? 0 }}</span></td>
                                                                                <td>{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') : 'N/A' }}</td>
                                                                                <td>
                                                                                    @if($booking->status == 'confirmed')
                                                                                        <span class="badge bg-success">Đã xác nhận</span>
                                                                                    @elseif($booking->status == 'grouped')
                                                                                        <span class="badge bg-info">Đã nhóm</span>
                                                                                    @else
                                                                                        <span class="badge bg-warning">{{ ucfirst($booking->status ?? 'pending') }}</span>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td colspan="7" class="text-center py-2">
                                                                                <small class="text-muted">Không có booking nào trong nhóm</small>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-users fa-2x mb-2"></i>
                                                    <p>Không có booking nhóm nào trong tháng này</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            @if(isset($bookingGroups) && method_exists($bookingGroups, 'hasPages') && $bookingGroups->hasPages())
                                {{ $bookingGroups->appends(['tab' => 'groups'])->withQueryString()->links('pagination::bootstrap-4') }}
                            @endif
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Chức năng booking nhóm chưa được kích hoạt hoặc không có dữ liệu.
                        </div>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
    const monthlyRevenueData = @json($monthlyRevenue->pluck('revenue'));
    const monthlyLabels = @json($monthlyRevenue->pluck('month'));
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Doanh thu',
                data: monthlyRevenueData,
                borderColor: '#198754',
                backgroundColor: 'rgba(25,135,84,0.1)',
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' tr';
                        }
                    }
                }
            }
        }
    });

    // Tab switching functionality
    function switchTab(tabName) {
        const individualTab = document.getElementById('individual-tab');
        const groupsTab = document.getElementById('groups-tab');
        const individualPane = document.getElementById('individual');
        const groupsPane = document.getElementById('groups');
        
        if (tabName === 'groups') {
            individualTab.classList.remove('active');
            groupsTab.classList.add('active');
            individualPane.classList.remove('show', 'active');
            groupsPane.classList.add('show', 'active');
        } else {
            groupsTab.classList.remove('active');
            individualTab.classList.add('active');
            groupsPane.classList.remove('show', 'active');
            individualPane.classList.add('show', 'active');
        }
    }

    // Initialize tab from URL parameter
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab');
        if (activeTab === 'groups') {
            switchTab('groups');
        }
    });

    // Tab click handlers
    document.getElementById('individual-tab').addEventListener('click', function() {
        updateURLParameter('tab', 'individual');
    });
    
    document.getElementById('groups-tab').addEventListener('click', function() {
        updateURLParameter('tab', 'groups');
    });

    // Toggle group details
    function toggleGroupDetails(groupId) {
        const detailsRow = document.getElementById('group-details-' + groupId);
        if (detailsRow.style.display === 'none') {
            detailsRow.style.display = 'table-row';
        } else {
            detailsRow.style.display = 'none';
        }
    }

    // Update URL parameter function
    function updateURLParameter(param, value) {
        const url = new URL(window.location);
        url.searchParams.set(param, value);
        window.history.replaceState({}, '', url);
    }
</script>
@endpush
@endsection