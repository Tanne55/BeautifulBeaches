@extends('layouts.guest')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/booking_result.css') }}">
@endpush
@section('content')

    <div class="container">
        <div class="card booking-card w-75 m-5 mx-auto">
            <div class="card-header-custom">
                <h2><i class="bi bi-search me-3"></i>Kết quả tra cứu đặt tour</h2>
            </div>

            <div class="card-body-custom">
                <!-- Error Message -->
                @if(session('error'))
                    <div class="alert alert-danger alert-custom d-none">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Booking Found -->
                @if($booking)
                        <div>
                            <div class="booking-header">
                                <h3 class="text-primary fw-bold">Thông tin đặt tour #{{ $booking->booking_code }}</h3>
                            </div>

                            <!-- Status -->
                            @php
                                $statusClass = '';
                                $statusText = '';
                                $statusIcon = '';

                                switch ($booking->status) {
                                    case 'pending':
                                        $statusClass = 'warning';
                                        $statusText = 'Đang chờ xác nhận';
                                        $statusIcon = '<i class="bi bi-hourglass-split"></i>';
                                        break;
                                    case 'confirmed':
                                        $statusClass = 'success';
                                        $statusText = 'Đã xác nhận';
                                        $statusIcon = '<i class="bi bi-check-circle"></i>';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'danger';
                                        $statusText = 'Đã hủy';
                                        $statusIcon = '<i class="bi bi-x-circle"></i>';
                                        break;
                                    case 'completed':
                                        $statusClass = 'info';
                                        $statusText = 'Đã hoàn thành';
                                        $statusIcon = '<i class="bi bi-check2-circle"></i>';
                                        break;
                                    default:
                                        $statusClass = 'secondary';
                                        $statusText = $booking->status;
                                        $statusIcon = '<i class="bi bi-question-circle"></i>';
                                }
                            @endphp
                            <div class="text-center">
                                <div class="br-status-badge br-status-{{ $statusClass }}">

                                    {!! $statusIcon !!}
                                    {{ $statusText }}
                                </div>
                            </div>
                            @php
                                switch ($booking->status) {
                                    case 'pending':
                                        $statusClass = 'warning';
                                        $statusText = 'Đang chờ xác nhận';
                                        $statusIcon = '<i class="bi bi-hourglass-split"></i>';
                                        break;
                                    case 'confirmed':
                                        $statusClass = 'success';
                                        $statusText = 'Đã xác nhận';
                                        $statusIcon = '<i class="bi bi-check-circle"></i>';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'danger';
                                        $statusText = 'Đã hủy';
                                        $statusIcon = '<i class="bi bi-x-circle"></i>';
                                        break;
                                    case 'completed':
                                        $statusClass = 'info';
                                        $statusText = 'Đã hoàn thành';
                                        $statusIcon = '<i class="bi bi-check2-circle"></i>';
                                        break;
                                    default:
                                        $statusClass = 'secondary';
                                        $statusText = $booking->status;
                                        $statusIcon = '<i class="bi bi-question-circle"></i>';
                                }
                            @endphp
                            <div class="text-center">
                                <div class="status-badge status-{{ $statusClass }}">

                                    {{ $statusIcon }}
                                    {{ $statusText }}
                                </div>
                            </div>

                            <!-- Booking Code & QR -->
                            <div class="booking-code-section">
                                <h4 class="mb-3">Mã đặt tour của bạn</h4>
                                <div class="qr-container">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode(route('bookings.result', ['booking_code' => $booking->booking_code])) }}"
                                        alt="QR Code" class="img-fluid mb-3">
                                    <div class="fw-bold">{{ $booking->booking_code }}</div>
                                </div>
                                <p class="text-muted mt-3 mb-0">Quét mã QR hoặc sử dụng mã để tra cứu</p>
                            </div>

                            <!-- Customer & Tour Info -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="br-info-section">
                                        <h4><i class="bi bi-person-circle me-2"></i>Thông tin khách hàng</h4>
                                        <div class="br-info-item">
                                            <span class="br-info-label">Họ và tên:</span>
                                            <span class="br-info-value">{{ $booking->full_name }}</span>
                                        </div>
                                        <div class="br-info-item">
                                            <span class="br-info-label">Email:</span>
                                            <span class="br-info-value"> {{ $booking->contact_email }}</span>
                                        </div>
                                        <div class="br-info-item">
                                            <span class="br-info-label">Điện thoại:</span>
                                            <span class="br-info-value">{{ $booking->contact_phone }}</span>
                                        </div>
                                        <div class="br-info-item">
                                            <span class="br-info-label">Số người:</span>
                                            <span class="br-info-value">{{ $booking->number_of_people }}</span>
                                        </div>
                                        <div class="br-info-item">
                                            <span class="br-info-label">Ngày đặt:</span>
                                            <span class="br-info-value">{{ $booking->booking_date->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="br-info-item">
                                            <span class="br-info-label">Tổng tiền:</span>
                                            <span
                                                class="br-info-value text-primary fw-bold">{{ number_format($booking->total_amount) }}
                                                tr vnđ</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 mb-4">
                                    <div class="br-info-section">
                                        <h4><i class="bi bi-map me-2"></i>Thông tin tour</h4>
                                        <div class="br-info-item">
                                            <span class="br-info-label">Tên tour:</span>
                                            <span class="br-info-value">{{ $booking->tour->title }}</span>
                                        </div>
                                        @if($booking->tour->detail)
                                            <div class="br-info-item">
                                                <span class="br-info-label">Khởi hành:</span>
                                                <span class="br-info-value">
                                                    @if($booking->tour->detail->departure_time)
                                                        {{ \Carbon\Carbon::parse($booking->tour->detail->departure_time)->format('d/m/Y H:i') }}
                                                    @else
                                                        Chưa xác định
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="br-info-item">
                                                <span class="br-info-label">Thời gian:</span>
                                                <span class="br-info-value">
                                                    @if($booking->tour->duration_days)
                                                        {{ $booking->tour->duration_days }} ngày
                                                    @else
                                                        Chưa xác định
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="br-info-item">
                                                <span class="br-info-label">Về:</span>
                                                <span class="br-info-value">
                                                    @if($booking->tour->detail->return_time)
                                                        {{ \Carbon\Carbon::parse($booking->tour->detail->return_time)->format('d/m/Y H:i') }}
                                                    @else
                                                        Chưa xác định
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Note -->
                        @if($booking->note)
                            <div class="note-section">
                                <h4><i class="bi bi-chat-square-text me-2"></i>Ghi chú</h4>
                                <p class="mb-0">{{ $booking->note }}</p>
                            </div>
                        @endif

                        <!-- Pending Note -->
                        <div class="pending-note d-none" id="pendingNote">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle fs-4 me-3 text-warning"></i>
                                <div>
                                    <strong>Đặt tour đang được xử lý</strong>
                                    <p class="mb-0 mt-1">Chúng tôi sẽ liên hệ với bạn qua email hoặc điện thoại
                                        để xác nhận trong thời gian sớm nhất.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="{{ route('home') }}" class="btn btn-primary-custom">
                                <i class="bi bi-house me-2"></i>Trang chủ
                            </a>
                            <a href="javascript:void(0)" onclick="window.print()" class="btn btn-outline-custom">
                                <i class="bi bi-printer me-2"></i>In thông tin
                            </a>
                        </div>
                    </div>
                @else
                <!-- Booking Not Found -->
                <div id="bookingNotFound" class="d-none">
                    <div class="search-section">
                        <i class="bi bi-search search-icon"></i>
                        <h4 class="text-primary fw-bold mb-3">Không tìm thấy thông tin đặt tour</h4>
                        <p class="text-muted mb-4">Vui lòng kiểm tra lại mã đặt tour của bạn hoặc thử tra
                            cứu lại.</p>

                        <form class="search-form" action="{{ route('bookings.track') }}" method="GET">
                            <div class="input-group">

                                <input type="text" name="booking_code" class="form-control search-input"
                                    placeholder="Nhập mã đặt tour" aria-label="Mã đặt tour" required
                                    value="{{ request('booking_code') }}">
                                <button class="btn btn-primary search-btn" type="submit">
                                    <i class="bi bi-search me-2"></i>Tra cứu
                                </button>
                            </div>
                        </form>

                        <div class="mt-4">
                            <a href="{{ route('home') }}" class="btn btn-outline-custom">
                                <i class="bi bi-house me-2"></i>Quay lại trang chủ
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection