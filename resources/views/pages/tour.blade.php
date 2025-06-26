@php
    $layout = Auth::check() ? 'layouts.auth' : 'layouts.guest';
@endphp


@extends($layout)

@section('content')
    <!-- banner container -->
    <section class="contact-banner ">
        <h1 id="banner-title">Danh sách Tour</h1>
        <img src="/assets/img1/aa.png" alt="" class="brush-bottom" />
    </section>

    <div class=" mb-4">

        <div class="beach-header p-4 shadow position-relative">
            <div class="floating-elements">
                <div class="floating-circle"></div>
                <div class="floating-circle"></div>
                <div class="floating-circle"></div>
            </div>
            <div class="text-center position-relative z-2" style="color: #1e3a8a;">
                <h1 class="fw-bold header-title fadeInUp">🧭 Khám Phá Các Tour Du Lịch Hấp Dẫn</h1>
                <p class="header-subtitle fadeInUp">Xem danh sách tour đa dạng và lựa chọn hành trình phù hợp cho bạn và
                    gia đình</p>
            </div>


        </div>
    </div>
    <section class="container my-5">
        <div class="row">
            <!-- Filter bên trái -->
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm p-3">
                    <h5 class="fw-bold mb-3">Bộ Lọc Tour</h5>

                    <div class="mb-3">
                        <label class="form-label">Tìm theo tên</label>
                        <input type="text" id="filter-title" class="form-control" placeholder="Nhập tên tour...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Khu vực</label>
                        <select id="filter-region" class="form-select">
                            <option value="">-- Tất cả --</option>
                            @foreach($tours->pluck('beach_region')->unique() as $region)
                                <option value="{{ $region }}">{{ $region }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sắp xếp giá</label>
                        <select id="filter-sort" class="form-select">
                            <option value="">-- Không sắp xếp --</option>
                            <option value="asc">Tăng dần</option>
                            <option value="desc">Giảm dần</option>
                        </select>
                    </div>
                    <button id="clear-filters" class="btn btn-outline-secondary w-100 mt-2">Xóa bộ lọc</button>
                </div>
            </div>

            <!-- Danh sách tour -->
            <div class="col-lg-9">
                <div class="row" id="tour-list">
                    @foreach($tours as $tour)
                        <div class="col-md-6 mb-4 tour-card" data-title="{{ strtolower($tour['title']) }}"
                            data-region="{{ $tour['beach_region'] }}" data-price="{{ $tour['price'] }}">
                            <div class="card h-100 shadow-sm">
                                @php
                                    $img = $tour['image'] ?? '';
                                    $beachImg = $tour['beach_image'] ?? '';
                                    $isAsset = $img && (str_starts_with($img, 'http') || str_starts_with($img, '/assets'));
                                    $isBeachAsset = $beachImg && (str_starts_with($beachImg, 'http') || str_starts_with($beachImg, '/assets'));
                                @endphp
                                @if($img)
                                    <img src="{{ $isAsset ? $img : asset('storage/' . $img) }}" class="card-img-top"
                                        alt="{{ $tour['title'] }}">
                                @elseif($beachImg)
                                    <img src="{{ $isBeachAsset ? $beachImg : asset('storage/' . $beachImg) }}" class="card-img-top"
                                        alt="{{ $tour['title'] }}">
                                @else
                                    <img src="https://via.placeholder.com/600x400?text=No+Image" class="card-img-top"
                                        alt="No image">
                                @endif

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $tour['title'] }}</h5>
                                    <p class="card-text text-muted mb-1"><strong>Khu vực:</strong> {{ $tour['beach_region'] }}
                                    </p>
                                    <p class="card-text text-muted mb-1"><strong>Thời lượng:</strong>
                                        {{ $tour['duration_days'] ?? $tour['duration'] }} ngày</p>
                                    <p class="card-text text-muted mb-1"><strong>Sức chứa:</strong> {{ $tour['capacity'] }}
                                        người</p>
                                    <p class="card-text text-muted mb-1"><strong>Giá:</strong>
                                        <span class="text-danger fw-bold">{{ number_format($tour['price'], 0, ',', '.') }}
                                            đ</span>
                                        @if($tour['original_price'] > $tour['price'])
                                            <small class="text-decoration-line-through text-muted">
                                                {{ number_format($tour['original_price'], 0, ',', '.') }} đ
                                            </small>
                                        @endif
                                    </p>
                                    <p class="card-text">{{ Str::limit($tour['beach_description'], 100) }}</p>

                                    <a href="{{ route('tour.show', $tour['id']) }}" class="btn btn-primary mt-auto">Xem chi
                                        tiết</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @vite('resources/js/tour.js')

@endsection