// School Detail JavaScript
document.addEventListener('DOMContentLoaded', function() {
    initSchoolDetail();
});

function initSchoolDetail() {
    // Initialize animations
    initAnimations();
    
    // Initialize interactive elements
    initInteractiveElements();
    
    // Initialize image gallery if present
    initImageGallery();
}

function initAnimations() {
    // Animate stat cards
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
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Animate innovation cards
    const innovationCards = document.querySelectorAll('.innovation-card');
    innovationCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.5s ease ${index * 0.1}s, transform 0.5s ease ${index * 0.1}s`;
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 200 + (index * 100));
    });
}

function initInteractiveElements() {
    // Add hover effects to contact items
    const contactItems = document.querySelectorAll('.contact-item');
    contactItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });

    // Add click effects to stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('click', function() {
            const statNumber = this.querySelector('.stat-number');
            statNumber.style.transform = 'scale(1.1)';
            statNumber.style.transition = 'transform 0.2s ease';
            
            setTimeout(() => {
                statNumber.style.transform = 'scale(1)';
            }, 200);
        });
    });

    // Add smooth scrolling to anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

function initImageGallery() {
    const innovationImages = document.querySelectorAll('.innovation-image img');
    
    innovationImages.forEach(img => {
        const card = img.closest('.innovation-card');
        const usesSharedLightbox = card && card.dataset.lightbox === '1';

        if (!usesSharedLightbox) {
            img.addEventListener('click', function() {
                openImageModal(this.src, this.alt);
            });
        }

        if (img.complete) {
            img.classList.add('loaded');
        } else {
            img.addEventListener('load', function() {
                this.classList.add('loaded');
            });
        }
    });
}

function openImageModal(src, alt) {
    // Create modal overlay
    const modal = document.createElement('div');
    modal.className = 'image-modal';
    modal.innerHTML = `
        <div class="image-modal-overlay">
            <div class="image-modal-content">
                <button class="image-modal-close">&times;</button>
                <img src="${src}" alt="${alt}" />
                <div class="image-modal-caption">${alt}</div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';

    // Add event listeners
    const closeBtn = modal.querySelector('.image-modal-close');
    const overlay = modal.querySelector('.image-modal-overlay');

    closeBtn.addEventListener('click', closeImageModal);
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closeImageModal();
        }
    });

    document.addEventListener('keydown', handleEscKey);

    // Animate in
    setTimeout(() => {
        modal.classList.add('active');
    }, 10);

    function closeImageModal() {
        modal.classList.remove('active');
        setTimeout(() => {
            document.body.removeChild(modal);
            document.body.style.overflow = '';
            document.removeEventListener('keydown', handleEscKey);
        }, 300);
    }

    function handleEscKey(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    }
}

// Copy to clipboard functionality
function copyToClipboard(text, element) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success feedback
        const original = element.textContent;
        element.textContent = 'คัดลอกแล้ว!';
        element.style.color = '#28a745';
        
        setTimeout(() => {
            element.textContent = original;
            element.style.color = '';
        }, 2000);
    });
}

// Add click-to-copy functionality to contact info
document.querySelectorAll('.contact-content a').forEach(link => {
    link.addEventListener('click', function(e) {
        if (this.href.startsWith('tel:') || this.href.startsWith('mailto:')) {
            e.preventDefault();
            const text = this.textContent;
            copyToClipboard(text, this);
        }
    });
});

// Add CSS for image modal and animations
const style = document.createElement('style');
style.textContent = `
    .innovation-image img {
        cursor: pointer;
        transition: transform 0.3s ease;
        opacity: 0;
    }
    
    .innovation-image img.loaded {
        opacity: 1;
    }
    
    .innovation-image img:hover {
        transform: scale(1.05);
    }
    
    .image-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .image-modal.active {
        opacity: 1;
    }
    
    .image-modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    
    .image-modal-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        text-align: center;
    }
    
    .image-modal-content img {
        max-width: 100%;
        max-height: 80vh;
        border-radius: 8px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }
    
    .image-modal-close {
        position: absolute;
        top: -40px;
        right: -40px;
        background: none;
        border: none;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
    }
    
    .image-modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    
    .image-modal-caption {
        color: white;
        margin-top: 1rem;
        font-size: 1.1rem;
    }
    
    .stat-card {
        cursor: pointer;
    }
    
    .contact-item {
        transition: all 0.3s ease;
    }
    
    @media (max-width: 768px) {
        .image-modal-close {
            top: -30px;
            right: -30px;
            font-size: 1.5rem;
        }
        
        .image-modal-content {
            max-width: 95%;
            max-height: 95%;
        }
    }
`;
document.head.appendChild(style);