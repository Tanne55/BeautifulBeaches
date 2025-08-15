@php
    $user = Auth::user();
    $layout = (!$user || $user->role === 'user') ? 'layouts.guest' : 'layouts.auth';
@endphp

@extends($layout)

@section('title', 'Explore')

@section('content')
    <!-- banner container -->
    <section class="contact-banner mb-5 ">
        <h1 id="banner-title">Trải nghiệm bãi biển</h1>
        <img src="/assets/img1/aa.png" alt="" class="brush-bottom" />
    </section>




    <!-- Main1 content -->
    <section class="my-5 container">
        <div class="main-container">
            <!-- Search Sidebar -->
            <div class="search-sidebar">
                <h2 class="search-title">Tìm Kiếm Bãi Biển</h2>

                <div class="search-group">
                    <label class="search-label" for="search-title">Tìm theo tên</label>
                    <input type="text" id="search-title" class="search-input" placeholder="Nhập tên bãi biển..."
                        oninput="filterTours()">
                </div>

                <div class="search-group">
                    <label class="search-label" for="search-region">Khu vực</label>
                    <select id="search-region" class="search-select" onchange="filterTours()">
                        <option value="">--Tất cả--</option>
                        @foreach($regions as $region)
                            <option value="{{ strtolower($region->name) }}">{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="search-group">
                    <label class="search-label" for="search-tag">Từ khóa</label>
                    <select id="search-tag" class="search-select" onchange="filterTours()">
                        <option value="">--Tất cả--</option>
                        @php
                            $tags = collect($beaches)->pluck('tags')->flatten()->filter()->unique();
                        @endphp
                        @foreach($tags as $tag)
                            <option value="{{ strtolower($tag) }}">{{ $tag }}</option>
                        @endforeach
                    </select>
                </div>

                <button class="clear-filters" onclick="clearFilters()">Xóa bộ lọc</button>
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
                            data-region="{{ strtolower($beach['region_name']) }}"
                            data-tags="{{ strtolower(implode(',', $beach['tags'] ?? [])) }}">
                            <div class="tour-image">
                                @php
                                    $img = $beach['image'] ?? '';
                                    $isAsset = $img && (str_starts_with($img, 'http') || str_starts_with($img, '/assets'));
                                @endphp
                                <img src="{{ $img ? ($isAsset ? $img : asset('storage/' . $img)) : '/assets/img/default.jpg' }}"
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
                                        {{ $beach['region_name'] ?? 'Unknown Region' }}
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
                                            onclick="window.location.href='{{ route('beaches.show', $beach['id']) }}'">Xem chi
                                            tiết</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <div id="no-result-explore" class="no-results"
                    style="display:none; text-align:center; color:#888; font-size:1.2rem; margin-top:2rem;">
                    <i class="fas fa-search"></i>
                    <h3>Không tìm thấy bãi biển phù hợp</h3>
                    <p>Hãy thử từ khóa khác hoặc kiểm tra lại bộ lọc.</p>
                </div>
            </div>
        </div>
    </section>
    @vite('resources/js/explore.js')
@endsection