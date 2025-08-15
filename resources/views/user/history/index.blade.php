@extends('layouts.guest')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4 text-center">Lịch sử đặt tour của bạn</h2>

        @if($bookings->where('status', 'confirmed')->count() > 0)
            <div class="alert alert-info text-center fw-bold mb-4" style="opacity: 0.7;">
                <span>Thanh toán cần thực hiện (chờ xác nhận): </span>
                <span class="text-danger fs-4">
                    {{ number_format($pendingTotalAmount, 0, ',', '.') }} tr vnđ
                </span>
                <span>(Đã bao gồm discount)</span>
            </div>
        @endif

        <!-- Filter Form -->
        <div class="cardd mb-5">
            <div class="card-body">
                <form method="GET" action="{{ route('user.history') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Tìm kiếm theo tên tour</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Nhập tên tour...">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận
                            </option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">Từ ngày</label>
                        <input type="date" class="form-control" id="date_from" name="date_from"
                            value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-grid gap-2 w-100">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                            <a href="{{ route('user.history') }}" class="btn btn-outline-secondary text-secondary">Làm
                                mới</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}<i class="fas fa-check-circle"></i> </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if($bookings->isEmpty())
            <div class="alert alert-info text-center">Bạn chưa đặt tour nào.</div>
        @else

            <div class="action-bar">
                <a href="{{ route('user.cancellation_requests') }}" class="btn-ocean">
                    <i class="fas fa-list"></i>
                    Xem các yêu cầu hủy đã gửi
                </a>
            </div>
            <div class="booking-grid">
                @foreach($bookings as $i => $booking)
                    <div class="booking-card">
                        <div class="card-header">
                            <h3 class="tour-title">
                                <span class="tour-number">{{ $i + 1 }}</span>
                                {{ $booking->tour->title ?? 'N/A' }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="booking-info m-2">
                                <div class="info-item">
                                    <span class="info-label">Ngày đặt</span>
                                    <span class="info-value">
                                        <i class="fas fa-calendar"></i>{{ $booking->booking_date ?
                        \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') : '' }}
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Trạng thái</span>
                                    <span class="status-badge">
                                        <i class="fas fa-check"></i>
                                        @if($booking->status === 'pending')
                                            <span class="badge text-dark status-pending p-2">Chờ xác nhận</span>
                                        @elseif($booking->status === 'confirmed')
                                            <span class="badge status-confirmed p-2">Đã xác nhận</span>
                                        @elseif($booking->status === 'cancelled')
                                            <span class="badge status-cancelled p-2">Đã hủy</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $booking->status }}</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Số người</span>
                                    <span class="info-value">
                                        <i class="fas fa-users"></i> {{ $booking->number_of_people }}
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Giá 1 người</span>
                                    <span class="info-value price-highlight">
                                        <i class="fas fa-tag"></i>
                                        @php
                                            $priceObj = optional($booking->tour->prices->first());
                                        @endphp
                                        {{ $priceObj->price ? number_format($priceObj->price, 0, ',', '.') . ' tr vnđ' : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Ghi chú</span>
                                <span class="info-value">{{ $booking->note }}</span>
                            </div>
                        </div>
                        <div class="card-actions">
                            @if($booking->canBeCancelled())
                                <a href="{{ route('user.booking.cancel.form', $booking->id) }}"
                                    class="btn btn-danger btn-sm rounded-4 p-2 px-4"> <i class="fas fa-times"></i> Yêu
                                    cầu hủy</a>
                            @else
                                <span class="text-muted">Đã hoàn tất</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Phân trang -->
            <div class="d-flex justify-content-center mt-4">
                {{ $bookings->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection