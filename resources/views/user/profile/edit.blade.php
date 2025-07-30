@extends('layouts.guest')

@section('content')
    <div class="min-vh-100" style="background: linear-gradient(135deg, #ffffff 0%, #c2e9fb 100%);">
        <div class="container py-5">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-black-75 mb-3">
                    <i class="fas fa-user-edit me-3"></i>Chỉnh sửa thông tin cá nhân
                </h1>
                <p class="lead text-black-50">Cập nhật thông tin cá nhân của bạn</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class=" border-0 shadow-lg" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                        <div class="card-body p-4">
                            @if ($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row">
                                    <!-- Avatar Upload Section -->
                                    <div class="col-md-4 mb-4 mb-md-0">
                                        <div class="text-center">
                                            <div class="avatar-upload-container mb-3">
                                                <div class="current-avatar mb-3">
                                                    @if($user->avatar)
                                                        @php
                                                            // Check if avatar path contains "avatars/" which means it was uploaded by the user
                                                            // Otherwise it's a seeded image, so use the direct path
                                                            $avatarPath = strpos($user->avatar, 'avatars/') !== false ? 
                                                                asset('storage/' . $user->avatar) : 
                                                                asset($user->avatar);
                                                        @endphp
                                                        <img id="avatarPreview" src="{{ $avatarPath }}" alt="User Avatar" 
                                                            class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #4facfe;">
                                                    @else
                                                        <div id="defaultAvatar" class="default-avatar rounded-circle d-flex align-items-center justify-content-center"
                                                            style="width: 150px; height: 150px; background: linear-gradient(45deg, #4facfe, #00f2fe); margin: 0 auto;">
                                                            <span class="text-white display-4">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                        </div>
                                                        <img id="avatarPreview" src="" alt="Avatar Preview" class="rounded-circle img-thumbnail d-none" 
                                                            style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #4facfe;">
                                                    @endif
                                                </div>
                                                
                                                <div class="custom-file">
                                                    <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" 
                                                           style="border-radius: 10px;">
                                                    <small class="form-text text-muted mt-2">Hỗ trợ định dạng: JPG, PNG, GIF (Tối đa: 2MB)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Profile Form Fields -->
                                    <div class="col-md-8">
                                        <div class="row g-3">
                                            <!-- Thông tin cá nhân -->
                                            <div class="col-md-12">
                                                <h5 class="fw-bold mb-3">Thông tin cơ bản</h5>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                                           style="border-radius: 10px; padding: 10px;">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email" value="{{ $user->email }}" disabled
                                                           style="border-radius: 10px; padding: 10px; background-color: #f9f9f9;">
                                                    <small class="form-text text-muted">Email không thể thay đổi</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="phone" class="form-label">Số điện thoại</label>
                                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                                           style="border-radius: 10px; padding: 10px;">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="dob" class="form-label">Ngày sinh</label>
                                                    <input type="date" class="form-control" id="dob" name="dob" 
                                                           value="{{ old('dob', $profile->dob ? $profile->dob->format('Y-m-d') : '') }}"
                                                           style="border-radius: 10px; padding: 10px;">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="nationality" class="form-label">Quốc tịch</label>
                                                    <input type="text" class="form-control" id="nationality" name="nationality" 
                                                           value="{{ old('nationality', $profile->nationality) }}"
                                                           style="border-radius: 10px; padding: 10px;">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="address" class="form-label">Địa chỉ</label>
                                                    <textarea class="form-control" id="address" name="address" rows="2" 
                                                              style="border-radius: 10px; padding: 10px;">{{ old('address', $user->address) }}</textarea>
                                                </div>
                                            </div>
                                            
                                            <!-- Tùy chọn người dùng -->
                                            <div class="col-md-12 mt-4">
                                                <h5 class="fw-bold mb-3">Tùy chọn hiển thị</h5>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label class="form-label d-block">Bãi biển yêu thích</label>
                                                    <select class="form-select" name="favorite_beaches[]" id="favoriteBeaches" multiple 
                                                            style="border-radius: 10px; padding: 10px; height: 100px;">
                                                        @foreach($beaches as $beach)
                                                            <option value="{{ $beach->id }}" 
                                                                {{ isset($profile->preferences['favorite_beaches']) && in_array($beach->id, $profile->preferences['favorite_beaches']) ? 'selected' : '' }}>
                                                                {{ $beach->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="form-text text-muted">Giữ Ctrl để chọn nhiều bãi biển</small>
                                                </div>
                                            </div>
                                            
                                            <!-- Theme selection temporarily removed -->
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                    <a href="{{ route('user.dashboard') }}" class="btn btn-secondary px-4"
                                       style="border-radius: 10px; padding: 10px 20px;">
                                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                                    </a>
                                    <button type="submit" class="btn text-white fw-semibold px-4"
                                            style="background: linear-gradient(45deg, #4facfe, #00f2fe); border: none; border-radius: 10px; padding: 10px 20px; transition: all 0.3s ease;"
                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(79,172,254,0.4)'"
                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Preview avatar before upload
    document.getElementById('avatar').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                const defaultAvatar = document.getElementById('defaultAvatar');
                
                // Đặt src từ dữ liệu file đã đọc
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                
                if (defaultAvatar) {
                    defaultAvatar.classList.add('d-none');
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
