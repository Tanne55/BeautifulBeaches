@php
    $layout = Auth::check() ? 'layouts.auth' : 'layouts.guest';
@endphp


@extends($layout)

@section('content')
    <div class="container my-5">
        <h2 class="mb-4 text-center">{{ $tour->title }}</h2>

        <div class="row">
            <div class="col-md-6">
                @php
                    $img = $tour->image ?? '';
                    $beachImg = ($tour->beach && $tour->beach->image) ? $tour->beach->image : '';
                    $isAsset = $img && (str_starts_with($img, 'http') || str_starts_with($img, '/assets'));
                    $isBeachAsset = $beachImg && (str_starts_with($beachImg, 'http') || str_starts_with($beachImg, '/assets'));
                @endphp
                @if($img)
                    <img src="{{ $isAsset ? $img : asset('storage/' . $img) }}" class="img-fluid rounded shadow"
                        alt="{{ $tour->title }}">
                @elseif($beachImg)
                    <img src="{{ $isBeachAsset ? $beachImg : asset('storage/' . $beachImg) }}" class="img-fluid rounded shadow"
                        alt="{{ $tour->title }}">
                @else
                    <img src="https://via.placeholder.com/600x400?text=No+Image" class="img-fluid rounded shadow"
                        alt="No image">
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
                            <td>{{ $tour->ceo?->name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- NÚT ĐẶT TOUR --}}
    <div class="container mb-4">
        @auth
            <a href="{{ route('tour.booking.form', $tour->id) }}" class="btn btn-success btn-lg w-100 mb-3">Đặt tour ngay</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-success btn-lg w-100 mb-3">Đăng nhập để đặt tour</a>
        @endauth
    </div>
    {{-- COMMENT/REVIEW SECTION --}}
    <div class="container mb-5">
        <h4 class="mb-3">Bình luận & Đánh giá</h4>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @auth
            <form action="{{ route('tour.review', $tour->id) }}" method="POST" class="mb-4" id="review-form">
                @csrf
                <div class="mb-2">
                    <label class="form-label">Đánh giá:</label>
                    <div id="star-rating" class="d-inline-block">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star star-icon" data-value="{{ $i }}"></i>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="5">
                </div>
                <div class="mb-2">
                    <label for="comment" class="form-label">Bình luận:</label>
                    <textarea name="comment" id="comment" class="form-control" rows="2" required></textarea>
                </div>
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                <button type="submit" class="btn btn-primary">Gửi bình luận</button>
            </form>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const stars = document.querySelectorAll('#star-rating .star-icon');
                    const ratingInput = document.getElementById('rating-input');
                    let currentRating = 5;
                    function setStars(rating) {
                        stars.forEach((star, idx) => {
                            if (idx < rating) {
                                star.classList.add('text-warning');
                            } else {
                                star.classList.remove('text-warning');
                            }
                        });
                    }
                    setStars(currentRating);
                    stars.forEach((star, idx) => {
                        star.addEventListener('mouseenter', () => setStars(idx + 1));
                        star.addEventListener('mouseleave', () => setStars(currentRating));
                        star.addEventListener('click', () => {
                            currentRating = idx + 1;
                            ratingInput.value = currentRating;
                            setStars(currentRating);
                        });
                    });
                });
            </script>
        @else
            <div class="alert alert-info">Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để bình luận.</div>
        @endauth

        <div class="review-list mt-4">
            @forelse($reviews as $review)
                <div class="border-bottom pb-2 mb-2">
                    <div class="d-flex align-items-center mb-1">
                        <strong>{{ $review->user->name ?? 'Ẩn danh' }}</strong>
                        @if(isset($review->user->role))
                            @if($review->user->role === 'admin')
                                <span class="badge bg-danger ms-2">Admin</span>
                            @elseif($review->user->role === 'ceo')
                                <span class="badge bg-primary ms-2">CEO</span>
                            @elseif($review->user->role === 'user')
                                <span class="badge bg-secondary ms-2">User</span>
                            @endif
                        @endif
                        <span class="ms-2 text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                            @endfor
                        </span>
                        <span class="ms-2 text-muted small">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <div>{{ $review->comment }}</div>
                </div>
            @empty
                <div class="text-muted">Chưa có bình luận nào.</div>
            @endforelse
        </div>
    </div>
@endsection