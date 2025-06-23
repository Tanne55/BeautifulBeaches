// Phần js của sec 4
const activityData = {
    'sunbathing': {
        title: 'Thiên Đường Tắm Nắng',
        image: '/assets/image/14.jpg',
        description: 'Thư giãn dưới ánh nắng vàng rực trên những bãi biển nguyên sơ với cát trắng mịn. Lý tưởng cho những ai tìm kiếm sự thư giãn tuyệt đối và làn da rám nắng đẹp.',
        features: [
            '🏖️ Ghế nằm và ô che nắng cao cấp',
            '🌞 Dịch vụ bảo vệ da khỏi tia UV',
            '🍹 Phục vụ đồ uống tận nơi trên bãi biển',
            '📸 Góc chụp ảnh hoàng hôn siêu đẹp'
        ],
        bestTime: 'Quanh năm, lý tưởng từ 10 giờ sáng đến 4 giờ chiều',
        destinations: '20 Điểm Đến Tuyệt Vời'
    },
    'snorkeling': {
        title: 'Phiêu Lưu Dưới Biển',
        image: '/assets/image/26.jpg',
        description: 'Khám phá những rạn san hô rực rỡ và sinh vật biển kỳ thú trong làn nước trong xanh. Trải nghiệm không thể quên phù hợp với mọi trình độ.',
        features: [
            '🐠 Hệ sinh thái rạn san hô đầy màu sắc',
            '🤿 Cung cấp thiết bị chuyên nghiệp',
            '👨‍🏫 Hướng dẫn viên lặn có chứng chỉ',
            '🐢 Cơ hội gặp rùa biển và cá nhiệt đới'
        ],
        bestTime: 'Tháng 4 đến tháng 10, biển lặng',
        destinations: '15 Địa Điểm Ngoạn Mục'
    },
    'kitesurfing': {
        title: 'Cảm Giác Mạnh Với Gió Biển',
        image: '/assets/image/8.jpg',
        description: 'Tận dụng sức mạnh của gió và sóng để có một cuộc phiêu lưu đầy adrenaline. Kết hợp hoàn hảo giữa lướt sóng và bay trên mặt nước.',
        features: [
            '🪁 Cho thuê thiết bị kitesurfing chuyên nghiệp',
            '💨 Điều kiện gió lý tưởng được đảm bảo',
            '🏄‍♂️ Lớp học từ cơ bản đến nâng cao',
            '🏆 Địa điểm tổ chức thi đấu quốc tế'
        ],
        bestTime: 'Tháng 11 đến tháng 3, mùa gió ổn định',
        destinations: '10 Điểm Lướt Sóng Đỉnh Cao'
    },
    'beach-relax': {
        title: 'Thư Giãn Tối Đa Trên Biển',
        image: '/assets/image/20.jpg',
        description: 'Tận hưởng không gian yên bình bên bãi biển với tiện nghi sang trọng. Trốn thoát khỏi áp lực cuộc sống trong một môi trường thư giãn hoàn hảo.',
        features: [
            '🏨 Khu nghỉ dưỡng cao cấp gần bãi biển',
            '💆‍♀️ Dịch vụ spa ngay cạnh biển',
            '🍽️ Ẩm thực cao cấp với tầm nhìn biển',
            '🌅 Cảnh bình minh và hoàng hôn tuyệt đẹp'
        ],
        bestTime: 'Quanh năm, phù hợp với mọi mùa',
        destinations: '25 Kỳ Nghỉ Bình Yên'
    },
    'tsunami-tours': {
        title: 'Chiêm Ngưỡng Sóng Lớn Kỳ Thú',
        image: '/assets/image/7.jpg',
        description: 'Trải nghiệm sức mạnh của thiên nhiên với các tour hướng dẫn quan sát các đợt sóng lớn và hiện tượng ven biển một cách an toàn.',
        features: [
            '🌊 Điểm quan sát an toàn cùng hướng dẫn viên',
            '📱 Tour giáo dục về địa chất biển',
            '🔬 Cơ hội tham gia nghiên cứu khoa học',
            '⛑️ Trang bị an toàn đầy đủ'
        ],
        bestTime: 'Mùa bão, từ tháng 10 đến tháng 2',
        destinations: '18 Bờ Biển Kịch Tính'
    },
    'night-parties': {
        title: 'Bữa Tiệc Đêm Bên Biển',
        image: '/assets/image/17.jpg',
        description: 'Nhảy múa dưới ánh sao trên cát biển cùng các DJ hàng đầu thế giới, cocktail hảo hạng và không khí sôi động không thể nào quên.',
        features: [
            '🎵 DJ quốc tế biểu diễn trực tiếp',
            '🍸 Quầy bar cocktail cao cấp',
            '🔥 Lửa trại và biểu diễn lửa trên bãi biển',
            '🎊 Sự kiện theo chủ đề mỗi tuần'
        ],
        bestTime: 'Các đêm cuối tuần, quanh năm',
        destinations: '12 Bãi Biển Sôi Động Nhất'
    }
};


document.addEventListener('DOMContentLoaded', function () {
    const activityCards = document.querySelectorAll('.activity-card');
    const detailBox = document.getElementById('activityDetail');
    const overlay = document.getElementById('overlay'); // ✅ Thêm dòng này
    const closeBtn = document.getElementById('closeDetail');

    activityCards.forEach(card => {
        card.addEventListener('click', function () {
            const activity = this.getAttribute('data-activity');
            const data = activityData[activity];

            if (data) {
                document.getElementById('activityTitle').textContent = data.title;
                document.getElementById('activityImage').src = data.image;
                document.getElementById('activityDescription').textContent = data.description;
                document.getElementById('activityBestTime').textContent = data.bestTime;
                document.getElementById('activityDestinations').textContent = data.destinations;

                const featuresList = document.getElementById('activityFeatures');
                featuresList.innerHTML = '';
                data.features.forEach(feature => {
                    const li = document.createElement('li');
                    li.innerHTML = `<i class="bi bi-check-circle-fill text-success me-2"></i>${feature}`;
                    featuresList.appendChild(li);
                });

                // ✅ Show detail box & overlay
                detailBox.classList.add('show');
                overlay.classList.add('show'); // ✅ Thêm dòng này
            }
        });
    });

    // ✅ Hide detail box & overlay
    closeBtn.addEventListener('click', function () {
        detailBox.classList.remove('show');
        overlay.classList.remove('show'); // ✅ Thêm dòng này
    });

    // 👉 Cho phép click vào nền để đóng cũng được (optional):
    overlay.addEventListener('click', function () {
        detailBox.classList.remove('show');
        overlay.classList.remove('show');
    });
});


