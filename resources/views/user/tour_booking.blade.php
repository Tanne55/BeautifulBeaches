@extends('layouts.guest')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4 text-center">Đặt tour: {{ $tour->title }}</h2>


        <div class="card shadow w-75 mx-auto">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        <div class="mb-2">{{ session('success') }}</div>
                        @if(session('booking_code'))
                            <div class="booking-code-display p-3 bg-light text-center rounded mb-2">
                                <h5 class="text-primary mb-0">Mã đặt tour</h5>
                                <div class="code-value fw-bold" style="font-size: 1.5rem;">{{ session('booking_code') }}</div>
                            </div>
                            <div class="d-grid gap-2 mb-2">
                                <a href="{{ route('bookings.result', ['booking_code' => session('booking_code')]) }}"
                                    class="btn btn-outline-primary">
                                    <i class="bi bi-search"></i> Xem chi tiết đặt tour
                                </a>
                            </div>
                            <p class="small mb-0"><i class="bi bi-info-circle"></i> Vui lòng lưu lại mã này để tra cứu trạng thái
                                đặt tour.</p>
                        @endif
                    </div>

                    @if(session('booking_code'))
                        <div class="card mt-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Thông tin đặt tour đã hoàn tất</h5>
                            </div>
                            <div class="card-body">
                                <p>Cảm ơn bạn đã đặt tour! Đơn hàng của bạn đã được ghi nhận và đang chờ xác nhận.</p>
                                <ul class="list-unstyled">
                                    <li><strong>Mã đặt tour:</strong> {{ session('booking_code') }}</li>
                                    <li><strong>Trạng thái:</strong> <span class="badge bg-warning text-dark">Đang chờ xác
                                            nhận</span></li>
                                    <li><strong>Bước tiếp theo:</strong> Chúng tôi sẽ liên hệ với bạn qua email hoặc điện thoại để
                                        xác nhận đặt tour.</li>
                                </ul>
                            </div>
                        </div>
                    @endif
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @php
                    $departureTime = optional($tour->detail)->departure_time;
                    $isPastTour = $departureTime && now()->gt($departureTime);
                    $today = now()->format('Y-m-d');
                    $price = $tour->prices->where('start_date', '<=', $today)
                        ->where('end_date', '>=', $today)
                        ->first();
                    if (!$price) {
                        $price = $tour->prices->first();
                    }
                    $unit = $price ? ($price->discount && $price->discount > 0 ? $price->final_price : $price->price) : 0;
                @endphp

                @if($isPastTour)
                    <div class="alert alert-warning">
                        <strong>Lưu ý:</strong> Tour này đã khởi hành vào
                        {{ $departureTime ? date('d/m/Y H:i', strtotime($departureTime)) : 'N/A' }}.
                        Không thể đặt tour đã qua thời gian khởi hành.
                    </div>
                @endif
                <form action="{{ route('tour.booking.store', $tour->id) }}" method="POST">
                    @csrf
                    <div class="row container">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Tên tour</label>
                                <input type="text" class="form-control" value="{{ $tour->title }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Thông tin tour</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">Thời lượng: {{ $tour->duration_days }} ngày</small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Giá:
                                            {{ number_format($tour->prices->first()->final_price, 0, ',', '.') }} triệu
                                            đồng</small>
                                    </div>
                                </div>
                                @if($tour->detail && $tour->detail->departure_time)
                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <small class="text-muted">Khởi hành:
                                                {{ date('d/m/Y H:i', strtotime($tour->detail->departure_time)) }}</small>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">Trở về:
                                                {{ $tour->detail->return_time ? date('d/m/Y', strtotime($tour->detail->return_time)) : 'N/A' }}</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Tên người đặt</label>
                                <input type="text" name="full_name" id="full_name" class="form-control"
                                    value="{{ old('full_name', Auth::check() ? Auth::user()->name : '') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact_email" class="form-label">Email liên hệ</label>
                                <input type="email" name="contact_email" id="contact_email" class="form-control"
                                    value="{{ old('contact_email', Auth::check() ? Auth::user()->email : '') }}" required>
                                <small class="text-muted">Chúng tôi sẽ gửi thông tin xác nhận qua email này</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="contact_phone" class="form-label">Số điện thoại</label>
                                <input type="text" name="contact_phone" id="contact_phone" class="form-control"
                                    value="{{ old('contact_phone') }}" required>
                                <small class="text-muted">Số điện thoại để liên hệ khi cần thiết</small>
                            </div>
                            <div class="mb-3">
                                <label for="number_of_people" class="form-label">Số lượng người</label>
                                <input type="number" name="number_of_people" id="number_of_people" class="form-control"
                                    value="{{ old('number_of_people', 1) }}" min="1" required {{ $isPastTour ? 'disabled' : '' }}>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tổng tiền:</label>
                                <div id="total-amount" class="fw-bold text-success" style="font-size:1.2rem;">0 đ</div>
                            </div>
                            <div class="mb-3">
                                <label for="note" class="form-label">Ghi chú (tuỳ chọn)</label>
                                <textarea name="note" id="note" class="form-control" rows="2">{{ old('note') }}</textarea>
                            </div>
                        </div>

                    </div>
                    <button type="submit" class="btn btn-success w-100" {{ $isPastTour ? 'disabled' : '' }}>
                        {{ $isPastTour ? 'Tour đã khởi hành' : 'Xác nhận đặt tour' }}
                    </button>
                    <a href="{{ route('tour.show', $tour->id) }}" class="btn btn-link w-100 mt-2">Quay lại chi tiết
                        tour</a>
                </form>
            </div>
        </div>


    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const numberInput = document.getElementById('number_of_people');
            const totalAmountDiv = document.getElementById('total-amount');
            const unitPrice = {{ $unit }};
            function updateTotal() {
                let qty = parseInt(numberInput.value);
                if (isNaN(qty) || qty < 1) qty = 1;
                const total = unitPrice * qty;
                totalAmountDiv.textContent = total.toLocaleString('vi-VN') + ' tr vnđ';
            }
            numberInput.addEventListener('input', updateTotal);
            updateTotal();
        });
    </script>
@endsection