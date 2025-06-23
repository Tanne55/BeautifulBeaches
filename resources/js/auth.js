import './bootstrap.js';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// Import Bootstrap's JavaScript
import * as bootstrap from 'bootstrap';

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

// Add CSS for ripple animation
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    .nav-link.active {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        transform: translateX(5px);
    }
    .nav-link {
        position: relative;
        overflow: hidden;
    }
`;
document.head.appendChild(style);