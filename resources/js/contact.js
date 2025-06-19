// JS riêng cho trang Contact 
document.querySelector('form').addEventListener('submit', function (e) {
    e.preventDefault(); // Ngăn chặn hành động mặc định của form (reload trang)

    // Lấy dữ liệu từ các input
    const name = document.querySelector('input[placeholder="Your Name*"]').value;
    const email = document.querySelector('input[placeholder="Your Email*"]').value;
    const message = document.querySelector('textarea[placeholder="Your Message*"]').value;

    // Lưu dữ liệu vào localStorage
    localStorage.setItem('contactData', JSON.stringify({ name, email, message }));

    // Chuyển hướng sang trang khác
    window.location.href = '/Pages/Contact/thanks.html';
});