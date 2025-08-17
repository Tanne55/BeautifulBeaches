@extends('layouts.auth')

@section('content')
<div class="container-fluid py-4" style="padding-left: 80px">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class=" border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 text-primary fw-bold">
                                <i class="fas fa-ban me-2"></i>
                                Quản lý Yêu cầu Hủy Booking
                            </h2>
                            <p class="text-muted mb-0">
                                Tháng {{ $viewMonth }}
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('ceo.bookings.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-1"></i> 
                                Quay lại Bookings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class=" border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-filter me-2"></i>
                        Bộ lọc tìm kiếm
                    </h6>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Tháng/Năm</label>
                            <input type="month" 
                                   name="month" 
                                   class="form-control" 
                                   value="{{ request('month', now()->format('Y-m')) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Tên Tour</label>
                            <input type="text" 
                                   name="tour" 
                                   class="form-control" 
                                   placeholder="Nhập tên tour..." 
                                   value="{{ request('tour') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Người đặt</label>
                            <input type="text" 
                                   name="user" 
                                   class="form-control" 
                                   placeholder="Tên người đặt..." 
                                   value="{{ request('user') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang chờ duyệt</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Bị từ chối</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Từ ngày</label>
                            <input type="date" 
                                   name="from" 
                                   class="form-control" 
                                   value="{{ request('from') }}">
                        </div>
                        <div class="col-md-2 d-flex flex-column justify-content-end">
                            <div class="btn-group" role="group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>
                                    Lọc
                                </button>
                                <a href="{{ route('ceo.cancellation_requests.index') }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Xóa
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content -->
    @if($requests->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class=" border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Không có yêu cầu hủy nào</h5>
                        <p class="text-muted">Chưa có yêu cầu hủy booking nào trong tháng này.</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class=" border-0 shadow-sm">
                    <div class="card-header bg-gradient-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Danh sách yêu cầu hủy booking
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Tour</th>
                                        <th>Người đặt</th>
                                        <th>Ngày đặt</th>
                                        <th width="100">Kiểu hủy</th>
                                        <th width="80">Số lượng</th>
                                        <th>Lý do</th>
                                        <th width="120">Trạng thái</th>
                                        <th width="130">Thời gian gửi</th>
                                        <th width="180">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $i => $req)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>
                                                <strong class="text-primary">{{ $req->booking->tour->title ?? 'N/A' }}</strong>
                                            </td>
                                            <td>{{ $req->user->name ?? 'N/A' }}</td>
                                            <td>
                                                @if($req->booking->booking_date)
                                                    <span class="badge bg-info">
                                                        {{ \Carbon\Carbon::parse($req->booking->booking_date)->format('d/m/Y') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($req->cancellation_type === 'full')
                                                    <span class="badge bg-danger">Toàn bộ</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Một phần</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $req->cancelled_slots }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ Str::limit($req->reason, 50) }}</small>
                                            </td>
                                            <td>
                                                @if($req->status === 'pending')
                                                    <span class="badge bg-warning text-dark">Đang chờ</span>
                                                @elseif($req->status === 'approved')
                                                    <span class="badge bg-success">Đã duyệt</span>
                                                @elseif($req->status === 'rejected')
                                                    <span class="badge bg-danger">Từ chối</span>
                                                @endif
                                                @if($req->status === 'rejected' && $req->reject_reason)
                                                    <br><small class="text-muted">{{ Str::limit($req->reject_reason, 30) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $req->created_at ? \Carbon\Carbon::parse($req->created_at)->format('d/m/Y') : '' }}
                                                    <br>
                                                    {{ $req->created_at ? \Carbon\Carbon::parse($req->created_at)->format('H:i') : '' }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($req->status === 'pending')
                                                    <form action="{{ route('ceo.cancellation_requests.update', $req->id) }}" method="POST" class="d-flex flex-column gap-1">
                                                        @csrf
                                                        <select name="status" class="form-select form-select-sm mb-1" required>
                                                            <option value="">-- Chọn --</option>
                                                            <option value="approved">Duyệt</option>
                                                            <option value="rejected">Từ chối</option>
                                                        </select>
                                                        <input type="text" 
                                                               name="reject_reason" 
                                                               class="form-control form-control-sm mb-1" 
                                                               placeholder="Lý do từ chối (nếu có)">
                                                        <button type="submit" 
                                                                class="btn btn-primary btn-sm"
                                                                onclick="return confirm('Xác nhận xử lý yêu cầu này?')">
                                                            <i class="fas fa-check me-1"></i>
                                                            Xử lý
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-success">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        Đã xử lý
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
}

.badge {
    font-size: 0.75em;
}

.btn-group .btn {
    border-radius: 0.375rem;
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endsection
