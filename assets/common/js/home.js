// Main init function
document.addEventListener('DOMContentLoaded', function () {
    // Animation helper functions
    const animateElement = (element, properties, delay = 0) => {
        if (!element) return;

        const apply = () => Object.assign(element.style, properties);
        delay ? setTimeout(apply, delay) : apply();
    };

    const batchAnimate = (elements, properties, interval = 100) => {
        if (!elements.length) return;

        // ใช้ requestAnimationFrame เพื่อให้ smooth และประหยัด performance
        elements.forEach((el, i) => {
            requestAnimationFrame(() => {
                setTimeout(() => {
                    if (!el) return;
                    Object.assign(el.style, properties);
                }, interval * i);
            });
        });
    };

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

        // Video section
        const videoSection = document.querySelector('.videos-section');
        if (videoSection) {
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    videoSection.querySelectorAll('.text-highlight').forEach(el => {
                        el.classList.add('animated');
                    });
                    observer.unobserve(videoSection);
                }
            }, { threshold: 0.3 });

            observer.observe(videoSection);
        }

        // Purchase News section
        const purchaseNewsSection = document.querySelector('.purchase-news-section');
        if (purchaseNewsSection) {
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    purchaseNewsSection.classList.add('animate-start');

                    const newsItems = purchaseNewsSection.querySelectorAll('.news-item');
                    batchAnimate(newsItems, { className: 'animate' });

                    observer.unobserve(purchaseNewsSection);
                }
            }, observerOptions);

            observer.observe(purchaseNewsSection);
        }

        // Latest News section
        const latestNewsSection = document.querySelector('.lasted-news');
        if (latestNewsSection) {
            latestNewsSection.classList.add('animate-start');

            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    latestNewsSection.classList.add('animate-active');

                    const newsCards = latestNewsSection.querySelectorAll('.news-card');
                    batchAnimate(newsCards, { opacity: '1', transform: 'translateY(0)' });

                    const highlight = latestNewsSection.querySelector('.text-highlight');
                    if (highlight) {
                        highlight.style.position = 'relative';
                        const underline = document.createElement('span');
                        underline.style.cssText = 'position:absolute;bottom:-5px;left:0;width:0;height:2px;background:linear-gradient(90deg, #dc3545, #fd7e14);transition:width 0.8s ease';
                        highlight.appendChild(underline);

                        requestAnimationFrame(() => {
                            requestAnimationFrame(() => {
                                underline.style.width = '100%';
                            });
                        });
                    }

                    observer.unobserve(latestNewsSection);
                }
            }, observerOptions);

            observer.observe(latestNewsSection);
        }

        // Webboard section
        const webboardSection = document.querySelector('.webboard-section');
        if (webboardSection) {
            webboardSection.classList.add('animate-start');

            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    webboardSection.classList.add('animate-active');

                    const rows = webboardSection.querySelectorAll('.webboard-row');
                    batchAnimate(rows, { opacity: '1', transform: 'translateX(0)' });

                    const title = webboardSection.querySelector('.webboard-title');
                    if (title) {
                        animateElement(title, { color: '#28a745' }, 500);
                        animateElement(title, { color: '' }, 1500);
                    }

                    const createBtn = webboardSection.querySelector('.create-thread-btn');
                    if (createBtn) {
                        setTimeout(() => {
                            createBtn.classList.add('pulse');
                            setTimeout(() => createBtn.classList.remove('pulse'), 5000);
                        }, 1000);
                    }

                    observer.unobserve(webboardSection);
                }
            }, observerOptions);

            observer.observe(webboardSection);
        }
    };

    // Event delegation and consolidated handlers
    const setupEventListeners = () => {
        document.addEventListener('mouseover', function (event) {
            const target = event.target;

            // TikTok containers
            if (target.closest('.tiktok-container')) {
                const iframe = target.closest('.tiktok-container').querySelector('iframe');
                if (iframe) {
                    iframe.style.transform = 'scale(1.02)';
                    iframe.style.transition = 'transform 0.3s ease';
                }
            }

            // View All buttons
            if (target.matches('.view-all-btn')) {
                target.style.transform = 'translateY(-3px)';
                if (target.classList.contains('news-btn')) {
                    target.style.boxShadow = '0 8px 20px rgba(220, 53, 69, 0.3)';
                } else if (target.classList.contains('webboard-btn')) {
                    target.style.boxShadow = '0 5px 15px rgba(40, 167, 69, 0.3)';
                    const icon = target.querySelector('i');
                    if (icon) icon.style.transform = 'translateX(3px)';
                }
            }

            // Webboard rows
            if (target.closest('.webboard-row')) {
                const row = target.closest('.webboard-row');
                const link = row.querySelector('.webboard-link');
                if (link) {
                    link.style.transform = 'translateX(5px)';
                    link.style.color = '#28a745';
                }
                row.style.backgroundColor = 'rgba(255, 193, 7, 0.05)';
            }

            // Create thread button
            if (target.matches('.create-thread-btn')) {
                target.style.transform = 'translateY(-3px)';
                target.style.boxShadow = '0 5px 15px rgba(255, 193, 7, 0.3)';

                const icon = target.querySelector('i');
                if (icon) icon.style.transform = 'rotate(90deg)';
            }

            // News images
            if (target.matches('.news-image')) {
                target.style.transform = 'scale(1.05)';
            }

            // Card titles
            if (target.matches('.card-title a')) {
                target.style.color = '#dc3545';
                target.style.paddingLeft = '3px';
                target.style.transition = 'all 0.3s ease';
            }

            // Webboard card
            if (target.closest('.webboard-card')) {
                const card = target.closest('.webboard-card');
                card.style.transform = 'translateY(-5px)';
                card.style.boxShadow = '0 12px 30px rgba(0, 0, 0, 0.1)';

                const titleElement = card.querySelector('.webboard-title');
                if (titleElement && !titleElement.querySelector('.title-underline')) {
                    const titleUnderline = document.createElement('span');
                    titleUnderline.className = 'title-underline';
                    titleUnderline.style.cssText = 'position:absolute;bottom:0;left:0;width:0;height:2px;background:linear-gradient(90deg,#ffc107,#28a745);transition:width 0.5s ease';
                    titleElement.appendChild(titleUnderline);

                    requestAnimationFrame(() => {
                        titleUnderline.style.width = '100%';
                    });
                }
            }

            // Title highlights
            if (target.closest('.purchase-news-section .title')) {
                const highlightText = target.closest('.title').querySelector('.text-highlight');
                if (highlightText) {
                    highlightText.style.transition = 'color 0.3s ease';
                    highlightText.style.color = '#fd7e14';
                }
            }
        });

        document.addEventListener('mouseout', function (event) {
            const target = event.target;

            // TikTok containers
            if (target.closest('.tiktok-container')) {
                const iframe = target.closest('.tiktok-container').querySelector('iframe');
                if (iframe) iframe.style.transform = 'scale(1)';
            }

            // View All buttons
            if (target.matches('.view-all-btn')) {
                target.style.transform = '';
                target.style.boxShadow = '';
                const icon = target.querySelector('i');
                if (icon) icon.style.transform = '';
            }

            // Webboard rows
            if (target.closest('.webboard-row')) {
                const row = target.closest('.webboard-row');
                const link = row.querySelector('.webboard-link');
                if (link) {
                    link.style.transform = '';
                    link.style.color = '';
                }
                row.style.backgroundColor = '';
            }

            // Create thread button
            if (target.matches('.create-thread-btn')) {
                target.style.transform = '';
                target.style.boxShadow = '';

                const icon = target.querySelector('i');
                if (icon) icon.style.transform = '';
            }

            // News images
            if (target.matches('.news-image')) {
                target.style.transform = '';
            }

            // Card titles
            if (target.matches('.card-title a')) {
                target.style.color = '';
                target.style.paddingLeft = '';
            }

            // Webboard card
            if (target.closest('.webboard-card')) {
                const card = target.closest('.webboard-card');
                card.style.transform = '';
                card.style.boxShadow = '';

                const titleUnderline = card.querySelector('.title-underline');
                if (titleUnderline) {
                    titleUnderline.style.width = '0';
                    setTimeout(() => {
                        if (titleUnderline.parentNode) {
                            titleUnderline.parentNode.removeChild(titleUnderline);
                        }
                    }, 500);
                }
            }

            // Title highlights
            if (target.closest('.purchase-news-section .title')) {
                const highlightText = target.closest('.title').querySelector('.text-highlight');
                if (highlightText) {
                    highlightText.style.color = '#dc3545';
                }
            }
        });

        // Video card interaction
        document.querySelectorAll('.video-card').forEach(card => {
            const iframes = card.querySelectorAll('iframe');

            card.addEventListener('mouseenter', () => {
                iframes.forEach(iframe => {
                    iframe.style.pointerEvents = 'none';
                });
            });

            card.addEventListener('click', (e) => {
                if (e.target.closest('.video-overlay')) {
                    e.preventDefault();
                    iframes.forEach(iframe => {
                        iframe.style.pointerEvents = 'auto';
                    });
                }
            });

            card.addEventListener('mouseleave', () => {
                iframes.forEach(iframe => {
                    iframe.style.pointerEvents = 'auto';
                });
            });
        });
    };

    // Style injection
    const injectStyles = () => {
        const style = document.createElement('style');
        style.textContent = `
            .tiktok-container { border:1px solid #f0f0f0; border-radius:0; overflow:hidden; height:100%; }
            .tiktok-info { border-top:1px solid #f0f0f0; transition:opacity 0.3s ease, max-height 0.5s ease; }
            .tiktok-iframe-container { border-radius:0; overflow:hidden; transform:translateZ(0); }
            .tiktok-iframe-container.expanding { z-index:10; }
            .tiktok-iframe-container iframe { border-radius:0; }
            .tiktok-overlay { background:rgba(0,0,0,0.01); }
            .tiktok-iframe-container:hover .tiktok-overlay { background:rgba(0,0,0,0.03); }
            .tiktok-slide-item { padding:10px; }
            .tiktok-view-all { display:block; height:100%; border:0; text-decoration:none; color:#007bff; background-color:transparent; transition:all 0.3s ease; padding:10px; position:relative; overflow:hidden; }
            .tiktok-view-all:hover { color:#0056b3; transform:translateY(-5px); box-shadow:0 5px 15px rgba(0,0,0,0.1); }
            .tiktok-view-all-content { position:relative; z-index:2; height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; background:transparent; min-height:300px; }
            .tiktok-thumbnails { position:absolute; top:0; left:0; width:100%; height:100%; z-index:1; display:flex; justify-content:center; align-items:center; }
            .tiktok-thumbnail { position:absolute; width:120px; height:200px; background-size:cover; background-position:center; border-radius:8px; box-shadow:0 8px 20px rgba(0,0,0,0.25); transition:all 0.4s ease; overflow:hidden; }
            .tiktok-thumbnail-1 { background-image:url('/assets/images/tiktok-placeholder-1.jpg'); transform:rotate(-20deg) translateX(-40px); z-index:1; animation:float 4s ease-in-out infinite; }
            .tiktok-thumbnail-2 { background-image:url('/assets/images/tiktok-placeholder-2.jpg'); z-index:2; animation:float2 4s ease-in-out 0.5s infinite; }
            .tiktok-thumbnail-3 { background-image:url('/assets/images/tiktok-placeholder-3.jpg'); transform:rotate(20deg) translateX(40px); z-index:3; animation:float3 4s ease-in-out 1s infinite; }
            .tiktok-view-all:hover .tiktok-thumbnail-1 { transform:rotate(-30deg) translateX(-60px) translateY(-10px); }
            .tiktok-view-all:hover .tiktok-thumbnail-2 { transform:translateY(-15px); }
            .tiktok-view-all:hover .tiktok-thumbnail-3 { transform:rotate(30deg) translateX(60px) translateY(-10px); }
            .view-all-text { margin-top:20px; position:relative; z-index:5; text-align:center; background-color:rgba(255,255,255,0.8); padding:10px; border-radius:8px; }
            .owl-nav button { position:absolute; top:50%; transform:translateY(-50%); background:rgba(255,255,255,0.7) !important; width:40px; height:40px; border-radius:50% !important; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
            .owl-prev { left:-20px; }
            .owl-next { right:-20px; }
            .owl-nav button span { font-size:24px; line-height:0; }
            .owl-dots { margin-top:15px !important; }
            @keyframes float { 0% { transform:translateY(0) rotate(-20deg) translateX(-40px); } 50% { transform:translateY(-10px) rotate(-20deg) translateX(-40px); } 100% { transform:translateY(0) rotate(-20deg) translateX(-40px); } }
            @keyframes float2 { 0% { transform:translateY(0); } 50% { transform:translateY(-10px); } 100% { transform:translateY(0); } }
            @keyframes float3 { 0% { transform:translateY(0) rotate(20deg) translateX(40px); } 50% { transform:translateY(-10px) rotate(20deg) translateX(40px); } 100% { transform:translateY(0) rotate(20deg) translateX(40px); } }
            .pulse { animation: pulse 1.5s infinite; }
            @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }
        `;
        document.head.appendChild(style);
    };

    // Visual effects
    const setupVisualEffects = () => {
        // Random shine effect for news images
        const addRandomShine = () => {
            const images = document.querySelectorAll('.news-image');
            if (!images.length) return;

            const img = images[Math.floor(Math.random() * images.length)];
            const shine = document.createElement('div');

            shine.style.cssText = 'position:absolute;top:0;left:-100%;width:50%;height:100%;background:linear-gradient(to right,rgba(255,255,255,0) 0%,rgba(255,255,255,0.3) 100%);transform:skewX(-25deg);transition:left 0.75s;';

            img.parentNode.style.position = 'relative';
            img.parentNode.style.overflow = 'hidden';
            img.parentNode.appendChild(shine);

            requestAnimationFrame(() => {
                shine.style.left = '125%';
                setTimeout(() => {
                    if (shine.parentNode) shine.parentNode.removeChild(shine);
                }, 750);
            });
        };

        // Webboard row highlight
        const randomHighlight = () => {
            const rows = document.querySelectorAll('.webboard-row');
            if (!rows.length) return;

            const selectedRow = rows[Math.floor(Math.random() * rows.length)];
            selectedRow.style.transition = 'background-color 0.5s ease';
            selectedRow.style.backgroundColor = 'rgba(255, 193, 7, 0.1)';

            setTimeout(() => {
                selectedRow.style.backgroundColor = '';
            }, 1500);
        };

        // Start intervals using requestIdleCallback for non-critical animations
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => {
                setInterval(() => {
                    const images = document.querySelectorAll('.news-image');
                    if (images.length > 0) {
                        const img = images[Math.floor(Math.random() * images.length)];
                        const shine = document.createElement('div');
                        shine.style.cssText = 'position:absolute;top:0;left:-100%;width:50%;height:100%;background:linear-gradient(to right,rgba(255,255,255,0) 0%,rgba(255,255,255,0.3) 100%);transform:skewX(-25deg);transition:left 0.75s;';
                        img.parentNode.style.position = 'relative';
                        img.parentNode.style.overflow = 'hidden';
                        img.parentNode.appendChild(shine);
                        requestAnimationFrame(() => {
                            shine.style.left = '125%';
                            setTimeout(() => shine.remove(), 750);
                        });
                    }
                }, 3000);
        
                setInterval(() => {
                    const rows = document.querySelectorAll('.webboard-row');
                    if (rows.length > 0) {
                        const selectedRow = rows[Math.floor(Math.random() * rows.length)];
                        selectedRow.style.transition = 'background-color 0.5s ease';
                        selectedRow.style.backgroundColor = 'rgba(255, 193, 7, 0.1)';
                        setTimeout(() => selectedRow.style.backgroundColor = '', 1500);
                    }
                }, 5000);
            });
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

        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false,
            disable: 'mobile'
        });
    };

    // Execute all initializations
    injectStyles();
    setupScrollAnimations();
    setupEventListeners();
    setupVisualEffects();
    initLibraries();
});
