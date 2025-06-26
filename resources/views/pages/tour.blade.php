@php
    $layout = Auth::check() ? 'layouts.auth' : 'layouts.guest';
@endphp


@extends($layout)

@section('content')
    <div class="container my-5">
        <h2 class="mb-4 text-center">Danh sách Tour</h2>

        <div class="row">
            @foreach($tours as $tour)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($tour['image'])
                            <img src="{{ asset($tour['image']) }}" class="card-img-top"
                                alt="{{ $tour['title'] }}">
                        @elseif($tour['beach_image'])
                            <img src="{{ asset($tour['beach_image']) }}" class="card-img-top"
                                alt="{{ $tour['title'] }}">
                        @else
                            <img src="https://via.placeholder.com/600x400?text=No+Image" class="card-img-top" alt="No image">
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $tour['title'] }}</h5>
                            <p class="card-text text-muted mb-1"><strong>Khu vực:</strong> {{ $tour['beach_region'] }}</p>
                            <p class="card-text text-muted mb-1"><strong>Thời lượng:</strong> {{ $tour['duration_days'] ?? $tour['duration'] }} ngày</p>
                            <p class="card-text text-muted mb-1"><strong>Sức chứa:</strong> {{ $tour['capacity'] }} người</p>
                            <p class="card-text text-muted mb-1"><strong>Giá:</strong>
                                <span class="text-danger fw-bold">{{ number_format($tour['price'], 0, ',', '.') }} đ</span>
                                @if($tour['original_price'] > $tour['price'])
                                    <small class="text-decoration-line-through text-muted">
                                        {{ number_format($tour['original_price'], 0, ',', '.') }} đ
                                    </small>
                                @endif
                            </p>
                            <p class="card-text text-muted mb-1"><strong>CEO:</strong> {{ $tour['ceo_id'] ?? '' }}</p>
                            <p class="card-text">{{ Str::limit($tour['beach_description'], 100) }}</p>

                            <a href="{{ route('tour.show', $tour['id']) }}" class="btn btn-primary mt-auto">Xem chi tiết</a>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection