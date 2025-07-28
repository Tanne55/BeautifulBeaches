
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
                    <div class="fs-3 fw-bold text-success">{{ number_format($totalRevenue, 0, ',', '.') }} đ</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <div class="fs-6 text-muted">Tăng trưởng doanh thu</div>
                    <div class="fs-3 fw-bold text-primary">
                        {{ isset($growthRevenue) ? ($growthRevenue > 0 ? '+' : '') . number_format($growthRevenue, 2) . '%' : 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body">
                    <div class="fs-6 text-muted">ARPU</div>
                    <div class="fs-3 fw-bold text-info">
                        {{ isset($arpu) ? number_format($arpu, 0, ',', '.') . ' đ' : 'N/A' }}
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
                        @forelse($topTours as $tour => $revenue)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $tour }}</span>
                                <span class="fw-bold text-success">{{ number_format($revenue, 0, ',', '.') }} đ</span>
                            </li>
                        @empty
                            <li class="list-group-item">Không có dữ liệu</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class=" h-100">
                <div class="card-body">
                    <h5 class="card-title">Top 5 tour doanh thu thấp nhất</h5>
                    <ul class="list-group">
                        @forelse($bottomTours as $tour => $revenue)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $tour }}</span>
                                <span class="fw-bold text-danger">{{ number_format($revenue, 0, ',', '.') }} đ</span>
                            </li>
                        @empty
                            <li class="list-group-item">Không có dữ liệu</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng booking đã xác nhận -->
    <div class="mb-4">
        <div class="card-body">
            <h5 class="card-title">Danh sách booking đã xác nhận tháng {{ $viewMonth }}</h5>
            <!-- Bộ lọc -->
            <form id="bookingFilterForm" class="row g-2 mb-3 align-items-end" method="GET">
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
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
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
                        @foreach($bookings as $i => $booking)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $booking->tour->title ?? 'N/A' }}</td>
                                <td class="text-info fw-semibold">{{ number_format($booking->tickets->first()->unit_price ?? 0, 0, ',', '.') }} đ</td>
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
                                    {{ number_format($booking->total_amount, 0, ',', '.') }} đ
                                </td>
                                <td>{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') : '' }}</td>
                                <td>{{ $booking->full_name }}</td>
                                <td>{{ $booking->contact_email }}</td>
                                <td>{{ $booking->contact_phone }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $bookings->withQueryString()->links('pagination::bootstrap-4') }}
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
                            return value.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection