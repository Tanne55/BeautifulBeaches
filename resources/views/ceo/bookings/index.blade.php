@extends('layouts.auth')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">Quản lý booking các tour của bạn</h2>
    @if($bookings->isEmpty())
        <div class="alert alert-info text-center">Chưa có booking nào cho các tour của bạn.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tên tour</th>
                        <th>Người đặt</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th>Ghi chú</th>
                        <th>Số vé thực tế của tour / Tổng số vé</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $i => $booking)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $booking->tour->title ?? 'N/A' }}</td>
                            <td>{{ $booking->full_name }}</td>
                            <td>{{ $booking->contact_email }}</td>
                            <td>{{ $booking->contact_phone }}</td>
                            <td>{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') : '' }}</td>
                            <td>
                                @if($booking->status === 'pending')
                                    <form action="{{ route('ceo.bookings.confirm', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Xác nhận booking này và sinh vé?')">
                                            <i class="fas fa-check me-1"></i> Xác nhận & sinh vé
                                        </button>
                                    </form>
                                    <form action="{{ route('ceo.bookings.updateStatus', $booking->id) }}" method="POST" class="d-inline ms-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn hủy booking này?')">
                                            <i class="fas fa-times me-1"></i> Hủy booking
                                        </button>
                                    </form>
                                @elseif($booking->status === 'confirmed')
                                    <span class="badge bg-success">Đã xác nhận</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="badge bg-danger">Đã hủy</span>
                                @endif
                            </td>
                            <td>{{ $booking->note }}</td>
                            <td>
                                {{ $booking->tickets->where('status', 'valid')->count() }} / {{ $booking->tour->capacity }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
