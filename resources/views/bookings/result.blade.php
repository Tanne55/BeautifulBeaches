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
                            <button type="button" class="btn btn-outline-custom" data-bs-toggle="offcanvas"
                                data-bs-target="#ticketsOffcanvas" aria-controls="ticketsOffcanvas">
                                <i class="bi bi-ticket-perforated me-2"></i>Xem vé
                            </button>
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

    <!-- Tickets Offcanvas -->
    @if($booking)
        <div class="offcanvas offcanvas-bottom" tabindex="-1" id="ticketsOffcanvas" aria-labelledby="ticketsOffcanvasLabel"
            data-bs-backdrop="true" data-bs-keyboard="true">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title fw-bold" id="ticketsOffcanvasLabel">
                    <i class="bi bi-ticket-perforated me-2 text-white"></i>
                    Vé của booking #{{ $booking->booking_code }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                @include('partials.ticket-display')
            </div>
        </div>
    @endif

    <script>
        // Load tickets when offcanvas is shown
        @if($booking)
            document.addEventListener('DOMContentLoaded', function () {
                const ticketsOffcanvas = document.getElementById('ticketsOffcanvas');
                let ticketsLoaded = false;

                // Initialize Bootstrap Offcanvas with default backdrop
                const offcanvasInstance = new bootstrap.Offcanvas(ticketsOffcanvas, {
                    backdrop: true, // Use default Bootstrap backdrop
                    keyboard: true,
                    scroll: false
                });

                ticketsOffcanvas.addEventListener('show.bs.offcanvas', function () {
                    if (!ticketsLoaded) {
                        loadBookingTickets('{{ $booking->booking_code }}');
                        ticketsLoaded = true;
                    }
                });

                // Handle escape key
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape' && ticketsOffcanvas.classList.contains('show')) {
                        offcanvasInstance.hide();
                    }
                });

                // Add swipe down to close for mobile
                let startY = 0;
                let currentY = 0;
                let isDragging = false;

                ticketsOffcanvas.addEventListener('touchstart', function (e) {
                    startY = e.touches[0].clientY;
                    isDragging = true;
                });

                ticketsOffcanvas.addEventListener('touchmove', function (e) {
                    if (!isDragging) return;

                    currentY = e.touches[0].clientY;
                    const diffY = currentY - startY;

                    // Only allow downward swipe when at the top of scroll
                    const scrollTop = ticketsOffcanvas.querySelector('.offcanvas-body').scrollTop;

                    if (diffY > 0 && scrollTop === 0) {
                        e.preventDefault();
                        const progress = Math.min(diffY / 150, 1);
                        ticketsOffcanvas.style.transform = `translateY(${diffY}px)`;
                        ticketsOffcanvas.style.opacity = 1 - progress * 0.3;
                    }
                });

                ticketsOffcanvas.addEventListener('touchend', function (e) {
                    if (!isDragging) return;

                    const diffY = currentY - startY;
                    const scrollTop = ticketsOffcanvas.querySelector('.offcanvas-body').scrollTop;

                    if (diffY > 100 && scrollTop === 0) {
                        // Close if swiped down more than 100px
                        offcanvasInstance.hide();
                    } else {
                        // Reset position
                        ticketsOffcanvas.style.transform = '';
                        ticketsOffcanvas.style.opacity = '';
                    }

                    isDragging = false;
                    startY = 0;
                    currentY = 0;
                });
            });

            function loadBookingTickets(bookingCode) {
                const container = document.getElementById('ticketContainer');

                fetch(`/api/bookings/tickets?booking_code=${bookingCode}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.tickets.length > 0) {
                            renderTickets(data.tickets);
                        } else {
                            showNoTickets();
                        }
                    })
                    .catch(error => {
                        console.error('Error loading tickets:', error);
                        showTicketError();
                    });
            }

            function renderTickets(tickets) {
                const container = document.getElementById('ticketContainer');
                let html = '';

                tickets.forEach((ticket, index) => {
                    html += `
                            <div class="ticket-card mb-3 p-3">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="ticket-code">${ticket.ticket_code}</div>
                                            <span class="status-badge-ticket status-${ticket.status_class}">
                                                ${ticket.status_text}
                                            </span>
                                        </div>

                                        <div class="ticket-divider"></div>

                                        <div class="ticket-info">
                                            <div class="row">
                                                <div class="col-md-6 col-12 mb-2">
                                                    <small class="text-white-50">Họ tên</small>
                                                    <div class="fw-semibold">${ticket.full_name}</div>
                                                </div>
                                                <div class="col-md-6 col-12 mb-2">
                                                    <small class="text-white-50">Giá vé</small>
                                                    <div class="fw-semibold">${formatPrice(ticket.unit_price)} VNĐ</div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-12 mb-2">
                                                    <small class="text-white-50">Email</small>
                                                    <div class="small">${ticket.email || 'N/A'}</div>
                                                </div>
                                                <div class="col-md-6 col-12 mb-2">
                                                    <small class="text-white-50">SĐT</small>
                                                    <div class="small">${ticket.phone || 'N/A'}</div>
                                                </div>
                                            </div>
                                            <div class="mt-2 text-center">
                                                <small class="text-white-50">Ngày tạo: ${ticket.created_at}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                });

                container.innerHTML = html;
            }

            function showNoTickets() {
                const container = document.getElementById('ticketContainer');
                container.innerHTML = `
                        <div class="no-tickets">
                            <div class="no-tickets-icon">
                                <i class="bi bi-ticket-perforated"></i>
                            </div>
                            <h6 class="text-muted">Chưa có vé nào được tạo</h6>
                            <p class="text-muted small">Vé sẽ được tạo sau khi booking được xác nhận</p>
                        </div>
                    `;
            }

            function showTicketError() {
                const container = document.getElementById('ticketContainer');
                container.innerHTML = `
                        <div class="no-tickets">
                            <div class="no-tickets-icon text-danger">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <h6 class="text-danger">Có lỗi xảy ra</h6>
                            <p class="text-muted small">Không thể tải thông tin vé. Vui lòng thử lại sau.</p>
                        </div>
                    `;
            }

            function formatPrice(price) {
                return new Intl.NumberFormat('vi-VN').format(price);
            }
        @endif
    </script>
@endsection