@php
    $user = Auth::user();
    $layout = (!$user || $user->role === 'user') ? 'layouts.guest' : 'layouts.auth';
@endphp

@extends($layout)

@section('title', 'About Us')

@section('content')
    <!-- banner container -->
    <section class="contact-banner mb-5 ">
        <h1 id="banner-title">About us</h1>
        <img src="/assets/img1/aa.png" alt="" class="brush-bottom" />
    </section>




    <!-- Main1 content -->
    <main class="container container-custom">
        <div class="text-center mb-5 row">
            <div class="col-6">
                <div class="d-flex align-items-center mb-4 mx-5">
                    <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                    <p class="text-danger fw-bold m-0">VỀ HÀNH TRÌNH CỦA CHÚNG TÔI</p>
                </div>

                <h1 class="fw-bold">TẠO NÊN NHỮNG TRẢI NGHIỆM BIỂN ĐÁNG NHỚ</h1>
            </div>
            <div class="col-6 d-flex align-items-center text-start text-muted">
                Với nhiều năm kinh nghiệm trong du lịch biển, chúng tôi chuyên mang đến những kỳ nghỉ biển được thiết kế
                riêng,
                kết hợp giữa vẻ đẹp thiên nhiên, nét văn hóa sống động và sự thư giãn trọn vẹn.
                Dù bạn mơ ước một chuyến đi nhiệt đới hay chỉ là kỳ nghỉ cuối tuần bên bờ biển, chúng tôi đều sẵn sàng đồng
                hành.
                Sứ mệnh của chúng tôi là đưa bạn đến gần hơn với đại dương — thông qua những điểm đến được chọn lọc, gói du
                lịch
                giá hợp lý và dịch vụ tận tâm, ấm áp để từng khoảnh khắc đều trở nên đáng nhớ.
            </div>
        </div>


        <div>
            <div class="row">
                <div class="col-4 d-flex gap-4">
                    <div class="bg-primary mb-4">
                        <img src="https://demo.bosathemes.com/html/travele/assets/images/icon15.png" alt="Về Chúng Tôi"
                            class="img-fluid">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <h5 class="fw-bold">Chuyến Đi Tiết Kiệm</h5>
                        <p class="small">Tận hưởng kỳ nghỉ biển tuyệt vời</br> mà không lo vượt ngân sách.</p>
                    </div>
                </div>

                <div class="col-4 d-flex gap-4">
                    <div class="bg-primary mb-4">
                        <img src="https://demo.bosathemes.com/html/travele/assets/images/icon16.png" alt="Về Chúng Tôi"
                            class="img-fluid">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <h5 class="fw-bold">Điểm Đến Ven Biển Hàng Đầu</h5>
                        <p class="small">Khám phá những bãi biển tuyệt đẹp</br> và bí ẩn nhất trên khắp thế giới.</p>
                    </div>
                </div>

                <div class="col-4 d-flex gap-4">
                    <div class="bg-primary mb-4">
                        <img src="https://demo.bosathemes.com/html/travele/assets/images/icon17.png" alt="Về Chúng Tôi"
                            class="img-fluid">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <h5 class="fw-bold">Chuyên Gia Địa Phương Thân Thiện</h5>
                        <p class="small">Đội ngũ của chúng tôi luôn mang đến cảm giác như ở nhà</br> từ lúc đặt tour cho đến
                            khi ra biển.</p>
                    </div>
                </div>


            </div>
        </div>
        <!-- video -->
        <div class="video-thumbnail-container" onclick="openVideo()">
            <img src="/assets/img1/banner.jpg" alt="Video" class="video-thumb">
            <div class="custom-play-button">&#9658;</div>
        </div>

        <!-- Popup -->
        <div id="video-popup" class="popup-overlay">
            <div class="popup-content">
                <span class="close-button" onclick="closeVideo()">&times;</span>
                <iframe id="youtube-frame" width="800" height="450" src="" frameborder="0" allow="autoplay; encrypted-media"
                    allowfullscreen></iframe>
            </div>
        </div>


    </main>

    <!-- Main2 content -->
    <!-- Our Story Section -->
    <section class="container my-5 py-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="d-flex align-items-center mb-4">
                    <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                    <p class="text-danger fw-bold m-0">CÂU CHUYỆN CỦA CHÚNG TÔI</p>
                </div>
                <h2 class="fw-bold mb-4">TỪ GIẤC MƠ THÀNH HIỆN THỰC</h2>
                <p class="text-muted mb-4">
                    Được thành lập vào năm 2015 bởi một nhóm những người đam mê du lịch, công ty chúng tôi bắt đầu từ một
                    giấc mơ giản đơn:
                    đưa những bãi biển đẹp nhất thế giới đến gần hơn với mọi người. Từ một đơn vị lữ hành nhỏ tại địa
                    phương,
                    chúng tôi đã phát triển thành một thương hiệu toàn cầu đáng tin cậy.
                </p>
                <p class="text-muted mb-4">
                    Chúng tôi tin rằng mỗi người đều xứng đáng được tận hưởng sự bình yên của sóng biển, cảm giác ấm áp của
                    bãi cát vàng
                    và niềm vui khi khám phá những nền văn hóa ven biển mới. Triết lý đó là động lực cho mọi việc chúng tôi
                    làm.
                </p>
                <div class="row">
                    <div class="col-6">
                        <h4 class="text-danger fw-bold">2015</h4>
                        <p class="small">Thành lập công ty</p>
                    </div>
                    <div class="col-6">
                        <h4 class="text-danger fw-bold">30+</h4>
                        <p class="small">Tỉnh thành phục vụ</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                    alt="Our Story" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </section>

    <!-- Main3 content -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="text-center mb-5">
                <div class="d-flex align-items-center mb-4 justify-content-center">
                    <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                    <p class="text-danger fw-bold m-0">GIÁ TRỊ CỐT LÕI</p>
                </div>
                <h2 class="fw-bold">ĐỘNG LỰC ĐƯA CHÚNG TÔI TIẾN BƯỚC</h2>
                <p class="text-muted">Những giá trị cốt lõi định hướng mọi quyết định và trải nghiệm mà chúng tôi mang đến
                </p>
            </div>


            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Đam Mê Sự Hoàn Hảo</h5>
                        <p class="text-muted">Chúng tôi đặt trọn tâm huyết vào từng chi tiết, đảm bảo mỗi trải nghiệm biển
                            không chỉ đáp ứng mà còn vượt xa mong đợi của bạn, tạo nên những kỷ niệm đáng nhớ.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Tin Cậy & An Toàn</h5>
                        <p class="text-muted">Sự an toàn và yên tâm của bạn luôn là ưu tiên hàng đầu của chúng tôi. Chúng
                            tôi duy trì những tiêu chuẩn cao nhất trong mọi hoạt động và quan hệ hợp tác.</p>
                    </div>
                </div>


                <div class="col-lg-4 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-globe-americas"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Trách Nhiệm Môi Trường</h5>
                        <p class="text-muted">Chúng tôi cam kết gìn giữ vẻ đẹp tự nhiên của đại dương và bãi biển để các thế
                            hệ tương lai vẫn có thể tận hưởng.</p>
                    </div>
                </div>


                <div class="col-lg-4 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Hướng Tới Cộng Đồng</h5>
                        <p class="text-muted">Chúng tôi hỗ trợ các cộng đồng ven biển bằng cách hợp tác với doanh nghiệp địa
                            phương và quảng bá những trải nghiệm văn hóa chân thực.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-lightbulb-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Đổi Mới</h5>
                        <p class="text-muted">Chúng tôi không ngừng đổi mới để mang đến cho bạn công nghệ du lịch tiên tiến
                            và những trải nghiệm biển độc đáo.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Dịch Vụ Cá Nhân Hóa</h5>
                        <p class="text-muted">Mỗi du khách là một cá nhân độc nhất, và chúng tôi điều chỉnh dịch vụ để phù
                            hợp với sở thích cũng như ước mơ của bạn.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- Banner quảng cáo lớn -->
    <div id="ad-banner">
        <button class="close-btn" aria-label="Close">&times;</button>
        <div class="content-wrapper">
            <img src="/assets/img2/bien-sam-son.jpg" alt="Beach" />
            <div class="ad-text">
                <p>🌴 <strong>Giảm giá 20% ngay hôm nay!</strong></p>
                <p>Đặt tour du lịch mơ ước của bạn ngay hôm nay và tận hưởng ưu đãi độc quyền.</p>
            </div>
            <a href="{{route('tour')}}" class="book-now">Book Now</a>

        </div>
    </div>


    <!-- Main4 content -->
    <main class="container mt-5">
        <div class="text-center mb-5">

            <div class="d-flex align-items-center mb-4 mx-5 justify-content-center">
                <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                <p class="text-danger fw-bold m-0">ĐỐI TÁC CỦA CHÚNG TÔI</p>
            </div>

            <h1 class="fw-bold">CÁC ĐỐI TÁC VÀ KHÁCH HÀNG</h1>

            <div class="d-flex align-items-center text-muted justify-content-center">
                Chúng tôi tự hào hợp tác cùng mạng lưới đối tác uy tín và khách hàng trung thành trên toàn thế giới, những
                người luôn tin tưởng vào giá trị mà chúng tôi mang lại.</br>
                Cùng nhau, chúng tôi tạo nên những trải nghiệm du lịch khó quên với sự tận tâm và chất lượng vượt trội.
            </div>

        </div>

        <div class="mb-5">
            <div class="row justify-content-center align-items-center g-4 mx-5">
                <div class="col-6 col-sm-3 logo-wrapper">
                    <img src="https://img.freepik.com/free-vector/summer-background-with-lettering_23-2147819913.jpg?uid=R201273119&ga=GA1.1.1768268784.1747900003&semt=ais_hybrid&w=740"
                        class="img-fluid logo-img" alt="Logo 1">
                </div>
                <div class="col-6 col-sm-3 logo-wrapper">
                    <img src="https://img.freepik.com/premium-vector/beach-bar-with-beach-bar-top_1191547-68.jpg?uid=R201273119&ga=GA1.1.1768268784.1747900003&semt=ais_hybrid&w=740"
                        class="img-fluid logo-img" alt="Logo 2">
                </div>

                <div class="col-6 col-sm-3 logo-wrapper">
                    <img src="https://img.freepik.com/free-vector/flat-design-beach-club-logo-template_23-2149486421.jpg?uid=R201273119&ga=GA1.1.1768268784.1747900003&semt=ais_hybrid&w=740"
                        class="img-fluid logo-img" alt="Logo 4">
                </div>
                <div class="col-6 col-sm-3 logo-wrapper">
                    <img src="https://img.freepik.com/free-vector/detailed-travel-logo-template_23-2148614916.jpg?uid=R201273119&ga=GA1.1.1768268784.1747900003&semt=ais_hybrid&w=740"
                        class="img-fluid logo-img" alt="Logo 5">
                </div>
            </div>
        </div>
    </main>


    <!-- Why Choose Us Section -->
    <section class="container my-5 py-5">
        <div class="text-center mb-5">
            <div class="d-flex align-items-center mb-4 justify-content-center">
                <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                <p class="text-danger fw-bold m-0">VÌ SAO CHỌN CHÚNG TÔI</p>
            </div>
            <h2 class="fw-bold">SỰ KHÁC BIỆT CHÚNG TÔI MANG LẠI</h2>


            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="d-flex mb-4">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 50px; height: 50px; min-width: 50px;">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Hỗ Trợ 24/7</h5>
                            <p class="text-muted">Hỗ trợ liên tục mọi lúc, đảm bảo bạn không bao giờ đơn độc trong hành
                                trình. Đội ngũ hỗ trợ đa ngôn ngữ của chúng tôi luôn sẵn sàng giúp đỡ.</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 50px; height: 50px; min-width: 50px;">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Đảm Bảo Giá Tốt Nhất</h5>
                            <p class="text-muted">Tìm được giá tốt hơn ở nơi khác? Chúng tôi sẽ khớp giá đó và tặng thêm 5%
                                giảm giá cho lần đặt tiếp theo của bạn.</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 50px; height: 50px; min-width: 50px;">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Am Hiểu Địa Phương</h5>
                            <p class="text-muted">Các đối tác địa phương của chúng tôi cung cấp kiến thức nội bộ và những
                                trải nghiệm chân thực mà bạn khó tìm thấy trong sách hướng dẫn.</p>
                        </div>
                    </div>
                </div>


                <div class="col-lg-6">
                    <div class="d-flex mb-4">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 50px; height: 50px; min-width: 50px;">
                            <i class="bi bi-credit-card-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Thanh Toán Linh Hoạt</h5>
                            <p class="text-muted">Thanh toán theo từng đợt, sử dụng nhiều loại tiền tệ và tận hưởng chính
                                sách hủy tour linh hoạt, phù hợp với nhu cầu của bạn.</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 50px; height: 50px; min-width: 50px;">
                            <i class="bi bi-phone-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Công Nghệ Thông Minh</h5>
                            <p class="text-muted">Ứng dụng được hỗ trợ bởi AI của chúng tôi cung cấp cập nhật theo thời gian
                                thực, gợi ý cá nhân hóa và quản lý chuyến đi mượt mà.</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 50px; height: 50px; min-width: 50px;">
                            <i class="bi bi-recycle"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">Ý Thức Bảo Vệ Môi Trường</h5>
                            <p class="text-muted">Mỗi lần đặt tour đều góp phần vào công tác bảo tồn biển và phát triển du
                                lịch bền vững tại các cộng đồng địa phương.</p>
                        </div>
                    </div>
                </div>

            </div>
    </section>


    <!-- Call to Action Section -->
    <section class="bg-light py-5">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-4">Sẵn Sàng Bắt Đầu Hành Trình Biển Của Bạn?</h2>
                    <p class="text-muted mb-4 fs-5">
                        Hãy cùng hàng nghìn du khách hài lòng đã tìm thấy thiên đường biển mơ ước của mình với chúng tôi.
                        Kỳ nghỉ ven biển trong mơ của bạn chỉ cách một cú nhấp chuột.
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{route('tour')}}" class="btn btn-danger btn-lg px-4 py-2">
                            <i class="bi bi-airplane-fill me-2"></i>
                            Lên Kế Hoạch Chuyến Đi
                        </a>
                        <a href="tel:0123456789" class="btn btn-outline-danger btn-lg px-4 py-2">
                            <i class="bi bi-telephone-fill me-2"></i>
                            Gọi Ngay Cho Chúng Tôi
                        </a>
                    </div>

                    <p class="text-muted mt-3 small">
                        <i class="bi bi-shield-check text-success me-1"></i>
                        Tư vấn miễn phí • Không phí ẩn • Xác nhận ngay lập tức
                    </p>
                </div>

            </div>
        </div>
    </section>



    <!-- Main5 content -->
    <main class="container-fluid footer-banner mt-5">
        <div class="container">
            <div class="text-center m-5">
                <h2>Cảm Ơn Bạn Đã Ghé Thăm!</h2>
                <p>Hãy cùng chúng tôi khám phá những bãi biển đẹp nhất và trải nghiệm du lịch khó quên.</p>
            </div>

            <div class="row text-center text-white m-5">
                <div class="col-6 col-md-3 bg-red">
                    <div class="stat-box p-3">
                        <i class="bi bi-people-fill display-4"></i>
                        <h3 class="fw-bold mt-2">500K+</h3>
                        <p>Khách Hàng Hài Lòng</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-box p-3 bg-red">
                        <i class="bi bi-gem display-4"></i>
                        <h3 class="fw-bold mt-2">250K+</h3>
                        <p>Giải Thưởng Nhận Được</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-box p-3 bg-red">
                        <i class="bi bi-person-plus-fill display-4"></i>
                        <h3 class="fw-bold mt-2">15K+</h3>
                        <p>Thành Viên Hoạt Động</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-box p-3 bg-red">
                        <i class="bi bi-map display-4"></i>
                        <h3 class="fw-bold mt-2">10K+</h3>
                        <p>Điểm Du Lịch</p>
                    </div>
                </div>
            </div>

        </div>
    </main>

@endsection