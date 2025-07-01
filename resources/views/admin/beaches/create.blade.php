@extends('layouts.auth')
@section('content')
    <div class="container py-4" style="padding-left: 80px;">
        <div class="row justify-content-center">
            <!-- Form bên trái -->
            <div class="col-lg-6">
                <div class="card shadow-sm mb-3">
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
                        <form action="{{ route('admin.beaches.store') }}" method="POST" enctype="multipart/form-data" id="beachForm">
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
                            <!-- Khu vực (enum) -->
                            <div class="mb-3">
                                <select class="form-control" id="region" name="region" required>
                                    <option value="">Chọn vùng</option>
                                    <option value="Northern Vietnam">Bắc Bộ</option>
                                    <option value="Central Vietnam">Trung Bộ</option>
                                    <option value="Southern Vietnam">Nam Bộ</option>
                                </select>
                            </div>
                            <!-- Mô tả ngắn -->
                            <div class="mb-3">
                                <textarea class="form-control" id="short_description" name="short_description" rows="2"
                                    placeholder="Mô tả ngắn về bãi biển" required>{{ old('short_description') }}</textarea>
                            </div>
                            <!-- Mô tả chi tiết -->
                            <div class="mb-3">
                                <textarea class="form-control" id="long_description" name="long_description" rows="3"
                                    placeholder="Mô tả chi tiết về bãi biển">{{ old('long_description') }}</textarea>
                            </div>
                            <!-- Trích dẫn nổi bật -->
                            <div class="mb-3">
                                <div class="p-3 border-start border-3 border-primary bg-light rounded mb-2">
                                    <textarea class="form-control border-0 bg-transparent fst-italic" id="highlight_quote"
                                        name="highlight_quote" rows="2"
                                        placeholder="Trích dẫn nổi bật (ví dụ: Let Bai Chay's breeze renew your soul.)">{{ old('highlight_quote') }}</textarea>
                                </div>
                            </div>
                            <!-- Mô tả chi tiết 2 -->
                            <div class="mb-3">
                                <textarea class="form-control" id="long_description_2" name="long_description_2" rows="2"
                                    placeholder="Mô tả chi tiết bổ sung">{{ old('long_description_2') }}</textarea>
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
            <!-- Preview bên phải -->
            <div class="col-lg-6">
                <div class="card shadow-sm mb-3">
                    <div class="card-body p-3">
                        <div class="text-center mb-3">
                            <img id="previewImageShow" src="https://via.placeholder.com/900x350?text=Preview+Image" alt="Preview" class="img-fluid rounded" style="max-height:350px;object-fit:cover;">
                        </div>
                        <h2 id="previewTitle">Tiêu đề bãi biển</h2>
                        <span class="badge bg-primary mb-2" id="previewRegion">Vùng</span>
                        <p class="short-description" id="previewShortDescription"></p>
                        <p class="long-description" id="previewLongDescription"></p>
                        <div class="highlight-quote bg-light p-2 rounded fst-italic border-start border-3 border-primary mb-2">
                            <p id="previewHighlightQuote"></p>
                        </div>
                        <p class="long-description-2" id="previewLongDescription2"></p>
                        <div class="tags-container" id="previewTags"></div>
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
        const previewImageShow = document.getElementById('previewImageShow');

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
            const url = URL.createObjectURL(file);
            imagePreview.src = url;
            previewImageShow.src = url;
        }

        // Live preview các trường form
        document.getElementById('title').addEventListener('input', function() {
            document.getElementById('previewTitle').textContent = this.value || 'Tiêu đề bãi biển';
        });
        document.getElementById('region').addEventListener('change', function() {
            document.getElementById('previewRegion').textContent = this.value || 'Vùng';
        });
        document.getElementById('short_description').addEventListener('input', function() {
            document.getElementById('previewShortDescription').textContent = this.value;
        });
        document.getElementById('long_description').addEventListener('input', function() {
            document.getElementById('previewLongDescription').textContent = this.value;
        });
        document.getElementById('highlight_quote').addEventListener('input', function() {
            document.getElementById('previewHighlightQuote').textContent = this.value;
        });
        document.getElementById('long_description_2').addEventListener('input', function() {
            document.getElementById('previewLongDescription2').textContent = this.value;
        });
        document.getElementById('tags').addEventListener('input', function() {
            const tagsContainer = document.getElementById('previewTags');
            tagsContainer.innerHTML = '';
            let tags = [];
            try {
                tags = JSON.parse(this.value);
                if (!Array.isArray(tags)) tags = [];
            } catch {
                tags = this.value.split(',').map(t => t.trim()).filter(t => t);
            }
            tags.forEach(tag => {
                const span = document.createElement('span');
                span.className = 'tag badge bg-secondary me-1';
                span.innerHTML = '<i class="fas fa-tag"></i> ' + tag;
                tagsContainer.appendChild(span);
            });
        });
    </script>
@endsection