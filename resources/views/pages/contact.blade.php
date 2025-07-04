@php
    $user = Auth::user();
    $layout = (!$user || $user->role === 'user') ? 'layouts.guest' : 'layouts.auth';
@endphp

@extends($layout)

@section('title', 'Contact')

@section('content')
    <!-- banner contact -->
    <section class="contact-banner mb-5 ">
        <h1 id="banner-title">Contact us</h1>
        <img src="/assets/img1/aa.png" alt="" class="brush-bottom" />
    </section>


    <!-- Main 1 content -->
    <main class="contact-section mb-5 py-5 container-custom container">
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

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('support.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Your Name*"
                            value="{{ old('name') }}" {{ Auth::check() ? 'readonly' : '' }}>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Your Email*"
                            value="{{ old('email', Auth::user()->email ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="phone" class="form-control" placeholder="Your Phone"
                            value="{{ old('phone') }}">
                    </div>
                    <div class="mb-3">
                        <input type="text" name="title" class="form-control" placeholder="Subject*"
                            value="{{ old('title') }}" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="message" class="form-control" rows="5" placeholder="Your Message*"
                            required>{{ old('message') }}</textarea>
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
    </main>

    <!-- Map Section -->
    <section id="contact">
        <!-- Google Map Embed -->
        <div class="map-container rounded shadow" style="overflow:hidden; height:400px;">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1862.3265387915885!2d105.82588489668649!3d21.006539231820625!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1svi!2s!4v1751523636712!5m2!1svi!2s"
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

@endsection