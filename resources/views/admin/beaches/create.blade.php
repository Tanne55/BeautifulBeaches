@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Thêm bãi biển mới</h1>
        <form action="{{ route('admin.beaches.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="region" class="form-label">Khu vực</label>
                <input type="text" class="form-control" id="region" name="region" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Ảnh (đường dẫn)</label>
                <input type="text" class="form-control" id="image" name="image">
            </div>
            <div class="mb-3">
                <label for="short_description" class="form-label">Mô tả ngắn</label>
                <textarea class="form-control" id="short_description" name="short_description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="long_description" class="form-label">Mô tả chi tiết</label>
                <textarea class="form-control" id="long_description" name="long_description"></textarea>
            </div>
            <div class="mb-3">
                <label for="long_description_2" class="form-label">Mô tả chi tiết 2</label>
                <textarea class="form-control" id="long_description_2" name="long_description_2"></textarea>
            </div>
            <div class="mb-3">
                <label for="highlight_quote" class="form-label">Trích dẫn nổi bật</label>
                <input type="text" class="form-control" id="highlight_quote" name="highlight_quote">
            </div>
            <div class="mb-3">
                <label for="tags" class="form-label">Tags (json hoặc chuỗi)</label>
                <input type="text" class="form-control" id="tags" name="tags">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Giá</label>
                <input type="number" class="form-control" id="price" name="price">
            </div>
            <div class="mb-3">
                <label for="original_price" class="form-label">Giá gốc</label>
                <input type="number" class="form-control" id="original_price" name="original_price">
            </div>
            <div class="mb-3">
                <label for="capacity" class="form-label">Sức chứa</label>
                <input type="number" class="form-control" id="capacity" name="capacity">
            </div>
            <div class="mb-3">
                <label for="duration" class="form-label">Thời lượng</label>
                <input type="text" class="form-control" id="duration" name="duration">
            </div>
            <div class="mb-3">
                <label for="rating" class="form-label">Đánh giá (số)</label>
                <input type="number" class="form-control" id="rating" name="rating">
            </div>
            <div class="mb-3">
                <label for="reviews" class="form-label">Số lượt đánh giá</label>
                <input type="number" class="form-control" id="reviews" name="reviews">
            </div>
            <button type="submit" class="btn btn-success">Lưu</button>
            <a href="{{ route('admin.beaches.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection