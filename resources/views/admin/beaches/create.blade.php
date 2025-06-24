@extends('layouts.auth')
@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-2">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('admin.beaches.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Ảnh preview -->
                            <div class="mb-4 text-center">
                                <div id="drop-area"
                                    class="p-4 mb-3 border border-2 border-primary border-dashed rounded bg-light position-relative"
                                    style="cursor:pointer;">
                                    <img id="imagePreview" src="https://via.placeholder.com/900x350?text=Preview+Image"
                                        alt="Preview" class="img-fluid rounded mb-2"
                                        style="max-height:350px;object-fit:cover;">
                                    <div id="drop-text" class="text-secondary">
                                        <i class="bi bi-upload" style="font-size:2rem;"></i><br>
                                        <span>Kéo & thả ảnh vào đây hoặc bấm để chọn ảnh từ máy</span>
                                    </div>
                                    <input type="file" class="form-control d-none" id="image" name="image" accept="image/*">
                                </div>
                                <div class="form-text">Chỉ nhận file ảnh (jpg, png, webp...)</div>
                            </div>
                            <!-- Tiêu đề -->
                            <div class="mb-3">
                                <input type="text" class="form-control form-control-lg fw-bold" id="title" name="title"
                                    placeholder="Tiêu đề bãi biển" required value="{{ old('title') }}">
                            </div>
                            <!-- Khu vực -->
                            <div class="mb-3">
                                <input type="text" class="form-control" id="region" name="region"
                                    placeholder="Khu vực (ví dụ: Quảng Ninh, Đà Nẵng...)" required
                                    value="{{ old('region') }}">
                            </div>
                            <!-- Mô tả ngắn -->
                            <div class="mb-3">
                                <textarea class="form-control" id="short_description" name="short_description" rows="2"
                                    placeholder="Mô tả ngắn về bãi biển" required value="{{ old('short_description') }}"></textarea>
                            </div>
                            <!-- Mô tả chi tiết -->
                            <div class="mb-3">
                                <textarea class="form-control" id="long_description" name="long_description" rows="3"
                                    placeholder="Mô tả chi tiết về bãi biển" value="{{ old('long_description') }}"></textarea>
                            </div>
                            <!-- Trích dẫn nổi bật -->
                            <div class="mb-3">
                                <div class="p-3 border-start border-3 border-primary bg-light rounded mb-2">
                                    <textarea class="form-control border-0 bg-transparent fst-italic" id="highlight_quote"
                                        name="highlight_quote" rows="2"
                                        placeholder="Trích dẫn nổi bật (ví dụ: Let Bai Chay's breeze renew your soul.)" value="{{ old('highlight_quote') }}"></textarea>
                                </div>
                            </div>
                            <!-- Mô tả chi tiết 2 -->
                            <div class="mb-3">
                                <textarea class="form-control" id="long_description_2" name="long_description_2" rows="2"
                                    placeholder="Mô tả chi tiết bổ sung" value="{{ old('long_description_2') }}"></textarea>
                            </div>
                            <!-- Tags -->
                            <div class="mb-4">
                                <input type="text" class="form-control" id="tags" name="tags" value="{{ old('tags') }}"
                                    placeholder='Tags (ví dụ: ["Travel","Beach","Quang Ninh","Vietnam","Family"])'>
                                <div class="form-text">Hint: Nhập tags dạng chuỗi json hoặc cách nhau bởi dấu phẩy.</div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success px-4">Lưu</button>
                                <a href="{{ route('admin.beaches.index') }}" class="btn btn-secondary px-4">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Drag & drop + click to select
        const dropArea = document.getElementById('drop-area');
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const dropText = document.getElementById('drop-text');

        dropArea.addEventListener('click', () => imageInput.click());
        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('bg-primary', 'bg-opacity-10');
        });
        dropArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropArea.classList.remove('bg-primary', 'bg-opacity-10');
        });
        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.classList.remove('bg-primary', 'bg-opacity-10');
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                imageInput.files = e.dataTransfer.files;
                previewImageFile(e.dataTransfer.files[0]);
            }
        });
        imageInput.addEventListener('change', (e) => {
            if (e.target.files && e.target.files[0]) {
                previewImageFile(e.target.files[0]);
            }
        });
        function previewImageFile(file) {
            imagePreview.src = URL.createObjectURL(file);
        }
    </script>
@endsection