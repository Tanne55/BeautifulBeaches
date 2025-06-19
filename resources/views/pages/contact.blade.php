@extends('layouts.app')

@section('title', 'Contact')

@section('content')
    <!-- banner contact -->
    <section class="contact-banner mb-5">
        <div class="overlay">
            <h1 id="banner-title">Contact us</h1>
        </div>
        <img src="/assets/img1/aa.png" alt="" class="brush-bottom" />
    </section>


    <!-- Main 1 content -->
    <main class="contact-section py-5">
        <div class="container">
            <div class="row">
                <!-- Left Section -->
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-4">
                        <div style="width: 60px; height: 2px; background-color: red; margin-right: 10px;"></div>
                        <p class="text-danger fw-bold">CONTACT</p>
                    </div>
                    <h1 class="fw-bold">GET IN TOUCH WITH US</br> FOR MORE INFORMATION</h1>
                    <p class="text-muted small">
                        We are always ready to listen and assist you in planning your perfect beach vacation.
                        Contact us for detailed consultation about our beach tours and services.
                    </p>
                    <form>
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Your Name*" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Your Email*" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="5" placeholder="Your Message*" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-20">SUBMIT MESSAGE</button>
                    </form>
                </div>

                <!-- Right Section -->
                <div class="col-md-6 p-5">
                    <h5 class="fw-bold">Need assistance? Get in touch with us!</h5>
                    <p class="text-muted small">
                        Our support team is always ready to assist you</br>
                        24/7 to ensure you have a perfect beach holiday.
                    </p>
                    <p class="text-muted small">
                        We are committed to bringing you the best experiences</br>
                        at the most beautiful beaches in Vietnam.
                    </p>
                    <div class="contact-info">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-geo-alt-fill text-primary fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold">Address</h6>
                                <p class="text-muted m-0"> 175 chuaboc, dongda , hanoi</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-envelope-fill text-primary fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold">Email</h6>
                                <p class="text-muted m-0">aptechvn.com</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-telephone-fill text-primary fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold">Phone</h6>
                                <p class="text-muted m-0">Landline: (028) 1234 5678 / Mobile: 0912 345 678</p>
                            </div>
                        </div>
                    </div>
                    <h6 class="fw-bold mt-4">Follow us on social media..</h6>
                    <div class="social-icons">
                        <a href="#" class="text-danger me-3"><i class="bi bi-facebook fs-4"></i></a>
                        <a href="#" class="text-danger me-3"><i class="bi bi-twitter fs-4"></i></a>
                        <a href="#" class="text-danger me-3"><i class="bi bi-instagram fs-4"></i></a>
                        <a href="#" class="text-danger"><i class="bi bi-linkedin fs-4"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Map Section -->
    <section id="contact">
        <!-- Google Map Embed -->
        <div class="map-container rounded shadow" style="overflow:hidden; height:400px;">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d19805.71280111444!2d-0.1363986!3d51.501364!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487604a2be6aa1ff%3A0xd4e70f3ea220f3f9!2sRiverside%20Building%2C%20County%20Hall!5e0!3m2!1sen!2suk!4v1716270139646!5m2!1sen!2suk"
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </section>

@endsection