@php
    $layout = Auth::check() ? 'layouts.auth' : 'layouts.guest';
@endphp


@extends($layout)

@section('content')
    <div class="container my-5">
        <h2 class="mb-4 text-center">{{ $tour->title }}</h2>

        <div class="row">
            <div class="col-md-6">
                @if($tour->beach && $tour->beach->image)
                    <img src="{{ asset('storage/' . $tour->beach->image) }}" class="img-fluid rounded shadow"
                        alt="{{ $tour->title }}">
                @else
                    <img src="https://via.placeholder.com/600x400?text=No+Image" class="img-fluid rounded shadow"
                        alt="No image">
                @endif
            </div>
            <div class="col-md-6">
                <p><strong>Khu vực:</strong> {{ $tour->beach?->region }}</p>
                <p><strong>Thời lượng:</strong> {{ $tour->duration }}</p>
                <p><strong>Sức chứa:</strong> {{ $tour->capacity }} người</p>
                <p><strong>Giá:</strong>
                    <span class="text-danger fw-bold">{{ number_format($tour->price, 0, ',', '.') }} đ</span>
                    @if($tour->original_price > $tour->price)
                        <small class="text-decoration-line-through text-muted">
                            {{ number_format($tour->original_price, 0, ',', '.') }} đ
                        </small>
                    @endif
                </p>
                <p><strong>Mô tả:</strong> {{ $tour->beach?->short_description }}</p>
            </div>
        </div>
    </div>
@endsection