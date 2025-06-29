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
                        <form action="{{ route('tour.booking.store', $tour->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Tên tour</label>
                                <input type="text" class="form-control" value="{{ $tour->title }}" disabled>
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
                                <label for="note" class="form-label">Ghi chú (tuỳ chọn)</label>
                                <textarea name="note" id="note" class="form-control" rows="2">{{ old('note') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Xác nhận đặt tour</button>
                            <a href="{{ route('tour.show', $tour->id) }}" class="btn btn-link w-100 mt-2">Quay lại chi tiết
                                tour</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection