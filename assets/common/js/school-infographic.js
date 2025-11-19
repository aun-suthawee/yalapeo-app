// School Infographic JavaScript
document.addEventListener('DOMContentLoaded', function() {
    initInfographic();
});

function initInfographic() {
    // Initialize search functionality
    initSearch();
    
    // Initialize animations
    initAnimations();
    
    // Initialize modal functionality
    initModalEvents();
}

function initSearch() {
    const searchInput = document.getElementById('schoolSearch');
    if (!searchInput) return;

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().trim();
        filterSchools(searchTerm);
    });
}

function filterSchools(searchTerm) {
    const schoolItems = document.querySelectorAll('.school-item');
    const educationAreaSections = document.querySelectorAll('.department-section');
    
    schoolItems.forEach(item => {
        const schoolName = item.getAttribute('data-name') || '';
        const matches = searchTerm === '' || schoolName.includes(searchTerm);
        
        if (matches) {
            item.style.display = 'block';
            item.style.animation = 'fadeInUp 0.3s ease';
        } else {
            item.style.display = 'none';
        }
    });

    // Hide empty education area sections
    educationAreaSections.forEach(section => {
        const visibleSchools = section.querySelectorAll('.school-item[style*="display: block"], .school-item:not([style*="display: none"])');
        const hasVisibleSchools = Array.from(visibleSchools).some(school => 
            school.style.display !== 'none'
        );
        
        section.style.display = hasVisibleSchools ? 'block' : 'none';
    });
}

function initAnimations() {
    // Animate stats cards
    const statCards = document.querySelectorAll('.stat-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }, { threshold: 0.1 });

    statCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Animate education area sections
    const educationAreaSections = document.querySelectorAll('.department-section');
    educationAreaSections.forEach((section, index) => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';
        section.style.transition = `opacity 0.5s ease ${index * 0.1}s, transform 0.5s ease ${index * 0.1}s`;
        
        setTimeout(() => {
            section.style.opacity = '1';
            section.style.transform = 'translateY(0)';
        }, 500 + (index * 100));
    });
}

function initModalEvents() {
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSchoolModal();
            closeFullscreen();
        }
    });

    // Close modal when clicking outside
    const modal = document.getElementById('schoolModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeSchoolModal();
            }
        });
    }
}

function showSchoolModal(schoolId) {
    const school = schoolsData.find(s => s.id === schoolId);
    if (!school) return;

    const modal = document.getElementById('schoolModal');
    const modalTitle = document.getElementById('modalSchoolName');
    const modalBody = document.getElementById('modalBody');

    modalTitle.textContent = school.name;
    
    // Show loading
    modalBody.innerHTML = '<div class="loading">กำลังโหลดข้อมูล...</div>';
    
    // Show modal
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

    // Load content
    setTimeout(() => {
        modalBody.innerHTML = generateSchoolModalContent(school);
        initModalImageGallery();
    }, 300);
}

function generateSchoolModalContent(school) {
    const innovationsHtml = school.innovations.map(innovation => {
        if (!innovation.is_active) return '';
        
        return `
            <div class="innovation-item">
                <div class="innovation-header">
                    <h4 class="innovation-title">${innovation.title}</h4>
                    ${innovation.category ? `<span class="innovation-category">${innovation.category}</span>` : ''}
                </div>
                ${innovation.description ? `<p class="innovation-description">${innovation.description}</p>` : ''}
                ${innovation.image_path ? `
                    <div class="innovation-image" onclick="openFullscreen('${innovation.image_path}', '${innovation.title}')">
                        <img src="/storage/${innovation.image_path}" alt="${innovation.title}" loading="lazy">
                        <div class="image-overlay">
                            <i class="fas fa-expand"></i> คลิกเพื่อขยาย
                        </div>
                    </div>
                ` : ''}
                <div class="innovation-meta">
                    ${innovation.year ? `<span class="innovation-year">ปี ${innovation.year}</span>` : ''}
                </div>
            </div>
        `;
    }).filter(html => html).join('');

    return `
        <div class="school-detail">
            <div class="school-stats-section">
                <h3>ข้อมูลโรงเรียน</h3>
                <div class="school-stats-grid">
                    <div class="stat-item">
                        <div class="stat-icon">👥</div>
                        <div class="stat-content">
                            <div class="stat-title">นักเรียนทั้งหมด</div>
                            <div class="stat-number">${school.total_students.toLocaleString()}</div>
                            <div class="stat-breakdown">ชาย ${school.male_students.toLocaleString()} | หญิง ${school.female_students.toLocaleString()}</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">👨‍🏫</div>
                        <div class="stat-content">
                            <div class="stat-title">ครู/บุคลากร</div>
                            <div class="stat-number">${school.total_teachers.toLocaleString()}</div>
                            <div class="stat-breakdown">ชาย ${school.male_teachers.toLocaleString()} | หญิง ${school.female_teachers.toLocaleString()}</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">💡</div>
                        <div class="stat-content">
                            <div class="stat-title">นวัตกรรม</div>
                            <div class="stat-number">${school.active_innovations_count}</div>
                            <div class="stat-breakdown">Active Innovations</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">📊</div>
                        <div class="stat-content">
                            <div class="stat-title">อัตราส่วน</div>
                            <div class="stat-number">${school.total_teachers > 0 ? (school.total_students / school.total_teachers).toFixed(1) : 'N/A'}:1</div>
                            <div class="stat-breakdown">นักเรียน : ครู</div>
                        </div>
                    </div>
                </div>
            </div>

            ${innovationsHtml ? `
                <div class="innovations-section">
                    <h3>นวัตกรรมของโรงเรียน</h3>
                    <div class="innovations-gallery">
                        ${innovationsHtml}
                    </div>
                </div>
            ` : `
                <div class="no-innovations">
                    <div class="empty-icon">💡</div>
                    <h4>ยังไม่มีนวัตกรรม</h4>
                    <p>โรงเรียนนี้ยังไม่ได้เพิ่มนวัตกรรมในระบบ</p>
                </div>
            `}
        </div>
    `;
}

function initModalImageGallery() {
    const images = document.querySelectorAll('.innovation-image img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.3s ease';
    });
}

function closeSchoolModal() {
    const modal = document.getElementById('schoolModal');
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

function openFullscreen(imagePath, title) {
    const overlay = document.querySelector('.fullscreen-overlay');
    const image = overlay.querySelector('.fullscreen-image');
    
    image.src = `/storage/${imagePath}`;
    image.alt = title;
    
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeFullscreen() {
    const overlay = document.querySelector('.fullscreen-overlay');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
}

// Add CSS for modal content
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .school-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .modal-body .stat-item {
        background: var(--light-gray);
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        border-left: 4px solid var(--primary-orange);
    }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .stat-title {
        font-size: 0.9rem;
        color: var(--dark-gray);
        margin-bottom: 0.5rem;
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.3rem;
    }

    .stat-breakdown {
        font-size: 0.8rem;
        color: var(--dark-gray);
    }

    .innovations-gallery {
        display: grid;
        gap: 1.5rem;
    }

    .innovation-item {
        background: var(--light-gray);
        padding: 1.5rem;
        border-radius: 10px;
        border-left: 4px solid var(--primary-green);
    }

    .innovation-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .innovation-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
        flex: 1;
    }

    .innovation-category {
        background: var(--primary-blue);
        color: white;
        font-size: 0.7rem;
        padding: 0.3rem 0.6rem;
        border-radius: 15px;
        font-weight: 500;
        margin-left: 1rem;
    }

    .innovation-description {
        color: var(--dark-gray);
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .innovation-image {
        position: relative;
        cursor: pointer;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .innovation-image img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .innovation-image:hover img {
        transform: scale(1.05);
    }

    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        opacity: 0;
        transition: opacity 0.3s ease;
        font-size: 0.9rem;
        gap: 0.5rem;
    }

    .innovation-image:hover .image-overlay {
        opacity: 1;
    }

    .innovation-meta {
        font-size: 0.9rem;
        color: var(--dark-gray);
    }

    .no-innovations {
        text-align: center;
        padding: 3rem;
        color: var(--dark-gray);
    }

    .empty-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .no-innovations h4 {
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {
        .school-stats-grid {
            grid-template-columns: 1fr;
        }
        
        .innovation-header {
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-start;
        }
        
        .innovation-category {
            margin-left: 0;
        }
    }
`;
document.head.appendChild(style);