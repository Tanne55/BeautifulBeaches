@extends('layouts.auth')

@section('content')
    <div class="min-vh-100" style="background: linear-gradient(135deg, #ffffff 0%, #c2e9fb 100%);">
        <div class="container py-5 container-custom">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-black-75 mb-3">
                    <i class="fas fa-tachometer-alt me-3"></i>Dashboard Quản trị
                </h1>
                <p class="lead text-black-50">Quản lý hệ thống một cách hiệu quả và chuyên nghiệp</p>
            </div>

            @if(auth()->user() && auth()->user()->isCeo())
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
                                                <img src="{{ asset($user->avatar) }}" alt="CEO Avatar" 
                                                    class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #f5576c;">
                                            @else
                                                <div class="default-avatar rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 150px; height: 150px; background: linear-gradient(45deg, #f093fb, #f5576c); margin: 0 auto;">
                                                    <span class="text-white display-4">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <h5 class="fw-bold">{{ $user->name }}</h5>
                                        <span class="badge" 
                                            style="background: #f5576c;">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                    
                                    <!-- CEO Info Column -->
                                    <div class="col-md-9">
                                        <h4 class="fw-bold mb-4">Thông tin cá nhân</h4>
                                        
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
                                                    @if(isset($profile->preferences['theme']))
                                                        <span class="badge bg-secondary me-1">Theme: {{ $profile->preferences['theme'] }}</span>
                                                    @endif
                                                    @if(isset($profile->preferences['dashboard_widgets']))
                                                        <span class="badge bg-info me-1">Widgets: {{ count($profile->preferences['dashboard_widgets']) }}</span>
                                                    @endif
                                                @endif
                                            </p>
                                        </div>
                                        @endif
                                        
                                        <div class="mt-4 pt-3 border-top">
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('ceo.bookings.index') }}" class="btn btn-sm text-white ms-auto"
                                                    style="background: linear-gradient(45deg, #f093fb, #f5576c); border: none; border-radius: 10px;">
                                                    <i class="fas fa-calendar-check me-2"></i>Quản lý booking
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
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-lg h-100 management-card"
                            style="transition: all 0.3s ease; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-wrapper me-3"
                                        style="width: 60px; height: 60px; background: linear-gradient(45deg, #4facfe, #00f2fe); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-calendar-alt fa-lg text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1 fw-bold text-dark">Đơn đặt tour</h5>
                                        <small class="text-muted">Theo dõi và xử lý đặt chỗ tour</small>
                                    </div>
                                </div>
                                <p class="card-text text-muted mb-4">Quản lý yêu cầu đặt tour, xác nhận và theo dõi
                                    trạng thái đặt chỗ của khách hàng.</p>
                                <a href="{{route('ceo.bookings.index')}}" class="btn w-100 text-white fw-semibold"
                                    style="background: linear-gradient(45deg, #4facfe, #00f2fe); border: none; padding: 12px; border-radius: 10px; transition: all 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(79,172,254,0.4)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-arrow-right me-2"></i>Đơn đặt tour
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-lg h-100 management-card"
                            style="transition: all 0.3s ease; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-wrapper me-3"
                                        style="width: 60px; height: 60px; background: linear-gradient(45deg, #667eea, #764ba2); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-route fa-lg text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1 fw-bold text-dark">Quản lý tour</h5>
                                        <small class="text-muted">Thêm, sửa, xóa tour</small>
                                    </div>
                                </div>
                                <p class="card-text text-muted mb-4">Quản lý thông tin chi tiết về các tour, cập nhật trạng thái
                                    và thông tin tour du lịch.</p>
                                <a href="{{ route('ceo.tours.index') }}" class="btn w-100 text-white fw-semibold"
                                    style="background: linear-gradient(45deg, #667eea, #764ba2); border: none; padding: 12px; border-radius: 10px; transition: all 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102,126,234,0.4)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-arrow-right me-2"></i>Quản lý tour
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-lg h-100 management-card"
                            style="transition: all 0.3s ease; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-wrapper me-3"
                                        style="width: 60px; height: 60px; background: linear-gradient(45deg, #43e97b, #38f9d7); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-chart-bar fa-lg text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1 fw-bold text-dark">Thống kê báo cáo</h5>
                                        <small class="text-muted">Xem báo cáo và thống kê</small>
                                    </div>
                                </div>
                                <p class="card-text text-muted mb-4">Xem các báo cáo chi tiết về doanh thu, lượt truy cập và
                                    hiệu suất hệ thống.</p>
                                <a href="{{ route('ceo.reports.index') }}" class="btn w-100 text-white fw-semibold"
                                    style="background: linear-gradient(45deg, #43e97b, #38f9d7); border: none; padding: 12px; border-radius: 10px; transition: all 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(67,233,123,0.4)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-arrow-right me-2"></i>Xem báo cáo
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 ">
                        <div class="card border-0 shadow-lg h-100 management-card"
                            style="transition: all 0.3s ease; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-wrapper me-3"
                                        style="width: 60px; height: 60px; background: linear-gradient(45deg, #ffecd2, #fcb69f); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-ticket-alt fa-lg text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1 fw-bold text-dark">Quản lý vé</h5>
                                        <small class="text-muted">Quản lý vé tour du lịch</small>
                                    </div>
                                </div>
                                <p class="card-text text-muted mb-4">Quản lý vé tour, kiểm tra trạng thái và xử lý các vấn đề
                                    liên quan đến vé của khách hàng.</p>
                                <a href="{{ route('ceo.tickets.index') }}" class="btn w-100 text-white fw-semibold"
                                    style="background: linear-gradient(45deg, #ffecd2, #fcb69f); border: none; padding: 12px; border-radius: 10px; transition: all 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(255,236,210,0.4)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-arrow-right me-2"></i>Quản lý vé
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