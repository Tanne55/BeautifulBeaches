function getQueryParam(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name) || '';
}

function filterTours() {
    const title = document.getElementById('search-title').value.toLowerCase();
    const region = document.getElementById('search-region').value.toLowerCase();
    const tag = document.getElementById('search-tag').value.toLowerCase();

    const tours = document.querySelectorAll('.tour-card');
    let visibleCount = 0;

    tours.forEach(tour => {
        const tourTitle = tour.dataset.title;
        const tourRegion = tour.dataset.region;
        const tourTags = tour.dataset.tags;

        const matchTitle = tourTitle.includes(title);
        const matchRegion = !region || tourRegion === region;
        const matchTag = !tag || tourTags.includes(tag);

        if (matchTitle && matchRegion && matchTag) {
            tour.classList.remove('hidden');
            visibleCount++;
        } else {
            tour.classList.add('hidden');
        }
    });

    const noResultDiv = document.getElementById('no-result-explore');
    if (noResultDiv) {
        if (visibleCount === 0) {
            noResultDiv.style.display = 'block';
        } else {
            noResultDiv.style.display = 'none';
        }
    }
}

function clearFilters() {
    document.getElementById('search-title').value = '';
    document.getElementById('search-region').value = '';
    document.getElementById('search-tag').value = '';
    filterTours();
}

// Khi trang vừa tải, nếu URL có ?region=xxx thì điền sẵn vào filter
document.addEventListener('DOMContentLoaded', () => {
    const regionParam = getQueryParam('region');
    if (regionParam) {
        document.getElementById('search-region').value = regionParam.toLowerCase();
    }

    // Gọi filter ban đầu
    filterTours();
});

window.filterTours = filterTours;
window.clearFilters = clearFilters;
