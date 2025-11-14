@extends('layouts.guest')

@section('head')
    @vite(['resources/css/calendar.css', 'resources/js/booking-calendar.js'])
@endsection

@section('content')
    <div class="container my-5">
        <h2 class="mb-4 text-center">Đặt tour: {{ $tour->title }}</h2>


        <div class="card shadow w-75 mx-auto">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        <div class="mb-2">{{ session('success') }}</div>
                        @if(session('booking_code'))
                            <div class="booking-code-display p-3 text-center rounded mb-2">
                                <h5 class="text-dark mb-0">Mã đặt tour</h5>
                                <div class="code-value fw-bold" style="font-size: 1.5rem;">{{ session('booking_code') }}</div>
                            </div>
                            <div class="d-grid gap-2 mb-2">
                                <a href="{{ route('bookings.result', ['booking_code' => session('booking_code')]) }}"
                                    class="btn  text-dark">
                                    <i class="bi bi-search"></i> Xem chi tiết đặt tour
                                </a>
                            </div>
                            <p class="small mb-0"><i class="bi bi-info-circle"></i> Vui lòng lưu lại mã này để tra cứu trạng thái
                                đặt tour.</p>
                        @endif
                    </div>

                   
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
                    $departureDates = optional($tour->detail)->departure_dates ?? [];
                    $hasAvailableDates = !empty($departureDates);
                    $today = now()->format('Y-m-d');
                    $price = $tour->prices->where('start_date', '<=', $today)
                        ->where('end_date', '>=', $today)
                        ->first();
                    if (!$price) {
                        $price = $tour->prices->first();
                    }
                    $unit = $price ? ($price->discount && $price->discount > 0 ? $price->final_price : $price->price) : 0;
                @endphp

                

                @if(!$hasAvailableDates)
                    <div class="alert alert-warning">
                        <strong>Lưu ý:</strong> Tour này hiện tại không có ngày khởi hành khả dụ.
                        Vui lòng liên hệ để biết thêm thông tin.
                    </div>
                @endif
                <form action="{{ route('tour.booking.store', $tour->id) }}" method="POST" id="booking-form">
                    @csrf
                    <input type="hidden" name="selected_departure_date" id="selected_departure_date" value="{{ old('selected_departure_date') }}">
                    
                    <!-- Calendar Section -->
                    @if($hasAvailableDates)
                    <div class="mb-4">
                        <h5 class="mb-3">Chọn ngày khởi hành</h5>
                        <div class="calendar-container">
                            <div id="calendar"></div>
                        </div>
                        <div id="selected-date-info" class="mt-3 p-3 bg-light rounded border" style="display: none;">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar-check text-success me-2"></i>
                                <h6 class="text-primary mb-0 me-2">Ngày khởi hành đã chọn:</h6>
                            </div>
                            <div id="selected-date-display" class="fw-bold fs-5 text-dark mt-1"></div>
                            <small class="text-muted">Bạn có thể thay đổi bằng cách chọn ngày khác trên lịch</small>
                        </div>
                    </div>
                    @endif
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
                                @if($tour->detail && !empty($tour->detail->departure_dates))
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <small class="text-muted">Ngày khởi hành khả dụ: {{ count($tour->detail->departure_dates) }} ngày</small>
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
                                    value="{{ old('number_of_people', 1) }}" min="1" required {{ !$hasAvailableDates ? 'disabled' : '' }}>
                            </div>
                            
                            <!-- Hiển thị thông tin ngày đã chọn -->
                            <div class="mb-3">
                                <label class="form-label">Ngày khởi hành:</label>
                                <div id="departure-date-selected" class="form-control-plaintext border rounded p-2 bg-light">
                                    <span id="departure-date-text" class="text-muted">
                                        <i class="bi bi-calendar-x me-1"></i>Chưa chọn ngày khởi hành
                                    </span>
                                </div>
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
                    <button type="submit" class="btn btn-success w-100" id="submit-btn" {{ !$hasAvailableDates ? 'disabled' : '' }}>
                        {{ !$hasAvailableDates ? 'Không có ngày khởi hành' : 'Vui lòng chọn ngày khởi hành' }}
                    </button>
                    <a href="{{ route('tour.show', $tour->id) }}" class="btn btn-link w-100 mt-2">Quay lại chi tiết
                        tour</a>
                </form>
            </div>
        </div>


    </div>
    
    @if($hasAvailableDates)
    <script>
        // Available dates from PHP
        const availableDates = @json($departureDates);
        console.log('Available dates:', availableDates);
        console.log('Has available dates:', {{ $hasAvailableDates ? 'true' : 'false' }});
        
        document.addEventListener('DOMContentLoaded', function () {
            // Price calculation
            const numberInput = document.getElementById('number_of_people');
            const totalAmountDiv = document.getElementById('total-amount');
            const unitPrice = {{ $unit }};
            
            function updateTotal() {
                let qty = parseInt(numberInput.value);
                if (isNaN(qty) || qty < 1) qty = 1;
                const total = unitPrice * qty;
                totalAmountDiv.textContent = total.toLocaleString('vi-VN') + ' TrVND';
            }
            
            numberInput.addEventListener('input', updateTotal);
            updateTotal();
            
            // Calendar initialization
            const calendar = new Calendar('calendar', {
                availableDates: availableDates,
                onDateSelect: function(date) {
                    document.getElementById('selected_departure_date').value = date;
                    
                    // Hiển thị thông tin ngày đã chọn trong calendar info
                    document.getElementById('selected-date-info').style.display = 'block';
                    document.getElementById('selected-date-display').textContent = 
                        new Date(date).toLocaleDateString('vi-VN', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    
                    // Cập nhật label ngày khởi hành
                    const departureDateText = document.getElementById('departure-date-text');
                    const formattedDate = new Date(date).toLocaleDateString('vi-VN', {
                        weekday: 'short',
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                    departureDateText.innerHTML = `<i class="bi bi-calendar-check text-success me-1"></i><strong>${formattedDate}</strong>`;
                    
                    // Thay đổi style của departure date container
                    const departureDateContainer = document.getElementById('departure-date-selected');
                    departureDateContainer.classList.remove('bg-light');
                    departureDateContainer.classList.add('bg-success-subtle', 'border-success');
                    
                    // Enable submit button
                    const submitBtn = document.getElementById('submit-btn');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Xác nhận đặt tour';
                    submitBtn.classList.remove('btn-secondary');
                    submitBtn.classList.add('btn-success');
                }
            });
            
            // Form validation - ensure date is selected
            document.getElementById('booking-form').addEventListener('submit', function(e) {
                const selectedDepartureDate = document.getElementById('selected_departure_date').value;
                if (!selectedDepartureDate) {
                    e.preventDefault();
                    alert('Vui lòng chọn ngày khởi hành!');
                    return false;
                }
            });
            
            // Initially disable submit button until date is selected
            const submitBtn = document.getElementById('submit-btn');
            const selectedDepartureDate = document.getElementById('selected_departure_date').value;
            
            if (!selectedDepartureDate) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Vui lòng chọn ngày khởi hành';
                submitBtn.classList.remove('btn-success');
                submitBtn.classList.add('btn-secondary');
            } else {
                // Nếu có ngày đã chọn từ old input, hiển thị lại
                const departureDateText = document.getElementById('departure-date-text');
                const formattedDate = new Date(selectedDepartureDate).toLocaleDateString('vi-VN', {
                    weekday: 'short',
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
                departureDateText.innerHTML = `<i class="bi bi-calendar-check text-success me-1"></i><strong>${formattedDate}</strong>`;
                
                const departureDateContainer = document.getElementById('departure-date-selected');
                departureDateContainer.classList.remove('bg-light');
                departureDateContainer.classList.add('bg-success-subtle', 'border-success');
                
                // Also show in calendar info section
                document.getElementById('selected-date-info').style.display = 'block';
                document.getElementById('selected-date-display').textContent = 
                    new Date(selectedDepartureDate).toLocaleDateString('vi-VN', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                
                // Enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Xác nhận đặt tour';
                submitBtn.classList.remove('btn-secondary');
                submitBtn.classList.add('btn-success');
            }
        });
    </script>
    @else
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Price calculation only
            const numberInput = document.getElementById('number_of_people');
            const totalAmountDiv = document.getElementById('total-amount');
            const unitPrice = {{ $unit }};
            
            function updateTotal() {
                let qty = parseInt(numberInput.value);
                if (isNaN(qty) || qty < 1) qty = 1;
                const total = unitPrice * qty;
                totalAmountDiv.textContent = total.toLocaleString('vi-VN') + ' TrVND';
            }
            
            numberInput.addEventListener('input', updateTotal);
            updateTotal();

            // Prevent form submission when no available dates
            document.getElementById('booking-form').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Tour này hiện tại không có ngày khởi hành khả dụ. Vui lòng liên hệ để biết thêm thông tin.');
                return false;
            });
            
            // Set departure date text to unavailable
            const departureDateText = document.getElementById('departure-date-text');
            departureDateText.innerHTML = `<i class="bi bi-calendar-x text-danger me-1"></i><span class="text-danger">Không có ngày khởi hành khả dụ</span>`;
            
            const departureDateContainer = document.getElementById('departure-date-selected');
            departureDateContainer.classList.remove('bg-light');
            departureDateContainer.classList.add('bg-danger-subtle', 'border-danger');
        });
    </script>
    @endif
@endsection