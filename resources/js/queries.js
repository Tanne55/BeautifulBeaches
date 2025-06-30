
// Toggle FAQ Function
function toggleFAQ(button) {
    const answer = button.nextElementSibling;
    const isOpen = answer.classList.contains('show');

    // Close all other FAQs
    document.querySelectorAll('.faq-answer').forEach(item => {
        item.classList.remove('show');
    });
    document.querySelectorAll('.faq-question').forEach(item => {
        item.classList.remove('active');
    });

    // Toggle current FAQ
    if (!isOpen) {
        answer.classList.add('show');
        button.classList.add('active');

        // Smooth scroll to question
        setTimeout(() => {
            button.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }, 100);
    }
}

// Search Functionality
const searchInput = document.getElementById('searchInput');
const faqItems = document.querySelectorAll('.faq-item');
const noResults = document.getElementById('noResults');
const faqContainer = document.getElementById('faqContainer');

searchInput.addEventListener('input', function () {
    const searchTerm = this.value.toLowerCase().trim();
    let visibleCount = 0;

    faqItems.forEach(item => {
        const questionText = item.querySelector('.faq-question span').textContent.toLowerCase();
        const answerText = item.querySelector('.faq-answer-content').textContent.toLowerCase();
        const keywords = item.dataset.keywords.toLowerCase();

        const isVisible = questionText.includes(searchTerm) ||
            answerText.includes(searchTerm) ||
            keywords.includes(searchTerm);

        if (isVisible) {
            item.style.display = 'block';
            item.style.animation = 'fadeIn 0.5s ease forwards';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    // Show/hide no results message
    if (visibleCount === 0 && searchTerm !== '') {
        noResults.style.display = 'block';
        faqContainer.style.display = 'none';
    } else {
        noResults.style.display = 'none';
        faqContainer.style.display = 'block';
    }

    // Update total questions count
    document.getElementById('totalQuestions').textContent = visibleCount || faqItems.length;
});

// Add entrance animation to FAQ items
window.addEventListener('load', function () {
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach((item, index) => {
        setTimeout(() => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.animation = `fadeIn 0.6s ease ${index * 0.1}s forwards`;
        }, 100);
    });
});

// Keyboard navigation
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        // Close all FAQs on Escape
        document.querySelectorAll('.faq-answer').forEach(item => {
            item.classList.remove('show');
        });
        document.querySelectorAll('.faq-question').forEach(item => {
            item.classList.remove('active');
        });
    }
});

// Add click outside to close functionality
document.addEventListener('click', function (e) {
    if (!e.target.closest('.faq-item')) {
        const openAnswers = document.querySelectorAll('.faq-answer.show');
        if (openAnswers.length > 0) {
            setTimeout(() => {
                document.querySelectorAll('.faq-answer').forEach(item => {
                    item.classList.remove('show');
                });
                document.querySelectorAll('.faq-question').forEach(item => {
                    item.classList.remove('active');
                });
            }, 100);
        }
    }
});
window.toggleFAQ = toggleFAQ;