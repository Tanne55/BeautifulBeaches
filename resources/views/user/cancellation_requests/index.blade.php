@extends('layouts.guest')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4 text-center">Yêu cầu hủy của bạn</h2>
        @if($requests->isEmpty())
            <div class="alert alert-info text-center">Bạn chưa gửi yêu cầu hủy nào.</div>
        @else
            <div class="cancellation-grid">
                @foreach($requests as $i => $req)
                    <div class="cancellation-card" style="animation-delay: {{ $i * 0.1 }}s;">
                        <div class="card-headerr">
                            <h3 class="tour-title">
                                <span class="tour-number">{{ $i + 1 }}</span>
                                {{ $req->booking->tour->title ?? 'N/A' }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="cancellation-info">
                                <div class="info-item">
                                    <span class="info-label">Ngày đặt tour</span>
                                    <span class="info-value">
                                        <i class="fas fa-calendar"></i>
                                        {{ $req->booking->booking_date ?
                        \Carbon\Carbon::parse($req->booking->booking_date)->format('d/m/Y') : 'N/A' }}
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Trạng thái</span>
                                    @if($req->status === 'pending')
                                        <span class="status-badge status-pending">
                                            <i class="fas fa-clock"></i> Đang chờ duyệt
                                        </span>
                                    @elseif($req->status === 'approved')
                                        <span class="status-badge status-approved">
                                            <i class="fas fa-check"></i> Đã duyệt
                                        </span>
                                    @elseif($req->status === 'rejected')
                                        <span class="status-badge status-rejected">
                                            <i class="fas fa-times"></i> Bị từ chối
                                        </span>
                                    @endif
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Kiểu hủy</span>
                                    @if($req->cancellation_type === 'full')
                                        <span class="cancellation-type type-full">
                                            <i class="fas fa-ban"></i> Toàn bộ
                                        </span>
                                    @else
                                        <span class="cancellation-type type-partial">
                                            <i class="fas fa-user-minus"></i> Một phần
                                        </span>
                                    @endif
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Số lượng hủy</span>
                                    <span class="info-value">
                                        <i class="fas fa-users-slash"></i> {{ $req->cancelled_slots }} người
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Thời gian gửi</span>
                                    <span class="timestamp-info">
                                        <i class="fas fa-paper-plane"></i>
                                        {{ $req->created_at ? \Carbon\Carbon::parse($req->created_at)->format('d/m/Y H:i') : 'N/A'
                                                                            }}
                                    </span>
                                </div>
                            </div>
                            @if($req->reason)
                                <div class="reason-box">
                                    <div class="info-label">Lý do hủy</div>
                                    <p class="reason-text m-0">
                                        "{{ $req->reason }}"
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="action-bar">
            <a href="{{ route('user.history') }}" class="btn-ocean">
                <i class="fas fa-arrow-left"></i>
                Quay lại lịch sử đặt tour
            </a>
        </div>
    </div>
@endsection