@php
    $user = Auth::user();
    $layout = (!$user || $user->role === 'user') ? 'layouts.guest' : 'layouts.auth';
@endphp

@extends($layout)

@section('title', 'Chi tiết')

@section('content')

    <!-- banner container -->
    <section class="contact-banner ">
        <h1 id="banner-title">Chi tiết bãi biển</h1>
        <img src="/assets/img1/aa.png" alt="" class="brush-bottom" />
    </section>

    <section class=" container">
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
                    @auth
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
                            <div class="mb-2">
                                <label for="comment" class="form-label">Bình luận:</label>
                                <textarea name="comment" id="comment" class="form-control" rows="2" required></textarea>
                            </div>
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <input type="hidden" name="beach_id" value="{{ $beach->id }}">
                            <button type="submit" class="btn btn-primary">Gửi bình luận</button>
                        </form>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
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
                            });
                        </script>
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
            </div>

            <div class="col-lg-5 p-5">

                <!-- Author Card -->
                <div class="author-card mb-4">
                    <div class="text-center">
                        <div class="mb-3">
                            <a href="#" class="category-tag">ABOUT AUTHOR</a>
                        </div>
                        <img src="https://demo.bosathemes.com/html/travele/assets/images/img21.jpg" alt="..."
                            class="author-avatar mb-3">
                        <h5>James Watson</h5>
                        <p class="text-muted small">
                            James is a travel writer and beach enthusiast who has explored over 50 coastal destinations
                            across Southeast Asia.
                            With a passion for storytelling and photography, he shares hidden gems, cultural insights,
                            and practical travel tips
                            to help others plan unforgettable seaside adventures!!
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

                <!-- Recent Posts -->
                <div class="bg-white p-3 rounded shadow-sm mb-4">
                    <h6 class="border-bottom pb-2 mb-3">RECENT POSTS</h6>

                    <div class="recent-post-item">
                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=120&h=90&fit=crop"
                            alt="Post" class="recent-post-img">
                        <div>
                            <h6 class="mb-1 fs-6">Normally I'm going to be free and available to plan.</h6>
                            <small class="text-muted">August 17, 2020 | by Demokeam19</small>
                        </div>
                    </div>

                    <div class="recent-post-item">
                        <img src="https://images.unsplash.com/photo-1547036967-23d11aacaee0?w=120&h=90&fit=crop" alt="Post"
                            class="recent-post-img">
                        <div>
                            <h6 class="mb-1 fs-6">Exploring the beauty of the great nature</h6>
                            <small class="text-muted">August 17, 2020 | by Demokeam19</small>
                        </div>
                    </div>

                    <div class="recent-post-item">
                        <img src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=120&h=90&fit=crop"
                            alt="Post" class="recent-post-img">
                        <div>
                            <h6 class="mb-1 fs-6">Let's start adventure with local tour guide</h6>
                            <small class="text-muted">August 17, 2020 | by Demokeam19</small>
                        </div>
                    </div>

                    <div class="recent-post-item">
                        <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=120&h=90&fit=crop"
                            alt="Post" class="recent-post-img">
                        <div>
                            <h6 class="mb-1 fs-6">Planning to go ideal measured to new places</h6>
                            <small class="text-muted">August 17, 2020 | by Demokeam19</small>
                        </div>
                    </div>

                    <div class="recent-post-item border-0">
                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=120&h=90&fit=crop"
                            alt="Post" class="recent-post-img">
                        <div>
                            <h6 class="mb-1 fs-6">Take only memories, leave only footprints</h6>
                            <small class="text-muted">August 17, 2020 | by Demokeam19</small>
                        </div>
                    </div>
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