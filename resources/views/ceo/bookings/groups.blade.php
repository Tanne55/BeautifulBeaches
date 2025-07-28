@extends('layouts.auth')

@section('content')
<div class="container my-5 container-custom">
    <h2 class="mb-4 text-center">Quản lý nhóm bookings tháng {{ request('month', now()->format('Y-m')) }}</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Bộ lọc tìm kiếm và chọn tháng -->
    <form class="row g-2 mb-3 align-items-end" method="GET">
        <div class="col-md-3">
            <label class="form-label mb-1">Chọn tháng</label>
            <input type="month" name="month" class="form-control" value="{{ request('month', now()->format('Y-m')) }}">
        </div>
        <div class="col-md-3">
            <label class="form-label mb-1">Tìm tour</label>
            <input type="text" name="tour" class="form-control" placeholder="Nhập tên tour..." value="{{ request('tour') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label mb-1">Người đặt</label>
            <input type="text" name="user" class="form-control" placeholder="Tên người đặt..." value="{{ request('user') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Lọc</button>
        </div>
    </form>
    @if($bookingGroups->isEmpty())
        <div class="alert alert-info text-center">Chưa có nhóm booking nào.</div>
    @else
        <div class="row">
            @foreach($bookingGroups as $group)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <strong>{{ $group->tour->title }}</strong>
                                <span class="badge bg-info ms-2">{{ $group->group_code }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Tổng người:</strong> {{ $group->total_people }}
                                </div>
                                <div class="col-6">
                                    <strong>Số bookings:</strong> {{ count($group->booking_ids) }}
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Vé đã sinh:</strong> {{ $group->total_tickets }}
                                </div>
                                <div class="col-6">
                                    <strong>Trạng thái:</strong>
                                    @if($group->total_tickets > 0)
                                        <span class="badge bg-success">Đã sinh vé</span>
                                    @else
                                        <span class="badge bg-warning">Chưa sinh vé</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Danh sách bookings trong nhóm -->
                            <div class="mb-3">
                                <h6>Danh sách bookings:</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Người đặt</th>
                                                <th>Số người</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // Sử dụng phương thức an toàn để lấy danh sách bookings
                                                $bookings = $group->getBookingsAttribute();
                                            @endphp
                                            @forelse($bookings as $booking)
                                                <tr>
                                                    <td>{{ $booking->full_name }}</td>
                                                    <td>{{ $booking->number_of_people }}</td>
                                                    <td>{{ number_format($booking->total_amount ?? 0, 0, ',', '.') }} đ</td>
                                                    <td>
                                                        @if($booking->status === 'grouped')
                                                            <span class="badge bg-info">Đã nhóm</span>
                                                        @elseif($booking->status === 'confirmed')
                                                            <span class="badge bg-success">Đã xác nhận</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">Chưa có booking nào trong nhóm</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Tổng tiền nhóm -->
                            <div class="mb-2">
                                <strong>Tổng tiền nhóm:</strong> {{ number_format($group->getTotalAmountAttribute(), 0, ',', '.') }} đ
                            </div>

                            <!-- Actions -->
                            <div class="d-grid gap-2">
                                @if($group->total_tickets == 0)
                                    <form action="{{ route('ceo.bookings.generateTicketsForGroup', $group->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Sinh vé cho tất cả bookings trong nhóm này?')">
                                            <i class="fas fa-ticket-alt me-1"></i> Sinh vé ({{ $group->total_people }} vé)
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-check me-1"></i> Đã sinh vé
                                    </button>
                                @endif
                                
                                <a href="{{ route('ceo.bookings.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách bookings
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection 