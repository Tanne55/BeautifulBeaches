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
                            <button type="button" class="btn btn-info btn-sm rounded-4 p-2 px-4 me-2" 
                                data-bs-toggle="offcanvas" 
                                data-bs-target="#ticketsOffcanvas" 
                                data-booking-id="{{ $booking->id }}"
                                data-booking-code="{{ $booking->booking_code }}">
                                <i class="bi bi-ticket-perforated"></i> Xem vé
                            </button>
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

    <!-- Tickets Offcanvas -->
    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="ticketsOffcanvas" aria-labelledby="ticketsOffcanvasLabel" data-bs-backdrop="true" data-bs-keyboard="true">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold" id="ticketsOffcanvasLabel">
                <i class="bi bi-ticket-perforated me-2 text-white"></i>
                <span id="offcanvas-title-text">Danh sách vé</span>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            @include('partials.ticket-display')
        </div>
    </div>

    <style>
        /* Offcanvas styles for user history page */
        .offcanvas.offcanvas-bottom {
            height: 70vh !important;
            max-height: 600px !important;
            border-radius: 20px 20px 0 0;
            box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.15);
        }

        .offcanvas-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom: none;
            border-radius: 20px 20px 0 0;
            position: relative;
        }

        .offcanvas-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .offcanvas-header .btn-close {
            filter: invert(1);
            opacity: 0.8;
        }

        .offcanvas-header .btn-close:hover {
            opacity: 1;
        }

        .offcanvas-body {
            background: #f8f9fa;
            padding: 20px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(102, 126, 234, 0.3) transparent;
        }

        .offcanvas-body::-webkit-scrollbar {
            width: 6px;
        }

        .offcanvas-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .offcanvas-body::-webkit-scrollbar-thumb {
            background: rgba(102, 126, 234, 0.3);
            border-radius: 3px;
        }

        .offcanvas-body::-webkit-scrollbar-thumb:hover {
            background: rgba(102, 126, 234, 0.5);
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .offcanvas.offcanvas-bottom {
                height: 80vh !important;
            }
            
            .offcanvas-body {
                padding: 15px;
            }
        }

        /* Ticket cards in offcanvas */
        .offcanvas .ticket-card {
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .offcanvas .ticket-card:last-child {
            margin-bottom: 0;
        }

        .offcanvas .ticket-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        /* Backdrop enhancement */
        .offcanvas-backdrop {
            background-color: rgba(0, 0, 0, 0.5) !important;
            backdrop-filter: blur(2px);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ticketsOffcanvas = document.getElementById('ticketsOffcanvas');
            let currentBookingId = null;

            // Initialize Bootstrap Offcanvas with default backdrop
            const offcanvasInstance = new bootstrap.Offcanvas(ticketsOffcanvas, {
                backdrop: true, // Use default Bootstrap backdrop
                keyboard: true,
                scroll: false
            });

            // Handle button clicks to load tickets
            document.querySelectorAll('[data-bs-target="#ticketsOffcanvas"]').forEach(button => {
                button.addEventListener('click', function() {
                    currentBookingId = this.getAttribute('data-booking-id');
                    const bookingCode = this.getAttribute('data-booking-code');
                    
                    // Update title
                    document.getElementById('offcanvas-title-text').textContent = `Vé của booking #${bookingCode}`;
                    
                    // Reset container
                    resetTicketContainer();
                });
            });

            ticketsOffcanvas.addEventListener('show.bs.offcanvas', function () {
                if (currentBookingId) {
                    loadUserBookingTickets(currentBookingId);
                }
            });

            // Handle escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && ticketsOffcanvas.classList.contains('show')) {
                    offcanvasInstance.hide();
                }
            });

            // Add swipe down to close for mobile
            let startY = 0;
            let currentY = 0;
            let isDragging = false;

            ticketsOffcanvas.addEventListener('touchstart', function(e) {
                startY = e.touches[0].clientY;
                isDragging = true;
            });

            ticketsOffcanvas.addEventListener('touchmove', function(e) {
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

            ticketsOffcanvas.addEventListener('touchend', function(e) {
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

        function resetTicketContainer() {
            const container = document.getElementById('ticketContainer');
            container.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                    <p class="text-muted mt-2">Đang tải thông tin vé...</p>
                </div>
            `;
        }

        function loadUserBookingTickets(bookingId) {
            const container = document.getElementById('ticketContainer');
            
            fetch(`/user/booking/${bookingId}/tickets`)
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
    </script>
@endsection