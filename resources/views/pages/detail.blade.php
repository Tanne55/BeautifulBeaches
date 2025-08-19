@php
    $user = Auth::user();
    $layout = (!$user || $user->role === 'user') ? 'layouts.guest' : 'layouts.auth';
@endphp

@extends($layout)

@section('title', 'Chi tiết')

@section('head')
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

    <!-- banner container -->
    <section class="contact-banner mb-5">
        <h1 id="banner-title">Chi tiết bãi biển</h1>
        <img src="/assets/img1/aa.png" alt="" class="brush-bottom" />
    </section>

    <section class=" container container-custom ">
        <div class="row">
            <div class="content-section col-lg-7 p-5">
                <!-- Beach Image Gallery -->
                <div class="position-relative mb-4">
                    @php
                        // Ưu tiên hiển thị: 1) field image trong beaches > 2) primaryImage > 3) ảnh đầu tiên
                        $displayImage = null;
                        $altText = $beach->title;
                        
                        if($beach->image) {
                            // Ưu tiên 1: Field image trong bảng beaches
                            $isAsset = str_starts_with($beach->image, 'http') || str_starts_with($beach->image, '/assets');
                            $displayImage = $isAsset ? $beach->image : asset('storage/' . $beach->image);
                            $altText = $beach->title;
                        } elseif($beach->primaryImage) {
                            // Ưu tiên 2: Primary image từ beach_images
                            $displayImage = $beach->primaryImage->getFullImageUrlAttribute();
                            $altText = $beach->primaryImage->alt_text ?? $beach->title;
                        } elseif($beach->images->first()) {
                            // Ưu tiên 3: Ảnh đầu tiên từ beach_images
                            $displayImage = $beach->images->first()->getFullImageUrlAttribute();
                            $altText = $beach->images->first()->alt_text ?? $beach->title;
                        }
                        
                        $totalImages = $beach->images->count();
                        if($beach->image) $totalImages++; // Thêm 1 nếu có field image
                    @endphp
                    
                    @if($displayImage)
                        <img src="{{ $displayImage }}" 
                             class="img-fluid rounded shadow primary-image" 
                             alt="{{ $altText }}"
                             onclick="openGallery('beach', {{ $beach->id }}, '{{ $beach->title }}')"
                             style="cursor: pointer; height: 300px; width: 100%; object-fit: cover;">
                    @else
                        <img src="/assets/img/default.jpg" 
                             class="img-fluid rounded shadow primary-image" 
                             alt="{{ $beach->title }}"
                             style="height: 300px; width: 100%; object-fit: cover;">
                    @endif
                    
                    @if($totalImages > 0)
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-primary fs-6 px-3 py-2" style="cursor: pointer;" 
                                  onclick="openGallery('beach', {{ $beach->id }}, '{{ $beach->title }}')">
                                <i class="fas fa-images me-2"></i>{{ $totalImages }} ảnh
                            </span>
                        </div>
                    @endif
                </div>
                <h2>Discovering the Beauty of {{ $beach->title }}</h2>
                <p class="short-description">{{ $beach->short_description }}</p>
                <p class="long-description">{{ $beach->long_description }}</p>

                <div class="highlight-quote">
                    <p>{{ $beach->highlight_quote }}</p>
                </div>

                <p class="long-description-2">{{ $beach->long_description_2 }}</p>

                <div class="tags-container">
                    @foreach (json_decode($beach->tags) as $tag)
                        <span class="tag"><i class="fas fa-tag"></i> {{ $tag }}</span>
                    @endforeach
                </div>

                <div class="social-share">
                    <a href="#" class="social-btn facebook">Facebook</a>
                    <a href="#" class="social-btn google-plus">Google+</a>
                    <a href="#" class="social-btn pinterest">Pinterest</a>
                    <a href="#" class="social-btn linkedin">LinkedIn</a>
                    <a href="#" class="social-btn twitter">Twitter</a>
                </div>

                {{-- COMMENT/REVIEW SECTION --}}
                <div class="mt-5">
                    <h4 class="mb-3">Bình luận & Đánh giá</h4>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <form action="{{ route('beaches.review', $beach->id) }}" method="POST" class="mb-4" id="review-form">
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
                        
                        <input type="hidden" name="beach_id" value="{{ $beach->id }}">
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
                                loadMoreBtn.addEventListener('click', function() {
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
                                showLessBtn.addEventListener('click', function() {
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
            </div>

            <div class="col-lg-5 px-5">

                <!-- Author Card -->
                <div class="author-card mb-4">
                    <div class="text-center">
                        <div class="mb-3">
                            <a href="#" class="category-tag">GIỚI THIỆU TÁC GIẢ</a>
                        </div>
                        <img src="{{asset('assets/img1/img_author.jpg')}}" alt="..."
                            class="author-avatar mb-3">
                        <h5>DUY TÂN</h5>
                        <p class="text-muted small">
                            TÂN là một nhà văn du lịch và người đam mê biển, đã khám phá hơn 50 điểm đến ven biển khắp Đông Nam Á.
Với niềm đam mê kể chuyện và nhiếp ảnh, anh chia sẻ những “viên ngọc ẩn”, góc nhìn văn hóa và mẹo du lịch thực tế để giúp mọi người lên kế hoạch cho những chuyến phiêu lưu bên bờ biển khó quên!
                        </p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="text-muted social-icon-hover"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="text-muted social-icon-hover"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-muted social-icon-hover"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-muted social-icon-hover"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-muted social-icon-hover"><i class="fab fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Related Tours -->
                <div class="bg-white p-3 rounded shadow-sm mb-4">
                    <h6 class="border-bottom pb-2 mb-3">RELATED TOURS</h6>

                    @php
                        $relatedTours = \App\Models\Tour::with('beach')
                            ->where('beach_id', $beach->id)
                            ->where('status', 'confirmed')
                            ->take(5)
                            ->get();
                    @endphp

                    @forelse($relatedTours as $tour)
                        <div class="recent-post-item {{ $loop->last ? 'border-0' : '' }}">
                            @php
                                $img = $tour->image ?? '';
                                $beachImg = ($tour->beach && $tour->beach->image) ? $tour->beach->image : '';
                                $isAsset = $img && (str_starts_with($img, 'http') || str_starts_with($img, '/assets'));
                                $isBeachAsset = $beachImg && (str_starts_with($beachImg, 'http') || str_starts_with($beachImg, '/assets'));
                            @endphp
                            
                            @if($img)
                                <img src="{{ $isAsset ? $img : asset('storage/' . $img) }}" 
                                     alt="{{ $tour->title }}" class="recent-post-img">
                            @elseif($beachImg)
                                <img src="{{ $isBeachAsset ? $beachImg : asset('storage/' . $beachImg) }}" 
                                     alt="{{ $tour->title }}" class="recent-post-img">
                            @else
                                <img src="https://via.placeholder.com/120x90?text=No+Image" 
                                     alt="No image" class="recent-post-img">
                            @endif
                            
                            <div>
                                <h6 class="mb-1 fs-6">
                                    <a href="{{ route('tour.show', $tour->id) }}" class="text-decoration-none text-dark">
                                        {{ $tour->title }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                   <span class="text-danger fw-bold">{{ number_format($tour->current_price, 0, ',', '.') }} đ
                                            </span>
                                        @if($tour->prices && $tour->prices->first() && $tour->prices->first()->discount && $tour->prices->first()->discount < $tour->current_price)
                                            <small class="text-decoration-line-through text-muted">
                                                {{ number_format($tour->prices->first()->discount, 0, ',', '.') }} đ
                                            </small>
                                        @endif  | 
                                    {{ $tour->duration_days }} ngày | 
                                    {{ $tour->ceo->name ?? 'Unknown' }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted text-center py-3">
                            <i class="fas fa-info-circle"></i>
                            <br>Chưa có tour nào cho bãi biển này
                        </div>
                    @endforelse
                </div>

                <!-- Social Links -->
                <div class="bg-white p-3 rounded shadow-sm">
                    <h6 class="border-bottom pb-2 mb-3">SOCIAL LINKS</h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="#" class="social-btn facebook w-100 justify-content-center">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="social-btn pinterest w-100 justify-content-center">
                                <i class="fab fa-pinterest"></i> Pinterest
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="social-btn twitter w-100 justify-content-center">
                                <i class="fab fa-twitter"></i> Twitter
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="social-btn linkedin w-100 justify-content-center">
                                <i class="fab fa-linkedin-in"></i> LinkedIn
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="social-btn google w-100 justify-content-center">
                                <i class="fab fa-google"></i> Google+
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="social-btn instagram w-100 justify-content-center">
                                <i class="fab fa-instagram"></i> Instagram
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                'main': 'Ảnh chính beach',
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

@endsection