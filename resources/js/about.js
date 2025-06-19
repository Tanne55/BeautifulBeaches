document.addEventListener('DOMContentLoaded', function () {
    window.openVideo = function() {
        const popup = document.getElementById('video-popup');
        const frame = document.getElementById('youtube-frame');
        frame.src = "https://www.youtube.com/embed/z5toCqsceFo?si=5okfcpYu8xnxzTHs";
        popup.style.display = "flex";
    }

    window.closeVideo = function() {
        const popup = document.getElementById('video-popup');
        const frame = document.getElementById('youtube-frame');
        frame.src = ""; // Stop the video
        popup.style.display = "none";
    }

    // Đóng banner quảng cáo nếu tồn tại
    const closeBtn = document.querySelector('#ad-banner .close-btn');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            document.getElementById('ad-banner').style.display = 'none';
        });
    }
});