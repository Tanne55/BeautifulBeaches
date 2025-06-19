// JS riêng cho trang Explore 
// Global variables
let toursData = [];
let filteredTours = [];

// Function to get URL parameters
function getUrlParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

// Function to set filter from URL parameter
function setFilterFromUrl() {
    const regionParam = getUrlParameter('region');
    if (regionParam) {
        // Decode URL parameter (in case of spaces)
        const decodedRegion = decodeURIComponent(regionParam);

        // Set the region dropdown value
        const regionSelect = document.getElementById('search-region');

        // Find matching option in dropdown
        for (let option of regionSelect.options) {
            if (option.value === decodedRegion) {
                regionSelect.value = option.value;
                break;
            }
        }

        // Apply filter
        filterTours();
    }
}

// Load data from JSON file
async function loadToursData() {
    try {
        console.log('🔄 Fetching data from /api/beaches...');
        const response = await fetch('/api/beaches');

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        toursData = await response.json();
        console.log('✅ DATA received:', toursData);
        console.log('📊 Data length:', toursData.length);
        console.log('🔍 First item:', toursData[0]);
        
        filteredTours = [...toursData];

        // Initialize page after data is loaded
        console.log('🔧 Initializing filters...');
        initializeFilters();

        // Set filter from URL parameter if exists
        setFilterFromUrl();

        // If no URL parameter, render all tours
        if (!getUrlParameter('region')) {
            console.log('🎨 Rendering tours...');
            renderTours();
        }

        updateToursCount();

        console.log('🎉 Tours data loaded successfully:', toursData.length, 'tours');

    } catch (error) {
        console.error('❌ Error loading tours data:', error);

        // Hiển thị thông báo lỗi cho user
        const toursListElement = document.getElementById('tours-list');
        if (toursListElement) {
            toursListElement.innerHTML = `
                <div class="no-results">
                    <h3>Error Loading Tours</h3>
                    <p>Could not load tours data. Please check:</p>
                    <ul style="text-align: left; margin-top: 10px;">
                        <li>API endpoint '/api/beaches' is accessible</li>
                        <li>Server is running</li>
                        <li>Database has data</li>
                    </ul>
                    <p style="margin-top: 15px; font-size: 14px; color: #ef4444;">
                        Error: ${error.message}
                    </p>
                </div>
            `;
        }
    }
}

function initializeFilters() {
    try {
        console.log('🔧 Initializing filters with', toursData.length, 'tours');
        
        // Populate regions dropdown
        const regions = [...new Set(toursData.map(tour => tour.region).filter(region => region))];
        console.log('📍 Regions found:', regions);
        
        const regionSelect = document.getElementById('search-region');
        if (regionSelect) {
            regions.forEach(region => {
                const option = document.createElement('option');
                option.value = region;
                option.textContent = region;
                regionSelect.appendChild(option);
            });
        }

        // Populate tags dropdown - FIX: Xử lý tags an toàn
        const allTags = [...new Set(toursData.flatMap(tour => {
            if (tour.tags && Array.isArray(tour.tags)) {
                return tour.tags;
            }
            return [];
        }))];
        
        console.log('🏷️ Tags found:', allTags);
        
        const tagSelect = document.getElementById('search-tag');
        if (tagSelect) {
            allTags.forEach(tag => {
                const option = document.createElement('option');
                option.value = tag;
                option.textContent = tag;
                tagSelect.appendChild(option);
            });
        }
        
        console.log('✅ Filters initialized successfully');
    } catch (error) {
        console.error('❌ Error initializing filters:', error);
    }
}

function filterTours() {
    try {
        const titleFilter = document.getElementById('search-title').value.toLowerCase();
        const regionFilter = document.getElementById('search-region').value;
        const tagFilter = document.getElementById('search-tag').value;

        console.log('🔍 Filtering with:', { titleFilter, regionFilter, tagFilter });

        filteredTours = toursData.filter(tour => {
            const titleMatch = tour.title && tour.title.toLowerCase().includes(titleFilter);
            const regionMatch = !regionFilter || tour.region === regionFilter;
            
            // FIX: Xử lý tags an toàn
            let tagMatch = true;
            if (tagFilter) {
                tagMatch = tour.tags && Array.isArray(tour.tags) && tour.tags.includes(tagFilter);
            }

            return titleMatch && regionMatch && tagMatch;
        });

        console.log('📊 Filtered results:', filteredTours.length);
        
        renderTours();
        updateToursCount();
    } catch (error) {
        console.error('❌ Error filtering tours:', error);
    }
}

function clearFilters() {
    document.getElementById('search-title').value = '';
    document.getElementById('search-region').value = '';
    document.getElementById('search-tag').value = '';
    filteredTours = [...toursData];
    renderTours();
    updateToursCount();

    // Clear URL parameter
    const url = new URL(window.location);
    url.searchParams.delete('region');
    window.history.replaceState({}, '', url);
}

function updateToursCount() {
    const count = filteredTours.length;
    const countElement = document.getElementById('tours-count');
    if (countElement) {
        countElement.textContent = `${count} Tours found`;
    }
}

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('explore-btn')) {
        const id = e.target.getAttribute('data-id');
        // FIX: Sửa đường dẫn cho Laravel
        window.location.href = `/beaches/${id}`;
    }
});

function renderTourCard(data) {
    try {
        const stars = Array(5).fill().map((_, i) =>
            `<svg class="star" viewBox="0 0 24 24">
                <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" 
                         fill="${i < (data.rating || 0) ? '#fbbf24' : '#e5e7eb'}"/>
             </svg>`
        ).join('');

        // FIX: Xử lý tags an toàn
        let tagsDisplay = 'No tags';
        if (data.tags) {
            if (Array.isArray(data.tags) && data.tags.length > 0) {
                tagsDisplay = data.tags.join(', ');
            } else if (typeof data.tags === 'string') {
                tagsDisplay = data.tags;
            }
        }

        return `
            <div class="tour-card">
                <div class="tour-image">
                    <img src="${data.image || '/assets/img/default.jpg'}" alt="${data.title || 'Tour'}">
                    <div class="feature-badge">Feature</div>
                    <button class="heart-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                    <div class="media-buttons">
                        <button class="media-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="5,3 19,12 5,21"/>
                            </svg>
                            View Video
                        </button>
                        <button class="media-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21,15 16,10 5,21"/>
                            </svg>
                            10 Photos
                        </button>
                    </div>
                </div>
                
                <div class="tour-content">
                    <div class="tour-main">
                        <h2 class="tour-title">${data.title || 'Untitled Tour'}</h2>
                        
                        <div class="tour-region">
                            <svg class="location-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            ${data.region || 'Unknown Region'}
                        </div>

                        <div class="tour-rating">
                            <div class="stars">${stars}</div>
                            <span class="reviews">${data.reviews || 0} Reviews</span>
                        </div>
                        
                        <p class="tour-description">${data.shortDescription || 'No description available'}</p>
                        <p class="cursor-pointer"><i class="bi bi-tags-fill"></i> ${tagsDisplay}</p>
                    </div>
                    
                    <div class="tour-footer">
                        <div class="tour-meta">
                            <div class="meta-item">
                                <svg class="meta-icon" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="m22 21-3-3"/>
                                </svg>
                                ${data.capacity || 'N/A'}
                            </div>
                            <div class="meta-item">
                                <svg class="meta-icon" viewBox="0 0 24 24" fill="currentColor">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12,6 12,12 16,14"/>
                                </svg>
                                ${data.duration || 'N/A'}
                            </div>
                        </div>
                        
                        <div class="tour-price">
                            <div>
                                <span class="price-current">$${data.price || 0}</span>
                                <span class="price-original">$${data.originalPrice || 0}</span>
                            </div>
                            <button class="explore-btn" data-id="${data.id}">Explore</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } catch (error) {
        console.error('❌ Error rendering tour card:', error, data);
        return '<div class="tour-card">Error rendering tour</div>';
    }
}

function renderTours() {
    try {
        console.log('🎨 renderTours called with', filteredTours.length, 'tours');
        
        const container = document.getElementById('tours-list');
        if (!container) {
            console.error('❌ tours-list container not found!');
            return;
        }

        if (filteredTours.length === 0) {
            container.innerHTML = '<div class="no-results">No tours found matching your criteria.</div>';
            console.log('⚠️ No tours to display');
            return;
        }

        const htmlContent = filteredTours.map(tour => renderTourCard(tour)).join('');
        container.innerHTML = htmlContent;
        
        console.log('✅ Tours rendered successfully');
    } catch (error) {
        console.error('❌ Error in renderTours:', error);
        const container = document.getElementById('tours-list');
        if (container) {
            container.innerHTML = '<div class="no-results">Error rendering tours</div>';
        }
    }
}

// Initialize the page
document.addEventListener('DOMContentLoaded', function () {
    console.log('📄 DOM Content Loaded - Starting tour loading...');
    // Load data from JSON file when page loads
    loadToursData();
});