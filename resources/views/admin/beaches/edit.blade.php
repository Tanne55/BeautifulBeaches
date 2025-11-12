@extends('layouts.auth')
@section('content')
    <div class="container py-4 container-custom">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1"><i class="bi bi-pencil-square text-warning me-2"></i>Chỉnh Sửa Bãi Biển</h2>
                        <p class="text-muted mb-0">Cập nhật thông tin cho: <strong>{{ $beach->title }}</strong></p>
                    </div>
                    <a href="{{ route('admin.beaches.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
                <hr class="mt-3 mb-0">
            </div>
        </div>

        <div class="row justify-content-center">
            <!-- Form bên trái -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm mb-4 w-100">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Cập nhật thông tin</h5>
                    </div>
                    <div class=" p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                    <strong>Có lỗi xảy ra:</strong>
                                </div>
                                <ul class="mb-0 ps-4">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('admin.beaches.update', $beach->id) }}" method="POST"
                            enctype="multipart/form-data" id="beachForm">
                            @csrf
                            @method('PUT')
                            <!-- Ảnh chính -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-primary">
                                    <i class="bi bi-image me-2"></i>Ảnh chính
                                    <span class="text-danger">*</span>
                                </label>
                                <div id="primary-drop-area"
                                    class="upload-area border border-2 border-primary border-dashed rounded-3 bg-light position-relative overflow-hidden"
                                    style="cursor:pointer; min-height: 200px;">
                                    @php
                                        $primaryImg = $beach->primaryImage ? $beach->primaryImage->image_url : $beach->image;
                                        $primaryImgSrc = $primaryImg ? (str_starts_with($primaryImg, 'http') || str_starts_with($primaryImg, '/assets') ? $primaryImg : asset('storage/' . (str_starts_with($primaryImg, 'beaches/') ? $primaryImg : 'beaches/' . $primaryImg))) : 'https://via.placeholder.com/900x350?text=Ảnh+Chính&bg=e3f2fd&color=1976d2';
                                    @endphp
                                    <img id="primaryImagePreview" src="{{ $primaryImgSrc }}" alt="Primary Preview"
                                        class="img-fluid rounded-3 mb-2 w-100" style="max-height:200px;object-fit:cover;">
                                    <div id="primary-drop-text"
                                        class="upload-overlay position-absolute top-50 start-50 translate-middle text-center w-100 p-3"
                                        style="opacity: 0; transition: opacity 0.3s ease;">
                                        <i class="bi bi-cloud-upload display-4 text-primary mb-2"></i>
                                        <p class="h5 text-primary mb-2">Thay đổi ảnh chính</p>
                                        <p class="text-muted mb-0">Click để chọn ảnh mới</p>
                                    </div>
                                    <input type="file" class="form-control d-none" id="primary_image" name="primary_image"
                                        accept="image/*">
                                </div>
                                <div class="form-text mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Để trống nếu không muốn thay đổi ảnh chính.
                                </div>
                            </div>

                            <!-- Ảnh hiện có -->
                            @if($beach->images->count() > 0)
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-info">
                                        <i class="bi bi-images me-2"></i>Ảnh hiện có
                                        <span class="badge bg-info">{{ $beach->images->count() }} ảnh</span>
                                    </label>
                                    <div class="row g-3" id="existing-images">
                                        @foreach($beach->images->sortBy('sort_order') as $image)
                                            <div class="col-4" data-image-id="{{ $image->id }}">
                                                <div class="image-preview-item">
                                                    <img src="{{ str_starts_with($image->image_url, 'http') || str_starts_with($image->image_url, '/assets') ? $image->image_url : asset('storage/' . (str_starts_with($image->image_url, 'beaches/') ? $image->image_url : 'beaches/' . $image->image_url)) }}"
                                                        class="img-fluid rounded shadow-sm w-100"
                                                        style="height:80px;object-fit:cover;">
                                                    <button type="button" class="btn btn-danger btn-sm remove-btn"
                                                        onclick="removeExistingImage({{ $image->id }})" title="Xóa ảnh này">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                    @if($image->is_primary)
                                                        <span class="badge bg-primary position-absolute bottom-0 start-0 m-1"
                                                            style="font-size:10px;">
                                                            <i class="bi bi-star-fill me-1"></i>Chính
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" id="deleted_images" name="deleted_images" value="">
                                    <div class="alert alert-info mt-3 mb-0" role="alert">
                                        <i class="bi bi-lightbulb me-2"></i>
                                        Click vào nút <strong>×</strong> để đánh dấu ảnh cần xóa. Ảnh sẽ được xóa sau khi lưu.
                                    </div>
                                </div>
                            @endif

                            <!-- Ảnh bổ sung -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-success">
                                    <i class="bi bi-plus-square me-2"></i>Thêm ảnh mới
                                    <small class="text-muted">(Tùy chọn)</small>
                                </label>
                                <div id="additional-drop-area"
                                    class="upload-area border border-2 border-success border-dashed rounded-3 bg-light"
                                    style="cursor:pointer; min-height: 120px;">
                                    <div id="additional-drop-text" class="text-center p-4">
                                        <i class="bi bi-images display-4 text-success mb-2"></i>
                                        <p class="h6 text-success mb-2">Thêm ảnh bổ sung</p>
                                        <p class="text-muted mb-0">Chọn nhiều ảnh cùng lúc (tối đa 10 ảnh)</p>
                                    </div>
                                    <input type="file" class="form-control d-none" id="additional_images"
                                        name="additional_images[]" accept="image/*" multiple>
                                </div>
                                <div id="additional-preview" class="row g-3 mt-2"></div>
                                <div class="form-text mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Ảnh mới sẽ được thêm vào bộ sưu tập hiện có.
                                </div>
                            </div>
                            <!-- Tiêu đề -->
                            <div class="mb-3">
                                <input type="text" class="form-control form-control-lg fw-bold" id="title" name="title"
                                    placeholder="Tiêu đề bãi biển" required value="{{ old('title', $beach->title) }}">
                            </div>
                            <!-- Khu vực -->
                            <div class="mb-3">
                                <select class="form-control" id="region_id" name="region_id" required>
                                    <option value="">Chọn vùng</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}" {{ (old('region_id', $beach->region_id) == $region->id) ? 'selected' : '' }}>
                                            {{ $region->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Mô tả ngắn -->
                            <div class="mb-3">
                                <textarea class="form-control" id="short_description" name="short_description" rows="2"
                                    placeholder="Mô tả ngắn về bãi biển"
                                    required>{{ old('short_description', $beach->short_description) }}</textarea>
                            </div>
                            <!-- Mô tả chi tiết -->
                            <div class="mb-3">
                                <textarea class="form-control" id="long_description" name="long_description" rows="3"
                                    placeholder="Mô tả chi tiết về bãi biển">{{ old('long_description', $beach->detail->long_description) }}</textarea>
                            </div>
                            <!-- Trích dẫn nổi bật -->
                            <div class="mb-3">
                                <div class="p-3 border-start border-3 border-primary bg-light rounded mb-2">
                                    <textarea class="form-control border-0 bg-transparent fst-italic" id="highlight_quote"
                                        name="highlight_quote" rows="2"
                                        placeholder="Trích dẫn nổi bật (ví dụ: Let Bai Chay's breeze renew your soul.)">{{ old('highlight_quote', $beach->detail->highlight_quote) }}</textarea>
                                </div>
                            </div>
                            <!-- Mô tả chi tiết 2 -->
                            <div class="mb-3">
                                <textarea class="form-control" id="long_description2" name="long_description2" rows="2"
                                    placeholder="Mô tả chi tiết bổ sung">{{ old('long_description2', $beach->detail->long_description2) }}</textarea>
                            </div>
                            <!-- Tags -->
                            <div class="mb-4">
                                <input type="text" class="form-control" id="tags" name="tags"
                                    value="{{ old('tags', is_array($beach->detail->tags) ? implode(', ', $beach->detail->tags) : $beach->detail->tags) }}"
                                    placeholder='Tags (ví dụ: ["Travel","Beach","Quang Ninh","Vietnam","Family"])'>
                                <div class="form-text">Hint: Nhập tags dạng chuỗi json hoặc cách nhau bởi dấu phẩy.</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <button type="submit" class="btn btn-warning btn-lg px-5 shadow">
                                    <i class="bi bi-check-circle me-2"></i>Cập Nhật
                                </button>
                                <a href="{{ route('admin.beaches.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="bi bi-x-circle me-2"></i>Hủy bỏ
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Preview bên phải -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm sticky-top w-100" style="top: 20px;">
                    <div class="card-header bg-gradient"
                        style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                        <h5 class="mb-0 text-dark"><i class="bi bi-eye me-2"></i>Xem trước</h5>
                        <small class="opacity-75 text-dark">Giao diện sau khi cập nhật</small>
                    </div>
                    <div class=" p-4">
                        <div class="preview-container">
                            <div class="text-center mb-4">
                                @php
                                    $primaryImg = $beach->primaryImage ? $beach->primaryImage->image_url : $beach->image;
                                    $primaryImgSrc = $primaryImg ? (str_starts_with($primaryImg, 'http') || str_starts_with($primaryImg, '/assets') ? $primaryImg : asset('storage/' . (str_starts_with($primaryImg, 'beaches/') ? $primaryImg : 'beaches/' . $primaryImg))) : 'https://via.placeholder.com/900x350?text=Beach+Preview';
                                @endphp
                                <img id="previewPrimaryImage" src="{{ $primaryImgSrc }}" alt="Preview"
                                    class="img-fluid rounded-3 shadow-sm w-100" style="max-height:200px;object-fit:cover;">
                            </div>
                            <div id="previewAdditionalImages" class="row g-2 mb-4">
                                @foreach($beach->images->where('is_primary', false)->sortBy('sort_order') as $image)
                                    <div class="col-4 existing-image" data-image-id="{{ $image->id }}">
                                        <img src="{{ str_starts_with($image->image_url, 'http') || str_starts_with($image->image_url, '/assets') ? $image->image_url : asset('storage/' . (str_starts_with($image->image_url, 'beaches/') ? $image->image_url : 'beaches/' . $image->image_url)) }}"
                                            class="img-fluid rounded shadow-sm w-100" style="height:60px;object-fit:cover;">
                                    </div>
                                @endforeach
                            </div>

                            <div class="preview-content">
                                <h3 id="previewTitle" class="fw-bold text-dark mb-2">{{ $beach->title }}</h3>
                                <div class="mb-3">
                                    <span class="badge bg-primary rounded-pill px-3 py-2" id="previewRegion">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $beach->region->name }}
                                    </span>
                                </div>

                                <div class="description-section">
                                    <p class="text-muted mb-3 lh-lg" id="previewShortDescription">
                                        {{ $beach->short_description }}
                                    </p>
                                    <p class="mb-3" id="previewLongDescription">{{ $beach->detail->long_description ?? '' }}
                                    </p>

                                    <div class="quote-section mb-3" id="previewQuoteContainer"
                                        style="{{ $beach->detail->highlight_quote ? 'display: block;' : 'display: none;' }}">
                                        <div class="bg-light border-start border-4 border-info p-3 rounded-end">
                                            <i class="bi bi-quote text-info me-2"></i>
                                            <em id="previewHighlightQuote"
                                                class="text-info">{{ $beach->detail->highlight_quote ?? '' }}</em>
                                        </div>
                                    </div>

                                    <p class="mb-3" id="previewLongDescription2">
                                        {{ $beach->detail->long_description2 ?? '' }}
                                    </p>

                                    <div class="tags-section" id="previewTagsContainer">
                                        <div class="d-flex flex-wrap gap-1" id="previewTags">
                                            @php
                                                $tags = is_array($beach->detail->tags) ? $beach->detail->tags : (json_decode($beach->detail->tags) ?: explode(',', $beach->detail->tags ?? ''));
                                            @endphp
                                            @foreach ($tags as $tag)
                                                <span class="badge bg-secondary text-white me-1 mb-1 px-2 py-1">
                                                    <i class="bi bi-tag me-1"></i>{{ trim($tag, ' "') }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CSS tùy chỉnh -->
    <style>
        .upload-area {
            transition: all 0.3s ease;
        }

        .upload-area:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .upload-area:hover #primary-drop-text {
            opacity: 1 !important;
        }

        .upload-overlay {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            border-radius: 0.75rem;
        }

        .preview-container {
            max-height: 70vh;
            overflow-y: auto;
        }

        .preview-container::-webkit-scrollbar {
            width: 4px;
        }

        .preview-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 2px;
        }

        .preview-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 2px;
        }

        .image-preview-item {
            position: relative;
            overflow: hidden;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .image-preview-item:hover {
            transform: scale(1.05);
            z-index: 2;
        }

        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            opacity: 0.8;
            transition: all 0.2s ease;
        }

        .remove-btn:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
    <script>
        let deletedImages = [];

        // Primary image drag & drop + click to select
        const primaryDropArea = document.getElementById('primary-drop-area');
        const primaryImageInput = document.getElementById('primary_image');
        const primaryImagePreview = document.getElementById('primaryImagePreview');
        const previewPrimaryImage = document.getElementById('previewPrimaryImage');

        primaryDropArea.addEventListener('click', () => primaryImageInput.click());
        primaryDropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            primaryDropArea.classList.add('bg-primary', 'bg-opacity-10');
        });
        primaryDropArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            primaryDropArea.classList.remove('bg-primary', 'bg-opacity-10');
        });
        primaryDropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            primaryDropArea.classList.remove('bg-primary', 'bg-opacity-10');
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                const dt = new DataTransfer();
                dt.items.add(e.dataTransfer.files[0]);
                primaryImageInput.files = dt.files;
                previewPrimaryImageFile(e.dataTransfer.files[0]);
            }
        });
        primaryImageInput.addEventListener('change', (e) => {
            if (e.target.files && e.target.files[0]) {
                previewPrimaryImageFile(e.target.files[0]);
            }
        });
        function previewPrimaryImageFile(file) {
            const url = URL.createObjectURL(file);
            primaryImagePreview.src = url;
            previewPrimaryImage.src = url;
        }

        // Additional images drag & drop + click to select
        const additionalDropArea = document.getElementById('additional-drop-area');
        const additionalImageInput = document.getElementById('additional_images');
        const additionalPreview = document.getElementById('additional-preview');
        const previewAdditionalImages = document.getElementById('previewAdditionalImages');

        additionalDropArea.addEventListener('click', () => additionalImageInput.click());
        additionalDropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            additionalDropArea.classList.add('bg-secondary', 'bg-opacity-10');
        });
        additionalDropArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            additionalDropArea.classList.remove('bg-secondary', 'bg-opacity-10');
        });
        additionalDropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            additionalDropArea.classList.remove('bg-secondary', 'bg-opacity-10');
            if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                additionalImageInput.files = e.dataTransfer.files;
                previewAdditionalImageFiles(Array.from(e.dataTransfer.files));
            }
        });
        additionalImageInput.addEventListener('change', (e) => {
            if (e.target.files && e.target.files.length > 0) {
                previewAdditionalImageFiles(Array.from(e.target.files));
            }
        });

        function previewAdditionalImageFiles(files) {
            additionalPreview.innerHTML = '';

            // Clear new images from preview (keep existing ones)
            const existingImages = previewAdditionalImages.querySelectorAll('.existing-image');
            previewAdditionalImages.innerHTML = '';
            existingImages.forEach(img => previewAdditionalImages.appendChild(img));

            files.slice(0, 10).forEach((file, index) => {
                const url = URL.createObjectURL(file);

                // Preview trong form
                const colDiv = document.createElement('div');
                colDiv.className = 'col-4';
                colDiv.innerHTML = `
                                    <div class="image-preview-item">
                                        <img src="${url}" class="img-fluid rounded shadow-sm w-100" style="height:80px;object-fit:cover;">
                                        <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="removeAdditionalImage(${index})" title="Xóa ảnh mới">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                `;
                additionalPreview.appendChild(colDiv);

                // Preview bên phải
                const previewDiv = document.createElement('div');
                previewDiv.className = 'col-4';
                previewDiv.innerHTML = `<img src="${url}" class="img-fluid rounded shadow-sm w-100" style="height:60px;object-fit:cover;">`;
                previewAdditionalImages.appendChild(previewDiv);
            });
        }

        function removeAdditionalImage(index) {
            const files = Array.from(additionalImageInput.files);
            files.splice(index, 1);

            const dt = new DataTransfer();
            files.forEach(file => dt.items.add(file));
            additionalImageInput.files = dt.files;

            previewAdditionalImageFiles(files);
        }

        function removeExistingImage(imageId) {
            if (confirm('Bạn có chắc chắn muốn xóa ảnh này? Thao tác này không thể hoàn tác.')) {
                deletedImages.push(imageId);
                document.getElementById('deleted_images').value = deletedImages.join(',');

                // Remove from DOM with animation
                const imageElement = document.querySelector(`[data-image-id="${imageId}"]`);
                if (imageElement) {
                    imageElement.style.transition = 'all 0.3s ease';
                    imageElement.style.opacity = '0';
                    imageElement.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        imageElement.remove();
                    }, 300);
                }

                // Remove from preview if it's there
                const previewElement = previewAdditionalImages.querySelector(`.existing-image[data-image-id="${imageId}"]`);
                if (previewElement) {
                    previewElement.style.transition = 'all 0.3s ease';
                    previewElement.style.opacity = '0';
                    setTimeout(() => {
                        previewElement.remove();
                    }, 300);
                }

                // Show success message
                const toast = document.createElement('div');
                toast.className = 'position-fixed top-0 end-0 p-3';
                toast.style.zIndex = '9999';
                toast.innerHTML = `
                                    <div class="toast show" role="alert">
                                        <div class="toast-body bg-success text-white rounded">
                                            <i class="bi bi-check-circle me-2"></i>Ảnh đã được đánh dấu xóa
                                        </div>
                                    </div>
                                `;
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.remove();
                }, 2000);
            }
        }

        // Live preview các trường form
        document.getElementById('title').addEventListener('input', function () {
            document.getElementById('previewTitle').textContent = this.value || 'Tiêu đề bãi biển';
        });
        document.getElementById('region_id').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('previewRegion').textContent = selectedOption.textContent || 'Vùng';
        });
        document.getElementById('short_description').addEventListener('input', function () {
            document.getElementById('previewShortDescription').textContent = this.value;
        });
        document.getElementById('long_description').addEventListener('input', function () {
            document.getElementById('previewLongDescription').textContent = this.value;
        });
        document.getElementById('highlight_quote').addEventListener('input', function () {
            document.getElementById('previewHighlightQuote').textContent = this.value;
        });
        document.getElementById('long_description2').addEventListener('input', function () {
            document.getElementById('previewLongDescription2').textContent = this.value;
        });
        document.getElementById('tags').addEventListener('input', function () {
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