// Main init function
document.addEventListener('DOMContentLoaded', function () {
    // TikTok functions
    window.expandTikTok = function (container) {
        container.classList.add('expanding');

        const overlay = container.querySelector('.tiktok-overlay');
        if (overlay) {
            overlay.style.background = 'rgba(0,0,0,0)';
            overlay.style.zIndex = '-1';
        }

        const infoSection = container.parentNode.querySelector('.tiktok-info');
        if (infoSection) {
            infoSection.style.maxHeight = '0';
            infoSection.style.opacity = '0';
            infoSection.style.overflow = 'hidden';
        }

        container.style.paddingBottom = '177.8%';
        container.setAttribute('data-expanded', 'true');
        document.addEventListener('click', collapseOnClickOutside);

        const hintElement = container.querySelector('.tiktok-expand-hint');
        if (hintElement) {
            animateElement(hintElement, { opacity: '0' }, 500);
        }
    };

    function collapseOnClickOutside(event) {
        const expandedTikTok = document.querySelector('.tiktok-iframe-container.expanding');
        if (!expandedTikTok) {
            document.removeEventListener('click', collapseOnClickOutside);
            return;
        }

        if (!expandedTikTok.contains(event.target) && event.target !== expandedTikTok) {
            collapseTikTok(expandedTikTok);
            document.removeEventListener('click', collapseOnClickOutside);
        }
    }

    function collapseTikTok(container) {
        container.classList.remove('expanding');

        const overlay = container.querySelector('.tiktok-overlay');
        if (overlay) {
            overlay.style.background = 'rgba(0,0,0,0.01)';
            overlay.style.opacity = '1';
            overlay.style.zIndex = '1';
        }

        const infoSection = container.parentNode.querySelector('.tiktok-info');
        if (infoSection) {
            infoSection.style.maxHeight = '';
            infoSection.style.opacity = '1';
            infoSection.style.overflow = '';
        }

        container.style.paddingBottom = '100%';
        container.removeAttribute('data-expanded');

        const hintElement = container.querySelector('.tiktok-expand-hint');
        if (hintElement) {
            animateElement(hintElement, { opacity: '1' }, 300);
        }
    }

    // Scroll animations
    const setupScrollAnimations = () => {
        const observerOptions = { threshold: 0.2, once: true };

        // TikTok section
        const tiktokSection = document.querySelector('.tiktok-videos');
        if (tiktokSection) {
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    entries[0].target.classList.add('in-view');

                    const animatedElements = entries[0].target.querySelectorAll('.animate-on-scroll');
                    batchAnimate(animatedElements, { opacity: '1', transform: 'translateY(0)' });

                    observer.unobserve(entries[0].target);
                }
            }, { threshold: 0.1 });

            observer.observe(tiktokSection);
        }

        // Assessment section
        const assessmentSection = document.getElementById('assessment');
        if (assessmentSection) {
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    assessmentSection.classList.add('animate-section');

                    const assessmentCards = assessmentSection.querySelectorAll('.assessment-card');
                    assessmentCards.forEach((card, index) => {
                        setTimeout(() => card.classList.add('animated'), 150 * index);
                    });

                    observer.unobserve(assessmentSection);
                }
            }, observerOptions);

            observer.observe(assessmentSection);
        }
    };

    // Initialize external libraries
    const initLibraries = () => {
        $('.tiktok-slider').owlCarousel({
            loop: false,
            margin: 20,
            nav: true,
            dots: true,
            autoplay: false,
            autoplayHoverPause: true,
            responsive: {
                0: { items: 1 },
                576: { items: 2 },
                768: { items: 3 },
                992: { items: 4 },
                1200: { items: 4 }
            },
            navText: ['<span>&lt;</span>', '<span>&gt;</span>']
        });
    };

    // Execute all initializations
    setupScrollAnimations();
    initLibraries();
});
