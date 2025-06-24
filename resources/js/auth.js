import './bootstrap.js';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
// Import các script khác sau khi Bootstrap đã được load
import './welcome.js';
import './about.js';
import './gallery.js';
import './contact.js';
import './explore.js';
import './queries.js';
import './detail.js';



// Thêm hiệu ứng click cho các nav items
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function (e) {
        // Nếu là dropdown toggle thì không thực hiện
        if (this.getAttribute('data-bs-toggle') === 'collapse') return;

        e.preventDefault();

        // Remove active class from all links
        document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));

        // Add active class to clicked link
        this.classList.add('active');

        // Add ripple effect
        const ripple = document.createElement('div');
        ripple.style.position = 'absolute';
        ripple.style.borderRadius = '50%';
        ripple.style.background = 'rgba(255, 255, 255, 0.3)';
        ripple.style.transform = 'scale(0)';
        ripple.style.animation = 'ripple 0.6s linear';
        ripple.style.left = '50%';
        ripple.style.top = '50%';
        ripple.style.width = '20px';
        ripple.style.height = '20px';
        ripple.style.marginLeft = '-10px';
        ripple.style.marginTop = '-10px';

        this.appendChild(ripple);

        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
});

// Handle dropdown item clicks
document.querySelectorAll('.dropdown-item').forEach(item => {
    item.addEventListener('click', function (e) {
        e.preventDefault();

        // Remove active class from all nav links
        document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));

        // Add active class to parent nav item's link
        const navLink = this.closest('.nav-item').querySelector('.nav-link');
        navLink.classList.add('active');

        console.log('Selected:', this.textContent);
    });
});

// Handle logout functionality
document.getElementById('logoutBtn').addEventListener('click', function (e) {
    e.preventDefault();

    // Add a smooth logout animation
    this.style.transform = 'translateX(5px) scale(0.95)';
    this.style.opacity = '0.7';

    setTimeout(() => {
        // You can replace this with actual logout logic
        alert('Đăng xuất thành công!');
        window.location.reload();
    }, 200);
});
