@php
    $user = Auth::user();
    $layout = (!$user || $user->role === 'user') ? 'layouts.guest' : 'layouts.auth';
@endphp

@extends($layout)

@section('title', 'Queries')

@section('content')
    <!-- banner contact -->
    <section class="contact-banner mb-5 ">
        <h1 id="banner-title">Queries</h1>
        <img src="/assets/img1/aa.png" alt="" class="brush-bottom" />
    </section>




    <div class="container py-5">
        <div class="row g-5 align-items-start">
            <!-- Left: Form -->
            <div class="col-md-7">
                <h2 class="mb-4">Have a Question? Let Us Help!</h2>
                <p>If you have any issues, questions, or need clarification about our services, please submit a query
                    below. Our support team will get back to you as soon as possible.</p>
                <form id="queryForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name:</label>
                        <input type="text" id="name" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Query Category:</label>
                        <select id="category" class="form-select" required>
                            <option value="">-- Select a Category --</option>
                            <option value="product">Product Inquiry</option>
                            <option value="payment">Payment Issue</option>
                            <option value="technical">Technical Support</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority:</label><br />
                        <div class="form-check form-check-inline">
                            <input type="radio" name="priority" value="normal" id="normal" class="form-check-input"
                                checked />
                            <label class="form-check-label" for="normal">Normal</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="priority" value="urgent" id="urgent" class="form-check-input" />
                            <label class="form-check-label" for="urgent">Urgent</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject:</label>
                        <input type="text" id="subject" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Your Question:</label>
                        <textarea id="message" rows="5" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="attachment" class="form-label">Attach a File (optional):</label>
                        <input type="file" id="attachment" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-secondary me-2"
                            onclick="previewQuery()">Preview</button>
                        <button type="submit" class="btn btn-primary">Submit Query</button>
                    </div>
                    <div id="responseMsg" class="mt-3"></div>
                </form>
                <div id="previewBox" class="mt-4 d-none">
                    <h5>Preview:</h5>
                    <div id="previewContent" class="border rounded p-3 bg-light"></div>
                </div>
            </div>

            <!-- Right: Support Info -->
            <div class="col-md-5">
                <div class="support-box text-center p-4 shadow rounded">
                    <img src="https://demo.bosathemes.com/html/travele/assets/images/img21.jpg" alt="Support Agent"
                        class="img-fluid rounded-circle mb-3" width="150" />
                    <h5>James Watson</h5>
                    <p class="text-muted mb-1">Customer Support Specialist</p>
                    <p>"I'm here to help! Feel free to send your questions any time. Our team responds within 24 hours."
                    </p>
                    <hr />
                    <p class="small mb-1"><strong>Hotline:</strong> 1900 999 888</p>
                    <p class="small mb-1"><strong>Email:</strong> aptechvn.com</p>
                    <p class="small"><strong>Working Hours:</strong> Mon - Fri, 9AM - 6PM</p>
                </div>
            </div>
        </div>
    </div>

@endsection