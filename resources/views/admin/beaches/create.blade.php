@extends('layouts.auth')
@section('content')
    <div class="container py-4 container-custom">
        <!-- Header -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1"><i class="bi bi-plus-circle text-primary me-2"></i>Thêm Bãi Biển Mới</h2>
                        <p class="text-muted mb-0">Tạo thông tin chi tiết cho một bãi biển mới</p>
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
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Thông tin bãi biển</h5>
                    </div>
                    <div class="p-4">
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
                        <form action="{{ route('admin.beaches.store') }}" method="POST" enctype="multipart/form-data"
                            id="beachForm">
                            @csrf
                            <!-- Ảnh chính -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-primary">
                                    <i class="bi bi-image me-2"></i>Ảnh chính 
                                    <span class="text-danger">*</span>
                                </label>
                                <div id="primary-drop-area"
                                    class="upload-area border border-2 border-primary border-dashed rounded-3 bg-light position-relative overflow-hidden"
                                    style="cursor:pointer; min-height: 200px;">
                                    <img id="primaryImagePreview" src="https://via.placeholder.com/900x350?text=Ảnh+Chính+Bãi+Biển&bg=e3f2fd&color=1976d2"
                                        alt="" class="img-fluid rounded-3 mb-2 w-100"
                                        style="max-height:200px;object-fit:cover;">
                                    <div id="primary-drop-text" class="upload-overlay position-absolute top-50 start-50 translate-middle text-center w-100 p-3">
                                        <i class="bi bi-cloud-upload display-4 text-primary mb-2"></i>
                                        <p class="h5 text-primary mb-2">Ảnh chính của bãi biển</p>
                                        <p class="text-muted mb-0">Kéo thả hoặc click để chọn ảnh</p>
                                    </div>
                                    <input type="file" class="form-control d-none" id="primary_image" name="primary_image" accept="image/*" required>
                                </div>
                                <div class="form-text mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Định dạng: JPG, PNG, WEBP. Kích thước tối ưu: 1920x1080px. Tối đa 2MB.
                                </div>
                            </div>

                            <!-- Ảnh bổ sung -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-success">
                                    <i class="bi bi-images me-2"></i>Ảnh bổ sung
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
                                    <input type="file" class="form-control d-none" id="additional_images" name="additional_images[]" accept="image/*" multiple>
                                </div>
                                <div id="additional-preview" class="row g-3 mt-2"></div>
                                <div class="form-text mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Mỗi ảnh tối đa 2MB. Định dạng: JPG, PNG, WEBP.
                                </div>
                            </div>
                            <!-- Tiêu đề -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-type text-info me-2"></i>Tiêu đề bãi biển 
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg border-0 shadow-sm" id="title" name="title"
                                    placeholder="Nhập tiêu đề hấp dẫn cho bãi biển..." required value="{{ old('title') }}"
                                    style="background: #f8f9fa;">
                            </div>

                            <!-- Khu vực -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-geo-alt text-warning me-2"></i>Vùng / Khu vực 
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-lg border-0 shadow-sm" id="region_id" name="region_id" required
                                        style="background: #f8f9fa;">
                                    <option value="">-- Chọn vùng --</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>
                                            {{ $region->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Mô tả ngắn -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-card-text text-secondary me-2"></i>Mô tả ngắn 
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control border-0 shadow-sm" id="short_description" name="short_description" rows="3"
                                    placeholder="Viết mô tả ngắn gọn, hấp dẫn về bãi biển..." required
                                    style="background: #f8f9fa; resize: none;">{{ old('short_description') }}</textarea>
                                <div class="form-text">
                                    <span id="short_desc_count">0</span>/200 ký tự
                                </div>
                            </div>

                            <!-- Mô tả chi tiết -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-file-text text-dark me-2"></i>Mô tả chi tiết
                                </label>
                                <textarea class="form-control border-0 shadow-sm" id="long_description" name="long_description" rows="4"
                                    placeholder="Mô tả chi tiết về cảnh quan, hoạt động, điểm đặc biệt..."
                                    style="background: #f8f9fa; resize: vertical;">{{ old('long_description') }}</textarea>
                            </div>

                            <!-- Trích dẫn nổi bật -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-quote text-info me-2"></i>Trích dẫn nổi bật
                                </label>
                                <div class="position-relative">
                                    <div class="quote-container p-4 border-start border-4 border-info bg-info bg-opacity-10 rounded-end">
                                        <i class="bi bi-quote position-absolute top-0 start-0 ms-2 mt-1 text-info" style="font-size: 1.2rem;"></i>
                                        <textarea class="form-control border-0 bg-transparent fst-italic ps-3" id="highlight_quote"
                                            name="highlight_quote" rows="2"
                                            placeholder="Câu trích dẫn ấn tượng (ví dụ: 'Nơi biển xanh hôn trời xanh...')"
                                            style="resize: none;">{{ old('highlight_quote') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Mô tả chi tiết 2 -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-file-earmark-text text-muted me-2"></i>Thông tin bổ sung
                                </label>
                                <textarea class="form-control border-0 shadow-sm" id="long_description2" name="long_description2" rows="3"
                                    placeholder="Thông tin bổ sung về giao thông, dịch vụ, lưu ý..."
                                    style="background: #f8f9fa; resize: vertical;">{{ old('long_description2') }}</textarea>
                            </div>
                            <!-- Tags -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-tags text-success me-2"></i>Tags / Từ khóa
                                </label>
                                <input type="text" class="form-control border-0 shadow-sm" id="tags" name="tags" value="{{ old('tags') }}"
                                    placeholder='Ví dụ: Travel,Beach,Quang Ninh,Vietnam hoặc ["Travel","Beach","Vietnam"]'
                                    style="background: #f8f9fa;">
                                <div class="form-text">
                                    <i class="bi bi-lightbulb text-warning me-1"></i>
                                    Nhập các từ khóa cách nhau bởi dấu phẩy hoặc định dạng JSON array.
                                </div>
                                <div id="tags-preview" class="mt-2"></div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                                    <i class="bi bi-check-circle me-2"></i>Lưu Bãi Biển
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
                    <div class="card-header bg-gradient text-dark" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <h5 class="mb-0"><i class="bi bi-eye me-2"></i>Xem trước</h5>
                        <small class="opacity-75">Giao diện sẽ hiển thị như thế này</small>
                    </div>
                    <div class=" p-4">
                        <div class="preview-container">
                            <div class="text-center mb-4">
                                <img id="previewPrimaryImage" src="https://via.placeholder.com/900x350?text=Ảnh+Chính&bg=e3f2fd&color=1976d2"
                                    alt="Preview" class="img-fluid rounded-3 shadow-sm w-100"
                                    style="max-height:200px;object-fit:cover;">
                            </div>
                            <div id="previewAdditionalImages" class="row g-2 mb-4"></div>
                            
                            <div class="preview-content">
                                <h3 id="previewTitle" class="fw-bold text-dark mb-2">Tiêu đề bãi biển</h3>
                                <div class="mb-3">
                                    <span class="badge bg-primary rounded-pill px-3 py-2" id="previewRegion">
                                        <i class="bi bi-geo-alt me-1"></i>Vùng
                                    </span>
                                </div>
                                
                                <div class="description-section">
                                    <p class="text-muted mb-3 lh-lg" id="previewShortDescription">
                                        <em>Mô tả ngắn sẽ hiển thị ở đây...</em>
                                    </p>
                                    <p class="mb-3" id="previewLongDescription"></p>
                                    
                                    <div class="quote-section mb-3" id="previewQuoteContainer" style="display: none;">
                                        <div class="bg-light border-start border-4 border-info p-3 rounded-end">
                                            <i class="bi bi-quote text-info me-2"></i>
                                            <em id="previewHighlightQuote" class="text-info"></em>
                                        </div>
                                    </div>
                                    
                                    <p class="mb-3" id="previewLongDescription2"></p>
                                    
                                    <div class="tags-section" id="previewTagsContainer">
                                        <div class="d-flex flex-wrap gap-1" id="previewTags"></div>
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
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .upload-overlay {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(5px);
            border-radius: 0.75rem;
        }
        .quote-container {
            transition: all 0.3s ease;
        }
        .quote-container:hover {
            background: rgba(13, 202, 240, 0.15) !important;
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
        .form-control:focus, .form-select:focus {
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
            previewAdditionalImages.innerHTML = '';
            
            files.slice(0, 10).forEach((file, index) => {
                const url = URL.createObjectURL(file);
                
                // Preview trong form
                const colDiv = document.createElement('div');
                colDiv.className = 'col-4';
                colDiv.innerHTML = `
                    <div class="image-preview-item">
                        <img src="${url}" class="img-fluid rounded shadow-sm w-100" style="height:80px;object-fit:cover;">
                        <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="removeAdditionalImage(${index})">
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

        // Live preview các trường form
        document.getElementById('title').addEventListener('input', function () {
            document.getElementById('previewTitle').textContent = this.value || 'Tiêu đề bãi biển';
        });

        document.getElementById('region_id').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('previewRegion').innerHTML = `<i class="bi bi-geo-alt me-1"></i>${selectedOption.textContent || 'Vùng'}`;
        });

        document.getElementById('short_description').addEventListener('input', function () {
            const text = this.value;
            document.getElementById('previewShortDescription').innerHTML = text || '<em>Mô tả ngắn sẽ hiển thị ở đây...</em>';
            
            // Cập nhật bộ đếm ký tự
            document.getElementById('short_desc_count').textContent = text.length;
            
            // Thay đổi màu khi gần giới hạn
            const counter = document.getElementById('short_desc_count');
            if (text.length > 180) {
                counter.className = 'text-danger fw-bold';
            } else if (text.length > 150) {
                counter.className = 'text-warning fw-bold';
            } else {
                counter.className = 'text-muted';
            }
        });

        document.getElementById('long_description').addEventListener('input', function () {
            document.getElementById('previewLongDescription').textContent = this.value;
        });

        document.getElementById('highlight_quote').addEventListener('input', function () {
            const quote = this.value;
            const container = document.getElementById('previewQuoteContainer');
            if (quote.trim()) {
                document.getElementById('previewHighlightQuote').textContent = quote;
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }
        });

        document.getElementById('long_description2').addEventListener('input', function () {
            document.getElementById('previewLongDescription2').textContent = this.value;
        });

        document.getElementById('tags').addEventListener('input', function () {
            const tagsContainer = document.getElementById('previewTags');
            const tagsPreview = document.getElementById('tags-preview');
            
            tagsContainer.innerHTML = '';
            tagsPreview.innerHTML = '';
            
            let tags = [];
            try {
                tags = JSON.parse(this.value);
                if (!Array.isArray(tags)) tags = [];
            } catch {
                tags = this.value.split(',').map(t => t.trim()).filter(t => t);
            }
            
            tags.forEach(tag => {
                // Preview trong form
                const previewSpan = document.createElement('span');
                previewSpan.className = 'badge bg-success me-1 mb-1';
                previewSpan.innerHTML = `<i class="bi bi-tag me-1"></i>${tag}`;
                tagsPreview.appendChild(previewSpan);
                
                // Preview bên phải
                const span = document.createElement('span');
                span.className = 'badge bg-secondary text-white me-1 mb-1 px-2 py-1';
                span.innerHTML = `<i class="bi bi-tag me-1"></i>${tag}`;
                tagsContainer.appendChild(span);
            });
        });
    </script>
@endsection