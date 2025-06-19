@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
    <!--section 1 -->
    <section class="position-relative hh">
        <!-- Carousel -->
        <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/assets/image/14.jpg" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="carousel-title">Discover Tropical Escapes</h1>
                        <p class="carousel-subtitle">Breathe in the ocean breeze and explore nature’s paradise.</p>
                        <a href="/Pages/Beaches/details.html" class="btn btn-primary mb-5">Explore Now</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/assets/image/12.jpg" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="carousel-title">Sunset Serenity Awaits</h1>
                        <p class="carousel-subtitle">Unwind on golden sands under magical evening skies.</p>
                        <a href="/Pages/Beaches/details.html" class="btn btn-primary mb-5">Explore Now</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="/assets/image/16.jpg" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="carousel-title">Mui Ne: The Windy Beach</h1>
                        <p class="carousel-subtitle">A coastal gem with dunes, kitesurfing, and unforgettable vibes.</p>
                        <a href="/Pages/Beaches/details.html" class="btn btn-primary mb-5">Explore Now</a>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>
    <!-- Form tìm kiếm -->
    <div class="container position-absolute start-50 translate-middle-x"
        style="bottom:-60px; z-index:2; max-width:1100px; top: 75%;">
        <form action="/Pages/Explore/explore.html"
            class="bg-white rounded shadow p-5 row g-3 align-items-end banner-search-form">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Search Destination*</label>
                <input type="text" class="form-control" placeholder="Enter Destination"
                    style="background-color:#F8F8F8; border:none;">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Pax Number*</label>
                <input type="number" class="form-control" placeholder="No. of People"
                    style="background-color:#F8F8F8; border:none;">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Checkin Date*</label>
                <input type="date" class="form-control" style="background-color:#F8F8F8; border:none;">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Checkout Date*</label>
                <input type="date" class="form-control" style="background-color:#F8F8F8; border:none;">
            </div>
            <div class="col-md-3 d-flex justify-content-center">
                <button type="submit" class="btn btn-danger btn-lg banner-search-btn">INQUIRE NOW</button>
            </div>
        </form>
    </div>



    <!-- section 2 -->
    <section class="popular-destination py-5 mt-5">
        <div class="container">
            <div class="text-center mb-5 row">
                <div class="col-6">
                    <div class="d-flex align-items-center mb-4">
                        <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                        <p class="text-danger fw-bold m-0">POPULAR DESTINATION</p>
                    </div>

                    <h1 class="fw-bold">Escape to the Coastline</h1>
                </div>
                <div class="col-6 d-flex align-items-center text-start text-muted">
                    Explore pristine coastlines, crystal-clear waters, and hidden tropical gems.<br>
                    From vibrant seaside towns to quiet sandy escapes — your dream vacation awaits.
                </div>

            </div>


            <div class="row g-4 mx-5">
                <div class="col-sm-3">
                    <div class="position-relative rounded overflow-hidden destination-card" style="
                                                    background-image: url('/assets/img2/bien-ha-long.jpg');
                                                    background-size: cover;
                                                    background-position: center;
                                                    height: 500px;
                                                    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                                                  ">

                        <!-- Lớp overlay làm tối nền -->
                        <div class="position-absolute top-0 start-0 w-100 h-100"
                            style="background: rgba(0, 0, 0, 0.4); z-index: 1;"></div>

                        <!-- Tag span -->
                        <span class="position-absolute top-0 start-0 m-3 px-3 py-1 bg-info text-white fw-bold rounded"
                            style="z-index: 2;">
                            HA LONG
                        </span>

                        <!-- Thông tin dưới -->
                        <div class="position-absolute bottom-0 start-0 w-100 text-white p-3 "
                            style="background: linear-gradient(to top, rgba(0,0,0,0.6), transparent); z-index: 2;">
                            <h5 class="m-0 fw-bold">Ha Long Beach</h5>
                            <div class="text-warning mt-1">★★★★☆</div>
                        </div>

                    </div>
                </div>


                <div class="col-sm-3 ">
                    <div class="position-relative rounded overflow-hidden destination-card" style="
                                                    background-image: url('/assets/img2/bien-sam-son.jpg');
                                                    background-size: cover;
                                                    background-position: center;
                                                    height:  500px;
                                                    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                                                  ">

                        <!-- Lớp overlay làm tối nền -->
                        <div class="position-absolute top-0 start-0 w-100 h-100"
                            style="background: rgba(0, 0, 0, 0.4); z-index: 1;"></div>

                        <!-- Tag span -->
                        <span class="position-absolute top-0 start-0 m-3 px-3 py-1 bg-info text-white fw-bold rounded"
                            style="z-index: 2;">
                            THANH HOA
                        </span>

                        <!-- Thông tin dưới -->
                        <div class="position-absolute bottom-0 start-0 w-100 text-white p-3"
                            style="background: linear-gradient(to top, rgba(0,0,0,0.6), transparent); z-index: 2;">
                            <h5 class="m-0 fw-bold">Sam Son Beach</h5>
                            <div class="text-warning mt-1">★★★★☆</div>
                        </div>

                    </div>
                </div>

                <div class="col-5 d-flex flex-column" style="height: 500px; gap: 20px;">
                    <!-- Ảnh 1 -->
                    <div class="position-relative rounded overflow-hidden shadow-lg flex-fill destination-card" style="
                                                    background-image: url('/assets/img2/cat-ba.jpg');
                                                    background-size: cover;
                                                    background-position: center;">
                        <!-- Overlay -->
                        <div class="position-absolute top-0 start-0 w-100 h-100"
                            style="background: rgba(0, 0, 0, 0.35); z-index: 1;"></div>
                        <!-- Tag -->
                        <span class="position-absolute top-0 start-0 m-3 px-3 py-1 bg-info text-white fw-bold rounded"
                            style="z-index: 2;">HAI PHONG</span>
                        <!-- Nội dung -->
                        <div class="position-absolute bottom-0 start-0 w-100 text-white p-3"
                            style="background: linear-gradient(to top, rgba(0,0,0,0.6), transparent); z-index: 2;">
                            <h5 class="m-0 fw-bold">Cat Ba Beach</h5>
                            <div class="text-warning mt-1">★★★★★</div>
                        </div>
                    </div>

                    <!-- Ảnh 2 -->
                    <div class="position-relative rounded overflow-hidden shadow-lg flex-fill destination-card" style="
                                                    background-image: url('/assets/img2/nha-trang.jpg');
                                                    background-size: cover;
                                                    background-position: center;">
                        <!-- Overlay -->
                        <div class="position-absolute top-0 start-0 w-100 h-100"
                            style="background: rgba(0, 0, 0, 0.35); z-index: 1;"></div>
                        <!-- Tag -->
                        <span class="position-absolute top-0 start-0 m-3 px-3 py-1 bg-info text-white fw-bold rounded"
                            style="z-index: 2;">NHA TRANG</span>
                        <!-- Nội dung -->
                        <div class="position-absolute bottom-0 start-0 w-100 text-white p-3"
                            style="background: linear-gradient(to top, rgba(0,0,0,0.6), transparent); z-index: 2;">
                            <h5 class="m-0 fw-bold">Nha Trang Beach</h5>
                            <div class="text-warning mt-1">★★★★★</div>
                        </div>
                    </div>
                </div>

            </div>



            <div class="text-center mt-4">
                <a href="/Pages/Explore/explore.html" class="btn btn-danger">MORE DESTINATION</a>
            </div>
        </div>
    </section>



    <!-- section 3 -->
    <section style="padding-bottom: 100px ;">
        <div class="container">
            <div class="text-center mb-4 mt-5 row">
                <div class="d-flex align-items-center mb-4 justify-content-center">
                    <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                    <p class="text-danger fw-bold m-0">EXPLORE COASTAL PARADISES</p>
                </div>

                <h1 class="fw-bold">POPULAR BEACH PACKAGES</h1>
            </div>
            <div class="text-center text-muted mb-5">
                Discover handpicked beach destinations with golden sands, turquoise waters,<br>
                and all-inclusive packages designed for the perfect escape.
            </div>

            <div class="card-container">
                <div class="card m-2">
                    <div class="card-image" style="background-image: url('/assets/image/11.jpg');">
                        <div class="price-tag">$95.00 <span>/ per person</span></div>
                        <div class="card-meta">
                            <span><i class="bi bi-calendar"></i> 7D/6N</span>
                            <span><i class="bi bi-people"></i> People: 5</span>
                            <span><i class="bi bi-geo-alt"></i> Da Nang</span>
                        </div>
                    </div>
                    <div class="card-info mt-4">

                        <h3 class="card-title"><a href="#" class="card-title">Discovering the Pristine Beauty of My Khe
                                Beach</a>
                        </h3>
                        <div class="card-reviews">
                            <span>(18 reviews)</span>
                            <div class="stars">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-half text-warning"></i>
                                <i class="bi bi-star text-muted"></i>
                            </div>
                        </div>
                        <p class="card-description">
                            My Khe Beach is one of Vietnam's most beautiful beaches with fine white sand, crystal-clear blue
                            waters,
                            and fresh air.
                        </p>
                        <div class="card-actions">
                            <a href="/Pages/Explore/explore.html"
                                class="btn custom-btn fw-bold d-flex align-items-center">Book Now
                                <i class="bi bi-arrow-right ms-2 icon-red"></i></a>
                            <a href="#" class="btn custom-btn fw-bold d-flex align-items-center"> Wish List
                                <i class="bi bi-heart ms-2 icon-red"></i>
                            </a>

                        </div>

                    </div>
                </div>
                <div class="card m-2">
                    <div class="card-image" style="background-image: url('/assets/image/25.jpg');">
                        <div class="price-tag">$299.0 <span>/ per person</span></div>
                        <div class="card-meta">
                            <span><i class="bi bi-calendar"></i> 7D/6N</span>
                            <span><i class="bi bi-people"></i> People: 5</span>
                            <span><i class="bi bi-geo-alt"></i> Nha Trang</span>
                        </div>
                    </div>
                    <div class="card-info mt-4">

                        <h3 class="card-title mb-4"><a href="#" class="card-title">Sunset Bliss at Nha Trang Beach</a></h3>
                        <div class="card-reviews">
                            <span>(25 reviews)</span>
                            <div class="stars">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-half text-warning"></i>
                                <i class="bi bi-star text-muted"></i>
                            </div>
                        </div>
                        <p class="card-description">
                            Nha Trang Beach offers stunning views, gentle waves, and a peaceful atmosphere, making it
                            perfect for a
                            seaside escape.
                        </p>
                        <div class="card-actions">
                            <a href="/Pages/Explore/explore.html"
                                class="btn custom-btn fw-bold d-flex align-items-center">Book Now
                                <i class="bi bi-arrow-right ms-2 icon-red"></i></a>
                            <a href="#" class="btn custom-btn fw-bold d-flex align-items-center"> Wish List
                                <i class="bi bi-heart ms-2 icon-red"></i>
                            </a>

                        </div>

                    </div>
                </div>
                <div class="card m-2">
                    <div class="card-image" style="background-image: url('/assets/image/21.jpg');">
                        <div class="price-tag">$95.00<span>/ per person</span></div>
                        <div class="card-meta">
                            <span><i class="bi bi-calendar"></i> 7D/6N</span>
                            <span><i class="bi bi-people"></i> People: 5</span>
                            <span><i class="bi bi-geo-alt"></i> Phu Quoc</span>
                        </div>
                    </div>
                    <div class="card-info mt-4">

                        <h3 class="card-title"><a href="#" class="card-title">Exploring the Serenity of Phu Quoc Beach</a>
                        </h3>
                        <div class="card-reviews">
                            <span>(25 reviews)</span>
                            <div class="stars">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-half text-warning"></i>
                                <i class="bi bi-star text-muted"></i>
                            </div>
                        </div>
                        <p class="card-description">
                            Phu Quoc Beach offers stunning views, gentle waves, and a peaceful atmosphere, making it perfect
                            for a
                            seaside escape.
                        </p>
                        <div class="card-actions">
                            <a href="/Pages/Explore/explore.html"
                                class="btn custom-btn fw-bold d-flex align-items-center">Book Now
                                <i class="bi bi-arrow-right ms-2 icon-red"></i></a>
                            <a href="#" class="btn custom-btn fw-bold d-flex align-items-center"> Wish List
                                <i class="bi bi-heart ms-2 icon-red"></i>
                            </a>

                        </div>

                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="/Pages/Explore/explore.html" class="btn btn-danger">MORE DESTINATION</a>
            </div>
        </div>


    </section>

    <!-- Quảng cáo giữa trang Home -->
    <div style="
                                            background-image: url('https://th.bing.com/th/id/R.a1c253924f0b8c174d1b69ad0f1dad1e?rik=fuiE9k6pEIFZGQ&pid=ImgRaw&r=0');
                                            background-size: cover;
                                            background-position: center;
                                            padding: 60px 20px;
                                            position: relative;
                                            color: white;
                                          ">
        <!-- Lớp phủ tối để dễ đọc chữ -->
        <div style="
                                            position: absolute;
                                            top: 0; left: 0; right: 0; bottom: 0;
                                            background-color: rgba(0, 0, 0, 0.5);
                                            z-index: 1;
                                          "></div>

        <!-- Nội dung quảng cáo -->
        <div style="position: relative; z-index: 2; max-width: 900px; margin: auto; text-align: center;">
            <h2 class="fw-bold">🛒 Shop for Beach Essentials on Shopee!</h2>
            <p class="lead">
                Discover summer deals: bikinis, sunglasses, sunscreen & more!
            </p>
            <a href="https://shopee.vn/" target="_blank" class="btn btn-warning fw-bold mt-3">
                Shop Now on Shopee
            </a>
        </div>
    </div>

    <!-- section 4 -->
    <section class="travel-by-activity py-5">
        <div class="container mt-5">
            <div class="row g-4">
                <div class="col-md-2">
                    <div class="activity-card text-center p-3 border rounded" data-activity="sunbathing">
                        <i class="bi bi-sun text-primary fs-1 activity-icon"></i>
                        <h5 class="fw-bold mt-3">Sunbathing</h5>
                        <p class="text-muted">20 Destinations</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="activity-card text-center p-3 border rounded" data-activity="snorkeling">
                        <i class="bi bi-water text-primary fs-1 activity-icon"></i>
                        <h5 class="fw-bold mt-3">Snorkeling</h5>
                        <p class="text-muted">15 Destinations</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="activity-card text-center p-3 border rounded" data-activity="kitesurfing">
                        <i class="bi bi-wind text-primary fs-1 activity-icon"></i>
                        <h5 class="fw-bold mt-3">Kitesurfing</h5>
                        <p class="text-muted">10 Destinations</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="activity-card text-center p-3 border rounded" data-activity="beach-relax">
                        <i class="bi bi-umbrella text-primary fs-1 activity-icon"></i>
                        <h5 class="fw-bold mt-3">Beach Relax</h5>
                        <p class="text-muted">25 Destinations</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="activity-card text-center p-3 border rounded" data-activity="tsunami-tours">
                        <i class="bi bi-tsunami text-primary fs-1 activity-icon"></i>
                        <h5 class="fw-bold mt-3">Tsunami Tours</h5>
                        <p class="text-muted">18 Destinations</p>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="activity-card text-center p-3 border rounded" data-activity="night-parties">
                        <i class="bi bi-star text-primary fs-1 activity-icon"></i>
                        <h5 class="fw-bold mt-3">Night Parties</h5>
                        <p class="text-muted">12 Destinations</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="activityModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content fade-in">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Activity Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <img id="modalImage" src="" alt="Activity" class="modal-image">
                        </div>
                        <div class="col-md-6">
                            <div class="modal-text">
                                <h4 id="modalActivityName" class="mb-3"></h4>
                                <p id="modalDescription" class="text-muted mb-3"></p>
                                <div class="mb-3">
                                    <h6 class="fw-bold">Key Features:</h6>
                                    <ul id="modalFeatures" class="list-unstyled">
                                    </ul>
                                </div>
                                <div class="mb-3">
                                    <h6 class="fw-bold">Best Time:</h6>
                                    <p id="modalBestTime" class="text-muted mb-0"></p>
                                </div>
                                <span id="modalDestinations" class="badge-destinations"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- section 5 -->
    <section class="recent-posts py-5">
        <div class="container">
            <div class="text-center mb-5">
                <div class="d-flex align-items-center mb-4 justify-content-center">
                    <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                    <p class="text-danger fw-bold m-0">FROM OUR BLOG</p>
                </div>
                <h2 class="fw-bold">OUR RECENT POSTS</h2>
                <p class="text-muted">
                    Dive into our latest stories about tropical escapes, seaside adventures, and travel tips for your next
                    beach
                    getaway.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <img src="/assets/img2/phu-quoc.webp" class="card-img-top" alt="Beach Adventure">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Life is Better at the Beach</h5>
                            <p class="card-text text-muted">Discover why the beach is the ultimate destination for
                                relaxation and
                                adventure.</p>
                            <p class="text-muted small">By BeachTeam | May 20, 2025 | No Comments</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <img src="/assets/img2/phong-nha.jpg" class="card-img-top" alt="Ocean View">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Top 5 Beaches to Visit This Summer</h5>
                            <p class="card-text text-muted">Explore the most stunning beaches to add to your travel bucket
                                list.</p>
                            <p class="text-muted small">By BeachTeam | May 18, 2025 | No Comments</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <img src="/assets/img2/nha-trang.jpg" class="card-img-top" alt="Tropical Paradise">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Packing Tips for a Beach Vacation</h5>
                            <p class="card-text text-muted">Learn how to pack smart for your next tropical escape.</p>
                            <p class="text-muted small">By BeachTeam | May 15, 2025 | No Comments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- section 6 -->
    <section class="customer-reviews">
        <div class="container">
            <div class="section-title">
                <h2>What Our Customers Say</h2>
                <p>Real experiences from travelers who trusted us with their dream vacations</p>
            </div>

            <div id="customerReviewsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner">
                    <!-- Slide 1 -->
                    <div class="carousel-item active">
                        <div class="review-card">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200"
                                class="customer-photo" alt="William Housten">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="review-text">
                                "Amazing service! The team was incredibly professional and helpful.</br> Our family trip was
                                absolutely perfect with unforgettable experiences. Will definitely book again!"
                            </p>
                            <h5 class="customer-name">William Housten</h5>
                            <p class="customer-role">Travel Agent</p>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="carousel-item">
                        <div class="review-card">
                            <img src="https://demo.bosathemes.com/html/travele/assets/images/img22.jpg"
                                class="customer-photo" alt="Sophia Carter">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="review-text">
                                "From booking to the end of our trip, everything was perfectly organized. Beautiful
                                beaches, </br>luxury hotels, and knowledgeable local guides. Absolutely wonderful!"
                            </p>
                            <h5 class="customer-name">Sophia Carter</h5>
                            <p class="customer-role">Beach Enthusiast</p>
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="carousel-item">
                        <div class="review-card">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200"
                                class="customer-photo" alt="James Anderson">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="review-text">
                                "As someone who loves adventure and exploration, I found exactly what I needed. </br>The
                                adventure activities were safe, well-organized and thrilling. Unforgettable experience!"
                            </p>
                            <h5 class="customer-name">James Anderson</h5>
                            <p class="customer-role">Adventure Seeker</p>
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#customerReviewsCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#customerReviewsCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>

                <!-- Indicators -->
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#customerReviewsCarousel" data-bs-slide-to="0"
                        class="active"></button>
                    <button type="button" data-bs-target="#customerReviewsCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#customerReviewsCarousel" data-bs-slide-to="2"></button>
                </div>
            </div>
        </div>
    </section>


@endsection