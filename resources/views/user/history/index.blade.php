@extends('layouts.auth')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">Lịch sử đặt tour của bạn</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($bookings->isEmpty())
        <div class="alert alert-info text-center">Bạn chưa đặt tour nào.</div>
    @else
        @if($bookings->where('status', 'confirmed')->count() > 0)
            <div class="alert alert-success text-center fw-bold mb-4">
                <span>Thanh toán cần thực hiện: </span>
                <span class="text-danger fs-4">
                    {{ number_format($bookings->where('status', 'confirmed')->sum(function($b) { return $b->tour->price ?? 0; }), 0, ',', '.') }} đ
                </span>
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tên tour</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th>Tên người đặt</th>
                        <th>Email liên hệ</th>
                        <th>Số điện thoại</th>
                        <th>Ghi chú</th>
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
                            <td>{{ $booking->full_name }}</td>
                            <td>{{ $booking->contact_email }}</td>
                            <td>{{ $booking->contact_phone }}</td>
                            <td>{{ $booking->note }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
