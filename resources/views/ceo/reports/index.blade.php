@extends('layouts.auth')

@section('content')


    <div class="container my-5">
        <h2 class="mb-4 text-center">Thống kê doanh thu tháng {{ $now->format('m/Y') }}</h2>

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
                        <div class="fs-6 text-muted">Tăng trưởng</div>
                        <div class="fs-3 fw-bold text-primary">
                            {{ isset($growthPercent) ? ($growthPercent > 0 ? '+' : '') . number_format($growthPercent, 2) . '%' : 'N/A' }}
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
                        <div class="fs-6 text-muted">Số giao dịch</div>
                        <div class="fs-3 fw-bold text-warning">
                            {{ isset($totalBookings) ? $totalBookings : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($bookings->isEmpty())
            <div class="alert alert-warning text-center">Chưa có booking nào được xác nhận trong tháng này.</div>
        @else
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
            <!-- Bảng dữ liệu -->
            <div class="table-responsive">
                <table id="bookingTable" class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tên tour</th>
                            <th>Giá vé</th>
                            <th>Số vé</th>
                            <th>Tổng tiền</th>
                            <th>Ngày đặt</th>
                            <th>Người đặt</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $i => $booking)
                            @php
                                $month = $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('m') : '';
                            @endphp
                            <tr data-month="{{ $month }}">
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $booking->tour->title ?? 'N/A' }}</td>
                                <td class="text-info fw-semibold">{{ number_format($booking->tour->price ?? 0, 0, ',', '.') }} đ
                                </td>
                                <td>
                                    @if($booking->number_of_people >= 4)
                                        <span class="badge bg-warning text-dark">{{ $booking->number_of_people }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $booking->number_of_people }}</span>
                                    @endif
                                </td>
                                <td class="text-success fw-bold">
                                    {{ number_format(($booking->tour->price ?? 0) * $booking->number_of_people, 0, ',', '.') }} đ
                                </td>
                                <td>{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') : '' }}
                                </td>
                                <td>{{ $booking->full_name }}</td>
                                <td>{{ $booking->contact_email }}</td>
                                <td>{{ $booking->contact_phone }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $bookings->links('pagination::bootstrap-4') }}
            </div>
        @endif

    </div>

    @push('scripts')
        <script>
            // Không cần lọc JS client-side nữa
        </script>
    @endpush

@endsection