@extends('layouts.auth')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">Quản lý yêu cầu hủy booking tháng {{ $viewMonth }}</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <!-- Bộ lọc tìm kiếm và chọn tháng -->
    <form class="row g-2 mb-3 align-items-end" method="GET">
        <div class="col-md-2">
            <label class="form-label mb-1">Tìm tour</label>
            <input type="text" name="tour" class="form-control" placeholder="Nhập tên tour..." value="{{ request('tour') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1">Người đặt</label>
            <input type="text" name="user" class="form-control" placeholder="Tên người đặt..." value="{{ request('user') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="">Tất cả</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang chờ duyệt</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Bị từ chối</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1">Từ ngày gửi</label>
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1">Đến ngày gửi</label>
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Lọc</button>
        </div>
    </form>
    @if($requests->isEmpty())
        <div class="alert alert-info text-center">Không có yêu cầu hủy nào.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tour</th>
                        <th>Người đặt</th>
                        <th>Ngày đặt</th>
                        <th>Kiểu hủy</th>
                        <th>Số lượng hủy</th>
                        <th>Lý do</th>
                        <th>Trạng thái</th>
                        <th>Thời gian gửi</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $i => $req)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $req->booking->tour->title ?? 'N/A' }}</td>
                            <td>{{ $req->user->name ?? 'N/A' }}</td>
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
                                    @if($req->reject_reason)
                                        <br><small class="text-muted">Lý do: {{ $req->reject_reason }}</small>
                                    @endif
                                @endif
                            </td>
                            <td>{{ $req->created_at ? \Carbon\Carbon::parse($req->created_at)->format('d/m/Y H:i') : '' }}</td>
                            <td>
                                @if($req->status === 'pending')
                                    <form action="{{ route('ceo.cancellation_requests.update', $req->id) }}" method="POST" class="d-flex flex-column gap-1">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm mb-1" required>
                                            <option value="approved">Duyệt</option>
                                            <option value="rejected">Từ chối</option>
                                        </select>
                                        <input type="text" name="reject_reason" class="form-control form-control-sm mb-1" placeholder="Lý do từ chối (nếu có)">
                                        <button type="submit" class="btn btn-primary btn-sm">Xác nhận</button>
                                    </form>
                                @else
                                    <span class="text-muted">Đã xử lý</span>
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
