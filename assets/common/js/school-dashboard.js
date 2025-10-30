// School Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard animations and interactions
    initDashboard();
});

function initDashboard() {
    // Animate stats cards on scroll
    animateStatsOnScroll();
    
    // Add interactive hover effects
    addHoverEffects();
    
    // Initialize tooltips if needed
    initTooltips();
}

function animateStatsOnScroll() {
    const statCards = document.querySelectorAll('.stat-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    statCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
}

function addHoverEffects() {
    // Add ripple effect to stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('click', function(e) {
            const ripple = document.createElement('div');
            ripple.style.position = 'absolute';
            ripple.style.width = '10px';
            ripple.style.height = '10px';
            ripple.style.background = 'rgba(255, 107, 53, 0.3)';
            ripple.style.borderRadius = '50%';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.left = (e.clientX - card.offsetLeft) + 'px';
            ripple.style.top = (e.clientY - card.offsetTop) + 'px';
            
            card.style.position = 'relative';
            card.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
}

function initTooltips() {
    // Initialize tooltips for stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        const statNumber = card.querySelector('.stat-number');
        const statLabel = card.querySelector('.stat-label');
        
        if (statNumber && statLabel) {
            card.setAttribute('title', `${statLabel.textContent}: ${statNumber.textContent}`);
        }
    });
}

// Add CSS animation for ripple effect
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);