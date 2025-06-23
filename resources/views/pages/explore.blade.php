@php
    $layout = Auth::check() ? 'layouts.auth' : 'layouts.guest';
@endphp


@extends($layout)

@section('title', 'Explore')

@section('content')
    <!-- banner container -->
    <section class="contact-banner mb-5 ">
        <h1 id="banner-title">Explore Tour</h1>
        <img src="/assets/img1/aa.png" alt="" class="brush-bottom" />
    </section>




    <!-- Main1 content -->
    <section class="my-5">
        <div class="main-container">
            <!-- Search Sidebar -->
            <div class="search-sidebar">
                <h2 class="search-title">Search Tours</h2>

                <div class="search-group">
                    <label class="search-label" for="search-title">Search by Title</label>
                    <input type="text" id="search-title" class="search-input" placeholder="Enter tour title..."
                        oninput="filterTours()">
                </div>

                <div class="search-group">
                    <label class="search-label" for="search-region">Region</label>
                    <select id="search-region" class="search-select" onchange="filterTours()">
                        <option value="">All Regions</option>
                    </select>
                </div>

                <div class="search-group">
                    <label class="search-label" for="search-tag">Activity</label>
                    <select id="search-tag" class="search-select" onchange="filterTours()">
                        <option value="">All Activities</option>
                    </select>
                </div>

                <button class="clear-filters" onclick="clearFilters()">Clear Filters</button>
            </div>

            <!-- Tours Container -->
            <div class="tours-container">
                <div class="tours-header">
                    <div>
                        <span class="tours-count" id="tours-count">15 Tours found</span>
                        <a href="#" class="clear-link" onclick="clearFilters()">Clear filter</a>
                    </div>
                    <div class="sort-controls">
                        <span class="sort-label">Sort By</span>
                        <select class="search-select" style="width: auto; min-width: 120px;">
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                            <option>Rating</option>
                            <option>Duration</option>
                        </select>
                        <div class="view-toggle">
                            <button class="view-btn active">≣</button>
                            <button class="view-btn">⊞</button>
                        </div>
                    </div>
                </div>

                <div class="tours-list" id="tours-list">
                    <!-- Tours will be rendered here -->
                    @foreach($beaches as $beach)
                        <div class="tour-card">
                            <div class="tour-image">
                                <img src="{{ $beach['image'] ?? '/assets/img/default.jpg' }}" alt="{{ $beach['title'] ?? 'Tour' }}">
                                <div class="feature-badge">Feature</div>
                            </div>
                            <div class="tour-content">
                                <div class="tour-main">
                                    <h2 class="tour-title">{{ $beach['title'] ?? 'Untitled Tour' }}</h2>
                                    <div class="tour-region">
                                        <svg class="location-icon" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                            <circle cx="12" cy="10" r="3" />
                                        </svg>
                                        {{ $beach['region'] ?? 'Unknown Region' }}
                                    </div>
                                    <p class="tour-description mb-2">
                                        {{ $beach['short_description'] ?? '' }}
                                    </p>
                                    @if (!empty($beach['tags']))
                                        <div>
                                            @foreach ($beach['tags'] as $tag)
                                                <span class="tag"><i class="fas fa-tag"></i> {{ $tag }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    <button class="explore-btn" data-id="{{ $beach['id'] }}" onclick="window.location.href='{{ route('beaches.show', $beach['id']) }}'">Explore</button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>
@endsection