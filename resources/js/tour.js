
document.addEventListener("DOMContentLoaded", function () {
    const titleInput = document.getElementById('filter-title');
    const regionSelect = document.getElementById('filter-region');
    const sortSelect = document.getElementById('filter-sort');
    const clearBtn = document.getElementById('clear-filters');
    const cards = Array.from(document.querySelectorAll('.tour-cardd'));
    const container = document.getElementById('tour-list');

    function filterTours() {
        const titleFilter = titleInput.value.toLowerCase();
        const regionFilter = regionSelect.value;
        const sortOrder = sortSelect.value;

        let filtered = cards.filter(card => {
            const title = card.dataset.title;
            const region = card.dataset.region;

            return (
                title.includes(titleFilter) &&
                (regionFilter === "" || region === regionFilter)
            );
        });

        if (sortOrder) {
            filtered.sort((a, b) => {
                const priceA = parseInt(a.dataset.price);
                const priceB = parseInt(b.dataset.price);
                return sortOrder === 'asc' ? priceA - priceB : priceB - priceA;
            });
        }

        container.innerHTML = "";
        filtered.forEach(card => container.appendChild(card));
    }

    function clearFilters() {
        titleInput.value = "";
        regionSelect.value = "";
        sortSelect.value = "";
        filterTours();
    }

    // Gắn sự kiện
    titleInput.addEventListener('input', filterTours);
    regionSelect.addEventListener('change', filterTours);
    sortSelect.addEventListener('change', filterTours);
    clearBtn.addEventListener('click', clearFilters);
});
