// School Management JavaScript with Infinite Scroll
document.addEventListener('DOMContentLoaded', function () {
    initSchoolManagement();
});

let currentPage = 1;
let isLoading = false;
let hasMoreData = true;
let currentFilters = {
    search: '',
    department: ''
};

function initSchoolManagement() {
    // Load initial data
    loadSchools(true);

    // Initialize search functionality
    initSearch();

    // Initialize filters
    initFilters();

    // Initialize infinite scroll
    initInfiniteScroll();
}

function initSearch() {
    const searchInput = document.getElementById('schoolSearch');
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

function initFilters() {
    const departmentFilter = document.getElementById('departmentFilter');
    const resetButton = document.getElementById('resetFilter');

    if (departmentFilter) {
        departmentFilter.addEventListener('change', function (e) {
            currentFilters.department = e.target.value;
            resetAndReload();
        });
    }

    if (resetButton) {
        resetButton.addEventListener('click', function () {
            document.getElementById('schoolSearch').value = '';
            document.getElementById('departmentFilter').value = '';
            currentFilters.search = '';
            currentFilters.department = '';
            resetAndReload();
        });
    }
}

function resetAndReload() {
    currentPage = 1;
    hasMoreData = true;
    document.getElementById('schoolsGrid').innerHTML = '';
    loadSchools(true);
}

function initInfiniteScroll() {
    window.addEventListener('scroll', function () {
        if (isLoading || !hasMoreData) return;

        const scrollPosition = window.innerHeight + window.scrollY;
        const documentHeight = document.documentElement.scrollHeight;

        // Load more when user scrolls to 80% of page
        if (scrollPosition >= documentHeight * 0.8) {
            currentPage++;
            loadSchools(false);
        }
    });
}

function loadSchools(isInitial) {
    if (isLoading) return;

    isLoading = true;
    showLoading();

    const params = new URLSearchParams({
        page: currentPage,
        search: currentFilters.search,
        department: currentFilters.department
    });

    fetch(`/sandbox/schools?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            hideLoading();

            if (data.schools.length === 0 && isInitial) {
                showEmptyState();
            } else {
                renderSchools(data.schools);
                hasMoreData = data.hasMore;
            }

            isLoading = false;
        })
        .catch(error => {
            console.error('Error loading schools:', error);
            hideLoading();
            isLoading = false;
        });
}

function renderSchools(schools) {
    const grid = document.getElementById('schoolsGrid');
    const emptyState = document.getElementById('emptyState');

    if (emptyState) {
        emptyState.style.display = 'none';
    }

    schools.forEach(school => {
        const card = createSchoolCard(school);
        grid.appendChild(card);
    });
}

function createSchoolCard(school) {
    const isAuth = document.querySelector('meta[name="auth-check"]')?.content === 'true';

    const card = document.createElement('div');
    card.className = 'school-card';
    card.style.animation = 'fadeInUp 0.5s ease';
    card.style.cursor = 'pointer';
    
    // Add click event to entire card
    card.addEventListener('click', function(e) {
        // Don't navigate if clicking on buttons/forms
        if (e.target.closest('.school-actions') || e.target.closest('button') || e.target.closest('a')) {
            return;
        }
        window.location.href = `/sandbox/schools/${school.id}`;
    });

    card.innerHTML = `
        <div class="school-header">
            <span class="department-badge">${escapeHtml(school.department)}</span>
            <h3 class="school-name">${escapeHtml(school.name)}</h3>
        </div>


        <div class="school-stats">
            <div class="stat-row">
                <div class="stat-item">
                    <span class="stat-icon">👥</span>
                    <div class="stat-details">
                        <span class="stat-label">นักเรียน</span>
                        <span class="stat-value">${formatNumber(school.total_students)}</span>
                        <span class="stat-breakdown">
                            ชาย ${formatNumber(school.male_students)} 
                            หญิง ${formatNumber(school.female_students)}
                        </span>
                    </div>
                </div>

                <div class="stat-item">
                    <span class="stat-icon">👨‍🏫</span>
                    <div class="stat-details">
                        <span class="stat-label">ครู/บุคลากร</span>
                        <span class="stat-value">${formatNumber(school.total_teachers)}</span>
                        <span class="stat-breakdown">
                            ชาย ${formatNumber(school.male_teachers)} 
                            หญิง ${formatNumber(school.female_teachers)}
                        </span>
                    </div>
                </div>

                <div class="stat-item">
                    <span class="stat-icon">💡</span>
                    <div class="stat-details">
                        <span class="stat-label">นวัตกรรม</span>
                        <span class="stat-value">${school.active_innovations_count}</span>
                        <span class="stat-breakdown">Active Innovations</span>
                    </div>
                </div>
            </div>
        </div>

        ${(school.phone || school.email) ? `
        <div class="school-contact">
            ${school.phone ? `
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>${escapeHtml(school.phone)}</span>
                </div>
            ` : ''}
            ${school.email ? `
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>${escapeHtml(school.email)}</span>
                </div>
            ` : ''}
        </div>
        ` : ''}

        <div class="school-actions">
            <a href="/sandbox/schools/${school.id}/innovations" class="btn btn-sm btn-success">
                <i class="fas fa-lightbulb"></i> นวัตกรรม
            </a>
            ${isAuth ? `
            <a href="/sandbox/schools/${school.id}/edit" class="btn btn-sm btn-warning">
                <i class="fas fa-edit"></i> แก้ไข
            </a>
            <form action="/sandbox/schools/${school.id}" method="POST" style="display: inline-block;" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบโรงเรียนนี้?')">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.content}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i> ลบ
                </button>
            </form>
            ` : ''}
        </div>
    `;

    return card;
}

function showLoading() {
    const loading = document.getElementById('loadingIndicator');
    if (loading) {
        loading.style.display = 'block';
    }
}

function hideLoading() {
    const loading = document.getElementById('loadingIndicator');
    if (loading) {
        loading.style.display = 'none';
    }
}

function showEmptyState() {
    const emptyState = document.getElementById('emptyState');
    if (emptyState) {
        emptyState.style.display = 'block';
    }
}

function formatNumber(num) {
    return new Intl.NumberFormat('th-TH').format(num);
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