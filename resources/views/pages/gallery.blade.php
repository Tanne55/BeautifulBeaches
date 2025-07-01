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
                <h1 class="hero-title">BEACH PARADISE</h1>
                <p class="hero-subtitle">Discover the Paradise Beauty of the Sea</p>
                <div class="hero-description">
                    Join us on a journey through the most stunning beaches in the world. From romantic sunsets over the
                    East Sea to the crystal-clear waters of tropical coasts, each photo tells its own story of nature's
                    wondrous beauty.
                </div>
                <p class="hero-quote">The sea is the mirror of the sky, where the soul finds peace</p>
                <div class="hero-description">
                    This collection is curated from the most beautiful beaches across 20 regions, offering you a
                    breathtaking visual experience and inspiration for your next getaway.
                </div>
            </div>

            <div class="filter-tabs">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="sunset">Sunset</button>
                <button class="filter-btn" data-filter="tropical">Tropical</button>
                <button class="filter-btn" data-filter="waves">Waves</button>
                <button class="filter-btn" data-filter="paradise">Paradise</button>
            </div>

            <div class="stats-section">
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">High-quality photos</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">50+</span>
                    <span class="stat-label">Beautiful beaches</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">20+</span>
                    <span class="stat-label">Countries</span>
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
        <div id="imageModal" class="gallery-modal">
            <div class="gallery-modal-content">
                <span class="gallery-close">&times;</span>
                <img id="modalImage" src="" alt="">
            </div>
        </div>
    </section>

@endsection