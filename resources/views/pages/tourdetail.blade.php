@php
    $layout = Auth::check() ? 'layouts.auth' : 'layouts.guest';
@endphp


@extends($layout)

@section('content')
    <div class="container my-5">
        <h2 class="mb-4 text-center">{{ $tour->title }}</h2>

        <div class="row">
            <div class="col-md-6">
                @if($tour->image)
                    <img src="{{ asset($tour->image) }}" class="img-fluid rounded shadow" alt="{{ $tour->title }}">
                @elseif($tour->beach && $tour->beach->image)
                    <img src="{{ asset($tour->beach->image) }}" class="img-fluid rounded shadow" alt="{{ $tour->title }}">
                @else
                    <img src="https://via.placeholder.com/600x400?text=No+Image" class="img-fluid rounded shadow" alt="No image">
                @endif
            </div>
            <div class="col-md-6">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>Khu vực</th>
                            <td>{{ $tour->beach?->region }}</td>
                        </tr>
                        <tr>
                            <th>Thời lượng</th>
                            <td>{{ $tour->duration_days }} ngày</td>
                        </tr>
                        <tr>
                            <th>Sức chứa</th>
                            <td>{{ $tour->capacity }} người</td>
                        </tr>
                        <tr>
                            <th>Giá</th>
                            <td>
                                <span class="text-danger fw-bold">{{ number_format($tour->price, 0, ',', '.') }} đ</span>
                                @if($tour->original_price > $tour->price)
                                    <small class="text-decoration-line-through text-muted">
                                        {{ number_format($tour->original_price, 0, ',', '.') }} đ
                                    </small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Mô tả</th>
                            <td>{{ $tour->beach?->short_description }}</td>
                        </tr>
                        @if($tour->detail)
                            <tr>
                                <th>Giờ khởi hành</th>
                                <td>{{ $tour->detail->departure_time }}</td>
                            </tr>
                            <tr>
                                <th>Giờ trở về</th>
                                <td>{{ $tour->detail->return_time }}</td>
                            </tr>
                            @if($tour->detail->included_services)
                                <tr>
                                    <th>Dịch vụ bao gồm</th>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach(json_decode($tour->detail->included_services, true) ?? [] as $service)
                                                <li>{{ $service }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endif
                            @if($tour->detail->excluded_services)
                                <tr>
                                    <th>Không bao gồm</th>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach(json_decode($tour->detail->excluded_services, true) ?? [] as $service)
                                                <li>{{ $service }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endif
                            @if($tour->detail->highlights)
                                <tr>
                                    <th>Điểm nổi bật</th>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach(json_decode($tour->detail->highlights, true) ?? [] as $highlight)
                                                <li>{{ $highlight }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endif
                        @endif
                        <tr>
                            <th>CEO</th>
                            <td>{{ $tour->ceo_id ?? '' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection