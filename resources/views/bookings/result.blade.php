@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Kết quả tra cứu đặt tour</h3>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($booking)
                        <div class="booking-info">
                            <h4 class="mb-4">Thông tin đặt tour #{{ $booking->booking_code }}</h4>
                            
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <div class="booking-status">
                                        <h5>Trạng thái đặt tour:</h5>
                                        @php
                                            $statusClass = '';
                                            $statusText = '';
                                            
                                            switch($booking->status) {
                                                case 'pending':
                                                    $statusClass = 'warning';
                                                    $statusText = 'Đang chờ xác nhận';
                                                    break;
                                                case 'confirmed':
                                                    $statusClass = 'success';
                                                    $statusText = 'Đã xác nhận';
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'danger';
                                                    $statusText = 'Đã hủy';
                                                    break;
                                                case 'completed':
                                                    $statusClass = 'info';
                                                    $statusText = 'Đã hoàn thành';
                                                    break;
                                                default:
                                                    $statusClass = 'secondary';
                                                    $statusText = $booking->status;
                                            }
                                        @endphp
                                        <div class="alert alert-{{ $statusClass }}">
                                            <h5 class="alert-heading">{{ $statusText }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="booking-code-qr">
                                        <h5>Mã đặt tour</h5>
                                        <div class="p-3 bg-light rounded-3">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode(route('bookings.result', ['booking_code' => $booking->booking_code])) }}" 
                                                alt="QR Code" class="img-fluid mb-2">
                                            <div class="fw-bold">{{ $booking->booking_code }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Thông tin khách hàng</h5>
                                    <ul class="list-unstyled">
                                        <li><strong>Họ và tên:</strong> {{ $booking->full_name }}</li>
                                        <li><strong>Email:</strong> {{ $booking->contact_email }}</li>
                                        <li><strong>Điện thoại:</strong> {{ $booking->contact_phone }}</li>
                                        <li><strong>Số người:</strong> {{ $booking->number_of_people }}</li>
                                        <li><strong>Ngày đặt:</strong> {{ $booking->booking_date->format('d/m/Y') }}</li>
                                        <li><strong>Tổng tiền:</strong> {{ number_format($booking->total_amount) }} VNĐ</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5>Thông tin tour</h5>
                                    <ul class="list-unstyled">
                                        <li><strong>Tên tour:</strong> {{ $booking->tour->name }}</li>
                                        @if($booking->tour->detail)
                                            <li><strong>Ngày khởi hành:</strong> 
                                                @if($booking->tour->detail->departure_time)
                                                    {{ \Carbon\Carbon::parse($booking->tour->detail->departure_time)->format('d/m/Y H:i') }}
                                                @else
                                                    Chưa xác định
                                                @endif
                                            </li>
                                            <li><strong>Thời gian tour:</strong> {{ $booking->tour->detail->duration ?? 'Chưa xác định' }}</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            
                            @if($booking->note)
                                <div class="mt-4">
                                    <h5>Ghi chú</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $booking->note }}
                                    </div>
                                </div>
                            @endif
                            
                            <div class="mt-4">
                                <a href="{{ route('home') }}" class="btn btn-primary">Quay lại trang chủ</a>
                                @if($booking->status == 'pending')
                                    <p class="text-muted mt-3">
                                        <i class="bi bi-info-circle"></i> Đặt tour của bạn đang được xử lý. Chúng tôi sẽ liên hệ với bạn qua email hoặc điện thoại để xác nhận.
                                    </p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-4">
                                <i class="bi bi-search" style="font-size: 3rem; color: #6c757d;"></i>
                            </div>
                            <h5>Không tìm thấy thông tin đặt tour</h5>
                            <p>Vui lòng kiểm tra lại mã đặt tour của bạn.</p>
                            
                            <form action="{{ route('bookings.track') }}" method="GET" class="mt-4 mb-4 col-md-8 mx-auto">
                                <div class="input-group">
                                    <input type="text" name="booking_code" class="form-control" placeholder="Nhập mã đặt tour" 
                                           aria-label="Mã đặt tour" required value="{{ request('booking_code') }}">
                                    <button class="btn btn-primary" type="submit">Tra cứu</button>
                                </div>
                            </form>
                            
                            <a href="{{ route('home') }}" class="btn btn-outline-primary mt-3">Quay lại trang chủ</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
