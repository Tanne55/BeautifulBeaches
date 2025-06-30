@php
    $user = Auth::user();
    $layout = (!$user || $user->role === 'user') ? 'layouts.guest' : 'layouts.auth';
@endphp

@extends($layout)

@section('title', 'Queries')

@section('content')
    <!-- banner contact -->
    <section class="contact-banner">
        <h1 id="banner-title">Câu hỏi thường gặp</h1>
        <img src="/assets/img1/aa.png" alt="" class="brush-bottom" />
    </section>




    <section class="container py-2">
        <div class="row g-5 align-items-start">
            <!-- Left: Form -->
            <div class="col-md-7">
                <div class="faq-container">
                    <!-- Search Box -->
                    <div class="search-box d-flex align-items-center">
                        <i class="fas fa-search search-icon me-3"></i>
                        <input type="text" class="search-input" placeholder="Tìm kiếm câu hỏi..." id="searchInput">
                    </div>

                    <!-- FAQ Items -->
                    <div id="faqContainer">
                        <div class="faq-item fade-in" data-keywords="tour trẻ em phù hợp">
                            <button class="faq-question" onclick="toggleFAQ(this)">
                                <span>Tour có phù hợp với trẻ em không?</span>
                                <i class="fas fa-chevron-down faq-icon-toggle"></i>
                            </button>
                            <div class="faq-answer">
                                <div class="faq-answer-content">
                                    Tour phù hợp với trẻ em từ 5 tuổi trở lên. Chúng tôi có áo phao an toàn và hướng dẫn
                                    viên
                                    chuyên nghiệp để đảm bảo an toàn cho các bé.
                                </div>
                            </div>
                        </div>

                        <div class="faq-item fade-in" data-keywords="chính sách hủy tour hoàn tiền">
                            <button class="faq-question" onclick="toggleFAQ(this)">
                                <span>Chính sách hủy tour như thế nào?</span>
                                <i class="fas fa-chevron-down faq-icon-toggle"></i>
                            </button>
                            <div class="faq-answer">
                                <div class="faq-answer-content">
                                    <strong>Hủy trước 7 ngày:</strong> hoàn 100% <br>
                                    <strong>Hủy trước 3 ngày:</strong> hoàn 70% <br>
                                    <strong>Hủy trước 1 ngày:</strong> hoàn 30% <br>
                                    <strong>Hủy trong ngày:</strong> không hoàn tiền.
                                </div>
                            </div>
                        </div>

                        <div class="faq-item fade-in" data-keywords="thời tiết xấu xử lý hoàn tiền">
                            <button class="faq-question" onclick="toggleFAQ(this)">
                                <span>Nếu thời tiết xấu sẽ xử lý như thế nào?</span>
                                <i class="fas fa-chevron-down faq-icon-toggle"></i>
                            </button>
                            <div class="faq-answer">
                                <div class="faq-answer-content">
                                    Chúng tôi sẽ liên hệ để dời lịch hoặc hoàn tiền 100%. An toàn của khách hàng là ưu tiên
                                    hàng
                                    đầu.
                                </div>
                            </div>
                        </div>

                        <div class="faq-item fade-in" data-keywords="mang theo đồ dùng thiết bị">
                            <button class="faq-question" onclick="toggleFAQ(this)">
                                <span>Có cần mang theo gì đặc biệt không?</span>
                                <i class="fas fa-chevron-down faq-icon-toggle"></i>
                            </button>
                            <div class="faq-answer">
                                <div class="faq-answer-content">
                                    Mang theo CMND/CCCD, đồ bơi, kem chống nắng, mũ, áo khoác nhẹ. Các thiết bị thể thao
                                    nước đã
                                    được cung cấp.
                                </div>
                            </div>
                        </div>

                        <div class="faq-item fade-in" data-keywords="mang theo thú cưng">
                            <button class="faq-question" onclick="toggleFAQ(this)">
                                <span>Có thể mang theo thú cưng không?</span>
                                <i class="fas fa-chevron-down faq-icon-toggle"></i>
                            </button>
                            <div class="faq-answer">
                                <div class="faq-answer-content">
                                    Một số tour cho phép mang theo thú cưng nhỏ gọn và đã tiêm phòng đầy đủ. Tuy nhiên, bạn
                                    nên thông báo trước với chúng tôi để được hỗ trợ tốt nhất và tránh ảnh hưởng đến các
                                    hành khách khác.
                                </div>
                            </div>
                        </div>

                        <div class="faq-item fade-in" data-keywords=" Có chỗ nào check-in đẹp không?">
                            <button class="faq-question" onclick="toggleFAQ(this)">
                                <span> Có chỗ nào check-in đẹp không?</span>
                                <i class="fas fa-chevron-down faq-icon-toggle"></i>
                            </button>
                            <div class="faq-answer">
                                <div class="faq-answer-content">
                                    Tour sẽ đưa bạn đến nhiều địa điểm “sống ảo” như bãi đá tự nhiên, cầu gỗ trên biển, các
                                    quán cà phê ven bờ với view cực chill. Hướng dẫn viên cũng sẽ giới thiệu những góc máy
                                    được yêu thích để bạn lưu lại ảnh đẹp.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- No Results Message -->
                    <div class="no-results" id="noResults">
                        <i class="fas fa-search"></i>
                        <h3>Không tìm thấy kết quả</h3>
                        <p>Hãy thử tìm kiếm với từ khóa khác hoặc liên hệ với chúng tôi để được hỗ trợ.</p>
                    </div>
                </div>
            </div>

            <!-- Right: Support Info -->
            <div class="col-md-5">
                <div class="support-box text-center p-4 shadow rounded">
                    <img src="https://demo.bosathemes.com/html/travele/assets/images/img21.jpg" alt="Support Agent"
                        class="img-fluid rounded-circle mb-3" width="150" />
                    <h5>James Watson</h5>
                    <p class="text-muted mb-1">Customer Support Specialist</p>
                    <p>"I'm here to help! Feel free to send your questions any time. Our team responds within 24 hours."
                    </p>
                    <hr />
                    <p class="small mb-1"><strong>Hotline:</strong> 1900 999 888</p>
                    <p class="small mb-1"><strong>Email:</strong> aptechvn.com</p>
                    <p class="small"><strong>Working Hours:</strong> Mon - Fri, 9AM - 6PM</p>
                </div>
            </div>
        </div>
    </section>

    <section class="container mb-5">
        <!-- Quick Tips -->
        <div class="quick-tips">
            <h4 class="tips-title">
                <i class="fas fa-lightbulb"></i>
                Tips Hữu Ích
            </h4>
            <div class="tip-item">
                <strong>Kem chống nắng:</strong> Luôn mang theo và thoa lại mỗi 2 tiếng
            </div>
            <div class="tip-item">
                <strong>Nước uống:</strong> Mang nhiều nước để tránh mất nước
            </div>
            <div class="tip-item">
                <strong>Giờ tắm biển:</strong> Tránh tắm biển khi có sóng lớn hoặc thời tiết xấu
            </div>
            <div class="tip-item">
                <strong>Đồ bơi:</strong> Chuẩn bị đồ bơi phù hợp và khăn tắm
            </div>
        </div>
    </section>

    @vite('resources/js/queries.js')
@endsection