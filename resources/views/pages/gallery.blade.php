@php
    $user = Auth::user();
    $layout = (!$user || $user->role === 'user') ? 'layouts.guest' : 'layouts.auth';
@endphp

@extends($layout)

@section('title', 'Gallery')

@section('content')


    <!-- Main content -->
    <section class="mt-5">
        <div class="floating-elements">
            <div class="floating-circle circle-1"></div>
            <div class="floating-circle circle-2"></div>
            <div class="floating-circle circle-3"></div>
        </div>

        <div class="gallery-container">
            <div class="hero-section">
                <h1 class="hero-title">Vùng Biển Mơ Ước</h1>
                <p class="hero-subtitle">Khám Phá Vẻ Đẹp Thiên Đường Của Biển</p>
                <div class="hero-description">
                    Hãy cùng chúng tôi dạo bước qua những bãi biển tuyệt đẹp nhất thế giới.
                    Từ hoàng hôn lãng mạn trên Biển Đông đến làn nước trong vắt của các bờ biển nhiệt đới,
                    mỗi khoảnh khắc đều kể một câu chuyện riêng về vẻ đẹp kỳ diệu của thiên nhiên.
                </div>
                <p class="hero-quote">"Biển là tấm gương của bầu trời, nơi tâm hồn tìm thấy sự bình yên."</p>
                <div class="hero-description">
                    Bộ sưu tập này được tuyển chọn từ những bãi biển đẹp nhất tại 20 khu vực,
                    mang đến cho bạn trải nghiệm thị giác ngoạn mục và nguồn cảm hứng cho kỳ nghỉ sắp tới.
                </div>
            </div>


            <div class="filter-tabs">
                <button class="filter-btn active" data-filter="all">Tất Cả</button>
                <button class="filter-btn" data-filter="sunset">Hoàng Hôn</button>
                <button class="filter-btn" data-filter="tropical">Nhiệt Đới</button>
                <button class="filter-btn" data-filter="waves">Sóng Biển</button>
                <button class="filter-btn" data-filter="paradise">Thiên Đường</button>
            </div>


            <div class="stats-section">
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Ảnh chất lượng cao</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">50+</span>
                    <span class="stat-label">Bãi biển tuyệt đẹp</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">20+</span>
                    <span class="stat-label">Tỉnh thành</span>
                </div>

            </div>

            <div class="gallery-grid" id="gallery">
                <div class="gallery-item large" data-category="sunset">
                    <img src="/assets/image/19.jpg" alt="img1" class="item-img">
                </div>

                <div class="gallery-item" data-category="tropical">
                    <img src="/assets/image/5.jpg" alt="img2" class="item-img">
                </div>

                <div class="gallery-item medium" data-category="waves">
                    <img src="/assets/image/14.jpg" alt="img3" class="item-img">
                </div>

                <div class="gallery-item" data-category="paradise">
                    <img src="/assets/image/1.jpg" alt="img4" class="item-img">
                </div>

                <div class="gallery-item" data-category="sunset">
                    <img src="/assets/image/6.jpg" alt="img5" class="item-img">
                </div>

                <div class="gallery-item" data-category="tropical">
                    <img src="/assets/image/17.jpg" alt="img6" class="item-img">
                </div>

                <div class="gallery-item" data-category="waves">
                    <img src="/assets/image/8.jpg" alt="img7" class="item-img">
                </div>

                <div class="gallery-item" data-category="paradise">
                    <img src="/assets/image/3.jpg" alt="img8" class="item-img">
                </div>

                <div class="gallery-item medium" data-category="sunset">
                    <img src="/assets/image/7.jpg" alt="img9" class="item-img">
                </div>

                <div class="gallery-item" data-category="tropical">
                    <img src="/assets/image/10.jpg" alt="img10" class="item-img">
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="imageModal" class="gallery-modal" style="z-index: 9999;">
            <div class="gallery-modal-content" style="position: relative; z-index: 10000;">
                <span class="gallery-close"
                    style="position: absolute; top: 15px; right: 25px; z-index: 10001; color: white; font-size: 35px; font-weight: bold; cursor: pointer;">&times;</span>
                <img id="modalImage" src="" alt="" style="max-width: 100%; max-height: 90vh; object-fit: contain;">
            </div>
        </div>
    </section>

@endsection