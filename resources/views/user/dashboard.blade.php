@extends('layouts.guest')

@section('content')
    <div class="min-vh-100" style="background: linear-gradient(135deg, #ffffff 0%, #c2e9fb 100%);">
        <div class="container py-5">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-black-75 mb-3">
                    <i class="fas fa-tachometer-alt me-3"></i>Dashboard Quản trị
                </h1>
                <p class="lead text-black-50">Quản lý hệ thống một cách hiệu quả và chuyên nghiệp</p>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
                     style="border-radius: 10px; background-color: rgba(25, 135, 84, 0.15); border-color: rgba(25, 135, 84, 0.4); color: #155724;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(auth()->user() && auth()->user()->isUser())
                <!-- User Profile Card -->
                <div class="row mb-5">
                    <div class="col-lg-12">
                        <div class=" border-0 shadow-lg"
                            style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                            <div class="card-body p-4">
                                <div class="row">
                                    <!-- Avatar Column -->
                                    <div class="col-md-3 text-center mb-4 mb-md-0">
                                        <div class="avatar-container mb-3">
                                            @if($user->avatar)
                                                @php
                                                    // Check if avatar path contains "avatars/" which means it was uploaded by the user
                                                    // Otherwise it's a seeded image, so use the direct path
                                                    $avatarPath = strpos($user->avatar, 'avatars/') !== false ? 
                                                        asset('storage/' . $user->avatar) : 
                                                        asset($user->avatar);
                                                @endphp
                                                <img src="{{ $avatarPath }}" alt="User Avatar" 
                                                    class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #4facfe;">
                                            @else
                                                <div class="default-avatar rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 150px; height: 150px; background: linear-gradient(45deg, #4facfe, #00f2fe); margin: 0 auto;">
                                                    <span class="text-white display-4">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <h5 class="fw-bold">{{ $user->name }}</h5>
                                        <span class="badge" 
                                            style="background: {{ $user->role === 'admin' ? '#764ba2' : ($user->role === 'ceo' ? '#f5576c' : '#4facfe') }};">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                    
                                    <!-- User Info Column -->
                                    <div class="col-md-9">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h4 class="fw-bold mb-0">Thông tin cá nhân</h4>
                                            <a href="{{ route('user.profile.edit') }}" class="btn btn-sm text-white fw-semibold"
                                                style="background: linear-gradient(45deg, #667eea, #764ba2); border: none; border-radius: 10px; padding: 8px 16px; transition: all 0.3s ease;"
                                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102,126,234,0.4)'"
                                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                                <i class="fas fa-user-edit me-2"></i>Chỉnh sửa thông tin
                                            </a>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="info-item mb-3">
                                                    <label class="text-muted mb-1"><i class="fas fa-envelope me-2"></i>Email:</label>
                                                    <p class="mb-0 fw-medium">{{ $user->email }}</p>
                                                </div>
                                                
                                                <div class="info-item mb-3">
                                                    <label class="text-muted mb-1"><i class="fas fa-phone me-2"></i>Số điện thoại:</label>
                                                    <p class="mb-0 fw-medium">{{ $user->phone ?? 'Chưa cập nhật' }}</p>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="info-item mb-3">
                                                    <label class="text-muted mb-1"><i class="fas fa-calendar me-2"></i>Ngày sinh:</label>
                                                    <p class="mb-0 fw-medium">
                                                        {{ $profile && $profile->dob ? date('d/m/Y', strtotime($profile->dob)) : 'Chưa cập nhật' }}
                                                    </p>
                                                </div>
                                                
                                                <div class="info-item mb-3">
                                                    <label class="text-muted mb-1"><i class="fas fa-calendar-check me-2"></i>Tham gia:</label>
                                                    <p class="mb-0 fw-medium">{{ $user->created_at->format('d/m/Y') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="info-item mb-3">
                                            <label class="text-muted mb-1"><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ:</label>
                                            <p class="mb-0 fw-medium">{{ $user->address ?? 'Chưa cập nhật' }}</p>
                                        </div>
                                        
                                        <div class="info-item mb-3">
                                            <label class="text-muted mb-1"><i class="fas fa-flag me-2"></i>Quốc tịch:</label>
                                            <p class="mb-0 fw-medium">{{ $profile->nationality ?? 'Chưa cập nhật' }}</p>
                                        </div>

                                        @if($profile && $profile->preferences)
                                        <div class="info-item mt-4">
                                            <label class="text-muted mb-1"><i class="fas fa-cog me-2"></i>Tùy chọn:</label>
                                            <p class="mb-0 fw-medium">
                                                @if(is_array($profile->preferences))
                                                    @if(isset($profile->preferences['favorite_beaches']))
                                                        <span class="badge bg-info me-1">Bãi biển yêu thích: {{ implode(', ', $profile->preferences['favorite_beaches']) }}</span>
                                                    @endif
                                                    <!-- Theme display temporarily removed -->
                                                @endif
                                            </p>
                                        </div>
                                        @endif
                                        
                                        <div class="mt-4 pt-3 border-top">
                                            <div class="d-flex align-items-center">
                                                <div class="me-4">
                                                    <span class="d-block text-muted small">Tour đã đặt</span>
                                                    <h5 class="mb-0 fw-bold">{{ $bookingCount }}</h5>
                                                </div>
                                                <a href="{{ route('user.history') }}" class="btn btn-sm text-white"
                                                    style="background: linear-gradient(45deg, #4facfe, #00f2fe); border: none; border-radius: 10px;">
                                                    <i class="fas fa-history me-2"></i>Xem lịch sử
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Cards -->
                <div class="row g-4">
                    <div class="col-lg-5 col-md-6 mx-auto">
                        <div class="card border-0 shadow-lg h-100 management-card"
                            style="transition: all 0.3s ease; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-wrapper me-3"
                                        style="width: 60px; height: 60px; background: linear-gradient(45deg, #4facfe, #00f2fe); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-calendar-plus fa-lg text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1 fw-bold text-dark">Đặt tour</h5>
                                        <small class="text-muted">Đặt tour du lịch mới</small>
                                    </div>
                                </div>
                                <p class="card-text text-muted mb-4">Đặt tour du lịch đến các bãi biển nổi tiếng, trải nghiệm
                                    dịch vụ tốt nhất.</p>
                                <a href="{{route('tour')}}" class="btn w-100 text-white fw-semibold"
                                    style="background: linear-gradient(45deg, #4facfe, #00f2fe); border: none; padding: 12px; border-radius: 10px; transition: all 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(79,172,254,0.4)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-arrow-right me-2"></i>Đặt tour
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-6">
                        <div class="card border-0 shadow-lg h-100 management-card"
                            style="transition: all 0.3s ease; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-wrapper me-3"
                                        style="width: 60px; height: 60px; background: linear-gradient(45deg, #43e97b, #38f9d7); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-history fa-lg text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1 fw-bold text-dark">Lịch sử đặt tour</h5>
                                        <small class="text-muted">Xem lịch sử đặt tour</small>
                                    </div>
                                </div>
                                <p class="card-text text-muted mb-4">Xem lại các tour đã đặt, trạng thái và chi tiết từng chuyến
                                    đi.</p>
                                <a href="{{ route('user.history') }}" class="btn w-100 text-white fw-semibold"
                                    style="background: linear-gradient(45deg, #43e97b, #38f9d7); border: none; padding: 12px; border-radius: 10px; transition: all 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(67,233,123,0.4)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-arrow-right me-2"></i>Xem lịch sử
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-lg"
                            style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                            <div class="card-body text-center p-5">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <h4 class="fw-bold text-dark mb-3">Không có quyền truy cập</h4>
                                <p class="text-muted mb-4">Bạn không có quyền truy cập dashboard quản trị. Vui lòng liên hệ quản
                                    trị viên để được hỗ trợ.</p>
                                <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">
                                    <i class="fas fa-home me-2"></i>Quay về trang chủ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection