@extends('layouts.auth')

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

            @if(auth()->user() && auth()->user()->isUser())
                <!-- Stats Cards -->
                <div class="row g-4 mb-5">
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-0 shadow-lg h-100"
                            style="background: linear-gradient(45deg, #667eea, #764ba2); transform: translateY(0); transition: all 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-10px)'"
                            onmouseout="this.style.transform='translateY(0)'">
                            <div class="card-body text-white text-center p-4">
                                <div class="mb-3">
                                    <i class="fas fa-users fa-3x opacity-75"></i>
                                </div>
                                <h2 class="display-6 fw-bold mb-1">1,234</h2>
                                <p class="mb-0 opacity-75">Tổng người dùng</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-0 shadow-lg h-100"
                            style="background: linear-gradient(45deg, #f093fb, #f5576c); transform: translateY(0); transition: all 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-10px)'"
                            onmouseout="this.style.transform='translateY(0)'">
                            <div class="card-body text-white text-center p-4">
                                <div class="mb-3">
                                    <i class="fas fa-umbrella-beach fa-3x opacity-75"></i>
                                </div>
                                <h2 class="display-6 fw-bold mb-1">56</h2>
                                <p class="mb-0 opacity-75">Bãi biển</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-0 shadow-lg h-100"
                            style="background: linear-gradient(45deg, #4facfe, #00f2fe); transform: translateY(0); transition: all 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-10px)'"
                            onmouseout="this.style.transform='translateY(0)'">
                            <div class="card-body text-white text-center p-4">
                                <div class="mb-3">
                                    <i class="fas fa-calendar-check fa-3x opacity-75"></i>
                                </div>
                                <h2 class="display-6 fw-bold mb-1">789</h2>
                                <p class="mb-0 opacity-75">Đặt chỗ</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-0 shadow-lg h-100"
                            style="background: linear-gradient(45deg, #43e97b, #38f9d7); transform: translateY(0); transition: all 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-10px)'"
                            onmouseout="this.style.transform='translateY(0)'">
                            <div class="card-body text-white text-center p-4">
                                <div class="mb-3">
                                    <i class="fas fa-dollar-sign fa-3x opacity-75"></i>
                                </div>
                                <h2 class="display-6 fw-bold mb-1">₫25M</h2>
                                <p class="mb-0 opacity-75">Doanh thu</p>
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
                                <p class="card-text text-muted mb-4">Đặt tour du lịch đến các bãi biển nổi tiếng, trải nghiệm dịch vụ tốt nhất.</p>
                                <a href="#" class="btn w-100 text-white fw-semibold"
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
                                <p class="card-text text-muted mb-4">Xem lại các tour đã đặt, trạng thái và chi tiết từng chuyến đi.</p>
                                <a href="#" class="btn w-100 text-white fw-semibold"
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