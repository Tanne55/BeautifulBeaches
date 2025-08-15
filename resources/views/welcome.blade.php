@php
    $user = Auth::user();
    $layout = (!$user || $user->role === 'user') ? 'layouts.guest' : 'layouts.auth';
@endphp

@extends($layout)

@section('title', 'Trang chủ')

@section('content')
    <!--section 1 -->
    <section class="position-relative hh">
        <!-- Carousel -->
        <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/assets/image/14.jpg" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="carousel-title">Khám Phá Thiên Đường Nhiệt Đới</h1>
                        <p class="carousel-subtitle">Hít thở làn gió biển và khám phá thiên đường của thiên nhiên.</p>
                        <a href="/beaches/14" class="btn btn-primary mb-5">Khám Phá Ngay</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/assets/image/7.jpg" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="carousel-title">Bình Yên Hoàng Hôn Đang Chờ</h1>
                        <p class="carousel-subtitle">Thư giãn trên cát vàng dưới bầu trời tối kỳ diệu.</p>
                        <a href="/beaches/7" class="btn btn-primary mb-5">Khám Phá Ngay</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/assets/image/22.jpg" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="carousel-title">Mũi Né: Bãi Biển Gió</h1>
                        <p class="carousel-subtitle">Viên ngọc ven biển với đồi cát, lướt ván diều và không khí tuyệt vời.
                        </p>
                        <a href="/beaches/22" class="btn btn-primary mb-5">Khám Phá Ngay</a>
                    </div>
                </div>
            </div>

            <!-- Controls -->

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Trước</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Tiếp</span>
            </button>

        </div>

        <!-- Form tìm kiếm -->
        <div class="container position-absolute start-50 translate-middle-x"
            style="bottom:-10%; z-index:2; max-width:1100px;">
            <form action="/Pages/Explore/explore.html"
                class="bg-white rounded shadow p-5 row g-3 align-items-end banner-search-form">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tìm Điểm Đến*</label>
                    <input type="text" class="form-control" placeholder="Nhập Điểm Đến"
                        style="background-color:#F8F8F8; border:none;">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Số Khách*</label>
                    <input type="number" class="form-control" placeholder="Số Người"
                        style="background-color:#F8F8F8; border:none;" min="1" max="10">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Ngày Đến*</label>
                    <input type="date" class="form-control" id="arrival-date" name="arrival_date"
                        style="background-color:#F8F8F8; border:none;" min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Ngày Về*</label>
                    <input type="date" class="form-control" id="departure-date" name="departure_date"
                        style="background-color:#F8F8F8; border:none;" min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-3 d-flex justify-content-center">
                    <a href="{{ route('contact') }}" class="btn btn-danger btn-lg banner-search-btn" role="button">TƯ VẤN
                        NGAY</a>
                </div>
            </form>
        </div>
    </section>




    <!-- section 2 -->
    <section class="popular-destination py-5 mt-5">
        <div class="container">
            <div class="text-center mb-5 row">
                <div class="col-6">
                    <div class="d-flex align-items-center mb-4">
                        <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                        <p class="text-danger fw-bold m-0">ĐIỂM ĐẾN PHỔ BIẾN</p>
                    </div>

                    <h1 class="fw-bold">Thoát Khỏi Bờ Biển</h1>
                </div>
                <div class="col-6 d-flex align-items-center text-start text-muted">
                    Khám phá những bờ biển nguyên sơ, nước trong vắt và những</br> viên ngọc nhiệt đới ẩn giấu.<br>
                    Từ những thị trấn ven biển sôi động đến những nơi nghỉ dưỡng</br> cát yên tĩnh — kỳ nghỉ mơ ước của bạn
                    đang
                    chờ đón.
                </div>

            </div>


            <div class="row g-4 mx-5">
                @foreach ($beaches->slice(0, 2) as $beach)
                    <div class="col-sm-3">
                        <a href="{{ route('beaches.show', $beach['id']) }}"
                            class="text-decoration-none text-white d-block h-100">
                            <div class="position-relative rounded overflow-hidden destination-card"
                                style="
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   background-image: url('{{$beach['image']}}');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   background-size: cover;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   background-position: left;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   height: 500px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 ">
                                <!-- Lớp overlay làm tối nền -->
                                <div class="position-absolute top-0 start-0 w-100 h-100"
                                    style="background: rgba(0, 0, 0, 0.4); z-index: 1;"></div>

                                <!-- Tag span -->
                                <span class="position-absolute top-0 start-0 m-3 px-3 py-1 bg-info text-white fw-bold rounded"
                                    style="z-index: 2;">
                                    HẠ LONG
                                </span>

                                <!-- Thông tin dưới -->
                                <div class="position-absolute bottom-0 start-0 w-100 text-white p-3 "
                                    style="background: linear-gradient(to top, rgba(0,0,0,0.6), transparent); z-index: 2;">
                                    <h5 class="m-0 fw-bold">{{$beach['region']}}</h5>
                                    <div class="text-warning mt-1">★★★★☆</div>
                                </div>

                            </div>
                        </a>
                    </div>

                @endforeach
                <div class="col-6 d-flex flex-column" style="height: 500px; gap: 20px;">
                    @foreach ($beaches->slice(2, 2) as $beach)
                        <a href="{{ route('beaches.show', $beach['id']) }}"
                            class="text-decoration-none text-white d-block h-100">
                            <div class="position-relative rounded overflow-hidden shadow-lg flex-fill destination-card"
                                style="
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                background-image: url('{{ $beach['image'] }}');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                background-size: cover;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                background-position: center;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                height: 240px;">
                                <!-- thêm height nếu cần cố định -->

                                <!-- Overlay -->
                                <div class="position-absolute top-0 start-0 w-100 h-100"
                                    style="background: rgba(0, 0, 0, 0.35); z-index: 1;"></div>

                                <!-- Tag -->
                                <span class="position-absolute top-0 start-0 m-3 px-3 py-1 bg-info text-white fw-bold rounded"
                                    style="z-index: 2;">HẢI PHÒNG</span>

                                <!-- Nội dung -->
                                <div class="position-absolute bottom-0 start-0 w-100 text-white p-3"
                                    style="background: linear-gradient(to top, rgba(0,0,0,0.6), transparent); z-index: 2;">
                                    <h5 class="m-0 fw-bold">{{ $beach['region'] }}</h5>
                                    <div class="text-warning mt-1">★★★★★</div>
                                </div>
                            </div>
                        </a>
                    @endforeach


                </div>

            </div>



            <div class="text-center mt-4">
                <a href="{{route('explore')}}" class="btn btn-danger">KHÁM PHÁ THÊM</a>
            </div>
        </div>
    </section>


    <!-- section 3 -->
    <section style="padding-bottom: 100px ;">
        <div class="container">
            <div class="text-center mb-4 mt-5 row">
                <div class="d-flex align-items-center mb-4 justify-content-center">
                    <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                    <p class="text-danger fw-bold m-0">KHÁM PHÁ THIÊN ĐƯỜNG VEN BIỂN</p>
                </div>

                <h1 class="fw-bold">GÓI TOUR BIỂN PHỔ BIẾN</h1>
            </div>
            <div class="text-center text-muted mb-5">
                Khám phá những điểm đến biển được chọn lọc với cát vàng, nước xanh ngọc bích,<br>
                và các gói trọn gói được thiết kế cho chuyến thoát khỏi hoàn hảo.
            </div>

            <div class="card-container">
                @forelse ($tours as $tour)
                    <div class="card m-4">
                        <div class="card-image"
                            style="background-image: url('{{ $tour['image'] ? asset($tour['image']) : asset('/assets/image/11.jpg') }}');">
                            <div class="price-tag">{{ number_format($tour['current_price'], 0, ',', '.') }} VNĐ <span>/ mỗi
                                    người</span></div>
                            <div class="card-meta">
                                <span><i class="bi bi-calendar"></i>
                                    {{ $tour['duration_days'] }}N</span>
                                <span><i class="bi bi-people"></i> Số người: {{ $tour['max_people'] ?: 'N/A' }}</span> <span><i
                                        class="bi bi-geo-alt"></i> {{ $tour['beach_region'] }}</span>
                            </div>
                        </div>
                        <div class="card-info mt-4">
                            <h3 class="card-title">
                                <a href="{{ route('tour.show', $tour['id']) }}" class="card-title">{{ $tour['title'] }}</a>
                            </h3>
                            <div class="card-reviews">
                                <span style="margin-bottom: 5px;">({{ $tour['total_reviews'] }} đánh giá)</span>
                                <div class="stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($tour['average_rating']))
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @elseif ($i - 0.5 <= $tour['average_rating'])
                                            <i class="bi bi-star-half text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-muted"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <p class="card-description">
                                {{ $tour['short_description'] ?: 'Khám phá vẻ đẹp tuyệt vời của bãi biển với tour được thiết kế hoàn hảo cho chuyến đi của bạn.' }}
                            </p>
                            <div class="card-actions d-flex justify-content-center">
                                <a href="{{ route('tour.booking.form', $tour['id']) }}" class="btn custom-btn fw-bold ">
                                    Đặt Ngay
                                    <i class="bi bi-arrow-right ms-2 icon-red"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">Chưa có tour nào được đăng.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-4">
                <a href="{{route('tour')}}" class="btn btn-danger">KHÁM PHÁ THÊM</a>
            </div>
        </div>


    </section>

    <!-- Quảng cáo giữa trang Home -->
    <div
        style="
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    background-image: url('https://th.bing.com/th/id/R.a1c253924f0b8c174d1b69ad0f1dad1e?rik=fuiE9k6pEIFZGQ&pid=ImgRaw&r=0');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    background-size: cover;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    background-position: center;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    padding: 60px 20px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    position: relative;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    color: white;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  ">
        <!-- Lớp phủ tối để dễ đọc chữ -->
        <div
            style="
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    position: absolute;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    top: 0; left: 0; right: 0; bottom: 0;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    background-color: rgba(0, 0, 0, 0.5);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    z-index: 1;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  ">
        </div>

        <!-- Nội dung quảng cáo -->
        <div style="position: relative; z-index: 2; max-width: 900px; margin: auto; text-align: center;">
            <h2 class="fw-bold">🛒 Mua Sắm Đồ Thiết Yếu Biển Trên Shopee!</h2>
            <p class="lead">
                Khám phá ưu đãi mùa hè: bikini, kính râm, kem chống nắng & nhiều hơn nữa!
            </p>
            <a href="https://shopee.vn/" target="_blank" class="btn btn-warning fw-bold mt-3">
                Mua Ngay Trên Shopee
            </a>
        </div>
    </div>

    <!-- section 4 -->
    <section class="travel-by-activity py-5">
        <div class="container mt-5">
            <div class="row g-4">
                <div class="col-md-2">
                    <div class="activity-card text-center p-3 border rounded" data-activity="sunbathing">
                        <i class="bi bi-sun text-primary fs-1 activity-icon"></i>
                        <h5 class="fw-bold mt-3">Tắm Nắng</h5>
                        <p class="text-muted">20 Điểm Đến</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="activity-card text-center p-3 border rounded" data-activity="snorkeling">
                        <i class="bi bi-water text-primary fs-1 activity-icon"></i>
                        <h5 class="fw-bold mt-3">Ngắm San Hô</h5>
                        <p class="text-muted">15 Điểm Đến</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="activity-card text-center p-3 border rounded" data-activity="kitesurfing">
                        <i class="bi bi-wind text-primary fs-1 activity-icon"></i>
                        <h5 class="fw-bold mt-3">Lướt Ván Diều</h5>
                        <p class="text-muted">10 Điểm Đến</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="activity-card text-center p-3 border rounded" data-activity="beach-relax">
                        <i class="bi bi-umbrella text-primary fs-1 activity-icon"></i>
                        <h5 class="fw-bold mt-3">Thư Giãn Biển</h5>
                        <p class="text-muted">25 Điểm Đến</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="activity-card text-center p-3 border rounded" data-activity="tsunami-tours">
                        <i class="bi bi-tsunami text-primary fs-1 activity-icon"></i>
                        <h5 class="fw-bold mt-3">Tour Sóng Thần</h5>
                        <p class="text-muted">18 Điểm Đến</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="activity-card text-center p-3 border rounded" data-activity="night-parties">
                        <i class="bi bi-star text-primary fs-1 activity-icon"></i>
                        <h5 class="fw-bold mt-3">Tiệc Đêm</h5>
                        <p class="text-muted">12 Điểm Đến</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Khung chi tiết hoạt động thay cho modal -->
    <div id="overlay" class="overlay"></div>
    <div id="activityDetail" class="activity-detail-box">
        <div class="d-flex justify-content-between align-items-center bg-info p-3 rounded-top m-0">
            <h4 id="activityTitle"></h4>
            <button id="closeDetail" class="btn-close float-end"></button>
        </div>
        <div class="row">
            <div class="col-6">
                <img id="activityImage" src="" class="img-fluid w-100 h-100 mb-3 rounded-bottom" alt="Hoạt động">
            </div>
            <div class="col-6 p-2">
                <h5>Thông tin chi tiết:</h5>
                <p id="activityDescription"></p>
                <ul id="activityFeatures"></ul>
                <p><strong>Thời gian tốt nhất:</strong> <span id="activityBestTime"></span></p>
                <p><strong>Điểm đến:</strong> <span id="activityDestinations"></span></p>
            </div>
        </div>
    </div>


    <!-- phần 5 -->
    <section class="recent-posts py-5">
        <div class="container">
            <div class="text-center mb-5">
                <div class="d-flex align-items-center mb-4 justify-content-center">
                    <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                    <p class="text-danger fw-bold m-0">TIN TỨC & SỰ KIỆN</p>
                </div>
                <h2 class="fw-bold">CẬP NHẬT MỚI NHẤT</h2>
                <p class="text-muted">
                    Theo dõi những tin tức, sự kiện và chương trình khuyến mãi mới nhất
                    từ chúng tôi để không bỏ lỡ cơ hội trải nghiệm tuyệt vời.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center p-3">
                        <div class="bg-light rounded p-4 mb-3 shadow-lg">
                            <i class="fas fa-calendar-alt text-primary mb-3" style="font-size: 2.5rem;"></i>
                            <h5 class="fw-bold text-dark">Lễ Hội Biển 2025</h5>
                            <p class="text-muted mb-3">15-17 Tháng 9</p>
                            <p class="text-muted small">
                                Tham gia lễ hội biển sôi động với các hoạt động văn hóa đa dạng,
                                biểu diễn âm nhạc sống động và ẩm thực đặc sắc bên bờ biển.
                                Có gian hàng thủ công mỹ nghệ, trò chơi dân gian và pháo hoa
                                rực rỡ vào tối chủ nhật. Miễn phí tham gia cho tất cả du khách.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3">
                        <div class="bg-light rounded p-4 mb-3 shadow-lg">
                            <i class="fas fa-percent text-primary mb-3" style="font-size: 2.5rem;"></i>
                            <h5 class="fw-bold text-dark">Khuyến Mãi Mùa Hè</h5>
                            <p class="text-muted mb-3">Giảm đến 30%</p>
                            <p class="text-muted small">
                                Đặt tour và dịch vụ trong tháng 8 để nhận ưu đãi đặc biệt
                                lên đến 30% giá trị. Áp dụng cho nhóm từ 4 người trở lên,
                                bao gồm dịch vụ lặn biển, tour khám phá đảo và thuê dụng cụ
                                thể thao nước. Thanh toán online nhận thêm 5% khuyến mãi.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3">
                        <div class="bg-light rounded p-4 mb-3 shadow-lg">
                            <i class="fas fa-award text-primary mb-3" style="font-size: 2.5rem;"></i>
                            <h5 class="fw-bold text-dark">Giải Thưởng Du Lịch</h5>
                            <p class="text-muted mb-3">Top 10 Bãi Biển</p>
                            <p class="text-muted small">
                                Vinh dự được bình chọn vào Top 10 bãi biển đẹp nhất
                                Việt Nam năm 2025 bởi Travel Awards Vietnam. Đây là
                                năm thứ 3 liên tiếp chúng tôi nhận được danh hiệu này
                                nhờ chất lượng dịch vụ xuất sắc và cảnh quan thiên nhiên tuyệt đẹp.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- phần 6 -->
    <section class="customer-reviews">
        <div class="container">
            <div class="section-title">
                <h2>Khách hàng nói gì về chúng tôi</h2>
                <p>Trải nghiệm thực tế từ những du khách đã tin tưởng chúng tôi với kỳ nghỉ mơ ước của họ</p>
            </div>

            <div id="customerReviewsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner">
                    <!-- Slide 1 -->
                    <div class="carousel-item active">
                        <div class="review-card">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200"
                                class="customer-photo" alt="William Housten">
                            <div class="stars d-flex justify-content-center">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="review-text">
                                "Dịch vụ tuyệt vời! Đội ngũ cực kỳ chuyên nghiệp và hữu ích.</br> Chuyến đi gia đình của
                                chúng tôi
                                hoàn toàn hoàn hảo với những trải nghiệm khó quên. Chắc chắn sẽ đặt lại!"
                            </p>
                            <h5 class="customer-name">William Housten</h5>
                            <p class="customer-role">Đại lý du lịch</p>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="carousel-item">
                        <div class="review-card">
                            <img src="https://demo.bosathemes.com/html/travele/assets/images/img22.jpg"
                                class="customer-photo" alt="Sophia Carter">
                            <div class="stars d-flex justify-content-center">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="review-text">
                                "Từ việc đặt chỗ đến cuối chuyến đi, mọi thứ đều được tổ chức hoàn hảo. Những bãi biển tuyệt
                                đẹp, </br>khách sạn sang trọng, và hướng dẫn viên địa phương am hiểu. Thật tuyệt vời!"
                            </p>
                            <h5 class="customer-name">Sophia Carter</h5>
                            <p class="customer-role">Người đam mê bãi biển</p>
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="carousel-item">
                        <div class="review-card">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200"
                                class="customer-photo" alt="James Anderson">
                            <div class="stars d-flex justify-content-center">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="review-text">
                                "Là một người yêu thích phiêu lưu và khám phá, tôi đã tìm thấy chính xác những gì mình cần.
                                </br>Các hoạt động phiêu lưu an toàn, được tổ chức tốt và ly kỳ. Trải nghiệm khó quên!"
                            </p>
                            <h5 class="customer-name">James Anderson</h5>
                            <p class="customer-role">Người tìm kiếm phiêu lưu</p>
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#customerReviewsCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Trước</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#customerReviewsCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Tiếp theo</span>
                </button>

                <!-- Indicators -->
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#customerReviewsCarousel" data-bs-slide-to="0"
                        class="active"></button>
                    <button type="button" data-bs-target="#customerReviewsCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#customerReviewsCarousel" data-bs-slide-to="2"></button>
                </div>
            </div>
        </div>
    </section>


@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const arrivalDateInput = document.getElementById('arrival-date');
        const departureDateInput = document.getElementById('departure-date');

        // Lấy ngày hiện tại
        const today = new Date().toISOString().split('T')[0];

        // Set min date cho cả hai trường
        arrivalDateInput.min = today;
        departureDateInput.min = today;

        // Khi ngày đến thay đổi, cập nhật min date cho ngày về
        arrivalDateInput.addEventListener('change', function () {
            const arrivalDate = this.value;
            if (arrivalDate) {
                departureDateInput.min = arrivalDate;

                // Nếu ngày về đã được chọn và nhỏ hơn ngày đến, reset ngày về
                if (departureDateInput.value && departureDateInput.value < arrivalDate) {
                    departureDateInput.value = '';
                }
            }
        });

        // Validation khi submit form
        const searchForm = document.querySelector('.banner-search-form');
        searchForm.addEventListener('submit', function (e) {
            const arrivalDate = arrivalDateInput.value;
            const departureDate = departureDateInput.value;

            // Kiểm tra ngày đến không được trong quá khứ
            if (arrivalDate && arrivalDate < today) {
                e.preventDefault();
                alert('Ngày đến không thể là ngày trong quá khứ!');
                arrivalDateInput.focus();
                return false;
            }

            // Kiểm tra ngày về phải sau ngày đến
            if (arrivalDate && departureDate && departureDate <= arrivalDate) {
                e.preventDefault();
                alert('Ngày về phải sau ngày đến!');
                departureDateInput.focus();
                return false;
            }

            // Kiểm tra ngày về không được trong quá khứ
            if (departureDate && departureDate < today) {
                e.preventDefault();
                alert('Ngày về không thể là ngày trong quá khứ!');
                departureDateInput.focus();
                return false;
            }
        });

        // Thêm validation real-time
        arrivalDateInput.addEventListener('blur', function () {
            const selectedDate = this.value;
            if (selectedDate && selectedDate < today) {
                this.setCustomValidity('Ngày đến không thể là ngày trong quá khứ!');
                this.reportValidity();
            } else {
                this.setCustomValidity('');
            }
        });

        departureDateInput.addEventListener('blur', function () {
            const selectedDate = this.value;
            const arrivalDate = arrivalDateInput.value;

            if (selectedDate && selectedDate < today) {
                this.setCustomValidity('Ngày về không thể là ngày trong quá khứ!');
                this.reportValidity();
            } else if (selectedDate && arrivalDate && selectedDate <= arrivalDate) {
                this.setCustomValidity('Ngày về phải sau ngày đến!');
                this.reportValidity();
            } else {
                this.setCustomValidity('');
            }
        });
    });
</script>