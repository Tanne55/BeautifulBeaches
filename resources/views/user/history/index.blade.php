@extends('layouts.guest')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">Lịch sử đặt tour của bạn</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }} Đã gửi thông tin hủy cho CEO trực thuộc, vui lòng chờ duyệt.</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($bookings->isEmpty())
        <div class="alert alert-info text-center">Bạn chưa đặt tour nào.</div>
    @else
        @if($bookings->where('status', 'confirmed')->count() > 0)
            <div class="alert alert-success text-center fw-bold mb-4">
                <span>Thanh toán cần thực hiện (chờ xác nhận): </span>
                <span class="text-danger fs-4">
                    {{ number_format($pendingTotalAmount, 0, ',', '.') }} đ
                </span>
            </div>
        @endif
        <div class="mb-3">
            <a href="{{ route('user.cancellation_requests') }}" class="btn btn-outline-info">Xem các yêu cầu hủy đã gửi</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tên tour</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th>Số người đặt</th>
                        <th>Giá 1 người</th>
                        <th>Ghi chú</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $i => $booking)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $booking->tour->title ?? 'N/A' }}</td>
                            <td>{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') : '' }}</td>
                            <td>
                                @if($booking->status === 'pending')
                                    <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="badge bg-success">Đã xác nhận</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="badge bg-danger">Đã hủy</span>
                                @else
                                    <span class="badge bg-secondary">{{ $booking->status }}</span>
                                @endif
                            </td>
                            <td>{{ $booking->number_of_people }}</td>
                            <td>
                                @php
                                    $priceObj = optional($booking->tour->prices->first());
                                @endphp
                                {{ $priceObj->price ? number_format($priceObj->price, 0, ',', '.') . ' đ' : 'N/A' }}
                            </td>
                            <td>{{ $booking->note }}</td>
                            <td>
                                @if($booking->canBeCancelled())
                                    <a href="{{ route('user.booking.cancel.form', $booking->id) }}" class="btn btn-outline-danger btn-sm">Yêu cầu hủy</a>
                                @else
                                    <span class="text-muted">Đã hoàn tất</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
