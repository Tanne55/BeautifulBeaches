@php
    $user = Auth::user();
    $layout = (!$user || $user->role === 'user') ? 'layouts.guest' : 'layouts.auth';
@endphp

@extends($layout)

@section('content')
    <div class="container my-5">
        <h2 class="mb-4 text-center fw-bold">{{ $tour->title }}</h2>

        <div class="row">
            <div class="col-md-6">
                @php
                    $img = $tour->image ?? '';
                    $beachImg = ($tour->beach && $tour->beach->image) ? $tour->beach->image : '';
                    $isAsset = $img && (str_starts_with($img, 'http') || str_starts_with($img, '/assets'));
                    $isBeachAsset = $beachImg && (str_starts_with($beachImg, 'http') || str_starts_with($beachImg, '/assets'));
                @endphp
                @if($img)
                    <img src="{{ $isAsset ? $img : asset('storage/' . $img) }}" class="img-fluid rounded shadow"
                        alt="{{ $tour->title }}">
                @elseif($beachImg)
                    <img src="{{ $isBeachAsset ? $beachImg : asset('storage/' . $beachImg) }}" class="img-fluid rounded shadow"
                        alt="{{ $tour->title }}">
                @else
                    <img src="https://via.placeholder.com/600x400?text=No+Image" class="img-fluid rounded shadow"
                        alt="No image">
                @endif
                <div class="my-5">
                    <h3 class="mb-3">{{ $tour->beach->short_description }}</h3>
                    <div class="fst-italic">
                        <p>{{ $tour->beach->detail->long_description }}</p>
                        <p>{{ $tour->beach->detail->long_description2 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>Khu vực</th>
                            <td>{{ $tour->beach?->region }}</td>
                        </tr>
                        <tr>
                            <th>Thời lượng</th>
                            <td>{{ $tour->duration_days }} ngày</td>
                        </tr>
                        <tr>
                            <th>Sức chứa</th>
                            <td>{{ $tour->capacity }} người</td>
                        </tr>
                        <tr>
                            <th>Giá</th>
                            <td>
                                <span class="text-danger fw-bold">{{ number_format($tour->price, 0, ',', '.') }} đ</span>
                                @if($tour->original_price > $tour->price)
                                    <small class="text-decoration-line-through text-muted">
                                        {{ number_format($tour->original_price, 0, ',', '.') }} đ
                                    </small>
                                @endif
                            </td>
                        </tr>
                        @if($tour->detail)
                            <tr>
                                <th>Giờ khởi hành</th>
                                <td>{{ $tour->detail->departure_time }}</td>
                            </tr>
                            <tr>
                                <th>Giờ trở về</th>
                                <td>{{ $tour->detail->return_time }}</td>
                            </tr>
                            @if($tour->detail->included_services)
                                <tr>
                                    <th>Dịch vụ bao gồm</th>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach(json_decode($tour->detail->included_services, true) ?? [] as $service)
                                                <li>{{ $service }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endif
                            @if($tour->detail->excluded_services)
                                <tr>
                                    <th>Không bao gồm</th>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach(json_decode($tour->detail->excluded_services, true) ?? [] as $service)
                                                <li>{{ $service }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endif
                            @if($tour->detail->highlights)
                                <tr>
                                    <th>Điểm nổi bật</th>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach(json_decode($tour->detail->highlights, true) ?? [] as $highlight)
                                                <li>{{ $highlight }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endif
                        @endif

                        <tr>
                            <th>CEO</th>
                            <td>{{ $tour->ceo?->name }}</td>
                        </tr>
                    </tbody>
                </table>
                {{-- NÚT ĐẶT TOUR --}}
                <div class="mb-4">
                    @auth
                        <a href="{{ route('tour.booking.form', $tour->id) }}" class="btn btn-success btn-lg w-100 mb-3">Đặt tour
                            ngay</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-success btn-lg w-100 mb-3">Đăng nhập để đặt tour</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- COMMENT/REVIEW SECTION --}}
    <div class="container mb-5">
        <h4 class="mb-3">Bình luận & Đánh giá</h4>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @auth
            <form action="{{ route('tour.review', $tour->id) }}" method="POST" class="mb-4" id="review-form">
                @csrf
                <div class="mb-2">
                    <label class="form-label">Đánh giá:</label>
                    <div id="star-rating" class="d-inline-block">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star star-icon" data-value="{{ $i }}"></i>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="5">
                </div>
                <div class="mb-2">
                    <label for="comment" class="form-label">Bình luận:</label>
                    <textarea name="comment" id="comment" class="form-control" rows="2" required></textarea>
                </div>
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                <button type="submit" class="btn btn-primary">Gửi bình luận</button>
            </form>

        @else
            <div class="alert alert-info">Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để bình luận.</div>
        @endauth

        <div class="review-list mt-4">
            @forelse($reviews as $review)
                <div class="border-bottom pb-2 mb-2">
                    <div class="d-flex align-items-center mb-1">
                        <strong>{{ $review->user->name ?? 'Ẩn danh' }}</strong>
                        @if(isset($review->user->role))
                            @if($review->user->role === 'admin')
                                <span class="badge bg-danger ms-2">Admin</span>
                            @elseif($review->user->role === 'ceo')
                                <span class="badge bg-primary ms-2">CEO</span>
                            @elseif($review->user->role === 'user')
                                <span class="badge bg-secondary ms-2">User</span>
                            @endif
                        @endif
                        <span class="ms-2 text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                            @endfor
                        </span>
                        <span class="ms-2 text-muted small">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <div>{{ $review->comment }}</div>
                </div>
            @empty
                <div class="text-muted">Chưa có bình luận nào.</div>
            @endforelse
        </div>
    </div>

    {{-- Lịch trình chi tiết --}}
    <section class="content-section container">
        <div class="section-header">
            🗓️ Lịch trình chi tiết
        </div>
        <div class="section-content">
            <div class="itinerary-timeline">
                <div class="timeline-item">
                    <div class="timeline-time">08:00 - 09:00</div>
                    <h4>Tập trung và khởi hành</h4>
                    <p>Tập trung tại điểm hẹn, check-in và lên xe khởi hành đi Bai Chay. Hướng dẫn viên giới thiệu
                        chương trình tour.</p>
                </div>
                <div class="timeline-item">
                    <div class="timeline-time">09:00 - 12:00</div>
                    <h4>Khám phá bãi biển vàng</h4>
                    <p>Tham quan bãi biển Bai Chay với cát vàng mịn, nước biển trong xanh. Tự do tắm biển, chụp ảnh
                        và thư giãn.</p>
                </div>
                <div class="timeline-item">
                    <div class="timeline-time">12:00 - 13:30</div>
                    <h4>Thưởng thức hải sản địa phương</h4>
                    <p>Dùng bữa trưa với các món hải sản tươi ngon đặc trưng của vùng biển Bai Chay.</p>
                </div>
                <div class="timeline-item">
                    <div class="timeline-time">13:30 - 16:00</div>
                    <h4>Hoạt động thể thao nước</h4>
                    <p>Tham gia các hoạt động thể thao nước như kayak, jet ski, parasailing (tùy chọn).</p>
                </div>
                <div class="timeline-item">
                    <div class="timeline-time">16:00 - 17:30</div>
                    <h4>Ngắm hoàng hôn</h4>
                    <p>Thưởng thức cà phê và ngắm hoàng hôn tuyệt đẹp trên biển Bai Chay.</p>
                </div>
                <div class="timeline-item">
                    <div class="timeline-time">17:30 - 18:00</div>
                    <h4>Trở về</h4>
                    <p>Kết thúc chương trình, lên xe về điểm xuất phát. Chia sẻ cảm nghĩ về chuyến đi.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Dự báo thời tiết --}}
    <section class="content-section container">
        <div class="section-header">
            🌤️ Dự báo thời tiết 7 ngày
        </div>
        <div class="section-content">
            <div class="weather-grid">
                <div class="weather-day">
                    <div class="weather-date">T2<br>30/6</div>
                    <div class="weather-icon">☀️</div>
                    <div class="weather-temp">28°C</div>
                </div>
                <div class="weather-day">
                    <div class="weather-date">T3<br>1/7</div>
                    <div class="weather-icon">🌤️</div>
                    <div class="weather-temp">26°C</div>
                </div>
                <div class="weather-day">
                    <div class="weather-date">T4<br>2/7</div>
                    <div class="weather-icon">⛅</div>
                    <div class="weather-temp">25°C</div>
                </div>
                <div class="weather-day">
                    <div class="weather-date">T5<br>3/7</div>
                    <div class="weather-icon">🌧️</div>
                    <div class="weather-temp">24°C</div>
                </div>
                <div class="weather-day">
                    <div class="weather-date">T6<br>4/7</div>
                    <div class="weather-icon">☀️</div>
                    <div class="weather-temp">29°C</div>
                </div>
                <div class="weather-day">
                    <div class="weather-date">T7<br>5/7</div>
                    <div class="weather-icon">☀️</div>
                    <div class="weather-temp">31°C</div>
                </div>
                <div class="weather-day">
                    <div class="weather-date">CN<br>6/7</div>
                    <div class="weather-icon">🌤️</div>
                    <div class="weather-temp">27°C</div>
                </div>
            </div>
            <div style="margin-top: 20px; padding: 15px; background: #e8f5e8; border-radius: 8px;">
                <h4>💡 Khuyến nghị trang phục:</h4>
                <p>Mang theo đồ bơi, kem chống nắng, mũ và áo khoác nhẹ. Dự báo thời tiết thuận lợi cho hoạt động
                    ngoài trời.</p>
            </div>
        </div>
    </section>

    {{-- Câu hỏi hay gặp --}}
    <section class="content-section container">
        <div class="section-header">
            ❓ Câu hỏi thường gặp
        </div>
        <div class="section-content">
            <div class="faq-item">
                <div class="faq-question">
                    Tour có phù hợp với trẻ em không? <span>+</span>
                </div>
                <div class="faq-answer">
                    Tour phù hợp với trẻ em từ 5 tuổi trở lên. Chúng tôi có áo phao an toàn và hướng dẫn viên chuyên
                    nghiệp để đảm bảo an toàn cho các bé.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Chính sách hủy tour như thế nào? <span>+</span>
                </div>
                <div class="faq-answer">
                    Hủy trước 7 ngày: hoàn 100% | Hủy trước 3 ngày: hoàn 70% | Hủy trước 1 ngày: hoàn 30% | Hủy
                    trong ngày: không hoàn tiền.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Nếu thời tiết xấu sẽ xử lý như thế nào? <span>+</span>
                </div>
                <div class="faq-answer">
                    Chúng tôi sẽ liên hệ để dời lịch hoặc hoàn tiền 100%. An toàn của khách hàng là ưu tiên hàng
                    đầu.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Có cần mang theo gì đặc biệt không? <span>+</span>
                </div>
                <div class="faq-answer">
                    Mang theo CMND/CCCD, đồ bơi, kem chống nắng, mũ, áo khoác nhẹ. Các thiết bị thể thao nước đã
                    được cung cấp.
                </div>
            </div>
        </div>
    </section>

    {{-- Thông tin quan trọng --}}
    <section class="content-section container">
        <div class="section-header">
            📋 Thông tin quan trọng
        </div>
        <div class="section-content">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div style="background: #fff3cd; padding: 20px; border-radius: 8px; border-left: 4px solid #ffc107;">
                    <h4 style="color: #856404; margin-bottom: 10px;">⚠️ Lưu ý an toàn</h4>
                    <ul style="color: #856404; padding-left: 20px;">
                        <li>Biết bơi cơ bản hoặc phải mặc áo phao</li>
                        <li>Không uống rượu bia trước khi tham gia hoạt động nước</li>
                        <li>Tuân thủ hướng dẫn của nhân viên cứu hộ</li>
                        <li>Thông báo tình trạng sức khỏe nếu có vấn đề</li>
                    </ul>
                </div>
                <div style="background: #d1ecf1; padding: 20px; border-radius: 8px; border-left: 4px solid #17a2b8;">
                    <h4 style="color: #0c5460; margin-bottom: 10px;">📞 Liên hệ khẩn cấp</h4>
                    <div style="color: #0c5460;">
                        <p><strong>Hotline 24/7:</strong> 1900-BEACH</p>
                        <p><strong>Trưởng tour:</strong> 0987-654-321</p>
                        <p><strong>Y tế:</strong> 0912-345-678</p>
                        <p><strong>Email:</strong> support@beautifulbeaches.vn</p>
                    </div>
                </div>
            </div>

            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 20px;">
                <h4 style="margin-bottom: 15px;">📝 Điều khoản và điều kiện</h4>
                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; font-size: 14px; color: #555;">
                    <div>
                        <strong>✓ Bao gồm:</strong>
                        <ul style="margin: 5px 0; padding-left: 20px;">
                            <li>Xe đưa đón tại điểm hẹn</li>
                            <li>Hướng dẫn viên chuyên nghiệp</li>
                            <li>Bữa trưa buffet hải sản</li>
                            <li>Thiết bị thể thao nước</li>
                            <li>Áo phao an toàn</li>
                            <li>Bảo hiểm du lịch</li>
                        </ul>
                    </div>
                    <div>
                        <strong>✗ Không bao gồm:</strong>
                        <ul style="margin: 5px 0; padding-left: 20px;">
                            <li>Chi phí cá nhân</li>
                            <li>Đồ uống có cồn</li>
                            <li>Tip cho hướng dẫn viên</li>
                            <li>Phí chụp ảnh chuyên nghiệp</li>
                            <li>Đưa đón tại khách sạn</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @vite('resources/js/tourdetail.js')
@endsection