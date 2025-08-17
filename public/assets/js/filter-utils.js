// Enhanced search and filter functionality
document.addEventListener('DOMContentLoaded', function() {
    // Fix collapse functionality
    const collapseButton = document.querySelector('[data-bs-toggle="collapse"]');
    const collapseElement = document.getElementById('filterCollapse');
    
    if (collapseButton && collapseElement) {
        // Initialize Bootstrap collapse if available
        if (window.bootstrap && bootstrap.Collapse) {
            new bootstrap.Collapse(collapseElement, {
                toggle: false
            });
        } else {
            // Fallback collapse functionality
            collapseButton.addEventListener('click', function() {
                const isCollapsed = collapseElement.classList.contains('show');
                if (isCollapsed) {
                    collapseElement.classList.remove('show');
                    collapseElement.style.height = '0px';
                    collapseElement.style.overflow = 'hidden';
                } else {
                    collapseElement.classList.add('show');
                    collapseElement.style.height = 'auto';
                    collapseElement.style.overflow = 'visible';
                }
                
                // Toggle chevron icon
                const chevronIcon = collapseButton.querySelector('.bi-chevron-down, .bi-chevron-up');
                if (chevronIcon) {
                    if (isCollapsed) {
                        chevronIcon.className = 'bi bi-chevron-down';
                    } else {
                        chevronIcon.className = 'bi bi-chevron-up';
                    }
                }
            });
        }
    }

    // Auto-submit form when search input changes (with debounce)
    const searchInput = document.getElementById('search');
    const filterForm = document.querySelector('form[action*="index"]');
    let searchTimeout;

    if (searchInput && filterForm) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                // Auto-submit form after 1 second of no typing
                filterForm.submit();
            }, 1000);
        });
    }

    // Auto-submit form when select fields change
    const selectFields = document.querySelectorAll('select[name="beach_id"], select[name="status"], select[name="ceo_id"], select[name="region_id"]');
    selectFields.forEach(function(select) {
        select.addEventListener('change', function() {
            filterForm.submit();
        });
    });

    // Clear individual filters
    function addClearButton(inputElement, clearCallback) {
        if (inputElement && inputElement.value) {
            const clearBtn = document.createElement('button');
            clearBtn.type = 'button';
            clearBtn.className = 'btn btn-sm btn-outline-secondary';
            clearBtn.innerHTML = '<i class="bi bi-x"></i>';
            clearBtn.style.position = 'absolute';
            clearBtn.style.right = '10px';
            clearBtn.style.top = '50%';
            clearBtn.style.transform = 'translateY(-50%)';
            clearBtn.style.zIndex = '10';
            
            inputElement.style.paddingRight = '40px';
            inputElement.parentNode.style.position = 'relative';
            inputElement.parentNode.appendChild(clearBtn);
            
            clearBtn.addEventListener('click', function() {
                clearCallback();
            });
        }
    }

    // Add clear functionality to search input
    addClearButton(searchInput, function() {
        searchInput.value = '';
        filterForm.submit();
    });

    // Reset filters functionality
    const resetButton = document.querySelector('a[href*="index"]:not([href*="create"])');
    if (resetButton) {
        resetButton.addEventListener('click', function(e) {
            e.preventDefault();
            // Clear all form inputs
            const form = document.querySelector('form[method="GET"]');
            if (form) {
                const inputs = form.querySelectorAll('input, select');
                inputs.forEach(function(input) {
                    if (input.type === 'text' || input.type === 'number' || input.type === 'date') {
                        input.value = '';
                    } else if (input.tagName === 'SELECT') {
                        input.selectedIndex = 0;
                    }
                });
                form.submit();
            }
        });
    }

    // Add loading state to form submission
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            const submitBtn = filterForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang tìm...';
                submitBtn.disabled = true;
            }
        });
    }

    // Toggle filter collapse state based on whether there are active filters
    const filterCollapse = document.getElementById('filterCollapse');
    const hasActiveFilters = window.location.search.includes('search=') || 
                           window.location.search.includes('beach_id=') || 
                           window.location.search.includes('status=') || 
                           window.location.search.includes('ceo_id=') || 
                           window.location.search.includes('region_id=') || 
                           window.location.search.includes('price_min=') || 
                           window.location.search.includes('price_max=') || 
                           window.location.search.includes('date_from=') || 
                           window.location.search.includes('date_to=');

    if (hasActiveFilters && filterCollapse) {
        filterCollapse.classList.add('show');
    }
});

// Export filter utilities for potential reuse
window.FilterUtils = {
    clearFilter: function(filterName) {
        const url = new URL(window.location);
        url.searchParams.delete(filterName);
        window.location.href = url.toString();
    },
    
    updateFilter: function(filterName, value) {
        const url = new URL(window.location);
        if (value) {
            url.searchParams.set(filterName, value);
        } else {
            url.searchParams.delete(filterName);
        }
        window.location.href = url.toString();
    },
    
    clearAllFilters: function() {
        const baseUrl = window.location.pathname;
        window.location.href = baseUrl;
    }
};
