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
                        if (auth()->user()->isAdmin()) {
                            $dashboardRoute = route('admin.dashboard');
                        } elseif (auth()->user()->isCEO()) {
                            $dashboardRoute = route('ceo.dashboard');
                        } elseif (auth()->user()->isUser()) {
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
                @auth
                            @if(auth()->user()->isAdmin())
                                        {{-- sidebar cho admin --}}
                                        <div class="collapse">
                                            <div class="px-2 py-1">
                                                <a href="{{ route('admin.beaches.index') }}" class="dropdown-item">Quản lý bãi biển</a>
                                                <a href="{{ route('admin.users.index') }}" class="dropdown-item">Quản lý người dùng</a>
                                                <a href="#" class="dropdown-item">Quản lý thông báo</a>
                                                <a href="#" class="dropdown-item">Cài đặt hệ thống</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('explore') }}" class="nav-link">
                                            <div class="nav-icon"><i class="bi bi-compass"></i></div>
                                            <span class="nav-text">Khám phá bãi biển</span>
                                            <div class="dropdown-arrow"><i class="bi bi-chevron-down"></i></div>
                                        </a>
                                        <div class="collapse">
                                            <div class="px-2 py-1">
                                                <a href="{{ route('explore') }}?region=Northern%20Vietnam" class="dropdown-item">Miền Bắc</a>
                                                <a href="{{ route('explore') }}?region={{ urlencode('Central Vietnam') }}"
                                                    class="dropdown-item">Miền Trung</a>
                                                <a href="{{ route('explore') }}?region=Southern%20Vietnam" class="dropdown-item">Miền Nam</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('tour')}}" class="nav-link">
                                            <div class="nav-icon"><i class="bi bi-luggage"></i></div>
                                            <span class="nav-text">Tour du lịch</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <div class="nav-icon"><i class="bi bi-envelope"></i></div>
                                            <span class="nav-text">Đánh giá và bình luận</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" class="nav-link">
                                            <div class="nav-icon"><i class="bi bi-headset"></i></div>
                                            <span class="nav-text">Hỗ trợ</span>
                                            <div class="dropdown-arrow"><i class="bi bi-chevron-down"></i></div>
                                        </a>
                                        <div class="collapse">
                                            <div class="px-2 py-1">
                                                <a href="{{ route('contact') }}" class="dropdown-item">Liên hệ với chúng tôi</a>
                                                <a href="{{ route('queries') }}" class="dropdown-item">Gửi thắc mắc</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('about') }}" class="nav-link">
                                            <div class="nav-icon"><i class="bi bi-people"></i></div>
                                            <span class="nav-text">Về chúng tôi</span>
                                        </a>
                                    </li>
                                </ul>

                            @elseif(auth()->user()->isCEO())
                        {{-- sidebar cho CEO --}}
                        <div class="collapse">
                            <div class="px-2 py-1">
                                <a href="#" class="dropdown-item">Đơn đặt tour</a>
                                <a href="{{ route('ceo.tours.index') }}" class="dropdown-item">Quản lý tour</a>
                                <a href="#" class="dropdown-item">Thống kê báo cáo</a>
                                <a href="#" class="dropdown-item">Quản lý thông báo</a>
                            </div>
                        </div>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('explore') }}" class="nav-link">
                                <div class="nav-icon"><i class="bi bi-compass"></i></div>
                                <span class="nav-text">Khám phá bãi biển</span>
                                <div class="dropdown-arrow"><i class="bi bi-chevron-down"></i></div>
                            </a>
                            <div class="collapse">
                                <div class="px-2 py-1">
                                    <a href="{{ route('explore') }}?region=Northern%20Vietnam" class="dropdown-item">Miền Bắc</a>
                                    <a href="{{ route('explore') }}?region={{ urlencode('Central Vietnam') }}"
                                        class="dropdown-item">Miền Trung</a>
                                    <a href="{{ route('explore') }}?region=Southern%20Vietnam" class="dropdown-item">Miền Nam</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('tour')}}" class="nav-link">
                                <div class="nav-icon"><i class="bi bi-luggage"></i></div>
                                <span class="nav-text">Tour du lịch</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <div class="nav-icon"><i class="bi bi-envelope"></i></div>
                                <span class="nav-text">Đánh giá và bình luận</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <div class="nav-icon"><i class="bi bi-headset"></i></div>
                                <span class="nav-text">Hỗ trợ</span>
                                <div class="dropdown-arrow"><i class="bi bi-chevron-down"></i></div>
                            </a>
                            <div class="collapse">
                                <div class="px-2 py-1">
                                    <a href="{{ route('contact') }}" class="dropdown-item">Liên hệ với chúng tôi</a>
                                    <a href="{{ route('queries') }}" class="dropdown-item">Gửi thắc mắc</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('about') }}" class="nav-link">
                                <div class="nav-icon"><i class="bi bi-people"></i></div>
                                <span class="nav-text">Về chúng tôi</span>
                            </a>
                        </li>
                        </ul>


                        {{-- sidebar cho user --}}
                        <div class="collapse">
                            <div class="px-2 py-1">
                                <a href="#" class="dropdown-item">Đặt tour du lịch</a>
                                <a href="#" class="dropdown-item">Lịch sử đặt tour</a>
                            </div>
                        </div>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('explore') }}" class="nav-link">
                                <div class="nav-icon"><i class="bi bi-compass"></i></div>
                                <span class="nav-text">Khám phá bãi biển</span>
                                <div class="dropdown-arrow"><i class="bi bi-chevron-down"></i></div>
                            </a>
                            <div class="collapse">
                                <div class="px-2 py-1">
                                    <a href="{{ route('explore') }}?region=Northern%20Vietnam" class="dropdown-item">Miền Bắc</a>
                                    <a href="{{ route('explore') }}?region={{ urlencode('Central Vietnam') }}"
                                        class="dropdown-item">Miền Trung</a>
                                    <a href="{{ route('explore') }}?region=Southern%20Vietnam" class="dropdown-item">Miền Nam</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('tour')}}" class="nav-link">
                                <div class="nav-icon"><i class="bi bi-luggage"></i></div>
                                <span class="nav-text">Tour du lịch</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <div class="nav-icon"><i class="bi bi-envelope"></i></div>
                                <span class="nav-text">Đánh giá và bình luận</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <div class="nav-icon"><i class="bi bi-headset"></i></div>
                                <span class="nav-text">Hỗ trợ</span>
                                <div class="dropdown-arrow"><i class="bi bi-chevron-down"></i></div>
                            </a>
                            <div class="collapse">
                                <div class="px-2 py-1">
                                    <a href="{{ route('contact') }}" class="dropdown-item">Liên hệ với chúng tôi</a>
                                    <a href="{{ route('queries') }}" class="dropdown-item">Gửi thắc mắc</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('about') }}" class="nav-link">
                                <div class="nav-icon"><i class="bi bi-people"></i></div>
                                <span class="nav-text">Về chúng tôi</span>
                            </a>
                        </li>
                        </ul>

                    @endif
                @else
            {{-- sidebar rỗng hoặc ẩn --}}
        @endauth

        <div class="logout-section">

            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <a href="/register" class="logout-link" id="logoutBtn"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <div class="logout-icon"><i class="bi bi-box-arrow-right"></i></div>
                    <span class="logout-text">Đăng xuất</span>
                </a>
            </form>
        </div>
    </div>
</div>