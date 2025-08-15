@extends('layouts.guest')

@section('content')
    <div class="container my-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <h2 class="mb-4 text-center">Yêu cầu hủy booking</h2>
        <div class="row justify-content-center">
            <div class="col-md-6 d-flex justify-content-center">
                <div class="card shadow">
                    <div class="card-body">
                        <form action="{{ route('user.booking.cancel.submit', $booking->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Kiểu hủy</label>
                                <select name="cancellation_type" class="form-select" required>
                                    <option value="full">Hủy toàn bộ</option>
                                    <option value="partial">Hủy một phần</option>
                                </select>
                            </div>
                            <div class="mb-3" id="partial-slots" style="display:none;">
                                <label class="form-label">Số lượng muốn hủy</label>
                                <input type="number" name="cancelled_slots" class="form-control" min="1"
                                    max="{{ $booking->number_of_people }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Lý do hủy (tuỳ chọn)</label>
                                <textarea name="reason" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">Gửi yêu cầu hủy</button>
                            <a href="{{ route('user.history') }}" class="btn btn-link w-100 mt-2">Quay lại lịch sử đặt
                                tour</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.querySelector('select[name="cancellation_type"]');
            const partialDiv = document.getElementById('partial-slots');
            typeSelect.addEventListener('change', function () {
                partialDiv.style.display = this.value === 'partial' ? 'block' : 'none';
            });
        });
    </script>
@endsection