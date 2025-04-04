// TikTok Videos Animation Functions
document.addEventListener('DOMContentLoaded', function() {
    // ปรับปรุงฟังก์ชัน expandTikTok
    window.expandTikTok = function(container) {
        container.classList.add('expanding');
        
        // เปลี่ยนวิธีจัดการกับ overlay - ไม่ซ่อนมันทั้งหมด แค่ทำให้โปร่งใสกว่าเดิม
        const overlay = container.querySelector('.tiktok-overlay');
        if (overlay) {
            // แทนที่จะซ่อนทั้งหมด ให้ลดความทึบลงแทน
            overlay.style.background = 'rgba(0,0,0,0)';
            // ปรับ z-index ให้ต่ำลงเพื่อให้สามารถคลิกผ่านไปที่ iframe ได้
            overlay.style.zIndex = '-1';
        }
        
        const infoSection = container.parentNode.querySelector('.tiktok-info');
        if (infoSection) {
            infoSection.style.maxHeight = '0';
            infoSection.style.opacity = '0';
            infoSection.style.overflow = 'hidden';
        }
        
        // Expand the container with animation
        container.style.paddingBottom = '177.8%'; // 16:9 ratio
        
        // เพิ่ม "collapsed" class เพื่อติดตามสถานะ
        container.setAttribute('data-expanded', 'true');

        // เพิ่มการติดตามคลิกภายนอกเพื่อย่อวิดีโอกลับ
        document.addEventListener('click', collapseOnClickOutside);

        // ซ่อนคำแนะนำหลังจากคลิก
        setTimeout(() => {
            const hintElement = container.querySelector('.tiktok-expand-hint');
            if (hintElement) {
                hintElement.style.opacity = '0';
            }
        }, 500);
    };

    // เพิ่มฟังก์ชันคลิกนอกวิดีโอเพื่อย่อกลับ
    function collapseOnClickOutside(event) {
        const expandedTikTok = document.querySelector('.tiktok-iframe-container.expanding');
        if (!expandedTikTok) {
            document.removeEventListener('click', collapseOnClickOutside);
            return;
        }

        // ตรวจสอบว่าคลิกภายนอก iframe หรือไม่
        if (!expandedTikTok.contains(event.target) && event.target !== expandedTikTok) {
            collapseTikTok(expandedTikTok);
            document.removeEventListener('click', collapseOnClickOutside);
        }
    }

    // ฟังก์ชันย่อ TikTok video กลับสู่สภาพเดิม
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
        
        // Collapse the container back
        container.style.paddingBottom = '100%';
        container.removeAttribute('data-expanded');
        
        // แสดงคำแนะนำอีกครั้ง
        setTimeout(() => {
            const hintElement = container.querySelector('.tiktok-expand-hint');
            if (hintElement) {
                hintElement.style.opacity = '1';
            }
        }, 300);
    }
    
    // Animate elements when they scroll into view
    const observeAnimation = () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    
                    // Animate each item with a staggered delay
                    const animatedElements = entry.target.querySelectorAll('.animate-on-scroll');
                    animatedElements.forEach((el, index) => {
                        setTimeout(() => {
                            el.style.opacity = '1';
                            el.style.transform = 'translateY(0)';
                        }, 100 * index);
                    });
                    
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });
        
        // Observe the TikTok section
        const tiktokSection = document.querySelector('.tiktok-videos');
        if (tiktokSection) {
            observer.observe(tiktokSection);
        }
    };
    
    // Run the animation observer
    observeAnimation();
    
    // Enhanced hover effects for TikTok containers
    const tiktokContainers = document.querySelectorAll('.tiktok-container');
    tiktokContainers.forEach(container => {
        container.addEventListener('mouseenter', function() {
            const iframe = this.querySelector('iframe');
            if (iframe) {
                iframe.style.transform = 'scale(1.02)';
                iframe.style.transition = 'transform 0.3s ease';
            }
        });
        
        container.addEventListener('mouseleave', function() {
            const iframe = this.querySelector('iframe');
            if (iframe) {
                iframe.style.transform = 'scale(1)';
            }
        });
    });

    const style = document.createElement('style');
    style.textContent = `
            .tiktok-container {
                border: 1px solid #f0f0f0;
                border-radius: 0;
                overflow: hidden;
                height: 100%;
            }
            .tiktok-info {
                border-top: 1px solid #f0f0f0;
                transition: opacity 0.3s ease, max-height 0.5s ease;
            }
            .tiktok-iframe-container {
                border-radius: 0;
                overflow: hidden;
                transform: translateZ(0);
            }
            .tiktok-iframe-container.expanding {
                z-index: 10;
            }
            .tiktok-iframe-container iframe {
                border-radius: 0;
            }
            .tiktok-overlay {
                background: rgba(0,0,0,0.01);
            }
            .tiktok-iframe-container:hover .tiktok-overlay {
                background: rgba(0,0,0,0.03);
            }
            
            .tiktok-slide-item {
                padding: 10px;
            }
            
            .tiktok-view-all {
                display: block;
                height: 100%;
                border: 0px solid #f0f0f0;
                text-decoration: none;
                color: #007bff;
                background-color: transparent;
                transition: all 0.3s ease;
                padding: 10px;
                height: 100%;
                position: relative;
                overflow: hidden;
            }
            .tiktok-view-all:hover {
                color: #0056b3;
                transform: translateY(-5px);
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
            .tiktok-view-all-content {
                position: relative;
                z-index: 2;
                height: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                background: transparent;
                min-height: 300px;
            }
            .tiktok-thumbnails {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 1;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .tiktok-thumbnail {
                position: absolute;
                width: 120px;
                height: 200px;
                background-size: cover;
                background-position: center;
                border-radius: 8px;
                box-shadow: 0 8px 20px rgba(0,0,0,0.25);
                transition: all 0.4s ease;
                overflow: hidden;
            }
            .tiktok-thumbnail-1 {
                background-image: url('/assets/images/tiktok-placeholder-1.jpg');
                transform: rotate(-20deg) translateX(-40px);
                z-index: 1;
            }
            .tiktok-thumbnail-2 {
                background-image: url('/assets/images/tiktok-placeholder-2.jpg');
                z-index: 2;
            }
            .tiktok-thumbnail-3 {
                background-image: url('/assets/images/tiktok-placeholder-3.jpg');
                transform: rotate(20deg) translateX(40px);
                z-index: 3;
            }
            .tiktok-view-all:hover .tiktok-thumbnail-1 {
                transform: rotate(-30deg) translateX(-60px) translateY(-10px);
            }
            .tiktok-view-all:hover .tiktok-thumbnail-2 {
                transform: translateY(-15px);
            }
            .tiktok-view-all:hover .tiktok-thumbnail-3 {
                transform: rotate(30deg) translateX(60px) translateY(-10px);
            }
            .view-all-text {
                margin-top: 20px;
                position: relative;
                z-index: 5;
                text-align: center;
                background-color: rgba(255, 255, 255, 0.8);
                padding: 10px;
                border-radius: 8px;
            }
            
            .owl-nav button {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                background: rgba(255, 255, 255, 0.7) !important;
                width: 40px;
                height: 40px;
                border-radius: 50% !important;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
            .owl-prev {
                left: -20px;
            }
            .owl-next {
                right: -20px;
            }
            .owl-nav button span {
                font-size: 24px;
                line-height: 0;
            }
            .owl-dots {
                margin-top: 15px !important;
            }
            
            /* เพิ่ม animation float สำหรับ thumbnails */
            @keyframes float {
                0% {
                    transform: translateY(0) rotate(-20deg) translateX(-40px);
                }
                50% {
                    transform: translateY(-10px) rotate(-20deg) translateX(-40px);
                }
                100% {
                    transform: translateY(0) rotate(-20deg) translateX(-40px);
                }
            }
            
            @keyframes float2 {
                0% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-10px);
                }
                100% {
                    transform: translateY(0);
                }
            }
            
            @keyframes float3 {
                0% {
                    transform: translateY(0) rotate(20deg) translateX(40px);
                }
                50% {
                    transform: translateY(-10px) rotate(20deg) translateX(40px);
                }
                100% {
                    transform: translateY(0) rotate(20deg) translateX(40px);
                }
            }
            
            .tiktok-thumbnail-1 {
                animation: float 4s ease-in-out infinite;
            }
            
            .tiktok-thumbnail-2 {
                animation: float2 4s ease-in-out 0.5s infinite;
            }
            
            .tiktok-thumbnail-3 {
                animation: float3 4s ease-in-out 1s infinite;
            }
        `;
    document.head.appendChild(style);

    $('.tiktok-slider').owlCarousel({
        loop: false,
        margin: 20,
        nav: true,
        dots: true,
        autoplay: false,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1
            },
            576: {
                items: 2
            },
            768: {
                items: 3
            },
            992: {
                items: 4
            },
            1200: {
                items: 4
            }
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

    const assessmentSection = document.getElementById('assessment');
    
    if (assessmentSection) {
        // ตรวจสอบการเลื่อนหน้าจอถึงส่วน assessment
        const assessmentObserver = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                // เพิ่ม class เมื่อ scroll ถึง
                assessmentSection.classList.add('animate-section');
                
                // เรียกใช้งานแอนิเมชันที่ซับซ้อนมากขึ้น (ถ้าต้องการ)
                animateAssessmentItems();
                
                // เลิกติดตามเมื่อเกิดแอนิเมชันแล้ว
                assessmentObserver.unobserve(assessmentSection);
            }
        }, { threshold: 0.2 });
        
        assessmentObserver.observe(assessmentSection);
    }
    
    // ฟังก์ชันสำหรับแอนิเมชันที่ซับซ้อนมากขึ้น
    function animateAssessmentItems() {
        const assessmentCards = document.querySelectorAll('.assessment-card');
        
        assessmentCards.forEach((card, index) => {
            // เรียงลำดับการเข้ามาของแต่ละการ์ด
            setTimeout(() => {
                card.classList.add('animated');
            }, 150 * index);
        });
    }
    
    // เพิ่มปฏิสัมพันธ์เมื่อ hover
    const assessmentLinks = document.querySelectorAll('.assessment-link');
    
    assessmentLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            // คุณสามารถเพิ่มโค้ดเพื่อสร้างเอฟเฟกต์พิเศษเมื่อ hover ได้ที่นี่
        });
    });
});

// Videos Animation
document.addEventListener('DOMContentLoaded', function() {
    // Highlight animation when scrolling to section
    const videoSection = document.querySelector('.videos-section');
    if (videoSection) {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                const highlights = videoSection.querySelectorAll('.text-highlight');
                highlights.forEach(highlight => {
                    highlight.classList.add('animated');
                });
                
                observer.unobserve(videoSection);
            }
        }, { threshold: 0.3 });
        
        observer.observe(videoSection);
    }
    
    // Fix for iframe interactions
    const videoCards = document.querySelectorAll('.video-card');
    videoCards.forEach(card => {
        // Find all iframes
        const iframes = card.querySelectorAll('iframe');
        
        iframes.forEach(iframe => {
            // Add pointer-events:none to iframe temporarily when hovering on card
            card.addEventListener('mouseenter', () => {
                iframe.style.pointerEvents = 'none';
            });
            
            // Remove pointer-events:none when clicking on overlay to allow interaction
            card.addEventListener('click', (e) => {
                if (e.target.closest('.video-overlay')) {
                    e.preventDefault();
                    iframe.style.pointerEvents = 'auto';
                    // เลือกกำหนดเป้าหมายอื่นๆ ตามที่ต้องการ เช่น คลิกปุ่มเล่น
                    // iframe.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
                }
            });
            
            // Add back pointer-events to iframe when leaving card
            card.addEventListener('mouseleave', () => {
                iframe.style.pointerEvents = 'auto';
            });
        });
    });
});

// Purchase News Animation Functions
document.addEventListener('DOMContentLoaded', function() {
    // ตรวจสอบเมื่อส่วน purchase-news เข้ามาในบริเวณที่มองเห็น
    const purchaseNewsSection = document.querySelector('.purchase-news-section');
    
    if (purchaseNewsSection) {
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                purchaseNewsSection.classList.add('animate-start');
                
                // Staggered animation for news items
                const newsItems = purchaseNewsSection.querySelectorAll('.news-item');
                newsItems.forEach((item, index) => {
                    setTimeout(() => {
                        item.classList.add('animate');
                    }, 100 * index);
                });
                
                // เลิกติดตามหลังจากที่ได้ทำงานแล้ว
                observer.unobserve(purchaseNewsSection);
            }
        }, { threshold: 0.2 });
        
        observer.observe(purchaseNewsSection);
    }
    
    // เพิ่มเอฟเฟกต์เมื่อชี้ที่ปุ่ม "ดูทั้งหมด"
    const viewAllButtons = document.querySelectorAll('.view-all-btn');
    viewAllButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // เพิ่มเอฟเฟกต์เมื่อชี้ที่หัวข้อ
    const titles = document.querySelectorAll('.purchase-news-section .title');
    titles.forEach(title => {
        title.addEventListener('mouseenter', function() {
            const highlightText = this.querySelector('.text-highlight');
            if (highlightText) {
                highlightText.style.transition = 'color 0.3s ease';
                highlightText.style.color = '#fd7e14';
            }
        });
        
        title.addEventListener('mouseleave', function() {
            const highlightText = this.querySelector('.text-highlight');
            if (highlightText) {
                highlightText.style.color = '#dc3545';
            }
        });
    });
});

// Latest News Animation Functions
document.addEventListener('DOMContentLoaded', function() {
    // ตรวจสอบเมื่อเลื่อนหน้าจอมาถึงส่วนข่าวล่าสุด
    const latestNewsSection = document.querySelector('.lasted-news');
    
    if (latestNewsSection) {
        latestNewsSection.classList.add('animate-start');
        
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                latestNewsSection.classList.add('animate-active');
                
                // Staggered animation for news cards
                const newsCards = latestNewsSection.querySelectorAll('.news-card');
                newsCards.forEach((card, index) => {
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100 * index);
                });
                
                // ทำให้เส้นใต้ text-highlight ปรากฏ
                setTimeout(() => {
                    const highlight = latestNewsSection.querySelector('.text-highlight');
                    if (highlight) {
                        highlight.style.position = 'relative';
                        const underline = document.createElement('span');
                        underline.style.position = 'absolute';
                        underline.style.bottom = '-5px';
                        underline.style.left = '0';
                        underline.style.width = '0';
                        underline.style.height = '2px';
                        underline.style.background = 'linear-gradient(90deg, #dc3545, #fd7e14)';
                        underline.style.transition = 'width 0.8s ease';
                        highlight.appendChild(underline);
                        
                        setTimeout(() => {
                            underline.style.width = '100%';
                        }, 300);
                    }
                }, 400);
                
                // เลิกติดตามหลังจากทำงานแล้ว
                observer.unobserve(latestNewsSection);
            }
        }, { threshold: 0.2 });
        
        observer.observe(latestNewsSection);
    }
    
    // Image hover effects
    const newsImages = document.querySelectorAll('.news-image');
    newsImages.forEach(img => {
        img.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        
        img.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
    
    // Add hover effect to "View All" button
    const viewAllBtn = document.querySelector('.view-all-btn');
    if (viewAllBtn) {
        viewAllBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 8px 20px rgba(220, 53, 69, 0.3)';
        });
        
        viewAllBtn.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    }
    
    // Add subtle animation to card titles
    const cardTitles = document.querySelectorAll('.card-title a');
    cardTitles.forEach(title => {
        title.addEventListener('mouseenter', function() {
            this.style.color = '#dc3545';
            this.style.paddingLeft = '3px';
            this.style.transition = 'all 0.3s ease';
        });
        
        title.addEventListener('mouseleave', function() {
            this.style.color = '';
            this.style.paddingLeft = '';
        });
    });
    
    // Add random shine effect to news images
    function addRandomShine() {
        const images = document.querySelectorAll('.news-image');
        const randomIndex = Math.floor(Math.random() * images.length);
        
        if (images[randomIndex]) {
            const img = images[randomIndex];
            
            // Create shine effect
            const shine = document.createElement('div');
            shine.style.position = 'absolute';
            shine.style.top = '0';
            shine.style.left = '-100%';
            shine.style.width = '50%';
            shine.style.height = '100%';
            shine.style.background = 'linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 100%)';
            shine.style.transform = 'skewX(-25deg)';
            shine.style.transition = 'left 0.75s';
            
            img.parentNode.style.position = 'relative';
            img.parentNode.style.overflow = 'hidden';
            img.parentNode.appendChild(shine);
            
            setTimeout(() => {
                shine.style.left = '125%';
                
                setTimeout(() => {
                    if (shine.parentNode) {
                        shine.parentNode.removeChild(shine);
                    }
                }, 750);
            }, 100);
        }
    }
    
    // Randomly add shine effect every few seconds
    setInterval(addRandomShine, 3000);
});

// Webboard Animation Functions
document.addEventListener('DOMContentLoaded', function() {
    // ตรวจสอบเมื่อเลื่อนหน้าจอมาถึงส่วนกระดานสนทนา
    const webboardSection = document.querySelector('.webboard-section');
    
    if (webboardSection) {
        webboardSection.classList.add('animate-start');
        
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                webboardSection.classList.add('animate-active');
                
                // สร้าง animation สำหรับแถวในตาราง
                const rows = webboardSection.querySelectorAll('.webboard-row');
                rows.forEach((row, index) => {
                    setTimeout(() => {
                        row.style.opacity = '1';
                        row.style.transform = 'translateX(0)';
                    }, 100 * index);
                });
                
                // สร้าง animation สำหรับหัวข้อ
                const title = webboardSection.querySelector('.webboard-title');
                if (title) {
                    setTimeout(() => {
                        title.style.color = '#28a745';
                        
                        setTimeout(() => {
                            title.style.color = '';
                        }, 1000);
                    }, 500);
                }
                
                // Pulse animation สำหรับปุ่มตั้งกระทู้ใหม่
                const createBtn = webboardSection.querySelector('.create-thread-btn');
                if (createBtn) {
                    setTimeout(() => {
                        createBtn.classList.add('pulse');
                        
                        // หยุด pulse หลังจาก 5 วินาที
                        setTimeout(() => {
                            createBtn.classList.remove('pulse');
                        }, 5000);
                    }, 1000);
                }
                
                // เลิกติดตามหลังจากทำงานแล้ว
                observer.unobserve(webboardSection);
            }
        }, { threshold: 0.2 });
        
        observer.observe(webboardSection);
    }
    
    // Add hover effects for table rows
    const webboardRows = document.querySelectorAll('.webboard-row');
    webboardRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            const link = this.querySelector('.webboard-link');
            if (link) {
                link.style.transform = 'translateX(5px)';
                link.style.color = '#28a745';
            }
            
            this.style.backgroundColor = 'rgba(255, 193, 7, 0.05)';
        });
        
        row.addEventListener('mouseleave', function() {
            const link = this.querySelector('.webboard-link');
            if (link) {
                link.style.transform = '';
                link.style.color = '';
            }
            
            this.style.backgroundColor = '';
        });
    });
    
    // Add hover effects for buttons
    const createThreadBtn = document.querySelector('.create-thread-btn');
    if (createThreadBtn) {
        createThreadBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 5px 15px rgba(255, 193, 7, 0.3)';
            
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = 'rotate(90deg)';
            }
        });
        
        createThreadBtn.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
            
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = '';
            }
        });
    }
    
    const viewAllBtn = document.querySelector('.view-all-btn');
    if (viewAllBtn) {
        viewAllBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 5px 15px rgba(40, 167, 69, 0.3)';
            
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = 'translateX(3px)';
            }
        });
        
        viewAllBtn.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
            
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = '';
            }
        });
    }
    
    // Add hover effect to the card
    const webboardCard = document.querySelector('.webboard-card');
    if (webboardCard) {
        webboardCard.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 12px 30px rgba(0, 0, 0, 0.1)';
            
            const titleElement = this.querySelector('.webboard-title');
            if (titleElement) {
                const titleUnderline = document.createElement('span');
                titleUnderline.style.position = 'absolute';
                titleUnderline.style.bottom = '0';
                titleUnderline.style.left = '0';
                titleUnderline.style.width = '0';
                titleUnderline.style.height = '2px';
                titleUnderline.style.background = 'linear-gradient(90deg, #ffc107, #28a745)';
                titleUnderline.style.transition = 'width 0.5s ease';
                titleUnderline.className = 'title-underline';
                
                // ถ้ายังไม่มี underline ให้เพิ่มเข้าไป
                if (!titleElement.querySelector('.title-underline')) {
                    titleElement.appendChild(titleUnderline);
                    
                    setTimeout(() => {
                        titleUnderline.style.width = '100%';
                    }, 10);
                }
            }
        });
        
        webboardCard.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
            
            const titleUnderline = this.querySelector('.title-underline');
            if (titleUnderline) {
                titleUnderline.style.width = '0';
                
                // ลบ underline เมื่อ animation เสร็จสิ้น
                setTimeout(() => {
                    if (titleUnderline.parentNode) {
                        titleUnderline.parentNode.removeChild(titleUnderline);
                    }
                }, 500);
            }
        });
    }
    
    // Random highlight for table rows
    function randomHighlight() {
        const rows = document.querySelectorAll('.webboard-row');
        if (rows.length === 0) return;
        
        // สุ่มแถวหนึ่งแถว
        const randomIndex = Math.floor(Math.random() * rows.length);
        const selectedRow = rows[randomIndex];
        
        // เพิ่ม highlight effect
        selectedRow.style.transition = 'background-color 0.5s ease';
        selectedRow.style.backgroundColor = 'rgba(255, 193, 7, 0.1)';
        
        // กลับไปเป็นปกติหลังจาก 1.5 วินาที
        setTimeout(() => {
            selectedRow.style.backgroundColor = '';
        }, 1500);
    }
    
    // ทำ highlight ทุก 5 วินาที ถ้ามีแถวในตาราง
    if (document.querySelectorAll('.webboard-row').length > 0) {
        setInterval(randomHighlight, 5000);
    }
});
