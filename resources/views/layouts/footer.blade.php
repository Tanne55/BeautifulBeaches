<!-- <footer></footer> -->
<footer class="footer-section py-5">
    <div class="container">
        <div class="row">
            <!-- Giới thiệu về du lịch -->
            <div class="col-md-3 mb-4 mb-md-0">
                <h5 class="footer-title">GIỚI THIỆU VỀ DU LỊCH</h5>
                <p class="footer-text">
                    Khám phá thế giới cùng chúng tôi. Chúng tôi mang đến những trải nghiệm du lịch tuyệt vời, phù hợp
                    với ước mơ và sự thoải mái của bạn.
                </p>
                <div class="footer-badges d-flex gap-3">
                    <img src="/assets/img1/logo_footer1.png" alt="Giải thưởng Travvy" class="img-fluid"
                        style="max-width: 80px;">
                    <img src="/assets/img1/logo_footer2.png" alt="Tạp chí Nature" class="img-fluid"
                        style="max-width: 100px;">
                </div>
            </div>

            <!-- Thông tin liên hệ -->
            <div class="col-md-3 mb-4 mb-md-0">
                <h5 class="footer-title">THÔNG TIN LIÊN HỆ</h5>
                <ul class="list-unstyled footer-contact-list">
                    <li><i class="bi bi-telephone me-2"></i> +01 (977) 2599 12</li>
                    <li><i class="bi bi-envelope me-2"></i> aptechvn.com</li>
                    <li><i class="bi bi-geo-alt me-2"></i> 175 Chuaboc, Đống Đa, Hà Nội</li>
                </ul>
            </div>

            <!-- Bài viết mới nhất -->
            <div class="col-md-3 mb-4 mb-md-0">
                <h5 class="footer-title">BÀI VIẾT MỚI NHẤT</h5>
                <ul class="list-unstyled footer-latest-post">
                    <li>
                        <a href="#" class="text-white text-decoration-none">Cuộc sống là một hành trình đẹp chứ không
                            phải đích đến</a>
                        <div class="small text-muted">17 Tháng 5, 2025 | Không có bình luận</div>
                    </li>
                    <li>
                        <a href="#" class="text-white text-decoration-none">Chỉ mang theo ký ức, để lại dấu chân</a>
                        <div class="small text-muted">17 Tháng 5, 2025 | Không có bình luận</div>
                    </li>
                </ul>
            </div>

            <!-- Theo dõi đặt chỗ -->
            <div class="col-md-3">
                <h5 class="footer-title">THEO DÕI ĐẶT CHỖ</h5>
                <p class="footer-text">
                    Kiểm tra trạng thái đặt chỗ của bạn bằng cách nhập mã đặt chỗ.
                </p>
                <form class="footer-booking-form" action="{{ route('bookings.track') }}" method="GET">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="booking_code" class="form-control" placeholder="Nhập mã đặt chỗ"
                            aria-label="Mã đặt chỗ" required>
                    </div>
                    <button class="btn btn-subscribe w-100" type="submit">THEO DÕI ĐẶT CHỖ</button>
                </form>
            </div>
        </div>

        <hr class="footer-divider my-4">

        <!-- Thanh dưới cùng -->
        <div class="d-flex justify-content-between">
            <div class="col-md-4">
                <ul class="list-inline footer-links m-0">
                    <li class="list-inline-item"><a href="#" class="text-white text-decoration-none">Privacy Policy</a>
                    </li>
                    <li class="list-inline-item">|</li>
                    <li class="list-inline-item"><a href="#" class="text-white text-decoration-none">Terms &
                            Conditions</a></li>
                    <li class="list-inline-item">|</li>
                    <li class="list-inline-item"><a href="#" class="text-white text-decoration-none">FAQ</a></li>
                </ul>

            </div>
            <div class="col-md-4 text-center mt-3 mt-md-0">
                <span class="text-white me-3 fw-bold"><img src="/assets/img1/logo_beach.jpg" alt="Travele" width="50"
                        class="botron me-2"> Beautiful Beaches</span>
            </div>
            <p class="text-white">&copy; Bản quyền 2025 Travele. Bảo lưu mọi quyền.</p>
        </div>
    </div>
</footer>