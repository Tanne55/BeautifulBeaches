@extends('layouts.guest')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4 text-center">Đặt tour: {{ $tour->title }}</h2>
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-body">
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
                            $availableSlots = $tour->capacity - $tour->bookings->sum('number_of_people');
                        @endphp
                        
                        @if($isPastTour)
                            <div class="alert alert-warning">
                                <strong>Lưu ý:</strong> Tour này đã khởi hành vào {{ $departureTime ? date('d/m/Y H:i', strtotime($departureTime)) : 'N/A' }}. 
                                Không thể đặt tour đã qua thời gian khởi hành.
                            </div>
                        @elseif($availableSlots <= 0)
                            <div class="alert alert-warning">
                                <strong>Hết chỗ:</strong> Tour này đã đầy. Vui lòng chọn tour khác.
                            </div>
                        @endif
                        <form action="{{ route('tour.booking.store', $tour->id) }}" method="POST">
                            @csrf
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
                                        <small class="text-muted">Giá: {{ number_format($tour->price, 0, ',', '.') }} triệu đồng</small>
                                    </div>
                                </div>
                                @if($tour->detail && $tour->detail->departure_time)
                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <small class="text-muted">Khởi hành: {{ date('d/m/Y H:i', strtotime($tour->detail->departure_time)) }}</small>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">Trở về: {{ $tour->detail->return_time ? date('d/m/Y H:i', strtotime($tour->detail->return_time)) : 'N/A' }}</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Tên người đặt</label>
                                <input type="text" name="full_name" id="full_name" class="form-control"
                                    value="{{ old('full_name', Auth::user()->name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact_email" class="form-label">Email liên hệ</label>
                                <input type="email" name="contact_email" id="contact_email" class="form-control"
                                    value="{{ old('contact_email', Auth::user()->email) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact_phone" class="form-label">Số điện thoại</label>
                                <input type="text" name="contact_phone" id="contact_phone" class="form-control"
                                    value="{{ old('contact_phone') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="number_of_people" class="form-label">Số lượng người</label>
                                <input type="number" name="number_of_people" id="number_of_people" class="form-control"
                                    value="{{ old('number_of_people', 1) }}"
                                    min="1"
                                    max="{{ $availableSlots }}"
                                    required
                                    {{ $isPastTour || $availableSlots <= 0 ? 'disabled' : '' }}>
                                <small class="text-muted">
                                    Số vé còn lại: {{ $availableSlots }}
                                </small>
                            </div>
                            <div class="mb-3">
                                <label for="note" class="form-label">Ghi chú (tuỳ chọn)</label>
                                <textarea name="note" id="note" class="form-control" rows="2">{{ old('note') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100" {{ $isPastTour || $availableSlots <= 0 ? 'disabled' : '' }}>
                                {{ $isPastTour ? 'Tour đã khởi hành' : ($availableSlots <= 0 ? 'Hết chỗ' : 'Xác nhận đặt tour') }}
                            </button>
                            <a href="{{ route('tour.show', $tour->id) }}" class="btn btn-link w-100 mt-2">Quay lại chi tiết
                                tour</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection