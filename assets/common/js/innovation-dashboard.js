// Innovation Dashboard with Infinite Scroll
document.addEventListener('DOMContentLoaded', function () {
    initInnovationDashboard();
});

let currentPage = 1;
let isLoading = false;
let hasMoreData = true;
let currentFilters = {
    search: '',
    category: ''
};

function initInnovationDashboard() {
    // Initialize search functionality
    initSearch();

    // Initialize category filters
    initCategoryFilters();

    // Initialize infinite scroll
    initInfiniteScroll();
}

function initSearch() {
    const searchInput = document.getElementById('innovationSearch');
    if (!searchInput) return;

    let searchTimeout;
    searchInput.addEventListener('input', function (e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentFilters.search = e.target.value;
            resetAndReload();
        }, 500); // Debounce 500ms
    });
}

function initCategoryFilters() {
    const categoryButtons = document.querySelectorAll('#innovationCategories .category-chip');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            categoryButtons.forEach(btn => btn.classList.remove('is-active'));
            
            // Add active class to clicked button
            this.classList.add('is-active');
            
            // Update filter
            currentFilters.category = this.dataset.category || '';
            resetAndReload();
        });
    });
}

function resetAndReload() {
    currentPage = 1;
    hasMoreData = true;
    const grid = document.getElementById('innovationGrid');
    if (grid) {
        grid.innerHTML = '';
    }
    loadInnovations(true);
}

function initInfiniteScroll() {
    window.addEventListener('scroll', function () {
        if (isLoading || !hasMoreData) return;

        const scrollPosition = window.innerHeight + window.scrollY;
        const documentHeight = document.documentElement.scrollHeight;

        // Load more when user scrolls to 80% of page
        if (scrollPosition >= documentHeight * 0.8) {
            currentPage++;
            loadInnovations(false);
        }
    });
}

function getPerPageCount() {
    const width = window.innerWidth;
    
    if (width >= 1200) {
        return 16; // Large screens
    } else if (width >= 768) {
        return 12; // Medium screens (tablets)
    } else if (width >= 576) {
        return 8; // Small screens
    } else {
        return 5; // Mobile
    }
}

function loadInnovations(isInitial) {
    if (isLoading) return;

    isLoading = true;
    showLoading();

    const perPage = getPerPageCount();
    const params = new URLSearchParams({
        page: currentPage,
        perPage: perPage,
        search: currentFilters.search,
        category: currentFilters.category
    });

    fetch(`/sandbox?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            hideLoading();

            if (data.innovations.length === 0 && isInitial) {
                showEmptyState();
                updateResultsCount(0);
            } else {
                renderInnovations(data.innovations);
                hasMoreData = data.hasMore;
                
                if (isInitial) {
                    updateResultsCount(data.total);
                }
                
                if (!hasMoreData && !isInitial) {
                    showNoMoreData();
                }
            }

            isLoading = false;
        })
        .catch(error => {
            console.error('Error loading innovations:', error);
            hideLoading();
            isLoading = false;
        });
}

function renderInnovations(innovations) {
    const grid = document.getElementById('innovationGrid');
    const emptyState = document.getElementById('innovationEmpty');

    if (emptyState) {
        emptyState.style.display = 'none';
    }

    innovations.forEach(innovation => {
        const card = createInnovationCard(innovation);
        grid.appendChild(card);
    });
}

function createInnovationCard(innovation) {
    const card = document.createElement('a');
    card.className = 'innovation-card';
    card.style.animation = 'fadeInUp 0.5s ease';
    
    const schoolName = innovation.school?.name || 'ไม่พบข้อมูลโรงเรียน';
    const categoryName = innovation.category || 'ไม่ระบุหมวดหมู่';
    
    // Get first image
    const firstImage = innovation.image_paths && innovation.image_paths.length > 0 
        ? innovation.image_paths[0] 
        : null;
    
    let imageUrl = null;
    let isPdf = false;
    
    if (firstImage) {
        const fileName = firstImage.split('/').pop();
        const extension = fileName.split('.').pop().toLowerCase();
        isPdf = extension === 'pdf';
        
        if (!isPdf) {
            imageUrl = `/sandbox/innovation-image/${innovation.school_id}/${fileName}`;
        }
    }
    
    const assetPlaceholder = '/assets/images/education-sandbox/innovation-placeholder.svg';
    const detailUrl = `/sandbox/schools/${innovation.school_id}/innovations?highlight=${innovation.id}`;
    
    card.href = detailUrl;
    card.setAttribute('data-title', escapeHtml(innovation.title).toLowerCase());
    card.setAttribute('data-category', categoryName.toLowerCase());
    card.setAttribute('data-school', schoolName.toLowerCase());
    card.setAttribute('data-description', escapeHtml(innovation.description || '').toLowerCase());
    
    card.innerHTML = `
        <div class="card-figure">
            ${imageUrl && !isPdf ? `
                <img src="${imageUrl}" alt="${escapeHtml(innovation.title)}" loading="lazy">
            ` : `
                <img src="${assetPlaceholder}" alt="Innovation placeholder" loading="lazy">
            `}
            <span class="figure-badge">${escapeHtml(categoryName)}</span>
            <div class="figure-overlay"></div>
        </div>
        <div class="card-body">
            <div class="card-heading">
                <span class="school-name">${escapeHtml(schoolName)}</span>
                <h3 class="innovation-title">${escapeHtml(innovation.title)}</h3>
            </div>
            ${innovation.description ? `
                <p class="innovation-description">
                    ${escapeHtml(limitText(stripTags(innovation.description), 120))}
                </p>
            ` : ''}
            <div class="card-footer">
                ${innovation.year ? `
                    <span class="meta-pill"><i class="far fa-calendar"></i> พ.ศ. ${innovation.year}</span>
                ` : ''}
                <span class="meta-pill"><i class="far fa-clock"></i> ${formatDate(innovation.created_at)}</span>
                <span class="cta">ดูรายละเอียด <i class="fas fa-arrow-right"></i></span>
            </div>
        </div>
    `;

    return card;
}

function showLoading() {
    let loading = document.getElementById('innovationLoadingIndicator');
    if (!loading) {
        // Create loading indicator if it doesn't exist
        loading = document.createElement('div');
        loading.id = 'innovationLoadingIndicator';
        loading.className = 'loading-indicator';
        loading.innerHTML = `
            <div class="spinner"></div>
            <p>กำลังโหลดนวัตกรรม...</p>
        `;
        
        const grid = document.getElementById('innovationGrid');
        if (grid && grid.parentNode) {
            grid.parentNode.insertBefore(loading, grid.nextSibling);
        }
    }
    loading.style.display = 'block';
}

function hideLoading() {
    const loading = document.getElementById('innovationLoadingIndicator');
    if (loading) {
        loading.style.display = 'none';
    }
}

function showEmptyState() {
    const emptyState = document.getElementById('innovationEmpty');
    if (emptyState) {
        emptyState.style.display = 'block';
    }
}

function showNoMoreData() {
    let noMoreData = document.getElementById('innovationNoMoreData');
    if (!noMoreData) {
        noMoreData = document.createElement('div');
        noMoreData.id = 'innovationNoMoreData';
        noMoreData.className = 'no-more-data';
        noMoreData.innerHTML = '<p>แสดงนวัตกรรมครบทั้งหมดแล้ว</p>';
        
        const loading = document.getElementById('innovationLoadingIndicator');
        if (loading && loading.parentNode) {
            loading.parentNode.insertBefore(noMoreData, loading.nextSibling);
        }
    }
    noMoreData.style.display = 'block';
}

function updateResultsCount(count) {
    const resultsCount = document.getElementById('innovationResultsCount');
    if (resultsCount) {
        resultsCount.textContent = formatNumber(count);
    }
}

function formatNumber(num) {
    return new Intl.NumberFormat('th-TH').format(num);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text ? String(text).replace(/[&<>"']/g, m => map[m]) : '';
}

function stripTags(html) {
    const tmp = document.createElement('div');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
}

function limitText(text, limit) {
    if (text.length <= limit) return text;
    return text.substring(0, limit) + '...';
}
