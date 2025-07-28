@extends('layouts.guest')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">Yêu cầu hủy của bạn</h2>
    @if($requests->isEmpty())
        <div class="alert alert-info text-center">Bạn chưa gửi yêu cầu hủy nào.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tour</th>
                        <th>Ngày đặt</th>
                        <th>Kiểu hủy</th>
                        <th>Số lượng hủy</th>
                        <th>Lý do</th>
                        <th>Trạng thái</th>
                        <th>Thời gian gửi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $i => $req)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $req->booking->tour->title ?? 'N/A' }}</td>
                            <td>{{ $req->booking->booking_date ? \Carbon\Carbon::parse($req->booking->booking_date)->format('d/m/Y') : '' }}</td>
                            <td>{{ $req->cancellation_type === 'full' ? 'Toàn bộ' : 'Một phần' }}</td>
                            <td>{{ $req->cancelled_slots }}</td>
                            <td>{{ $req->reason }}</td>
                            <td>
                                @if($req->status === 'pending')
                                    <span class="badge bg-warning text-dark">Đang chờ duyệt</span>
                                @elseif($req->status === 'approved')
                                    <span class="badge bg-success">Đã duyệt</span>
                                @elseif($req->status === 'rejected')
                                    <span class="badge bg-danger">Bị từ chối</span>
                                @endif
                            </td>
                            <td>{{ $req->created_at ? \Carbon\Carbon::parse($req->created_at)->format('d/m/Y H:i') : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    <a href="{{ route('user.history') }}" class="btn btn-link mt-3">Quay lại lịch sử đặt tour</a>
</div>
@endsection
