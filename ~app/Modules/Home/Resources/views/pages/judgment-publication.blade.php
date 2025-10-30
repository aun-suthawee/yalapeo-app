@extends('home::layouts.master')

@section('stylesheet-content')
    <style>
        :root {
            --primary-yellow: #FFD700;
            --secondary-yellow: #FFA500;
            --accent-yellow: #FFEB3B;
            --light-yellow: #FFF9C4;
            --dark-yellow: #F57F17;
            --gradient-yellow: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            --gradient-light: linear-gradient(135deg, #FFF9C4 0%, #FFEB3B 100%);
            --shadow-soft: 0 10px 30px rgba(255, 193, 7, 0.15);
            --shadow-hover: 0 15px 40px rgba(255, 193, 7, 0.25);
            --border-radius: 15px;
            --text-dark: #333333;
            --text-light: #666666;
        }

        body {
            background: linear-gradient(to bottom, #FFFDE7, #FFF8E1);
            font-family: 'Kanit', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .judgment-hero {
            background: var(--gradient-yellow);
            min-height: 50vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .judgment-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="%23ffffff15" points="0,0 1000,200 1000,1000 0,800"/><polygon fill="%23ffffff10" points="0,150 1000,0 1000,850 0,1000"/></svg>');
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
        }

        .hero-icon {
            font-size: 4rem;
            color: white;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .main-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-soft);
            margin: -3rem 0 3rem 0;
            position: relative;
            z-index: 3;
            overflow: hidden;
        }

        .tabs-container {
            background: var(--gradient-light);
            padding: 2rem 0;
            border-bottom: 1px solid rgba(255, 193, 7, 0.2);
        }

        .month-tabs {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            padding: 0 2rem;
        }

        .month-tab {
            background: white;
            border: 2px solid var(--primary-yellow);
            color: var(--dark-yellow);
            padding: 1rem 2rem;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
        }

        .month-tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient-yellow);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .month-tab:hover::before {
            left: 0;
        }

        .month-tab:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .month-tab.active {
            background: var(--gradient-yellow);
            color: white;
            border-color: var(--secondary-yellow);
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .month-tab.disabled {
            background: #f5f5f5;
            border-color: #ddd;
            color: #999;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .month-tab.disabled:hover {
            transform: none;
            box-shadow: none;
        }

        .month-tab.disabled::before {
            display: none;
        }

        .content-area {
            padding: 3rem 2rem;
        }

        .month-content {
            display: none;
            animation: fadeInUp 0.5s ease;
        }

        .month-content.active {
            display: block;
        }

        .content-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .content-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-yellow);
            margin-bottom: 1rem;
        }

        .content-subtitle {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 2rem;
        }

        .publications-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .publication-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
            overflow: hidden;
            cursor: pointer;
            position: relative;
        }

        .publication-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-hover);
        }

        .publication-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-yellow);
        }

        .publication-image {
            width: 100%;
            height: auto;
            min-height: 500px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .publication-card:hover .publication-image {
            transform: scale(1.05);
        }

        .publication-info {
            padding: 1.5rem;
        }

        .publication-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .publication-date {
            font-size: 0.9rem;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .download-btn {
            background: var(--gradient-yellow);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
            width: 100%;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
        }

        .stats-card {
            background: var(--gradient-light);
            border-radius: var(--border-radius);
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }

        .stats-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--dark-yellow);
            margin-bottom: 0.5rem;
        }

        .stats-label {
            font-size: 1.1rem;
            color: var(--text-light);
        }

        .no-publications {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-light);
        }

        .no-publications i {
            font-size: 4rem;
            color: var(--primary-yellow);
            margin-bottom: 1rem;
        }

        /* Modal Styles */
        .image-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            cursor: pointer;
        }

        .modal-image {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 95%;
            max-height: 95%;
            object-fit: contain;
            border-radius: 10px;
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 3rem;
            cursor: pointer;
            z-index: 10001;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-icon {
                font-size: 3rem;
            }

            .month-tabs {
                padding: 0 1rem;
            }

            .month-tab {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
            }

            .content-area {
                padding: 2rem 1rem;
            }

            .content-title {
                font-size: 2rem;
            }

            .publications-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .publication-image {
                height: 300px;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }

            .month-tabs {
                flex-direction: column;
                align-items: center;
            }

            .month-tab {
                width: 80%;
                text-align: center;
            }

            .stats-number {
                font-size: 2.5rem;
            }
        }
    </style>
@endsection

@section('app-content')
    <div class="judgment-publication-container">
        <!-- Hero Section -->
        <section class="judgment-hero">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-icon">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <h1 class="hero-title">บทความกฎหมาย</h1>
                    {{-- <p class="hero-subtitle">รวบรวมคำพิพากษาและคำวินิจฉัยของศาลยุติธรรม เพื่อเผยแพร่ให้ประชาชนได้ศึกษา</p> --}}
                </div>
            </div>
        </section>

        <div class="container">
            <!-- Main Container -->
            <div class="main-container">
                <!-- Tabs Section -->
                <div class="tabs-container">
                    <div class="month-tabs">
                        <div class="month-tab" data-month="may">
                            <i class="fas fa-calendar-alt me-2"></i>พฤษภาคม 2568
                        </div>
                        <div class="month-tab" data-month="june">
                            <i class="fas fa-calendar-alt me-2"></i>มิถุนายน 2568
                        </div>
                        <div class="month-tab" data-month="july">
                            <i class="fas fa-calendar-alt me-2"></i>กรกฎาคม 2568
                        </div>
                        <div class="month-tab" data-month="august">
                            <i class="fas fa-calendar-alt me-2"></i>สิงหาคม 2568
                        </div>
                        <div class="month-tab active" data-month="september">
                            <i class="fas fa-calendar-alt me-2"></i>กันยายน 2568
                        </div>
                        <div class="month-tab disabled" data-month="october">
                            <i class="fas fa-lock me-2"></i>ตุลาคม 2568
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="content-area">
                    <!-- May Content -->
                    <div class="month-content" id="may-content">
                        <div class="content-header">
                            <h2 class="content-title">พฤษภาคม 2568</h2>
                            <p class="content-subtitle">บทความกฎหมายประจำเดือนพฤษภาคม</p>
                        </div>

                        <div class="stats-card">
                            <div class="stats-number">1</div>
                            <div class="stats-label">บทความกฎหมาย</div>
                        </div>

                        <div class="publications-grid">
                            <div class="publication-card" onclick="openImageModal('{{ asset('assets/images/judgment-publication/1.webp') }}')">
                                <img src="{{ asset('assets/images/judgment-publication/1.webp') }}" alt="บทความกฎหมาย พฤษภาคม 2568" class="publication-image">
                                <div class="publication-info">
                                    <h3 class="publication-title">บทความกฎหมาย ฉบับที่ 1</h3>
                                    <div class="publication-date">
                                        <i class="fas fa-calendar"></i>
                                        พฤษภาคม 2568
                                    </div>
                                    <button class="download-btn" onclick="downloadImage('{{ asset('assets/images/judgment-publication/1.webp') }}', 'legal-article-may-2568.webp', event)">
                                        <i class="fas fa-download me-2"></i>ดาวน์โหลด
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- June Content -->
                    <div class="month-content" id="june-content">
                        <div class="content-header">
                            <h2 class="content-title">มิถุนายน 2568</h2>
                            <p class="content-subtitle">บทความกฎหมายประจำเดือนมิถุนายน</p>
                        </div>

                        <div class="stats-card">
                            <div class="stats-number">1</div>
                            <div class="stats-label">บทความกฎหมาย</div>
                        </div>

                        <div class="publications-grid">
                            <div class="publication-card" onclick="openImageModal('{{ asset('assets/images/judgment-publication/2.webp') }}')">
                                <img src="{{ asset('assets/images/judgment-publication/2.webp') }}" alt="บทความกฎหมาย มิถุนายน 2568" class="publication-image">
                                <div class="publication-info">
                                    <h3 class="publication-title">บทความกฎหมาย ฉบับที่ 2</h3>
                                    <div class="publication-date">
                                        <i class="fas fa-calendar"></i>
                                        มิถุนายน 2568
                                    </div>
                                    <button class="download-btn" onclick="downloadImage('{{ asset('assets/images/judgment-publication/2.webp') }}', 'legal-article-june-2568.webp', event)">
                                        <i class="fas fa-download me-2"></i>ดาวน์โหลด
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- July Content -->
                    <div class="month-content" id="july-content">
                        <div class="content-header">
                            <h2 class="content-title">กรกฎาคม 2568</h2>
                            <p class="content-subtitle">บทความกฎหมายประจำเดือนกรกฎาคม</p>
                        </div>

                        <div class="stats-card">
                            <div class="stats-number">1</div>
                            <div class="stats-label">บทความกฎหมาย</div>
                        </div>

                        <div class="publications-grid">
                            <div class="publication-card" onclick="openImageModal('{{ asset('assets/images/judgment-publication/3.webp') }}')">
                                <img src="{{ asset('assets/images/judgment-publication/3.webp') }}" alt="บทความกฎหมาย กรกฎาคม 2568 ฉบับที่ 1" class="publication-image">
                                <div class="publication-info">
                                    <h3 class="publication-title">บทความกฎหมาย ฉบับที่ 3</h3>
                                    <div class="publication-date">
                                        <i class="fas fa-calendar"></i>
                                        กรกฎาคม 2568
                                    </div>
                                    <button class="download-btn" onclick="downloadImage('{{ asset('assets/images/judgment-publication/3.webp') }}', 'legal-article-july-2568-1.webp', event)">
                                        <i class="fas fa-download me-2"></i>ดาวน์โหลด
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- August Content -->
                    <div class="month-content" id="august-content">
                        <div class="content-header">
                            <h2 class="content-title">สิงหาคม 2568</h2>
                            <p class="content-subtitle">บทความกฎหมายประจำเดือนสิงหาคม</p>
                        </div>

                        <div class="stats-card">
                            <div class="stats-number">1</div>
                            <div class="stats-label">บทความกฎหมาย</div>
                        </div>

                        <div class="publications-grid">
                            <div class="publication-card" onclick="openImageModal('{{ asset('assets/images/judgment-publication/4.webp') }}')">
                                <img src="{{ asset('assets/images/judgment-publication/4.webp') }}" alt="บทความกฎหมาย สิงหาคม 2568" class="publication-image">
                                <div class="publication-info">
                                    <h3 class="publication-title">บทความกฎหมาย ฉบับที่ 4</h3>
                                    <div class="publication-date">
                                        <i class="fas fa-calendar"></i>
                                        สิงหาคม 2568
                                    </div>
                                    <button class="download-btn" onclick="downloadImage('{{ asset('assets/images/judgment-publication/4.webp') }}', 'legal-article-august-2568.webp', event)">
                                        <i class="fas fa-download me-2"></i>ดาวน์โหลด
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <!-- September Content -->
                    <div class="month-content active" id="september-content">
                        <div class="content-header">
                            <h2 class="content-title">กันยายน 2568</h2>
                            <p class="content-subtitle">บทความกฎหมายประจำเดือนกันยายน</p>
                        </div>

                        <div class="stats-card">
                            <div class="stats-number">1</div>
                            <div class="stats-label">บทความกฎหมาย</div>
                        </div>

                        <div class="publications-grid">
                            <div class="publication-card" onclick="openImageModal('{{ asset('assets/images/judgment-publication/5.webp') }}')">
                                <img src="{{ asset('assets/images/judgment-publication/5.webp') }}" alt="บทความกฎหมาย กันยายน 2568" class="publication-image">
                                <div class="publication-info">
                                    <h3 class="publication-title">บทความกฎหมาย ฉบับที่ 5</h3>
                                    <div class="publication-date">
                                        <i class="fas fa-calendar"></i>
                                        กันยายน 2568
                                    </div>
                                    <button class="download-btn" onclick="downloadImage('{{ asset('assets/images/judgment-publication/5.webp') }}', 'legal-article-september-2568.webp', event)">
                                        <i class="fas fa-download me-2"></i>ดาวน์โหลด
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <!-- October Content (Disabled) -->
                    <div class="month-content" id="october-content">
                        <div class="content-header">
                            <h2 class="content-title">ตุลาคม 2568</h2>
                            <p class="content-subtitle">บทความกฎหมายประจำเดือนตุลาคม</p>
                        </div>

                        <div class="stats-card">
                            <div class="stats-number">1</div>
                            <div class="stats-label">บทความกฎหมาย</div>
                        </div>

                        <div class="publications-grid">
                            <div class="publication-card" onclick="openImageModal('{{ asset('assets/images/judgment-publication/6.webp') }}')">
                                <img src="{{ asset('assets/images/judgment-publication/6.webp') }}" alt="บทความกฎหมาย ตุลาคม 2568" class="publication-image">
                                <div class="publication-info">
                                    <h3 class="publication-title">บทความกฎหมาย ฉบับที่ 6</h3>
                                    <div class="publication-date">
                                        <i class="fas fa-calendar"></i>
                                        ตุลาคม 2568
                                    </div>
                                    <button class="download-btn" onclick="downloadImage('{{ asset('assets/images/judgment-publication/6.webp') }}', 'legal-article-october-2568.webp', event)">
                                        <i class="fas fa-download me-2"></i>ดาวน์โหลด
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="image-modal" id="imageModal" onclick="closeImageModal()">
        <span class="modal-close">&times;</span>
        <img class="modal-image" id="modalImage" src="" alt="">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching functionality
            const monthTabs = document.querySelectorAll('.month-tab');
            const monthContents = document.querySelectorAll('.month-content');

            monthTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Skip if disabled
                    if (this.classList.contains('disabled')) {
                        return;
                    }

                    // Remove active class from all tabs and contents
                    monthTabs.forEach(t => t.classList.remove('active'));
                    monthContents.forEach(c => c.classList.remove('active'));

                    // Add active class to clicked tab
                    this.classList.add('active');

                    // Show corresponding content
                    const monthId = this.dataset.month + '-content';
                    document.getElementById(monthId).classList.add('active');

                    // Smooth scroll to content
                    document.querySelector('.content-area').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                });
            });

            // Prevent click on disabled tabs
            document.querySelectorAll('.month-tab.disabled').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Show tooltip or alert
                    const tooltip = document.createElement('div');
                    tooltip.textContent = 'เนื้อหายังไม่เปิดให้ดู';
                    tooltip.style.cssText = `
                        position: absolute;
                        top: -40px;
                        left: 50%;
                        transform: translateX(-50%);
                        background: #333;
                        color: white;
                        padding: 8px 12px;
                        border-radius: 5px;
                        font-size: 12px;
                        white-space: nowrap;
                        z-index: 1000;
                    `;
                    
                    this.style.position = 'relative';
                    this.appendChild(tooltip);
                    
                    setTimeout(() => {
                        tooltip.remove();
                    }, 2000);
                });
            });
        });

        // Image modal functions
        function openImageModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            
            modalImage.src = imageSrc;
            modal.style.display = 'block';
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
            
            // Restore body scroll
            document.body.style.overflow = 'auto';
        }

        // Download function - Works better in production
        function downloadImage(imageSrc, fileName, event) {
            // Prevent event bubbling to parent elements
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            // console.log('Attempting to download:', imageSrc, 'as', fileName);
            
            // Method 1: Try fetch and blob (works better with CORS)
            fetch(imageSrc)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.blob();
                })
                .then(blob => {
                    // Create blob URL
                    const blobUrl = window.URL.createObjectURL(blob);
                    
                    // Create download link
                    const link = document.createElement('a');
                    link.href = blobUrl;
                    link.download = fileName;
                    link.style.display = 'none';
                    
                    // Add to document, click, and remove
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    // Clean up blob URL
                    window.URL.revokeObjectURL(blobUrl);
                    
                    // console.log('Download successful via fetch method');
                })
                .catch(error => {
                    // console.warn('Fetch method failed:', error);
                    
                    // Method 2: Fallback to direct link method
                    try {
                        const link = document.createElement('a');
                        link.href = imageSrc;
                        link.download = fileName;
                        link.target = '_blank';
                        link.rel = 'noopener noreferrer';
                        link.style.display = 'none';
                        
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        
                        // console.log('Download attempted via direct link method');
                    } catch (linkError) {
                        // console.warn('Direct link method failed:', linkError);
                        
                        // Method 3: Final fallback - open in new window
                        const newWindow = window.open(imageSrc, '_blank', 'noopener,noreferrer');
                        if (!newWindow) {
                            // Method 4: Show instructions
                            alert('การดาวน์โหลดอัตโนมัติไม่สำเร็จ\nกรุณาคลิกขวาที่รูปภาพแล้วเลือก "บันทึกรูปภาพ" หรือ "Save image as"');
                        } else {
                            // console.log('Opened in new window for manual download');
                        }
                    }
                });
        }

        // Keyboard support for modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
@endsection

