@extends('layouts.auth')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">Thống kê doanh thu tháng {{ $now->format('m/Y') }}</h2>
    <div class="alert alert-info text-center fs-5 mb-4">
        <strong>Tổng doanh thu tháng này:</strong>
        <span class="text-success fs-3">{{ number_format($totalRevenue, 0, ',', '.') }} đ</span>
    </div>
    @if($bookings->isEmpty())
        <div class="alert alert-warning text-center">Chưa có booking nào được xác nhận trong tháng này.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tên tour</th>
                        <th>Giá</th>
                        <th>Ngày đặt</th>
                        <th>Người đặt</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $i => $booking)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $booking->tour->title ?? 'N/A' }}</td>
                            <td>{{ number_format($booking->tour->price ?? 0, 0, ',', '.') }} đ</td>
                            <td>{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') : '' }}</td>
                            <td>{{ $booking->full_name }}</td>
                            <td>{{ $booking->contact_email }}</td>
                            <td>{{ $booking->contact_phone }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
