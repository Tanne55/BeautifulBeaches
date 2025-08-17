<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Test - Beautiful Beaches</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    
    <!-- Custom CSS -->
    <style>
        .gallery-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }
        .gallery-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .primary-image {
            height: 300px;
            width: 100%;
            object-fit: cover;
            border-radius: 8px;
        }
        
        /* Modal z-index cao hơn header */
        .modal {
            z-index: 9999 !important;
        }
        
        .modal-backdrop {
            z-index: 9998 !important;
        }
        
        .swiper {
            width: 100%;
            height: 70vh;
        }
        
        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .swiper-slide img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .swiper-button-next, .swiper-button-prev {
            color: #fff;
            background: rgba(0,0,0,0.5);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            z-index: 10;
        }
        
        .swiper-pagination {
            z-index: 10;
        }
        
        .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: rgba(255,255,255,0.8);
        }
        
        .swiper-pagination-bullet-active {
            background: #fff;
        }
        
        .modal-header {
            border-bottom: none;
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 11;
        }
        
        .modal-body {
            padding: 0;
        }
        
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .image-caption {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 15px;
            border-radius: 8px;
            z-index: 10;
        }
        
        .image-type-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 10;
        }
        
        .image-counter {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            z-index: 10;
            font-size: 14px;
        }
        
        /* Đảm bảo navbar không che modal */
        .navbar {
            z-index: 1030 !important;
        }
        
        /* Animation cho modal */
        .modal.fade .modal-dialog {
            transform: scale(0.8);
            transition: transform 0.3s ease-out;
        }
        
        .modal.show .modal-dialog {
            transform: scale(1);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <!-- Beach Section -->
        <div class="row mb-5">
            @foreach($beaches as $beach)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="gallery-card" 
                     onclick="openGallery('beach', {{ $beach->id }}, '{{ $beach->title }}')">
                    <div class="position-relative">
                        @if($beach->primaryImage)
                            <img src="{{ $beach->primaryImage->getFullImageUrlAttribute() }}" 
                                 class="primary-image" 
                                 alt="{{ $beach->primaryImage->alt_text }}">
                        @elseif($beach->images->first())
                            <img src="{{ $beach->images->first()->getFullImageUrlAttribute() }}" 
                                 class="primary-image" 
                                 alt="{{ $beach->images->first()->alt_text }}">
                        @else
                            <div class="primary-image bg-secondary d-flex align-items-center justify-content-center">
                                <span class="text-white">Không có ảnh</span>
                            </div>
                        @endif
                        
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-primary">{{ $beach->images->count() }} ảnh</span>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Tour Section -->
        <div class="row">
            @foreach($tours as $tour)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="gallery-card" 
                     onclick="openGallery('tour', {{ $tour->id }}, '{{ $tour->title }}')">
                    <div class="position-relative">
                        @if($tour->primaryImage)
                            <img src="{{ $tour->primaryImage->getFullImageUrlAttribute() }}" 
                                 class="primary-image" 
                                 alt="{{ $tour->primaryImage->alt_text }}">
                        @elseif($tour->images->first())
                            <img src="{{ $tour->images->first()->getFullImageUrlAttribute() }}" 
                                 class="primary-image" 
                                 alt="{{ $tour->images->first()->alt_text }}">
                        @else
                            <div class="primary-image bg-secondary d-flex align-items-center justify-content-center">
                                <span class="text-white">Không có ảnh</span>
                            </div>
                        @endif
                        
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-success">{{ $tour->images->count() }} ảnh</span>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Gallery Modal -->
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="galleryModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Image Counter -->
                    <div class="image-counter" id="imageCounter">
                        1 / 1
                    </div>
                    
                    <!-- Swiper -->
                    <div class="swiper gallerySwiper">
                        <div class="swiper-wrapper" id="swiperWrapper">
                            <!-- Slides will be added dynamically -->
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>

    <script>
        let swiper;
        
        function openGallery(type, id, title) {
            // Set modal title
            document.getElementById('galleryModalTitle').textContent = title;
            
            // Show loading state
            const wrapper = document.getElementById('swiperWrapper');
            wrapper.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            
            // Show modal immediately
            const modal = new bootstrap.Modal(document.getElementById('galleryModal'));
            modal.show();
            
            // Fetch images via AJAX
            fetch(`/api/gallery/${type}/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.images.length > 0) {
                        loadGalleryImages(data.images);
                    } else {
                        wrapper.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 text-muted"><div class="text-center"><i class="fas fa-image fa-3x mb-3"></i><br><p>Không có ảnh nào để hiển thị</p></div></div>';
                        document.getElementById('imageCounter').textContent = '0 / 0';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    wrapper.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 text-danger"><div class="text-center"><i class="fas fa-exclamation-triangle fa-3x mb-3"></i><br><p>Có lỗi xảy ra khi tải ảnh</p></div></div>';
                    document.getElementById('imageCounter').textContent = '0 / 0';
                });
        }
        
        function loadGalleryImages(images) {
            const wrapper = document.getElementById('swiperWrapper');
            wrapper.innerHTML = '';
            
            images.forEach((image, index) => {
                const slide = document.createElement('div');
                slide.className = 'swiper-slide';
                slide.innerHTML = `
                    <div class="position-relative w-100 h-100">
                        <img src="${image.image_url}" alt="${image.alt_text || ''}" class="w-100 h-100 object-fit-cover" loading="lazy">
                        
                        ${image.image_type ? `
                        <div class="image-type-badge">
                            <span class="badge bg-info">${getImageTypeLabel(image.image_type)}</span>
                        </div>
                        ` : ''}
                        
                        ${image.caption ? `
                        <div class="image-caption">
                            <p class="mb-0">${image.caption}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
                wrapper.appendChild(slide);
            });
            
            // Update counter
            updateImageCounter(1, images.length);
            
            // Initialize/Update Swiper
            if (swiper) {
                swiper.destroy();
            }
            
            swiper = new Swiper('.gallerySwiper', {
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                keyboard: {
                    enabled: true,
                },
                mousewheel: {
                    forceToAxis: true,
                },
                loop: images.length > 1,
                autoplay: false,
                speed: 300,
                on: {
                    slideChange: function () {
                        updateImageCounter(this.realIndex + 1, images.length);
                    }
                }
            });
        }
        
        function updateImageCounter(current, total) {
            document.getElementById('imageCounter').textContent = `${current} / ${total}`;
        }
        
        function getImageTypeLabel(type) {
            const labels = {
                'hero': 'Ảnh chính',
                'gallery': 'Thư viện',
                'thumbnail': 'Thu nhỏ',
                'detail': 'Chi tiết',
                'panorama': 'Toàn cảnh',
                'activity': 'Hoạt động',
                'location': 'Địa điểm'
            };
            return labels[type] || type;
        }
        
        // Clean up swiper when modal is closed
        document.getElementById('galleryModal').addEventListener('hidden.bs.modal', function () {
            if (swiper) {
                swiper.destroy();
                swiper = null;
            }
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('galleryModal');
            if (modal.classList.contains('show')) {
                switch(e.key) {
                    case 'Escape':
                        bootstrap.Modal.getInstance(modal).hide();
                        break;
                    case 'ArrowLeft':
                        if (swiper) swiper.slidePrev();
                        break;
                    case 'ArrowRight':
                        if (swiper) swiper.slideNext();
                        break;
                }
            }
        });
    </script>
</body>
</html>
