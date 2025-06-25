<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container d-flex align-items-center justify-content-between">
        <!-- Phần 1: Logo + brand + toggler -->
        <div class="d-flex align-items-center">
            <img src="/assets/img1/logo_beach.jpg" alt="Logo" width="50" class="botron me-2" />
            <a class="navbar-brand fw-bold custom-hover me-3" href="#">Beautiful Beaches</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <!-- Phần 2: Menu, đẩy ra giữa container -->
        <div class="collapse navbar-collapse mx-auto" id="navbarNav" style="flex: 0 1 auto;">
            <ul class="navbar-nav d-flex align-items-center gap-3 justify-content-center">
                <li class="nav-item">
                    <a class="nav-link fw-bold nav-link-hover" href="{{ route('home') }}">Trang chủ</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-between fw-bold"
                        href="{{ route('explore') }}" id="navDropdown" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Bãi biển
                        <i class="bi bi-caret-down-fill ms-1 dropdown-icon"></i>
                    </a>
                    <ul class="dropdown-menu border-top-red" aria-labelledby="navDropdown">
                        <li><a class="dropdown-item" href="{{ route('explore') }}?region=Northern%20Vietnam">Miền
                                Bắc</a></li>
                        <li><a class="dropdown-item"
                                href="{{ route('explore') }}?region={{ urlencode('Central Vietnam') }}">Miền
                                Trung</a></li>
                        <li><a class="dropdown-item" href="{{ route('explore') }}?region=Southern%20Vietnam">Miền
                                Nam</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold nav-link-hover" href="{{route('tour')}}">Tour du lịch </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold nav-link-hover" href="{{ route('gallery') }}">Thư viện ảnh </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-between fw-bold"
                        href="#" id="contactDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Liên hệ
                        <i class="bi bi-caret-down-fill ms-1 dropdown-icon"></i>
                    </a>
                    <ul class="dropdown-menu border-top-red" aria-labelledby="contactDropdown">
                        <li><a class="dropdown-item" href="{{ route('contact') }}">Gửi liên hệ</a></li>
                        <li><a class="dropdown-item" href="{{ route('queries') }}">Gửi thắc mắc</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold nav-link-hover" href="{{ route('about') }}">Về chúng tôi</a>
                </li>
            </ul>
        </div>

        <!-- Phần 3: Đăng nhập/Đăng ký hoặc Dashboard/Logout -->
        <div>
            <a href="{{ route('login') }}" class="btn btn-outline-danger fw-bold me-2">Đăng nhập</a>
            <a href="{{ route('register') }}" class="btn btn-danger fw-bold">Đăng ký</a>
        </div>
    </div>
</nav>