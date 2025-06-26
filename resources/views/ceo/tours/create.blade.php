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
                        <form action="{{ route('ceo.tours.store') }}" method="POST" enctype="multipart/form-data">
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
                                        <option value="{{ $beach->id }}" {{ old('beach_id') == $beach->id ? 'selected' : '' }}>{{ $beach->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Tên tour -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Tên tour</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Nhập tên tour" required value="{{ old('title') }}">
                            </div>
                            <!-- Giá -->
                            <div class="mb-3">
                                <label for="price" class="form-label">Giá (đơn vị triệu đồng)</label>
                                <input type="number" class="form-control" id="price" name="price" placeholder="Nhập giá" required value="{{ old('price') }}" min="0" step="0.5" oninput="updateOriginalPrice()">
                            </div>
                            <!-- Giá gốc (ẩn) = 1.1 giá -->
                            <input type="hidden" id="original_price" name="original_price" value="{{ old('original_price') }}">
                            <script>
                                function updateOriginalPrice() {
                                    var price = parseFloat(document.getElementById('price').value);
                                    if (!isNaN(price)) {
                                        document.getElementById('original_price').value = Math.round(price * 1.1 * 100) / 100;
                                    } else {
                                        document.getElementById('original_price').value = '';
                                    }
                                }
                                document.addEventListener('DOMContentLoaded', function() {
                                    updateOriginalPrice();
                                });
                            </script>
                            <!-- Sức chứa -->
                            <div class="mb-3">
                                <label for="capacity" class="form-label">Sức chứa</label>
                                <input type="number" class="form-control" id="capacity" name="capacity" placeholder="Nhập sức chứa" required value="{{ old('capacity')}}" min="10" step="1">
                            </div>
                            <!-- Thời lượng (số ngày) -->
                            <div class="mb-3">
                                <label for="duration_days" class="form-label">Thời lượng (số ngày)</label>
                                <input type="number" class="form-control" id="duration_days" name="duration_days" placeholder="Nhập số ngày" required value="{{ old('duration_days', 1) }}" min="1" step="1" oninput="updateReturnTime()">
                            </div>
                            <!-- Ngày giờ khởi hành -->
                            <div class="mb-3">
                                <label for="departure_time" class="form-label">Ngày giờ khởi hành</label>
                                <input type="datetime-local" class="form-control" id="departure_time" name="departure_time" value="{{ old('departure_time') }}" required oninput="updateReturnTime()">
                            </div>
                            <!-- Ngày giờ trở về (ẩn) -->
                            <input type="hidden" id="return_time" name="return_time" value="{{ old('return_time') }}">
                            <script>
                                function updateReturnTime() {
                                    var dep = document.getElementById('departure_time').value;
                                    var days = parseInt(document.getElementById('duration_days').value);
                                    if(dep && !isNaN(days)) {
                                        var depDate = new Date(dep);
                                        var retDate = new Date(depDate.getTime() + (days * 24 * 60 * 60 * 1000));
                                        // Giữ nguyên giờ phút giây của departure_time
                                        document.getElementById('return_time').value = retDate.toISOString().slice(0,16);
                                    }
                                }
                                document.addEventListener('DOMContentLoaded', function() {
                                    updateReturnTime();
                                });
                            </script>
                            <!-- Trạng thái -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                                </select>
                            </div>
                            <!-- Dịch vụ bao gồm -->
                            <div class="mb-3">
                                <label for="included_services" class="form-label">Dịch vụ bao gồm (mỗi dòng 1 dịch vụ)</label>
                                <textarea class="form-control" id="included_services" name="included_services" rows="2" placeholder="Nhập các dịch vụ bao gồm, mỗi dòng 1 dịch vụ">{{ old('included_services') }}</textarea>
                            </div>
                            <!-- Không bao gồm -->
                            <div class="mb-3">
                                <label for="excluded_services" class="form-label">Không bao gồm (mỗi dòng 1 mục)</label>
                                <textarea class="form-control" id="excluded_services" name="excluded_services" rows="2" placeholder="Nhập các mục không bao gồm, mỗi dòng 1 mục">{{ old('excluded_services') }}</textarea>
                            </div>
                            <!-- Điểm nổi bật -->
                            <div class="mb-3">
                                <label for="highlights" class="form-label">Điểm nổi bật (mỗi dòng 1 điểm)</label>
                                <textarea class="form-control" id="highlights" name="highlights" rows="2" placeholder="Nhập các điểm nổi bật, mỗi dòng 1 điểm">{{ old('highlights') }}</textarea>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success px-4">Lưu</button>
                                <a href="{{ route('ceo.tours.index') }}" class="btn btn-secondary px-4">Quay lại</a>
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