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

            @if(auth()->user() && auth()->user()->isAdmin())
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
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-lg h-100 management-card"
                            style="transition: all 0.3s ease; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-wrapper me-3"
                                        style="width: 60px; height: 60px; background: linear-gradient(45deg, #667eea, #764ba2); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-umbrella-beach fa-lg text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1 fw-bold text-dark">Quản lý bãi biển</h5>
                                        <small class="text-muted">Thêm, sửa, xóa bãi biển</small>
                                    </div>
                                </div>
                                <p class="card-text text-muted mb-4">Quản lý thông tin chi tiết về các bãi biển, cập nhật trạng
                                    thái và thông tin du lịch.</p>
                                <a href="{{ route('admin.beaches.index') }}" class="btn w-100 text-white fw-semibold"
                                    style="background: linear-gradient(45deg, #667eea, #764ba2); border: none; padding: 12px; border-radius: 10px; transition: all 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102,126,234,0.4)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-arrow-right me-2"></i>Quản lý bãi biển
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
                                        style="width: 60px; height: 60px; background: linear-gradient(45deg, #f093fb, #f5576c); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-users fa-lg text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1 fw-bold text-dark">Quản lý người dùng</h5>
                                        <small class="text-muted">Quản lý tài khoản người dùng</small>
                                    </div>
                                </div>
                                <p class="card-text text-muted mb-4">Quản lý thông tin người dùng, phân quyền và theo dõi hoạt
                                    động của hệ thống.</p>
                                <a href="{{ route('admin.users.index') }}" class="btn w-100 text-white fw-semibold"
                                    style="background: linear-gradient(45deg, #f093fb, #f5576c); border: none; padding: 12px; border-radius: 10px; transition: all 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(240,147,251,0.4)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-arrow-right me-2"></i>Quản lý người dùng
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
                                        <h5 class="card-title mb-1 fw-bold text-dark">Cài đặt hệ thống</h5>
                                        <small class="text-muted">Cấu hình và thiết lập</small>
                                    </div>
                                </div>
                                <p class="card-text text-muted mb-4">Cấu hình các thiết lập chung của hệ thống, email template
                                    và các tùy chọn khác.</p>
                                <a href="#" class="btn w-100 text-white fw-semibold"
                                    style="background: linear-gradient(45deg, #43e97b, #38f9d7); border: none; padding: 12px; border-radius: 10px; transition: all 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(67,233,123,0.4)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-arrow-right me-2"></i>Cài đặt hệ thống
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
                                        style="width: 60px; height: 60px; background: linear-gradient(45deg, #ff9a9e, #fecfef); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-headset fa-lg text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1 fw-bold text-dark">Quản lý hỗ trợ</h5>
                                        <small class="text-muted">Xử lý yêu cầu hỗ trợ</small>
                                    </div>
                                </div>
                                <p class="card-text text-muted mb-4">Quản lý các yêu cầu hỗ trợ từ khách hàng, phản hồi và giải quyết vấn đề một cách hiệu quả.</p>
                                <a href="{{ route('admin.support.index') }}" class="btn w-100 text-white fw-semibold"
                                    style="background: linear-gradient(45deg, #ff9a9e, #fecfef); border: none; padding: 12px; border-radius: 10px; transition: all 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(255,154,158,0.4)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-arrow-right me-2"></i>Quản lý hỗ trợ
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
                                        style="width: 60px; height: 60px; background: linear-gradient(45deg, #a8edea, #fed6e3); border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-bell fa-lg text-white"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1 fw-bold text-dark">Thông báo</h5>
                                        <small class="text-muted">Quản lý thông báo hệ thống</small>
                                    </div>
                                </div>
                                <p class="card-text text-muted mb-4">Gửi thông báo đến người dùng và quản lý các thông báo quan
                                    trọng của hệ thống.</p>
                                <a href="#" class="btn w-100 text-white fw-semibold"
                                    style="background: linear-gradient(45deg, #a8edea, #fed6e3); border: none; padding: 12px; border-radius: 10px; transition: all 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(168,237,234,0.4)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-arrow-right me-2"></i>Quản lý thông báo
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