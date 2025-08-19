@extends('layouts.auth')
@section('content')
    <div class="container py-4 container-custom">
        <h1 class="text-center mb-4 fw-bold">Chỉnh sửa tour</h1>
        <div class="row justify-content-center">
            <!-- Form bên trái -->
            <div class="col-lg-6">
                <div class="shadow-sm bg-light p-2">
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
                        <form action="{{ route('ceo.tours.update', $tour->id) }}" method="POST"
                            enctype="multipart/form-data" id="tourForm">
                            @csrf
                            @method('PUT')
                            <!-- Ảnh chính -->
                            <div class="mb-4">
                                <label class="form-label">Ảnh chính tour <span class="text-danger">*</span></label>
                                
                                <!-- Hiển thị ảnh chính hiện tại -->
                                @if($tour->image)
                                <div class="mb-3">
                                    <h6>Ảnh chính hiện tại:</h6>
                                    <div class="position-relative d-inline-block">
                                        <img src="{{ Storage::url($tour->image) }}" alt="Ảnh chính hiện tại" 
                                             class="img-fluid rounded" style="height: 200px; object-fit: cover;">
                                        <span class="badge bg-success position-absolute top-0 start-0 m-1">Ảnh chính</span>
                                    </div>
                                    <div class="form-text">Chọn ảnh mới để thay thế</div>
                                </div>
                                @endif
                                
                                <!-- Chọn ảnh chính mới -->
                                <div id="main-drop-area"
                                    class="p-4 mb-3 border border-2 border-success border-dashed rounded bg-light position-relative"
                                    style="cursor:pointer;">
                                    <div id="main-drop-text" class="text-secondary text-center">
                                        <i class="bi bi-image" style="font-size:2rem;"></i><br>
                                        <span>Chọn ảnh chính (bắt buộc)</span>
                                    </div>
                                    <input type="file" class="form-control d-none" id="main_image" name="main_image" accept="image/*">
                                    <img id="main-preview" class="img-fluid rounded mt-2" style="max-height: 200px; display: none;">
                                </div>
                            </div>

                            <!-- Ảnh gallery -->
                            <div class="mb-4">
                                <label class="form-label">Ảnh phụ (Gallery)</label>
                                
                                <!-- Hiển thị ảnh gallery hiện tại -->
                                @if($tour->images && $tour->images->count() > 0)
                                <div class="mb-3">
                                    <h6>Ảnh phụ hiện tại:</h6>
                                    <div class="d-flex flex-wrap gap-2" id="current-gallery-images">
                                        @foreach($tour->images->sortBy('sort_order') as $image)
                                        <div class="position-relative" style="width: 100px; height: 100px; flex-shrink: 0;" id="current-gallery-image-{{ $image->id }}">
                                            <img src="{{ $image->full_image_url }}" alt="Gallery image" 
                                                 class="img-fluid rounded" style="width: 100%; height: 100%; object-fit: cover;">
                                            <span class="badge bg-info position-absolute top-0 start-0 m-1" style="font-size: 0.6rem;">Phụ {{ $loop->iteration }}</span>
                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" 
                                                    onclick="deleteGalleryImage({{ $tour->id }}, {{ $image->id }})"
                                                    style="font-size: 0.6rem; padding: 1px 4px;" title="Xóa ảnh">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="form-text">Dùng nút X để xóa ảnh phụ</div>
                                </div>
                                @endif
                                
                                <!-- Thêm ảnh gallery mới -->
                                <div id="gallery-drop-area"
                                    class="p-4 mb-3 border border-2 border-primary border-dashed rounded bg-light position-relative"
                                    style="cursor:pointer;">
                                    <div id="gallery-drop-text" class="text-secondary text-center">
                                        <i class="bi bi-images" style="font-size:2rem;"></i><br>
                                        <span>Thêm ảnh phụ (tùy chọn, tối đa 9 ảnh)</span>
                                    </div>
                                    <input type="file" class="form-control d-none" id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                                </div>
                                <!-- Preview ảnh gallery mới -->
                                <div id="gallery-preview-container" class="d-flex flex-wrap gap-2 mt-2" style="display: none;">
                                    <!-- Previews sẽ được thêm vào đây bằng JavaScript -->
                                </div>
                                <div class="form-text">Chỉ nhận file ảnh (jpg, png, webp...). Tối đa 9 ảnh phụ.</div>
                            </div>
                            <!-- Bãi biển -->
                            <div class="mb-3">
                                <label for="beach_id" class="form-label">Bãi biển</label>
                                <select class="form-control" id="beach_id" name="beach_id" required>
                                    <option value="">Chọn bãi biển</option>
                                    @foreach($beaches as $beach)
                                        <option value="{{ $beach->id }}" data-region="{{ $beach->region->name }}" {{ old('beach_id', $tour->beach_id) == $beach->id ? 'selected' : '' }}>{{ $beach->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Tên tour -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Tên tour</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Nhập tên tour"
                                    required value="{{ old('title', $tour->title) }}">
                            </div>
                            <!-- Giá -->
                            <div class="mb-3">
                                <label for="price" class="form-label">Giá (triệu đồng)</label>
                                <input type="number" class="form-control" id="price" name="price" placeholder="Nhập giá"
                                    required value="{{ old('price', $tour->prices->last()->price ?? $tour->price ?? '') }}" min="0" step="0.1">
                            </div>
                            <!-- Discount -->
                            <div class="mb-3">
                                <label for="discount" class="form-label">Giảm giá (%)</label>
                                <input type="number" class="form-control" id="discount" name="discount"
                                    placeholder="Nhập % giảm giá" value="{{ old('discount', $tour->prices->last()->discount ?? 0) }}"
                                    min="0" max="100" step="0.1">
                                <div class="form-text">Nhập số phần trăm giảm giá (0-100)</div>
                            </div>
                            <!-- Ngày bắt đầu áp dụng giá -->
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Ngày bắt đầu áp dụng giá</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="{{ old('start_date', optional($tour->prices->last())->start_date ? date('Y-m-d', strtotime($tour->prices->last()->start_date)) : date('Y-m-d')) }}">
                            </div>
                            <!-- Ngày kết thúc áp dụng giá -->
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Ngày kết thúc áp dụng giá</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    value="{{ old('end_date', optional($tour->prices->last())->end_date ? date('Y-m-d', strtotime($tour->prices->last()->end_date)) : date('Y-m-d', strtotime('+1 year'))) }}">
                            </div>
                            <!-- Sức chứa -->
                            <div class="mb-3">
                                <label for="capacity" class="form-label">Sức chứa</label>
                                <input type="number" class="form-control" id="capacity" name="capacity"
                                    placeholder="Nhập sức chứa" required value="{{ old('capacity', $tour->capacity) }}"
                                    min="1">
                            </div>
                            <!-- Thời lượng (số ngày) -->
                            <div class="mb-3">
                                <label for="duration_days" class="form-label">Thời lượng (số ngày)</label>
                                <input type="number" class="form-control" id="duration_days" name="duration_days"
                                    placeholder="Nhập số ngày" required
                                    value="{{ old('duration_days', $tour->duration_days) }}" min="1" max="30" step="1"
                                    oninput="updateReturnTime()">
                            </div>
                            <!-- Ngày giờ khởi hành -->
                            <div class="mb-3">
                                <label class="form-label">Ngày giờ khởi hành <span class="text-danger">*</span></label>
                                <div id="departure-dates-container">
                                    @if(optional($tour->detail)->departure_dates && count($tour->detail->departure_dates) > 0)
                                        @foreach($tour->detail->departure_dates as $index => $date)
                                        <div class="departure-date-item d-flex align-items-center mb-2">
                                            <input type="datetime-local" class="form-control departure-date-input" 
                                                   name="departure_dates[]" value="{{ date('Y-m-d\TH:i', strtotime($date)) }}" 
                                                   required min="{{ date('Y-m-d\TH:i') }}">
                                            @if($index == 0)
                                                <button type="button" class="btn btn-success btn-sm ms-2" onclick="addDepartureDate()">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeDepartureDate(this)">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                            @endif
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="departure-date-item d-flex align-items-center mb-2">
                                            <input type="datetime-local" class="form-control departure-date-input" 
                                                   name="departure_dates[]" required min="{{ date('Y-m-d\TH:i') }}">
                                            <button type="button" class="btn btn-success btn-sm ms-2" onclick="addDepartureDate()">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-text">Thêm nhiều ngày khởi hành cho tour. Ngày phải từ hôm nay trở đi</div>
                            </div>
                            <!-- Trạng thái -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="confirmed" {{ old('status', $tour->status) == 'confirmed' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="pending" {{ old('status', $tour->status) == 'pending' ? 'selected' : '' }}>Chờ</option>
                                    <option value="cancelled" {{ old('status', $tour->status) == 'cancelled' ? 'selected' : '' }}>Hết hạn</option>
                                </select>
                            </div>
                            <!-- Dịch vụ bao gồm -->
                            <div class="mb-3">
                                <label for="included_services" class="form-label">Dịch vụ bao gồm (mỗi dòng 1 dịch
                                    vụ)</label>
                                <textarea class="form-control" id="included_services" name="included_services" rows="2"
                                    placeholder="Nhập các dịch vụ bao gồm, mỗi dòng 1 dịch vụ">{{ old('included_services', optional($tour->detail)->included_services && is_array($tour->detail->included_services) ? implode("\n", $tour->detail->included_services) : '') }}</textarea>
                            </div>
                            <!-- Không bao gồm -->
                            <div class="mb-3">
                                <label for="excluded_services" class="form-label">Không bao gồm (mỗi dòng 1 mục)</label>
                                <textarea class="form-control" id="excluded_services" name="excluded_services" rows="2"
                                    placeholder="Nhập các mục không bao gồm, mỗi dòng 1 mục">{{ old('excluded_services', optional($tour->detail)->excluded_services && is_array($tour->detail->excluded_services) ? implode("\n", $tour->detail->excluded_services) : '') }}</textarea>
                            </div>
                            <!-- Điểm nổi bật -->
                            <div class="mb-3">
                                <label for="highlights" class="form-label">Điểm nổi bật (mỗi dòng 1 điểm)</label>
                                <textarea class="form-control" id="highlights" name="highlights" rows="2"
                                    placeholder="Nhập các điểm nổi bật, mỗi dòng 1 điểm">{{ old('highlights', optional($tour->detail)->highlights && is_array($tour->detail->highlights) ? implode("\n", $tour->detail->highlights) : '') }}</textarea>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success px-4">Cập nhật</button>
                                <a href="{{ route('ceo.tours.index') }}" class="btn btn-secondary px-4">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Preview bên phải -->
            <div class="col-lg-6">
                <div class="shadow-sm mb-3 bg-light p-2">
                    <div class="card-body p-3">                            <div class="text-center mb-3">
                            @php
                                $img = $tour->image ? Storage::url($tour->image) : '';
                            @endphp
                            <img id="previewImageShow"
                                src="{{ $img ?: 'https://via.placeholder.com/900x350?text=Preview+Image' }}"
                                alt="Preview" class="img-fluid rounded" style="max-height:350px;object-fit:cover;">
                        </div>
                        <table class="table table-bordered table-striped mb-3">
                            <tbody>
                                <tr>
                                    <th>Khu vực</th>
                                    <td id="previewRegion">{{ $tour->beach?->region?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tên tour</th>
                                    <td id="previewTitle">{{ $tour->title ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Bãi biển</th>
                                    <td id="previewBeach">{{ $tour->beach?->title ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái</th>
                                    <td id="previewStatus">
                                        @php
                                            $statusText = match($tour->status) {
                                                'confirmed' => 'Hoạt động',
                                                'pending' => 'Chờ',
                                                'cancelled' => 'Hết hạn',
                                                default => '-'
                                            };
                                        @endphp
                                        {{ $statusText }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Thời lượng</th>
                                    <td><span id="previewDuration">{{ $tour->duration_days }}</span> ngày</td>
                                </tr>
                                <tr>
                                    <th>Sức chứa</th>
                                    <td><span id="previewCapacity">{{ $tour->capacity }}</span> người</td>
                                </tr>
                                <tr>
                                    <th>Giá</th>
                                    <td>
                                        @php
                                            $currentPrice = $tour->current_price_details;
                                            $originalPrice = $currentPrice['original_price'] ?? 0;
                                            $finalPrice = $currentPrice['final_price'] ?? 0;
                                            $discount = $currentPrice['discount'] ?? 0;
                                        @endphp
                                        <span class="text-danger fw-bold" id="previewPrice">{{ number_format($finalPrice, 2) }}</span> triệu đồng
                                        @if($discount > 0)
                                            <span class="text-decoration-line-through text-muted" id="previewOriginalPrice">
                                                Giá gốc: {{ number_format($originalPrice, 2) }} triệu
                                            </span>
                                            <div id="previewDiscount" class="text-success small">
                                                Giảm {{ $discount }}%
                                            </div>
                                        @else
                                            <span id="previewOriginalPrice"></span>
                                            <div id="previewDiscount"></div>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Thời gian áp dụng giá</th>
                                    <td id="previewPricePeriod">
                                        @php
                                            $latestPrice = $tour->prices->last();
                                            $startDate = $latestPrice->start_date ?? null;
                                            $endDate = $latestPrice->end_date ?? null;
                                        @endphp
                                        @if($startDate && $endDate)
                                            {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày khởi hành</th>
                                    <td id="previewDepartureDates">
                                        @if(optional($tour->detail)->departure_dates && count($tour->detail->departure_dates) > 0)
                                            {{ implode(', ', array_map(fn($date) => date('d/m/Y H:i', strtotime($date)), $tour->detail->departure_dates)) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dịch vụ bao gồm</th>
                                    <td>
                                        <ul class="mb-0" id="previewIncludedServices">
                                            @if(optional($tour->detail)->included_services && is_array($tour->detail->included_services))@foreach($tour->detail->included_services as $service)
                                            <li>{{ $service }}</li>@endforeach @endif
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Không bao gồm</th>
                                    <td>
                                        <ul class="mb-0" id="previewExcludedServices">
                                            @if(optional($tour->detail)->excluded_services && is_array($tour->detail->excluded_services))@foreach($tour->detail->excluded_services as $service)
                                            <li>{{ $service }}</li>@endforeach @endif
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Điểm nổi bật</th>
                                    <td>
                                        <ul class="mb-0" id="previewHighlights">
                                            @if(optional($tour->detail)->highlights && is_array($tour->detail->highlights))@foreach($tour->detail->highlights as $highlight)
                                            <li>{{ $highlight }}</li>@endforeach @endif
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Xử lý ảnh chính
        const mainDropArea = document.getElementById('main-drop-area');
        const mainImageInput = document.getElementById('main_image');
        const mainPreview = document.getElementById('main-preview');
        const mainDropText = document.getElementById('main-drop-text');

        mainDropArea.addEventListener('click', () => mainImageInput.click());
        mainDropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            mainDropArea.classList.add('bg-success', 'bg-opacity-10');
        });
        mainDropArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            mainDropArea.classList.remove('bg-success', 'bg-opacity-10');
        });
        mainDropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            mainDropArea.classList.remove('bg-success', 'bg-opacity-10');
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                mainImageInput.files = e.dataTransfer.files;
                previewMainImage(e.dataTransfer.files[0]);
            }
        });
        mainImageInput.addEventListener('change', (e) => {
            if (e.target.files && e.target.files[0]) {
                previewMainImage(e.target.files[0]);
            }
        });

        function previewMainImage(file) {
            const url = URL.createObjectURL(file);
            mainPreview.src = url;
            mainPreview.style.display = 'block';
            mainDropText.style.display = 'none';
            
            // Cập nhật preview chính
            document.getElementById('previewImageShow').src = url;
        }

        // Xử lý ảnh phụ (gallery)
        const galleryDropArea = document.getElementById('gallery-drop-area');
        const galleryImageInput = document.getElementById('gallery_images');
        const galleryPreviewContainer = document.getElementById('gallery-preview-container');
        const galleryDropText = document.getElementById('gallery-drop-text');
        let selectedGalleryFiles = [];

        galleryDropArea.addEventListener('click', () => galleryImageInput.click());
        galleryDropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            galleryDropArea.classList.add('bg-primary', 'bg-opacity-10');
        });
        galleryDropArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            galleryDropArea.classList.remove('bg-primary', 'bg-opacity-10');
        });
        galleryDropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            galleryDropArea.classList.remove('bg-primary', 'bg-opacity-10');
            if (e.dataTransfer.files) {
                handleGalleryFiles(Array.from(e.dataTransfer.files));
            }
        });
        galleryImageInput.addEventListener('change', (e) => {
            if (e.target.files) {
                handleGalleryFiles(Array.from(e.target.files));
            }
        });

        function handleGalleryFiles(files) {
            const currentGalleryCount = document.querySelectorAll('#current-gallery-images .col-6').length;
            const maxNewGallery = Math.max(0, 9 - currentGalleryCount);
            const imageFiles = files.filter(file => file.type.startsWith('image/')).slice(0, maxNewGallery);
            
            selectedGalleryFiles = selectedGalleryFiles.concat(imageFiles);
            selectedGalleryFiles = selectedGalleryFiles.slice(0, maxNewGallery);
            
            updateGalleryFileInput();
            displayGalleryPreviews();
        }

        function updateGalleryFileInput() {
            const dt = new DataTransfer();
            selectedGalleryFiles.forEach(file => dt.items.add(file));
            galleryImageInput.files = dt.files;
        }

        function displayGalleryPreviews() {
            galleryPreviewContainer.innerHTML = '';
            
            if (selectedGalleryFiles.length > 0) {
                galleryDropText.style.display = 'none';
                galleryPreviewContainer.style.display = 'flex';
                
                selectedGalleryFiles.forEach((file, index) => {
                    const imageWrapper = document.createElement('div');
                    imageWrapper.className = 'position-relative';
                    imageWrapper.style.cssText = 'width: 100px; height: 100px; flex-shrink: 0;';
                    
                    const url = URL.createObjectURL(file);
                    imageWrapper.innerHTML = `
                        <img src="${url}" alt="New Gallery ${index + 1}" 
                             class="img-fluid rounded" style="width: 100%; height: 100%; object-fit: cover;">
                        <span class="badge bg-success position-absolute top-0 start-0 m-1" style="font-size: 0.6rem;">Mới ${index + 1}</span>
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" 
                                onclick="removeGalleryImage(${index})" style="font-size: 0.6rem; padding: 1px 4px;">
                            <i class="bi bi-x"></i>
                        </button>
                    `;
                    galleryPreviewContainer.appendChild(imageWrapper);
                });
            } else {
                galleryDropText.style.display = 'block';
                galleryPreviewContainer.style.display = 'none';
            }
        }

        function removeGalleryImage(index) {
            selectedGalleryFiles.splice(index, 1);
            updateGalleryFileInput();
            displayGalleryPreviews();
        }

        // Xóa ảnh gallery hiện tại
        function deleteGalleryImage(tourId, imageId) {
            if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
                fetch(`/ceo/tours/${tourId}/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`current-gallery-image-${imageId}`).remove();
                    } else {
                        alert('Có lỗi xảy ra khi xóa ảnh');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa ảnh');
                });
            }
        }

        // Xử lý departure dates  
        function addDepartureDate() {
            const container = document.getElementById('departure-dates-container');
            const newItem = document.createElement('div');
            newItem.className = 'departure-date-item d-flex align-items-center mb-2';
            newItem.innerHTML = `
                <input type="datetime-local" class="form-control departure-date-input" 
                       name="departure_dates[]" required min="{{ date('Y-m-d\TH:i') }}">
                <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeDepartureDate(this)">
                    <i class="bi bi-dash"></i>
                </button>
            `;
            container.appendChild(newItem);
            updateDepartureDatesPreview();
        }

        function removeDepartureDate(button) {
            button.closest('.departure-date-item').remove();
            updateDepartureDatesPreview();
        }

        function updateDepartureDatesPreview() {
            const inputs = document.querySelectorAll('.departure-date-input');
            const previewDiv = document.getElementById('previewDepartureDates');
            if (previewDiv) {
                const dates = Array.from(inputs).map(input => input.value ? new Date(input.value).toLocaleDateString('vi-VN') : '').filter(d => d);
                previewDiv.textContent = dates.length > 0 ? dates.join(', ') : '-';
            }
        }

        // Event listeners cho departure dates
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('departure-date-input')) {
                updateDepartureDatesPreview();
            }
        });

        // Live preview các trường form
        function formatMoney(val) {
            if (!val) return '0';
            return parseFloat(val).toLocaleString('vi-VN', { maximumFractionDigits: 2 });
        }

        function calculateDiscountedPrice() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const discount = parseFloat(document.getElementById('discount').value) || 0;
            
            if (discount > 0) {
                const discountedPrice = price - (price * discount / 100);
                document.getElementById('previewPrice').textContent = formatMoney(discountedPrice);
                document.getElementById('previewDiscount').textContent = 'Giảm ' + discount + '%';
                document.getElementById('previewOriginalPrice').textContent = 'Giá gốc: ' + formatMoney(price) + ' triệu';
            } else {
                document.getElementById('previewPrice').textContent = formatMoney(price);
                document.getElementById('previewDiscount').textContent = '';
                document.getElementById('previewOriginalPrice').textContent = '';
            }
        }

        function updatePricePeriod() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            
            if (startDate && endDate) {
                const startFormatted = new Date(startDate).toLocaleDateString('vi-VN');
                const endFormatted = new Date(endDate).toLocaleDateString('vi-VN');
                document.getElementById('previewPricePeriod').textContent = startFormatted + ' - ' + endFormatted;
            } else if (startDate) {
                const startFormatted = new Date(startDate).toLocaleDateString('vi-VN');
                document.getElementById('previewPricePeriod').textContent = startFormatted + ' - ...';
            } else if (endDate) {
                const endFormatted = new Date(endDate).toLocaleDateString('vi-VN');
                document.getElementById('previewPricePeriod').textContent = '... - ' + endFormatted;
            } else {
                document.getElementById('previewPricePeriod').textContent = '-';
            }
        }

        // Cải thiện hàm tính toán thời gian trở về
        function updateReturnTime() {
            var dep = document.getElementById('departure_time').value;
            var days = parseInt(document.getElementById('duration_days').value);
            if (dep && !isNaN(days) && days > 0) {
                var depDate = new Date(dep);
                var retDate = new Date(depDate.getTime() + (days * 24 * 60 * 60 * 1000));
                document.getElementById('return_time').value = retDate.toISOString().slice(0, 16);

                // Cập nhật preview
                document.getElementById('previewReturn').textContent = formatDateTime(retDate);
            }
        }

        // Hàm format datetime cho preview
        function formatDateTime(date) {
            if (!date) return '-';
            return date.toLocaleDateString('vi-VN') + ' ' + date.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
        }

        document.addEventListener('DOMContentLoaded', function () {
            updateReturnTime();
        });

        document.getElementById('title').addEventListener('input', function () {
            document.getElementById('previewTitle').textContent = this.value || 'Tên tour';
        });
        document.getElementById('beach_id').addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            document.getElementById('previewBeach').textContent = selected.text || '-';
            document.getElementById('previewRegion').textContent = selected.dataset.region || '-';
        });
        // Gán data-region cho option
        Array.from(document.getElementById('beach_id').options).forEach(opt => {
            @foreach($beaches as $beach)
                if (opt.value == '{{ $beach->id }}') opt.dataset.region = '{{ $beach->region->name }}';
            @endforeach
        });
        document.getElementById('status').addEventListener('change', function () {
            document.getElementById('previewStatus').textContent = this.options[this.selectedIndex].text || '-';
        });
        document.getElementById('price').addEventListener('input', calculateDiscountedPrice);
        document.getElementById('discount').addEventListener('input', calculateDiscountedPrice);
        document.getElementById('start_date').addEventListener('change', updatePricePeriod);
        document.getElementById('end_date').addEventListener('change', updatePricePeriod);
        document.getElementById('capacity').addEventListener('input', function () {
            document.getElementById('previewCapacity').textContent = this.value || '0';
        });
        document.getElementById('duration_days').addEventListener('input', function () {
            document.getElementById('previewDuration').textContent = this.value || '1';
        });
        document.getElementById('included_services').addEventListener('input', function () {
            const ul = document.getElementById('previewIncludedServices');
            ul.innerHTML = '';
            this.value.split('\n').forEach(line => {
                if (line.trim()) {
                    const li = document.createElement('li');
                    li.textContent = line.trim();
                    ul.appendChild(li);
                }
            });
        });
        document.getElementById('excluded_services').addEventListener('input', function () {
            const ul = document.getElementById('previewExcludedServices');
            ul.innerHTML = '';
            this.value.split('\n').forEach(line => {
                if (line.trim()) {
                    const li = document.createElement('li');
                    li.textContent = line.trim();
                    ul.appendChild(li);
                }
            });
        });
        document.getElementById('highlights').addEventListener('input', function () {
            const ul = document.getElementById('previewHighlights');
            ul.innerHTML = '';
            this.value.split('\n').forEach(line => {
                if (line.trim()) {
                    const li = document.createElement('li');
                    li.textContent = line.trim();
                    ul.appendChild(li);
                }
            });
        });
        // Initial trigger for preview
        document.getElementById('title').dispatchEvent(new Event('input'));
        document.getElementById('beach_id').dispatchEvent(new Event('change'));
        document.getElementById('status').dispatchEvent(new Event('change'));
        calculateDiscountedPrice();
        updatePricePeriod();
        document.getElementById('capacity').dispatchEvent(new Event('input'));
        document.getElementById('duration_days').dispatchEvent(new Event('input'));
        document.getElementById('included_services').dispatchEvent(new Event('input'));
        document.getElementById('excluded_services').dispatchEvent(new Event('input'));
        document.getElementById('highlights').dispatchEvent(new Event('input'));
        
        // Initial update for departure dates preview
        updateDepartureDatesPreview();
    </script>
@endsection