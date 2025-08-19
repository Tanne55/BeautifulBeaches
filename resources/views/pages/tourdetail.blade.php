@php
    $user = Auth::user();
    $layout = (!$user || $user->role === 'user') ? 'layouts.guest' : 'layouts.auth';
@endphp

@extends($layout)

@section('head')
    @vite('resources/css/calendar.css')
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    
    <!-- Gallery Modal CSS -->
    <style>
        .primary-image {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        .primary-image:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        /* Modal z-index cao hơn header */
        .modal {
            z-index: 9999 !important;
        }
        
        .modal-backdrop {
            z-index: 9998 !important;
        }
        
        .swiper {
            width: 100%;
            height: 70vh;
        }
        
        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .swiper-slide img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .swiper-button-next, .swiper-button-prev {
            color: #fff;
            background: rgba(0,0,0,0.5);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            z-index: 10;
        }
        
        .swiper-pagination {
            z-index: 10;
        }
        
        .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: rgba(255,255,255,0.8);
        }
        
        .swiper-pagination-bullet-active {
            background: #fff;
        }
        
        .modal-header {
            border-bottom: none;
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 11;
        }
        
        .modal-body {
            padding: 0;
        }
        
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .image-caption {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 15px;
            border-radius: 8px;
            z-index: 10;
        }
        
        .image-type-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 10;
        }
        
        .image-counter {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            z-index: 10;
            font-size: 14px;
        }
        
        /* Đảm bảo navbar không che modal */
        .navbar {
            z-index: 1030 !important;
        }
        
        /* Animation cho modal */
        .modal.fade .modal-dialog {
            transform: scale(0.8);
            transition: transform 0.3s ease-out;
        }
        
        .modal.show .modal-dialog {
            transform: scale(1);
        }
    </style>
@endsection

@section('content')
    <div class="container my-5 container-custom">
        <h2 class="mb-4 text-center fw-bold">{{ $tour->title }}</h2>

        <div class="row">
            <div class="col-md-6">
                <!-- Tour Image Gallery -->
                <div class="position-relative mb-4">
                    @php
                        // Ưu tiên hiển thị: 1) field image trong tours > 2) primaryImage > 3) ảnh đầu tiên
                        $displayImage = null;
                        $altText = $tour->title;
                        
                        if($tour->image) {
                            // Ưu tiên 1: Field image trong bảng tours
                            $isAsset = str_starts_with($tour->image, 'http') || str_starts_with($tour->image, '/assets');
                            $displayImage = $isAsset ? $tour->image : asset('storage/' . (str_starts_with($tour->image, 'tours/') ? $tour->image : 'tours/' . $tour->image));
                            $altText = $tour->title;
                        } elseif($tour->primaryImage) {
                            // Ưu tiên 2: Primary image từ tour_images
                            $displayImage = $tour->primaryImage->getFullImageUrlAttribute();
                            $altText = $tour->primaryImage->alt_text ?? $tour->title;
                        } elseif($tour->images->first()) {
                            // Ưu tiên 3: Ảnh đầu tiên từ tour_images
                            $displayImage = $tour->images->first()->getFullImageUrlAttribute();
                            $altText = $tour->images->first()->alt_text ?? $tour->title;
                        } elseif($tour->beach && $tour->beach->primaryImage) {
                            // Fallback: Primary image của beach
                            $displayImage = $tour->beach->primaryImage->getFullImageUrlAttribute();
                            $altText = $tour->beach->primaryImage->alt_text ?? $tour->title;
                        } elseif($tour->beach && $tour->beach->images->first()) {
                            // Fallback: Ảnh đầu tiên của beach
                            $displayImage = $tour->beach->images->first()->getFullImageUrlAttribute();
                            $altText = $tour->beach->images->first()->alt_text ?? $tour->title;
                        }
                        
                        $totalImages = $tour->images->count();
                        if($tour->image) $totalImages++; // Thêm 1 nếu có field image
                    @endphp
                    
                    @if($displayImage)
                        <img src="{{ $displayImage }}" 
                             class="img-fluid rounded shadow primary-image" 
                             alt="{{ $altText }}"
                             onclick="openGallery('tour', {{ $tour->id }}, '{{ $tour->title }}')"
                             style="cursor: pointer; height: 300px; width: 100%; object-fit: cover;">
                    @else
                        <img src="https://via.placeholder.com/600x400?text=No+Image" 
                             class="img-fluid rounded shadow primary-image"
                             alt="No image"
                             style="height: 300px; width: 100%; object-fit: cover;">
                    @endif
                    
                    @if($totalImages > 0)
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-success fs-6 px-3 py-2" style="cursor: pointer;" 
                                  onclick="openGallery('tour', {{ $tour->id }}, '{{ $tour->title }}')">
                                <i class="fas fa-images me-2"></i>{{ $totalImages }} ảnh
                            </span>
                        </div>
                    @endif
                </div>
                <div class="my-5">
                    @if($tour->beach)
                        <h3 class="mb-3">{{ $tour->beach->short_description }}</h3>
                        <div class="fst-italic">
                            <p>{{ $tour->beach->detail->long_description ?? '' }}</p>
                            <p>{{ $tour->beach->detail->long_description2 ?? '' }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>Khu vực</th>
                            <td>{{ $tour->beach?->region->name }}</td>
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
                                @php
                                    $today = now()->format('Y-m-d');
                                    $price = $tour->prices->where('start_date', '<=', $today)
                                        ->where('end_date', '>=', $today)
                                        ->first();
                                    if (!$price) {
                                        $price = $tour->prices->first();
                                    }
                                @endphp
                                @if($price)
                                    @if($price->discount && $price->discount > 0)
                                        <span class="text-danger fw-bold">{{ number_format($price->final_price, 0, ',', '.') }}
                                            tr vnđ</span>
                                        <small class="text-decoration-line-through text-muted">
                                            {{ number_format($price->price, 0, ',', '.') }} tr vnđ
                                        </small>
                                    @else
                                        <span class="text-danger fw-bold">{{ number_format($price->price, 0, ',', '.') }} đ</span>
                                    @endif
                                @else
                                    <span class="text-danger fw-bold">Liên hệ</span>
                                @endif
                            </td>
                        </tr>
                        @if($tour->detail)
                            @if($tour->detail->departure_dates)
                                <tr>
                                    <th>Ngày khởi hành</th>
                                    <td>
                                        <div class="departure-dates-tags">
                                            @foreach($tour->detail->departure_dates as $departureDate)
                                                @php
                                                    $date = \Carbon\Carbon::parse($departureDate);
                                                    $isPast = $date->isPast();
                                                @endphp
                                                <span class="badge {{ $isPast ? 'bg-secondary' : 'bg-primary' }} me-1 mb-1">
                                                    <i class="bi bi-calendar-event me-1"></i>
                                                    {{ $date->format('d/m/Y') }}
                                                    @if($isPast)
                                                        <small>(Đã qua)</small>
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Có {{ count($tour->detail->departure_dates) }} ngày khởi hành khả dụ
                                        </small>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <th>Giờ trở về</th>
                                <td>{{ \Carbon\Carbon::parse($tour->detail->return_time)->format('d/m/Y H:i') }}</td>
                            </tr>
                            @if($tour->detail->included_services)
                                <tr>
                                    <th>Dịch vụ bao gồm</th>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach($tour->detail->included_services ?? [] as $service)
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
                                            @foreach($tour->detail->excluded_services ?? [] as $service)
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
                                            @foreach($tour->detail->highlights ?? [] as $highlight)
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
                    <a href="{{ route('tour.booking.form', $tour->id) }}" 
                       class="btn btn-success btn-lg w-100 mb-3">
                        <i class="bi bi-calendar-plus me-2"></i>Đặt tour ngay
                    </a>
                    <small class="text-muted d-block text-center">
                        <i class="bi bi-info-circle me-1"></i>
                        Bạn sẽ chọn ngày khởi hành cụ thể ở trang tiếp theo
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="container-custom container">
        {{-- COMMENT/REVIEW SECTION --}}
        <div class="mb-5">
            <h4 class="mb-3">Bình luận & Đánh giá</h4>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

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

                @auth
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                @else
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="guest_name" class="form-label">Tên của bạn:</label>
                            <input type="text" name="guest_name" id="guest_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="guest_email" class="form-label">Email:</label>
                            <input type="email" name="guest_email" id="guest_email" class="form-control" required>
                        </div>
                    </div>
                @endauth

                <div class="mb-2">
                    <label for="comment" class="form-label">Bình luận:</label>
                    <textarea name="comment" id="comment" class="form-control" rows="2" required></textarea>
                </div>

                <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                <button type="submit" class="btn btn-primary">Gửi bình luận</button>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Star rating functionality
                    const stars = document.querySelectorAll('#star-rating .star-icon');
                    const ratingInput = document.getElementById('rating-input');
                    let currentRating = 5;

                    function setStars(rating) {
                        stars.forEach((star, idx) => {
                            if (idx < rating) {
                                star.classList.add('text-warning');
                            } else {
                                star.classList.remove('text-warning');
                            }
                        });
                    }

                    setStars(currentRating);
                    stars.forEach((star, idx) => {
                        star.addEventListener('mouseenter', () => setStars(idx + 1));
                        star.addEventListener('mouseleave', () => setStars(currentRating));
                        star.addEventListener('click', () => {
                            currentRating = idx + 1;
                            ratingInput.value = currentRating;
                            setStars(currentRating);
                        });
                    });

                    // Load more reviews functionality
                    const loadMoreBtn = document.getElementById('load-more-reviews');
                    const showLessBtn = document.getElementById('show-less-reviews');
                    const remainingCountSpan = document.getElementById('remaining-count');

                    if (loadMoreBtn) {
                        loadMoreBtn.addEventListener('click', function () {
                            const hiddenReviews = document.querySelectorAll('.review-item.d-none');
                            const showCount = Math.min(5, hiddenReviews.length);

                            for (let i = 0; i < showCount; i++) {
                                hiddenReviews[i].classList.remove('d-none');
                            }

                            const stillHidden = document.querySelectorAll('.review-item.d-none').length;

                            if (stillHidden === 0) {
                                loadMoreBtn.style.display = 'none';
                            } else {
                                remainingCountSpan.textContent = stillHidden;
                            }

                            showLessBtn.style.display = 'inline-block';
                        });
                    }

                    if (showLessBtn) {
                        showLessBtn.addEventListener('click', function () {
                            const allReviews = document.querySelectorAll('.review-item');

                            for (let i = 3; i < allReviews.length; i++) {
                                allReviews[i].classList.add('d-none');
                            }

                            const totalHidden = allReviews.length - 3;
                            if (totalHidden > 0) {
                                remainingCountSpan.textContent = totalHidden;
                                loadMoreBtn.style.display = 'inline-block';
                            }
                            showLessBtn.style.display = 'none';
                        });
                    }
                });
            </script>

            <div class="review-list mt-4">
                @forelse($reviews as $review)
                    <div class="border-bottom pb-2 mb-2 review-item {{ $loop->index >= 3 ? 'd-none' : '' }}">
                        <div class="d-flex align-items-center mb-1">
                            <strong>
                                @if($review->user)
                                    {{ $review->user->name }}
                                    @if($review->user->role === 'admin')
                                        <span class="badge bg-danger ms-2">Admin</span>
                                    @elseif($review->user->role === 'ceo')
                                        <span class="badge bg-primary ms-2">CEO</span>
                                    @elseif($review->user->role === 'user')
                                        <span class="badge bg-secondary ms-2">User</span>
                                    @endif
                                @else
                                    {{ $review->guest_name ?? 'Khách' }}
                                    <span class="badge bg-info ms-2">Khách</span>
                                @endif
                            </strong>
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

                @if(count($reviews) > 3)
                    <div class="text-center mt-3">
                        <button id="load-more-reviews" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-chevron-down me-1"></i>
                            Đọc thêm (<span id="remaining-count">{{ count($reviews) - 3 }}</span> bình luận)
                        </button>
                        <button id="show-less-reviews" class="btn btn-outline-secondary btn-sm" style="display: none;">
                            <i class="fas fa-chevron-up me-1"></i>
                            Thu gọn
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- Lịch trình chi tiết --}}
        <section class="content-section">
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
        <section class="content-section">
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

        {{-- Thông tin quan trọng --}}
        <section class="content-section">
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

    </div>

    <!-- Gallery Modal -->
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="galleryModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Image Counter -->
                    <div class="image-counter" id="imageCounter">
                        1 / 1
                    </div>
                    
                    <!-- Swiper -->
                    <div class="swiper gallerySwiper">
                        <div class="swiper-wrapper" id="swiperWrapper">
                            <!-- Slides will be added dynamically -->
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    
    <script>
        let swiper;
        
        function openGallery(type, id, title) {
            // Set modal title
            document.getElementById('galleryModalTitle').textContent = title;
            
            // Show loading state
            const wrapper = document.getElementById('swiperWrapper');
            wrapper.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            
            // Show modal immediately
            const modal = new bootstrap.Modal(document.getElementById('galleryModal'));
            modal.show();
            
            // Fetch images via AJAX
            fetch(`/api/gallery/${type}/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.images.length > 0) {
                        loadGalleryImages(data.images);
                    } else {
                        wrapper.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 text-muted"><div class="text-center"><i class="fas fa-image fa-3x mb-3"></i><br><p>Không có ảnh nào để hiển thị</p></div></div>';
                        document.getElementById('imageCounter').textContent = '0 / 0';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    wrapper.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 text-danger"><div class="text-center"><i class="fas fa-exclamation-triangle fa-3x mb-3"></i><br><p>Có lỗi xảy ra khi tải ảnh</p></div></div>';
                    document.getElementById('imageCounter').textContent = '0 / 0';
                });
        }
        
        function loadGalleryImages(images) {
            const wrapper = document.getElementById('swiperWrapper');
            wrapper.innerHTML = '';
            
            images.forEach((image, index) => {
                const slide = document.createElement('div');
                slide.className = 'swiper-slide';
                slide.innerHTML = `
                    <div class="position-relative w-100 h-100">
                        <img src="${image.image_url}" alt="${image.alt_text || ''}" class="w-100 h-100 object-fit-cover" loading="lazy">
                        
                        ${image.image_type ? `
                        <div class="image-type-badge">
                            <span class="badge bg-info">${getImageTypeLabel(image.image_type)}</span>
                        </div>
                        ` : ''}
                        
                        ${image.caption ? `
                        <div class="image-caption">
                            <p class="mb-0">${image.caption}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
                wrapper.appendChild(slide);
            });
            
            // Update counter
            updateImageCounter(1, images.length);
            
            // Initialize/Update Swiper
            if (swiper) {
                swiper.destroy();
            }
            
            swiper = new Swiper('.gallerySwiper', {
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                keyboard: {
                    enabled: true,
                },
                mousewheel: {
                    forceToAxis: true,
                },
                loop: images.length > 1,
                autoplay: false,
                speed: 300,
                on: {
                    slideChange: function () {
                        updateImageCounter(this.realIndex + 1, images.length);
                    }
                }
            });
        }
        
        function updateImageCounter(current, total) {
            document.getElementById('imageCounter').textContent = `${current} / ${total}`;
        }
        
        function getImageTypeLabel(type) {
            const labels = {
                'hero': 'Ảnh chính',
                'main': 'Ảnh chính tour',
                'gallery': 'Thư viện',
                'thumbnail': 'Thu nhỏ',
                'detail': 'Chi tiết',
                'panorama': 'Toàn cảnh',
                'activity': 'Hoạt động',
                'location': 'Địa điểm'
            };
            return labels[type] || type;
        }
        
        // Clean up swiper when modal is closed
        document.getElementById('galleryModal').addEventListener('hidden.bs.modal', function () {
            if (swiper) {
                swiper.destroy();
                swiper = null;
            }
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('galleryModal');
            if (modal.classList.contains('show')) {
                switch(e.key) {
                    case 'Escape':
                        bootstrap.Modal.getInstance(modal).hide();
                        break;
                    case 'ArrowLeft':
                        if (swiper) swiper.slidePrev();
                        break;
                    case 'ArrowRight':
                        if (swiper) swiper.slideNext();
                        break;
                }
            }
        });
    </script>
    
    @vite('resources/js/tourdetail.js')
@endsection