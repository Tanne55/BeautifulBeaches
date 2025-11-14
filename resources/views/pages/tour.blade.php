@php
    $user = Auth::user();
    $layout = (!$user || $user->role === 'user') ? 'layouts.guest' : 'layouts.auth';
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
    <section class="container my-5 container-custom">
        <div class="row">
            <!-- Filter bên trái -->
            <div class="col-lg-3 mb-4 search-sidebar ">

                <h5 class="fw-bold mb-3 search-title">Bộ Lọc Tour</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tìm theo tên</label>
                    <input type="text" id="filter-title" class="form-control" placeholder="Nhập tên tour...">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Khu vực</label>
                    <select id="filter-region" class="form-select">
                        <option value="">-- Tất cả --</option>
                        @foreach($tours->pluck('beach.region.name')->unique() as $region)
                            <option value="{{ $region }}">{{ $region }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Sắp xếp giá</label>
                    <select id="filter-sort" class="form-select">
                        <option value="">-- Không sắp xếp --</option>
                        <option value="asc">Tăng dần</option>
                        <option value="desc">Giảm dần</option>
                    </select>
                </div>
                <button id="clear-filters" class="btn btn-outline-secondary w-100 mt-2">Xóa bộ lọc</button>

            </div>

            <!-- Danh sách tour -->
            <div class="col-lg-9">
                <div class="row" id="tour-list">
                    @foreach($tours as $tour)
                        <div class="col-md-6 mb-5 tour-cardd" data-title="{{ strtolower($tour->title) }}"
                            data-region="{{ $tour->beach && $tour->beach->region ? $tour->beach->region->name : '' }}"
                            data-price="{{ $tour->current_price }}">
                            <div class="card h-100 shadow-sm mx-auto">
                                @php
                                    $img = $tour->image ?? '';
                                    $beachImg = $tour->beach ? $tour->beach->image ?? '' : '';
                                @endphp
                                @if($img)
                                    <img src="{{ str_starts_with($img, 'http') || str_starts_with($img, '/assets') ? $img : asset('storage/' . (str_starts_with($img, 'tours/') ? $img : 'tours/' . $img)) }}"
                                        class="card-img-top" alt="{{ $tour->title }}">
                                @elseif($beachImg)
                                    <img src="{{ str_starts_with($beachImg, 'http') || str_starts_with($beachImg, '/assets') ? $beachImg : asset('storage/beaches/' . $beachImg) }}"
                                        class="card-img-top" alt="{{ $tour->title }}">
                                @else
                                    <img src="https://via.placeholder.com/600x400?text=No+Image" class="card-img-top"
                                        alt="No image">
                                @endif

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $tour->title }}</h5>
                                    <p class="card-text text-muted mb-1"><strong>Khu vực:</strong>
                                        {{ $tour->beach && $tour->beach->region ? $tour->beach->region->name : '' }}
                                    </p>
                                    <p class="card-text text-muted mb-1"><strong>Thời lượng:</strong>
                                        {{ $tour->duration_days ?? $tour->duration }} ngày</p>
                                    <p class="card-text text-muted mb-1"><strong>Sức chứa:</strong> {{ $tour->capacity }}
                                        người</p>
                                    <p class="card-text text-muted mb-1"><strong>Giá:</strong>
                                        @php
                                            $today = now()->format('Y-m-d');
                                            $price = $tour->prices->where('start_date', '<=', $today)
                                                ->where('end_date', '>=', $today)
                                                ->first();
                                            if (!$price) {
                                                $price = $tour->prices->first();
                                            }
                                        @endphp
                                        @if($price)
                                            @if($price->discount && $price->discount > 0)
                                                <span class="text-danger fw-bold">{{ number_format($price->final_price, 0, ',', '.') }}
                                                    TrVND</span>
                                                <small class="text-decoration-line-through text-muted">
                                                    {{ number_format($price->price, 0, ',', '.') }} TrVND
                                                </small>
                                            @else
                                                <span class="text-danger fw-bold">{{ number_format($price->price, 0, ',', '.') }}
                                                    TrVND</span>
                                            @endif
                                        @else
                                            <span class="text-danger fw-bold">Liên hệ</span>
                                        @endif
                                    </p>
                                    <p class="card-text">
                                        {{ $tour->beach && $tour->beach->short_description ? Str::limit($tour->beach->short_description, 100) : '' }}
                                    </p>

                                    <a href="{{ route('tour.show', $tour->id) }}" class="btn btn-primary mt-auto">Xem chi
                                        tiết</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div id="no-result-tour" class="no-results"
                    style="display:none; text-align:center; color:#888; font-size:1.2rem; margin-top:2rem;">
                    <i class="fas fa-search"></i>
                    <h3>Không tìm thấy tour phù hợp</h3>
                    <p>Hãy thử từ khóa khác hoặc kiểm tra lại bộ lọc.</p>
                </div>
            </div>
        </div>
    </section>
    @vite('resources/js/tour.js')

@endsection