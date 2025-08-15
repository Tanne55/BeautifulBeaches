@php
    $user = Auth::user();
    $layout = (!$user || $user->role === 'user') ? 'layouts.guest' : 'layouts.auth';
@endphp

@extends($layout)

@section('title', 'Chi tiết')

@section('content')

    <!-- banner container -->
    <section class="contact-banner mb-5">
        <h1 id="banner-title">Chi tiết bãi biển</h1>
        <img src="/assets/img1/aa.png" alt="" class="brush-bottom" />
    </section>

    <section class=" container container-custom ">
        <div class="row">
            <div class="content-section col-lg-7 p-5">
                @php
                    $img = $beach->image ?? '';
                    $isAsset = $img && (str_starts_with($img, 'http') || str_starts_with($img, '/assets'));
                @endphp
                <img src="{{ $img ? ($isAsset ? $img : asset('storage/' . $img)) : '/assets/img/default.jpg' }}"
                    alt="{{ $beach->title }}" class="img-fluid rounded mb-2">
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


@endsection