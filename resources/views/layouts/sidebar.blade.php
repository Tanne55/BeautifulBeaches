<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <div class="logo-icon overflow-hidden">
                <img src="/assets/img1/logo_beach.jpg" alt="" class="w-100 h-100 ">
            </div>
            <span class="logo-text fw-bold">Beautiful Beaches</span>
        </div>
    </div>

    <div class="nav-container">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="/" class="nav-link">
                    <div class="nav-icon"><i class="bi bi-house"></i></div>
                    <span class="nav-text">Trang chủ</span>
                </a>
            </li>
            <li class="nav-item">
                @auth
                    @php
                        $dashboardRoute = '';
                        if(auth()->user()->isAdmin()) {
                            $dashboardRoute = route('admin.dashboard');
                        } elseif(auth()->user()->isCEO()) {
                            $dashboardRoute = route('ceo.dashboard');
                        } elseif(auth()->user()->isUser()) {
                            $dashboardRoute = route('user.dashboard');
                        }
                    @endphp
                    <a href="{{ $dashboardRoute }}" class="nav-link">
                        <div class="nav-icon"><i class="bi bi-person"></i></div>
                        <span class="nav-text">Bảng điều hành</span>
                        <div class="dropdown-arrow"><i class="bi bi-chevron-down"></i></div>
                    </a>
                @else
                    <a href="/login" class="nav-link">
                        <div class="nav-icon"><i class="bi bi-person"></i></div>
                        <span class="nav-text">Bảng điều hành</span>
                        <div class="dropdown-arrow"><i class="bi bi-chevron-down"></i></div>
                    </a>
                @endauth
                <div class="collapse">
                    <div class="px-2 py-1">
                        <a href="/admin/beaches" class="dropdown-item">Dự án mới</a> 
                        <a href="#" class="dropdown-item">Dự án đang thực hiện</a>
                        <a href="#" class="dropdown-item">Dự án đã hoàn thành</a>
                        <a href="#" class="dropdown-item">Dự án đã hủy</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <div class="nav-icon"><i class="bi bi-folder"></i></div>
                    <span class="nav-text">Hồ sơ</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <div class="nav-icon"><i class="bi bi-bar-chart"></i></div>
                    <span class="nav-text">Thống kê</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <div class="nav-icon"><i class="bi bi-envelope"></i></div>
                    <span class="nav-text">Tin nhắn</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <div class="nav-icon"><i class="bi bi-gear"></i></div>
                    <span class="nav-text">Cài đặt</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <div class="nav-icon"><i class="bi bi-question-circle"></i></div>
                    <span class="nav-text">Trợ giúp</span>
                </a>
            </li>
        </ul>

        <div class="logout-section">

            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <a href="/register" class="logout-link" id="logoutBtn" onclick="event.preventDefault(); this.closest('form').submit();">
                    <div class="logout-icon"><i class="bi bi-box-arrow-right"></i></div>
                    <span class="logout-text">Đăng xuất</span>
                </a>
            </form>
        </div>
    </div>
</div>