@php
    $layout = Auth::check() ? 'layouts.auth' : 'layouts.guest';
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
                <img src="{{ $beach->image }}" alt="{{ $beach->title }}" class="img-fluid rounded mb-2">
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