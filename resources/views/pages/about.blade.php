@php
    $layout = Auth::check() ? 'layouts.auth' : 'layouts.guest';
@endphp


@extends($layout)

@section('title', 'About Us')

@section('content')
    <!-- banner container -->
    <section class="contact-banner mb-5 ">
        <h1 id="banner-title">About us</h1>
        <img src="/assets/img1/aa.png" alt="" class="brush-bottom" />
    </section>




    <!-- Main1 content -->
    <main class="container">
        <div class="text-center mb-5 row">
            <div class="col-6">
                <div class="d-flex align-items-center mb-4 mx-5">
                    <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                    <p class="text-danger fw-bold m-0">ABOUT OUR JOURNEY</p>
                </div>

                <h1 class="fw-bold">CREATING UNFORGETTABLE BEACH EXPERIENCES</h1>
            </div>
            <div class="col-6 d-flex align-items-center text-start text-muted">
                With years of experience in coastal tourism, we specialize in crafting tailor-made beach holidays that
                combine natural beauty, vibrant culture, and relaxation. Whether you're dreaming of a tropical escape or
                a weekend by the sea, we’ve got you covered.
                Our mission is to bring you closer to the ocean — with curated destinations, affordable packages, and
                warm, personalized service that makes every moment memorable.
            </div>
        </div>

        <div>
            <div class="row">
                <div class="col-4 d-flex gap-4">
                    <div class="bg-primary mb-4 ">
                        <img src="https://demo.bosathemes.com/html/travele/assets/images/icon15.png" alt="About Us"
                            class="img-fluid">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <h5 class="fw-bold">Budget-Friendly Trips</h5>
                        <p class="small">Enjoy amazing beach getaways</br> without breaking the bank.</p>
                    </div>
                </div>
                <div class="col-4 d-flex gap-4">
                    <div class="bg-primary mb-4 ">
                        <img src="https://demo.bosathemes.com/html/travele/assets/images/icon16.png" alt="About Us"
                            class="img-fluid">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <h5 class="fw-bold">Top Coastal Spots</h5>
                        <p class="small">Discover the most stunning and hidden</br> beach gems around the world.</p>
                    </div>
                </div>
                <div class="col-4 d-flex gap-4">
                    <div class="bg-primary mb-4 ">
                        <img src="https://demo.bosathemes.com/html/travele/assets/images/icon17.png" alt="About Us"
                            class="img-fluid">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <h5 class="fw-bold">Friendly Local Experts</h5>
                        <p class="small">Our team ensures you feel at home</br> from booking to beach.</p>
                    </div>
                </div>

            </div>
        </div>
        <!-- video -->
        <div class="video-thumbnail-container" onclick="openVideo()">
            <img src="/assets/img1/banner.jpg" alt="Video" class="video-thumb">
            <div class="custom-play-button">&#9658;</div>
        </div>

        <!-- Popup -->
        <div id="video-popup" class="popup-overlay">
            <div class="popup-content">
                <span class="close-button" onclick="closeVideo()">&times;</span>
                <iframe id="youtube-frame" width="800" height="450" src="" frameborder="0" allow="autoplay; encrypted-media"
                    allowfullscreen></iframe>
            </div>
        </div>


    </main>

    <!-- Main2 content -->
    <!-- Our Story Section -->
    <section class="container my-5 py-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="d-flex align-items-center mb-4">
                    <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                    <p class="text-danger fw-bold m-0">OUR STORY</p>
                </div>
                <h2 class="fw-bold mb-4">FROM DREAM TO REALITY</h2>
                <p class="text-muted mb-4">
                    Founded in 2015 by a group of passionate travelers, our company began with a simple dream: to make
                    the world's most beautiful beaches accessible to everyone. What started as a small local tour
                    operator has grown into a trusted global brand.
                </p>
                <p class="text-muted mb-4">
                    We believe that every person deserves to experience the tranquility of ocean waves, the warmth of
                    golden sand, and the joy of discovering new coastal cultures. This philosophy drives everything we
                    do.
                </p>
                <div class="row">
                    <div class="col-6">
                        <h4 class="text-danger fw-bold">2015</h4>
                        <p class="small">Company Founded</p>
                    </div>
                    <div class="col-6">
                        <h4 class="text-danger fw-bold">50+</h4>
                        <p class="small">Countries Served</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                    alt="Our Story" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </section>

    <!-- Main3 content -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="text-center mb-5">
                <div class="d-flex align-items-center mb-4 justify-content-center">
                    <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                    <p class="text-danger fw-bold m-0">OUR VALUES</p>
                </div>
                <h2 class="fw-bold">WHAT DRIVES US FORWARD</h2>
                <p class="text-muted">These core values guide every decision we make and every experience we create</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Passion for Excellence</h5>
                        <p class="text-muted">We pour our hearts into every detail, ensuring that each beach experience
                            exceeds your expectations and creates lasting memories.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Trust & Safety</h5>
                        <p class="text-muted">Your safety and peace of mind are our top priorities. We maintain the
                            highest standards in all our operations and partnerships.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-globe-americas"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Environmental Responsibility</h5>
                        <p class="text-muted">We're committed to preserving the natural beauty of our oceans and beaches
                            for future generations to enjoy.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Community Focus</h5>
                        <p class="text-muted">We support local coastal communities by partnering with local businesses
                            and promoting authentic cultural experiences.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-lightbulb-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Innovation</h5>
                        <p class="text-muted">We continuously innovate to provide you with cutting-edge travel
                            technology and unique beach experiences.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Personalized Service</h5>
                        <p class="text-muted">Every traveler is unique, and we tailor our services to match your
                            individual preferences and dreams.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Banner quảng cáo lớn -->
    <div id="ad-banner">
        <button class="close-btn" aria-label="Close">&times;</button>
        <div class="content-wrapper">
            <img src="/assets/img2/bien-sam-son.jpg" alt="Beach" />
            <div class="ad-text">
                <p>🌴 <strong>Get 20% off today!</strong></p>
                <p>Book your dream beach vacation now and enjoy exclusive savings.</p>
            </div>
            <a href="/Pages/Explore/explore.html" class="book-now">Book Now</a>

        </div>
    </div>


    <!-- Main4 content -->
    <main class="container mt-5">
        <div class="text-center mb-5">

            <div class="d-flex align-items-center mb-4 mx-5 justify-content-center">
                <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                <p class="text-danger fw-bold m-0">OUR ASSOCIATES</p>
            </div>

            <h1 class="fw-bold">OUR PARTNERS AND CLIENTS</h1>

            <div class="d-flex align-items-center text-muted justify-content-center">
                We are proud to collaborate with a global network of trusted partners and loyal clients who believe in
                the value we deliver.</br> Together, we create unforgettable travel experiences with integrity and
                excellence.
            </div>
        </div>

        <div class="mb-5">
            <div class="row justify-content-center align-items-center g-4 mx-5">
                <div class="col-6 col-sm-3 logo-wrapper">
                    <img src="https://img.freepik.com/free-vector/summer-background-with-lettering_23-2147819913.jpg?uid=R201273119&ga=GA1.1.1768268784.1747900003&semt=ais_hybrid&w=740"
                        class="img-fluid logo-img" alt="Logo 1">
                </div>
                <div class="col-6 col-sm-3 logo-wrapper">
                    <img src="https://img.freepik.com/premium-vector/beach-bar-with-beach-bar-top_1191547-68.jpg?uid=R201273119&ga=GA1.1.1768268784.1747900003&semt=ais_hybrid&w=740"
                        class="img-fluid logo-img" alt="Logo 2">
                </div>

                <div class="col-6 col-sm-3 logo-wrapper">
                    <img src="https://img.freepik.com/free-vector/flat-design-beach-club-logo-template_23-2149486421.jpg?uid=R201273119&ga=GA1.1.1768268784.1747900003&semt=ais_hybrid&w=740"
                        class="img-fluid logo-img" alt="Logo 4">
                </div>
                <div class="col-6 col-sm-3 logo-wrapper">
                    <img src="https://img.freepik.com/free-vector/detailed-travel-logo-template_23-2148614916.jpg?uid=R201273119&ga=GA1.1.1768268784.1747900003&semt=ais_hybrid&w=740"
                        class="img-fluid logo-img" alt="Logo 5">
                </div>
            </div>
        </div>
    </main>


    <!-- Why Choose Us Section -->
    <section class="container my-5 py-5">
        <div class="text-center mb-5">
            <div class="d-flex align-items-center mb-4 justify-content-center">
                <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                <p class="text-danger fw-bold m-0">WHY CHOOSE US</p>
            </div>
            <h2 class="fw-bold">THE DIFFERENCE WE MAKE</h2>
        </div>

        <div class="row g-5">
            <div class="col-lg-6">
                <div class="d-flex mb-4">
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px; min-width: 50px;">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">24/7 Support</h5>
                        <p class="text-muted">Round-the-clock assistance ensures you're never alone during your journey.
                            Our multilingual support team is always ready to help.</p>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px; min-width: 50px;">
                        <i class="bi bi-shield-check-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Best Price Guarantee</h5>
                        <p class="text-muted">Find a better price elsewhere? We'll match it and give you an additional
                            5% discount on your next booking.</p>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px; min-width: 50px;">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Local Expertise</h5>
                        <p class="text-muted">Our local partners provide insider knowledge and authentic experiences you
                            won't find in guidebooks.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="d-flex mb-4">
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px; min-width: 50px;">
                        <i class="bi bi-credit-card-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Flexible Payments</h5>
                        <p class="text-muted">Pay in installments, use multiple currencies, and enjoy hassle-free
                            cancellation policies tailored to your needs.</p>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px; min-width: 50px;">
                        <i class="bi bi-phone-fill"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Smart Technology</h5>
                        <p class="text-muted">Our AI-powered app provides real-time updates, personalized
                            recommendations, and seamless travel management.</p>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px; min-width: 50px;">
                        <i class="bi bi-recycle"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold">Eco-Conscious</h5>
                        <p class="text-muted">Every booking contributes to marine conservation efforts and sustainable
                            tourism development in local communities.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Call to Action Section -->
    <section class="bg-light py-5">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-4">Ready to Start Your Beach Adventure?</h2>
                    <p class="text-muted mb-4 fs-5">
                        Join thousands of satisfied travelers who have discovered their perfect beach paradise with us.
                        Your dream coastal getaway is just one click away.
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <button class="btn btn-danger btn-lg px-4 py-2">
                            <i class="bi bi-airplane-fill me-2"></i>
                            Plan My Trip
                        </button>
                        <button class="btn btn-outline-danger btn-lg px-4 py-2">
                            <i class="bi bi-telephone-fill me-2"></i>
                            Call Us Now
                        </button>
                    </div>
                    <p class="text-muted mt-3 small">
                        <i class="bi bi-shield-check text-success me-1"></i>
                        Free consultation • No hidden fees • Instant confirmation
                    </p>
                </div>
            </div>
        </div>
    </section>



    <!-- Main5 content -->
    <main class="container-fluid footer-banner mt-5">
        <div class="container">
            <div class="text-center m-5">
                <h2>Thank You for Visiting!</h2>
                <p>Join us to explore the best travel destinations and unforgettable experiences.</p>
            </div>
            <div class="row text-center text-white m-5">
                <div class="col-6 col-md-3 bg-red">
                    <div class="stat-box p-3">
                        <i class="bi bi-people-fill display-4"></i>
                        <h3 class="fw-bold mt-2">500K+</h3>
                        <p>Satisfied Clients</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 ">
                    <div class="stat-box p-3 bg-red">
                        <i class="bi bi-gem display-4"></i>
                        <h3 class="fw-bold mt-2">250K+</h3>
                        <p>Awards Achieve</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-box p-3 bg-red">
                        <i class="bi bi-person-plus-fill display-4"></i>
                        <h3 class="fw-bold mt-2">15K+</h3>
                        <p>Active Members</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-box p-3 bg-red">
                        <i class="bi bi-map display-4"></i>
                        <h3 class="fw-bold mt-2">10K+</h3>
                        <p>Tour Destinations</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection