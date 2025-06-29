
document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('#star-rating .star-icon');
    const ratingInput = document.getElementById('rating-input');
    let currentRating = 5;
    function setStars(rating) {
        stars.forEach((star, idx) => {
            if (idx < rating) {
                star.classList.add('text-warning');
            } else {
                star.classList.remove('text-warning');
            }
        });
    }
    setStars(currentRating);
    stars.forEach((star, idx) => {
        star.addEventListener('mouseenter', () => setStars(idx + 1));
        star.addEventListener('mouseleave', () => setStars(currentRating));
        star.addEventListener('click', () => {
            currentRating = idx + 1;
            ratingInput.value = currentRating;
            setStars(currentRating);
        });
    });
});


// FAQ Toggle
document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', () => {
        const answer = question.nextElementSibling;
        const isOpen = answer.style.display === 'block';

        // Close all other answers
        document.querySelectorAll('.faq-answer').forEach(ans => {
            ans.style.display = 'none';
        });
        document.querySelectorAll('.faq-question span').forEach(span => {
            span.textContent = '+';
        });

        // Toggle current answer
        if (!isOpen) {
            answer.style.display = 'block';
            question.querySelector('span').textContent = '-';
        }
    });
});


// Weather tooltip
document.querySelectorAll('.weather-day').forEach(day => {
    day.addEventListener('mouseenter', function () {
        const tooltip = document.createElement('div');
        tooltip.style.cssText = `
                    position: absolute;
                    background: #2c3e50;
                    color: white;
                    padding: 8px 12px;
                    border-radius: 4px;
                    font-size: 12px;
                    z-index: 100;
                    pointer-events: none;
                    margin-top: -40px;
                `;
        tooltip.textContent = 'Click để xem chi tiết thời tiết';
        this.appendChild(tooltip);
    });

    day.addEventListener('mouseleave', function () {
        const tooltip = this.querySelector('div[style*="position: absolute"]');
        if (tooltip) {
            this.removeChild(tooltip);
        }
    });
});
