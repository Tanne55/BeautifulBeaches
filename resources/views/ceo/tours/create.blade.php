@extends('layouts.auth')
@section('content')
    <div class="container py-4 container-custom">
        <h1 class="text-center mb-4 fw-bold">Thêm tour mới</h1>
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
                        <form action="{{ route('ceo.tours.store') }}" method="POST" enctype="multipart/form-data"
                            id="tourForm">
                            @csrf
                            <!-- Ảnh chính -->
                            <div class="mb-4">
                                <label for="main_image" class="form-label">Ảnh chính <span
                                        class="text-danger">*</span></label>
                                <div id="main-drop-area"
                                    class="p-4 mb-3 border border-2 border-success border-dashed rounded bg-light position-relative"
                                    style="cursor:pointer;">
                                    <div id="main-drop-text" class="text-secondary text-center">
                                        <i class="bi bi-image" style="font-size:2rem;"></i><br>
                                        <span>Chọn ảnh chính cho tour</span>
                                    </div>
                                    <img id="main-preview" class="img-fluid rounded"
                                        style="max-height:200px;object-fit:cover;display:none;">
                                    <input type="file" class="form-control d-none" id="main_image" name="main_image"
                                        accept="image/*" required>
                                </div>
                            </div>

                            <!-- Ảnh phụ -->
                            <div class="mb-4">
                                <label class="form-label">Ảnh phụ (tối đa 9 ảnh)</label>
                                <div id="gallery-drop-area"
                                    class="p-4 mb-3 border border-2 border-primary border-dashed rounded bg-light position-relative"
                                    style="cursor:pointer;">
                                    <div id="gallery-drop-text" class="text-secondary text-center">
                                        <i class="bi bi-images" style="font-size:2rem;"></i><br>
                                        <span>Kéo & thả ảnh vào đây hoặc bấm để chọn ảnh từ máy</span>
                                    </div>
                                    <input type="file" class="form-control d-none" id="gallery_images"
                                        name="gallery_images[]" accept="image/*" multiple>
                                </div>
                                <!-- Preview container -->
                                <div id="gallery-preview-container" class="d-flex flex-wrap gap-2 mt-2"
                                    style="display: none;">
                                    <!-- Previews sẽ được thêm vào đây bằng JavaScript -->
                                </div>
                                <div class="form-text">Chỉ nhận file ảnh (jpg, png, webp...). Ảnh chính và ảnh phụ riêng
                                    biệt.</div>
                            </div>
                            <!-- Bãi biển -->
                            <div class="mb-3">
                                <label for="beach_id" class="form-label">Bãi biển</label>
                                <select class="form-control" id="beach_id" name="beach_id" required>
                                    <option value="">Chọn bãi biển</option>
                                    @foreach($beaches as $beach)
                                        <option value="{{ $beach->id }}" {{ old('beach_id') == $beach->id ? 'selected' : '' }}>
                                            {{ $beach->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Tên tour -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Tên tour</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Nhập tên tour"
                                    required value="{{ old('title') }}">
                            </div>
                            <!-- Giá -->
                            <div class="mb-3">
                                <label for="price" class="form-label">Giá (triệu đồng)</label>
                                <input type="number" class="form-control" id="price" name="price" placeholder="Nhập giá"
                                    required value="{{ old('price') }}" min="0" step="0.1">
                            </div>
                            <!-- Giá gốc (loại bỏ trường này) -->
                            <!-- Discount -->
                            <div class="mb-3">
                                <label for="discount" class="form-label">Giảm giá (%)</label>
                                <input type="number" class="form-control" id="discount" name="discount"
                                    placeholder="Nhập % giảm giá" value="{{ old('discount') }}" min="0" max="100"
                                    step="0.1">
                                <div class="form-text">Nhập số phần trăm giảm giá (0-100)</div>
                            </div>
                            <!-- Ngày bắt đầu áp dụng giá -->
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Ngày bắt đầu áp dụng giá</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="{{ old('start_date', date('Y-m-d')) }}">
                            </div>
                            <!-- Ngày kết thúc áp dụng giá -->
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Ngày kết thúc áp dụng giá</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    value="{{ old('end_date', date('Y-m-d', strtotime('+1 year'))) }}">
                            </div>
                            <!-- Sức chứa -->
                            <div class="mb-3">
                                <label for="capacity" class="form-label">Sức chứa</label>
                                <input type="number" class="form-control" id="capacity" name="capacity"
                                    placeholder="Nhập sức chứa" required value="{{ old('capacity')}}" min="1" step="1">
                            </div>
                            <!-- Thời lượng (số ngày) -->
                            <div class="mb-3">
                                <label for="duration_days" class="form-label">Thời lượng (số ngày)</label>
                                <input type="number" class="form-control" id="duration_days" name="duration_days"
                                    placeholder="Nhập số ngày" required value="{{ old('duration_days', 1) }}" min="1"
                                    max="30" step="1" oninput="updateReturnTime()">
                            </div>
                            <!-- Ngày giờ khởi hành -->
                            <div class="mb-3">
                                <label class="form-label">Ngày giờ khởi hành <span class="text-danger">*</span></label>
                                <div id="departure-dates-container">
                                    <div class="departure-date-item d-flex align-items-center mb-2">
                                        <input type="datetime-local" class="form-control departure-date-input"
                                            name="departure_dates[]" required min="{{ date('Y-m-d\TH:i') }}">
                                        <button type="button" class="btn btn-success btn-sm ms-2"
                                            onclick="addDepartureDate()">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-text">Thêm nhiều ngày khởi hành cho tour. Ngày phải từ hôm nay trở đi</div>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    updateReturnTime();
                                });
                            </script>
                            <!-- Trạng thái -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Hoạt động
                                    </option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Chờ</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Hết hạn
                                    </option>
                                </select>
                            </div>
                            <!-- Dịch vụ bao gồm -->
                            <div class="mb-3">
                                <label for="included_services" class="form-label">Dịch vụ bao gồm (mỗi dòng 1 dịch
                                    vụ)</label>
                                <textarea class="form-control" id="included_services" name="included_services" rows="2"
                                    placeholder="Nhập các dịch vụ bao gồm, mỗi dòng 1 dịch vụ">{{ old('included_services') }}</textarea>
                            </div>
                            <!-- Không bao gồm -->
                            <div class="mb-3">
                                <label for="excluded_services" class="form-label">Không bao gồm (mỗi dòng 1 mục)</label>
                                <textarea class="form-control" id="excluded_services" name="excluded_services" rows="2"
                                    placeholder="Nhập các mục không bao gồm, mỗi dòng 1 mục">{{ old('excluded_services') }}</textarea>
                            </div>
                            <!-- Điểm nổi bật -->
                            <div class="mb-3">
                                <label for="highlights" class="form-label">Điểm nổi bật (mỗi dòng 1 điểm)</label>
                                <textarea class="form-control" id="highlights" name="highlights" rows="2"
                                    placeholder="Nhập các điểm nổi bật, mỗi dòng 1 điểm">{{ old('highlights') }}</textarea>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success px-4">Lưu</button>
                                <a href="{{ route('ceo.tours.index') }}" class="btn btn-secondary px-4">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Preview bên phải -->
            <div class="col-lg-6">
                <div class="shadow-sm mb-3 bg-light p-2">
                    <div class="card-body p-3">
                        <div class="text-center mb-3">
                            <img id="previewImageShow" src="https://via.placeholder.com/900x350?text=Preview+Image"
                                alt="Preview" class="img-fluid rounded" style="max-height:350px;object-fit:cover;">
                        </div>
                        <table class="table table-bordered table-striped mb-3">
                            <tbody>
                                <tr>
                                    <th>Khu vực</th>
                                    <td id="previewRegion">-</td>
                                </tr>
                                <tr>
                                    <th>Tên tour</th>
                                    <td id="previewTitle">Tên tour</td>
                                </tr>
                                <tr>
                                    <th>Bãi biển</th>
                                    <td id="previewBeach">-</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái</th>
                                    <td id="previewStatus">-</td>
                                </tr>
                                <tr>
                                    <th>Thời lượng</th>
                                    <td><span id="previewDuration">1</span> ngày</td>
                                </tr>
                                <tr>
                                    <th>Sức chứa</th>
                                    <td><span id="previewCapacity">0</span> người</td>
                                </tr>
                                <tr>
                                    <th>Giá</th>
                                    <td>
                                        <span class="text-danger fw-bold" id="previewPrice">0</span> triệu đồng
                                        <div id="previewDiscount" class="text-success small"></div>
                                        <div id="previewOriginalPrice"
                                            class="text-decoration-line-through text-muted small"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Thời gian áp dụng giá</th>
                                    <td id="previewPricePeriod">-</td>
                                </tr>
                                <tr>
                                    <th>Ngày khởi hành</th>
                                    <td id="previewDepartureDates">-</td>
                                </tr>
                                <tr>
                                    <th>Dịch vụ bao gồm</th>
                                    <td>
                                        <ul class="mb-0" id="previewIncludedServices"></ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Không bao gồm</th>
                                    <td>
                                        <ul class="mb-0" id="previewExcludedServices"></ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Điểm nổi bật</th>
                                    <td>
                                        <ul class="mb-0" id="previewHighlights"></ul>
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
            const imageFiles = files.filter(file => file.type.startsWith('image/')).slice(0, 9 - selectedGalleryFiles.length);
            selectedGalleryFiles = selectedGalleryFiles.concat(imageFiles);
            selectedGalleryFiles = selectedGalleryFiles.slice(0, 9);

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
                            <img src="${url}" alt="Gallery ${index + 1}" 
                                 class="img-fluid rounded" style="width: 100%; height: 100%; object-fit: cover;">
                            <span class="badge bg-info position-absolute top-0 start-0 m-1" style="font-size: 0.6rem;">Phụ ${index + 1}</span>
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
        document.addEventListener('change', function (e) {
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