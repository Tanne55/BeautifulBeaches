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
                        <th>Số vé đã đặt / Sức chứa</th>
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
                                <form action="{{ route('ceo.bookings.updateStatus', $booking->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                                        <option value="pending" @if($booking->status==='pending') selected @endif>Chờ xác nhận</option>
                                        <option value="confirmed" @if($booking->status==='confirmed') selected @endif>Đã xác nhận</option>
                                        <option value="cancelled" @if($booking->status==='cancelled') selected @endif>Đã hủy</option>
                                    </select>
                                </form>
                            </td>
                            <td>{{ $booking->note }}</td>
                            <td>
                                {{ $booking->tour->bookings->count() ?? 0 }} / {{ $booking->tour->capacity ?? '?' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
