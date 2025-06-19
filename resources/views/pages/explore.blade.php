@extends('layouts.app')

@section('title', 'Explore')

@section('content')
    <!-- banner container -->
    <section class="contact-banner mb-5">
        <div class="overlay">
            <h1 id="banner-title">Explore Tours</h1>
        </div>
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
                        <span class="tours-count" id="tours-count">0 Tours found</span>
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
                                <img src="{{ $beach->image ?? '/assets/img/default.jpg' }}" alt="{{ $beach->title ?? 'Tour' }}">
                                <div class="feature-badge">Feature</div>
                                <button class="heart-btn">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                        </path>
                                    </svg>
                                </button>
                                <div class="media-buttons">
                                    <button class="media-btn">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                            <polygon points="5,3 19,12 5,21" />
                                        </svg>
                                        View Video
                                    </button>
                                    <button class="media-btn">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21,15 16,10 5,21" />
                                        </svg>
                                        10 Photos
                                    </button>
                                </div>
                            </div>

                            <div class="tour-content">
                                <div class="tour-main">
                                    <h2 class="tour-title">{{ $beach->title ?? 'Untitled Tour' }}</h2>

                                    <div class="tour-region">
                                        <svg class="location-icon" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                            <circle cx="12" cy="10" r="3" />
                                        </svg>
                                        {{ $beach->region ?? 'Unknown Region' }}
                                    </div>

                                    <div class="tour-rating">
                                        <div class="stars">
                                            {{-- Render số sao tuỳ logic --}}
                                            @for ($i = 0; $i < ($beach->rating ?? 0); $i++)
                                                ⭐
                                            @endfor
                                        </div>
                                        <span class="reviews">{{ $beach->reviews ?? 0 }} Reviews</span>
                                    </div>

                                    <p class="tour-description">{{ $beach->short_description ?? 'No description available' }}
                                    </p>
                                    <p class="cursor-pointer"><i class="bi bi-tags-fill"></i> {{ is_array($beach->tags) ? implode(', ', $beach->tags) : $beach->tags }}
                                    </p>
                                </div>

                                <div class="tour-footer">
                                    <div class="tour-meta">
                                        <div class="meta-item">
                                            <svg class="meta-icon" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                                <circle cx="9" cy="7" r="4" />
                                                <path d="m22 21-3-3" />
                                            </svg>
                                            {{ $beach->capacity ?? 'N/A' }}
                                        </div>
                                        <div class="meta-item">
                                            <svg class="meta-icon" viewBox="0 0 24 24" fill="currentColor">
                                                <circle cx="12" cy="12" r="10" />
                                                <polyline points="12,6 12,12 16,14" />
                                            </svg>
                                            {{ $beach->duration ?? 'N/A' }}
                                        </div>
                                    </div>

                                    <div class="tour-price">
                                        <div>
                                            <span class="price-current">${{ $beach->price ?? 0 }}</span>
                                            <span class="price-original">${{ $beach->original_price ?? 0 }}</span>
                                        </div>
                                        <button class="explore-btn" data-id="{{ $beach->id }}">Explore</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>
@endsection