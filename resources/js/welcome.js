// Phần js của sec 4
const activityData = {
    'sunbathing': {
        title: 'Sunbathing Paradise',
        image: '/assets/image/14.jpg',
        description: 'Relax under the golden sun on pristine beaches with soft white sand. Perfect for those seeking ultimate relaxation and a beautiful tan.',
        features: [
            '🏖️ Premium beach loungers and umbrellas',
            '🌞 UV protection services available',
            '🍹 Beachside refreshment service',
            '📸 Instagram-worthy sunset spots'
        ],
        bestTime: 'Year-round, best between 10 AM - 4 PM',
        destinations: '20 Amazing Destinations'
    },
    'snorkeling': {
        title: 'Underwater Adventure',
        image: '/assets/image/26.jpg',
        description: 'Discover vibrant coral reefs and exotic marine life in crystal-clear waters. An unforgettable underwater experience for all skill levels.',
        features: [
            '🐠 Colorful coral reef ecosystems',
            '🤿 Professional equipment provided',
            '👨‍🏫 Certified diving instructors',
            '🐢 Chance to see sea turtles and tropical fish'
        ],
        bestTime: 'April to October, calm sea conditions',
        destinations: '15 Spectacular Locations'
    },
    'kitesurfing': {
        title: 'Wind-Powered Thrills',
        image: '/assets/image/8.jpg',
        description: 'Harness the power of wind and waves for an adrenaline-pumping adventure. Perfect combination of surfing and flying across the water.',
        features: [
            '🪁 Professional kiting equipment rental',
            '💨 Ideal wind conditions guaranteed',
            '🏄‍♂️ Beginner to advanced lessons',
            '🏆 International competition venues'
        ],
        bestTime: 'November to March, steady wind season',
        destinations: '10 World-Class Spots'
    },
    'beach-relax': {
        title: 'Ultimate Beach Relaxation',
        image: '/assets/image/20.jpg',
        description: 'Unwind in peaceful beach settings with luxury amenities. The perfect escape from daily stress with comfortable beach facilities.',
        features: [
            '🏨 Luxury beach resorts nearby',
            '💆‍♀️ Beachside spa services',
            '🍽️ Fine dining with ocean views',
            '🌅 Stunning sunrise and sunset views'
        ],
        bestTime: 'Year-round, perfect for any season',
        destinations: '25 Peaceful Getaways'
    },
    'tsunami-tours': {
        title: 'Extreme Wave Watching',
        image: '/assets/image/7.jpg',
        description: 'Experience the raw power of nature with guided tours to witness spectacular wave formations and coastal phenomena safely.',
        features: [
            '🌊 Safe observation points with guides',
            '📱 Educational marine geology tours',
            '🔬 Scientific research participation',
            '⛑️ Comprehensive safety equipment'
        ],
        bestTime: 'Storm season, October to February',
        destinations: '18 Dramatic Coastlines'
    },
    'night-parties': {
        title: 'Beach Nightlife Extravaganza',
        image: '/assets/image/17.jpg',
        description: 'Dance under the stars on sandy beaches with world-class DJs, cocktails, and unforgettable nightlife experiences by the ocean.',
        features: [
            '🎵 International DJ performances',
            '🍸 Premium cocktail bars',
            '🔥 Beach bonfires and fire shows',
            '🎊 Themed party events weekly'
        ],
        bestTime: 'Weekend nights, year-round',
        destinations: '12 Hottest Party Beaches'
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const activityCards = document.querySelectorAll('.activity-card');
    const modal = new bootstrap.Modal(document.getElementById('activityModal'));

    activityCards.forEach(card => {
        card.addEventListener('click', function () {
            const activity = this.getAttribute('data-activity');
            const data = activityData[activity];

            if (data) {
                document.getElementById('modalTitle').textContent = data.title;
                document.getElementById('modalImage').src = data.image;
                document.getElementById('modalActivityName').textContent = data.title;
                document.getElementById('modalDescription').textContent = data.description;
                document.getElementById('modalBestTime').textContent = data.bestTime;
                document.getElementById('modalDestinations').textContent = data.destinations;

                const featuresList = document.getElementById('modalFeatures');
                featuresList.innerHTML = '';
                data.features.forEach(feature => {
                    const li = document.createElement('li');
                    li.className = 'mb-2';
                    li.innerHTML = `<i class="bi bi-check-circle-fill text-success me-2"></i>${feature}`;
                    featuresList.appendChild(li);
                });

                modal.show();
            }
        });
    });
});
