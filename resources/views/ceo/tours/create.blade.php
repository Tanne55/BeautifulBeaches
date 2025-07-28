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
                                    required value="{{ old('price') }}" min="0" step="0.1" oninput="updateOriginalPrice()">
                            </div>
                            <!-- Giá gốc (ẩn) = 1.1 giá -->
                            <input type="hidden" id="original_price" name="original_price"
                                value="{{ old('original_price') }}">
                            <script>
                                function updateOriginalPrice() {
                                    var price = parseFloat(document.getElementById('price').value);
                                    if (!isNaN(price)) {
                                        document.getElementById('original_price').value = Math.round(price * 1.1 * 100) / 100;
                                    } else {
                                        document.getElementById('original_price').value = '';
                                    }
                                }
                                document.addEventListener('DOMContentLoaded', function () {
                                    updateOriginalPrice();
                                });
                            </script>
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
                                <label for="departure_time" class="form-label">Ngày giờ khởi hành</label>
                                <input type="datetime-local" class="form-control" id="departure_time" name="departure_time"
                                    value="{{ old('departure_time') }}" required oninput="updateReturnTime()"
                                    min="{{ date('Y-m-d\TH:i') }}">
                                <div class="form-text">Ngày khởi hành phải từ hôm nay trở đi</div>
                            </div>
                            <!-- Ngày giờ trở về (ẩn) -->
                            <input type="hidden" id="return_time" name="return_time" value="{{ old('return_time') }}">
                            <script>
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
                            </script>
                            <!-- Trạng thái -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Hoạt động
                                    </option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Chờ</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Hết hạn</option>
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
                                        <span class="text-decoration-line-through text-muted"
                                            id="previewOriginalPrice"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Giờ khởi hành</th>
                                    <td id="previewDeparture"></td>
                                </tr>
                                <tr>
                                    <th>Giờ trở về</th>
                                    <td id="previewReturn"></td>
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
            document.getElementById('drop-text').style.display = 'none';
        }

        // Live preview các trường form
        function formatMoney(val) {
            if (!val) return '0';
            return parseFloat(val).toLocaleString('vi-VN', { maximumFractionDigits: 2 });
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
        document.getElementById('price').addEventListener('input', function () {
            document.getElementById('previewPrice').textContent = formatMoney(this.value);
            const original = document.getElementById('original_price').value;
            document.getElementById('previewOriginalPrice').textContent = (original && original > this.value) ? '(' + formatMoney(original) + ')' : '';
        });
        document.getElementById('original_price').addEventListener('input', function () {
            const price = document.getElementById('price').value;
            document.getElementById('previewOriginalPrice').textContent = (this.value && this.value > price) ? '(' + formatMoney(this.value) + ')' : '';
        });
        document.getElementById('capacity').addEventListener('input', function () {
            document.getElementById('previewCapacity').textContent = this.value || '0';
        });
        document.getElementById('duration_days').addEventListener('input', function () {
            document.getElementById('previewDuration').textContent = this.value || '1';
        });
        document.getElementById('departure_time').addEventListener('input', function () {
            const depDate = this.value ? new Date(this.value) : null;
            document.getElementById('previewDeparture').textContent = depDate ? formatDateTime(depDate) : '-';
            updateReturnTime();
        });
        document.getElementById('return_time').addEventListener('input', function () {
            const retDate = this.value ? new Date(this.value) : null;
            document.getElementById('previewReturn').textContent = retDate ? formatDateTime(retDate) : '-';
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
        document.getElementById('price').dispatchEvent(new Event('input'));
        document.getElementById('original_price').dispatchEvent(new Event('input'));
        document.getElementById('capacity').dispatchEvent(new Event('input'));
        document.getElementById('duration_days').dispatchEvent(new Event('input'));
        document.getElementById('departure_time').dispatchEvent(new Event('input'));
        document.getElementById('return_time').dispatchEvent(new Event('input'));
        document.getElementById('included_services').dispatchEvent(new Event('input'));
        document.getElementById('excluded_services').dispatchEvent(new Event('input'));
        document.getElementById('highlights').dispatchEvent(new Event('input'));
    </script>
@endsection