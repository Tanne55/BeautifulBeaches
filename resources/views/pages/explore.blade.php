@php
    $layout = Auth::check() ? 'layouts.auth' : 'layouts.guest';
@endphp


@extends($layout)

@section('title', 'Explore')

@section('content')
    <!-- banner container -->
    <section class="contact-banner mb-5 ">
        <h1 id="banner-title">Explore Beaches</h1>
        <img src="/assets/img1/aa.png" alt="" class="brush-bottom" />
    </section>




    <!-- Main1 content -->
    <section class="my-5 container">
        <div class="main-container">
            <!-- Search Sidebar -->
            <div class="search-sidebar">
                <h2 class="search-title">Search Beachs</h2>

                <div class="search-group">
                    <label class="search-label" for="search-title">Search by Title</label>
                    <input type="text" id="search-title" class="search-input" placeholder="Enter tour title..."
                        oninput="filterTours()">
                </div>

                <div class="search-group">
                    <label class="search-label" for="search-region">Region</label>
                    <select id="search-region" class="search-select" onchange="filterTours()">
                        <option value="">All Regions</option>
                        @php
                            $regions = collect($beaches)->pluck('region')->filter()->unique();
                        @endphp
                        @foreach($regions as $region)
                            <option value="{{ strtolower($region) }}">{{ $region }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="search-group">
                    <label class="search-label" for="search-tag">Activity</label>
                    <select id="search-tag" class="search-select" onchange="filterTours()">
                        <option value="">All Activities</option>
                        @php
                            $tags = collect($beaches)->pluck('tags')->flatten()->filter()->unique();
                        @endphp
                        @foreach($tags as $tag)
                            <option value="{{ strtolower($tag) }}">{{ $tag }}</option>
                        @endforeach
                    </select>
                </div>

                <button class="clear-filters" onclick="clearFilters()">Clear Filters</button>
            </div>

            <!-- Tours Container -->
            <div class="tours-container">
                <div class="mb-4">
                    <div class="beach-header p-4 shadow position-relative">
                        <div class="floating-elements">
                            <div class="floating-circle"></div>
                            <div class="floating-circle"></div>
                            <div class="floating-circle"></div>
                        </div>
                        <div class="text-center position-relative z-2" style="color: #1e3a8a;">
                            <h1 class="fw-bold header-title fadeInUp">🏖️ Khám Phá Bãi Biển Tuyệt Đẹp</h1>
                            <p class="header-subtitle fadeInUp">Tìm kiếm những điểm đến biển xanh cát trắng hoàn hảo cho
                                chuyến du lịch của bạn</p>
                        </div>
                    </div>
                </div>



                <div class="tours-list" id="tours-list">
                    <!-- Tours will be rendered here -->
                    @foreach($beaches as $beach)
                        <div class="tour-card" data-title="{{ strtolower($beach['title']) }}"
                            data-region="{{ strtolower($beach['region']) }}"
                            data-tags="{{ strtolower(implode(',', $beach['tags'] ?? [])) }}">
                            <div class="tour-image">
                                <img src="{{ $beach['image'] ?? '/assets/img/default.jpg' }}"
                                    alt="{{ $beach['title'] ?? 'Tour' }}">
                                <div class="feature-badge">Feature</div>
                            </div>
                            <div class="tour-content">
                                <div class="tour-main">
                                    <h2 class="tour-title">{{ $beach['title'] ?? 'Untitled Tour' }}</h2>
                                    <div class="tour-region my-3">
                                        <svg class="location-icon" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                            <circle cx="12" cy="10" r="3" />
                                        </svg>
                                        {{ $beach['region'] ?? 'Unknown Region' }}
                                    </div>
                                    <p class="tour-description my-3">
                                        {{ $beach['short_description'] ?? '' }}
                                    </p>
                                    @if (!empty($beach['tags']))
                                        <div class="my-3">
                                            @foreach ($beach['tags'] as $tag)
                                                <span class="tag m-0"><i class="fas fa-tag"></i> {{ $tag }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-end">
                                        <button class="explore-btn" data-id="{{ $beach['id'] }}"
                                            onclick="window.location.href='{{ route('beaches.show', $beach['id']) }}'">Explore</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>
    @vite('resources/js/explore.js')
@endsection