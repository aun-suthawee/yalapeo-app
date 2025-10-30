<section id="statistics" class="dashboard-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="title text-center mb-4">
                    <span class="text-highlight">สารสนเทศทางการศึกษา</span>จังหวัดยะลา
                </h2>
                <p class="text-center text-muted mb-4">
                    ข้อมูลและสถิติที่สำคัญด้านการศึกษาของจังหวัดยะลา นำเสนอในรูปแบบ Dashboard ที่เข้าใจง่าย
                </p>
            </div>
        </div>

        <div class="row justify-content-center mb-4">
            <div class="col-md-8 text-center">
                <div class="dashboard-tabs">
                    <button type="button" class="dashboard-tab active" id="dashboard1-btn"
                        onclick="showDashboard('dashboard1')">
                        <i class="fas fa-chart-bar me-2"></i> สารสนเทศทางการศึกษา
                    </button>
                    <button type="button" class="dashboard-tab" id="dashboard2-btn"
                        onclick="showDashboard('dashboard2')">
                        <i class="fas fa-user-graduate me-2"></i> ข้อมูลติดตามเด็กนอกระบบ
                    </button>
                    <button type="button" class="dashboard-tab" id="dashboard3-btn"
                        onclick="showDashboard('dashboard3')">
                        <i class="fas fa-chart-line me-2"></i> รายงานผลการทดสอบทางการศึกษาระดับชาติ (NT/RT/ONET)
                    </button>
                </div>
            </div>
        </div>

        <div class="dashboard-panels">
            <div class="dashboard-panel active" id="dashboard1">
                <div class="dashboard-frame-wrapper">
                    <div class="dashboard-loading" id="loading-dashboard1">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">กำลังโหลด...</span>
                        </div>
                        <p class="mt-2">กำลังโหลดข้อมูล กรุณารอสักครู่...</p>
                    </div>
                    <div class="dashboard-frame">
                        {{-- 2567 --}}
                        {{-- <iframe
                            data-src="https://lookerstudio.google.com/embed/reporting/cce3a597-75c9-417f-885a-3c7dba974cbd/page/ZAkEF"
                            frameborder="0" allowfullscreen onload="hideLoading('loading-dashboard1')" loading="lazy"
                            sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"
                            style="width: 100%; height: 768px; aspect-ratio: 16/9;">
                        </iframe> --}}

                        {{-- 2568 --}}
                        <iframe
                            src="https://lookerstudio.google.com/embed/reporting/b8961b17-455d-4e22-8a28-c76338d3bdf0/page/UVgRF"
                            frameborder="0" allowfullscreen onload="hideLoading('loading-dashboard1')" loading="lazy"
                            sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"
                            style="width: 100%; height: 768px; aspect-ratio: 16/9;">
                        </iframe>
                    </div>
                </div>
            </div>

            <div class="dashboard-panel" id="dashboard2">
                <div class="dashboard-frame-wrapper">
                    <div class="dashboard-loading" id="loading-dashboard2">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">กำลังโหลด...</span>
                        </div>
                        <p class="mt-2">กำลังโหลดข้อมูล กรุณารอสักครู่...</p>
                    </div>
                    <div class="dashboard-frame">
                        <iframe title="เด็กนอกระบบ 26-3"
                            data-src="https://app.powerbi.com/view?r=eyJrIjoiY2U3YjI3NDYtZjI2NC00N2M4LWE2NDItMjY0NzU4YmJmNTUwIiwidCI6ImZhNGU3MjMyLWUyODgtNDhmMS05MzMyLWM3N2Q4ZmVhNzhhNyIsImMiOjEwfQ%3D%3D"
                            frameborder="0" onload="hideLoading('loading-dashboard2')" loading="lazy"
                            allowFullScreen="true" style="width: 100%; height: 768px; aspect-ratio: 16/9;">
                        </iframe>
                    </div>
                </div>
            </div>

            <div class="dashboard-panel" id="dashboard3">
                <div class="dashboard-frame-wrapper">
                    <div class="dashboard-loading" id="loading-dashboard3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">กำลังโหลด...</span>
                        </div>
                        <p class="mt-2">กำลังโหลดข้อมูล กรุณารอสักครู่...</p>
                    </div>
                    <div class="dashboard-frame">
                        <iframe
                            src="https://lookerstudio.google.com/embed/reporting/9b0cb1b5-e141-4e3a-8220-108e1a51d113/page/p_xqsvsprsud"
                            frameborder="0" allowfullscreen onload="hideLoading('loading-dashboard3')" loading="lazy"
                            sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"
                            style="width: 100%; height: 768px; aspect-ratio: 16/9;">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12 text-center">
                <div class="dashboard-info">
                    <div class="dashboard-info-item">
                        <i class="fas fa-info-circle text-primary"></i>
                        <span>หากไม่สามารถดูข้อมูลได้ กรุณาลองใช้เบราว์เซอร์อื่น หรือติดต่อเจ้าหน้าที่</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // ฟังก์ชันสำหรับซ่อนส่วน loading
    function hideLoading(loadingId) {
        const loadingElement = document.getElementById(loadingId);
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
    }

    // ฟังก์ชันสำหรับแสดงส่วน loading
    function showLoading(loadingId) {
        const loadingElement = document.getElementById(loadingId);
        if (loadingElement) {
            loadingElement.style.display = 'flex';
        }
    }

    function showDashboard(dashboardId) {
        // เปลี่ยนสถานะปุ่ม
        const dashboardButtons = document.querySelectorAll('.dashboard-tab');
        dashboardButtons.forEach(button => button.classList.remove('active'));
        const activeButton = document.getElementById(dashboardId + '-btn');
        if (activeButton) activeButton.classList.add('active');

        // จัดการกับ panel - ลบการใช้ transform และ opacity แบบ animation
        const dashboardPanels = document.querySelectorAll('.dashboard-panel');
        dashboardPanels.forEach(panel => {
            panel.classList.remove('active');
            // ลบการ reset animation styles
            panel.style.display = 'none';
        });

        // แสดง dashboard ที่เลือก
        const selectedDashboard = document.getElementById(dashboardId);
        if (selectedDashboard) {
            selectedDashboard.classList.add('active');
            selectedDashboard.style.display = 'block';

            try {
                const iframe = selectedDashboard.querySelector('iframe');
                const loadingId = 'loading-' + dashboardId;

                if (iframe && iframe.getAttribute('data-src') && !iframe.getAttribute('src')) {
                    showLoading(loadingId);
                    iframe.setAttribute('src', iframe.getAttribute('data-src'));
                }
            } catch (error) {
                console.error("Error loading iframe:", error);
            }
        }

        localStorage.setItem('selectedDashboard', dashboardId);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // กำหนดสไตล์เริ่มต้นสำหรับ loading indicator
        document.querySelectorAll('.dashboard-loading').forEach(loading => {
            loading.style.display = 'flex';
            loading.style.justifyContent = 'center';
            loading.style.alignItems = 'center';
            loading.style.flexDirection = 'column';
            loading.style.position = 'absolute';
            loading.style.top = '0';
            loading.style.left = '0';
            loading.style.width = '100%';
            loading.style.height = '100%';
            loading.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
            loading.style.zIndex = '10';
        });

        const savedDashboard = localStorage.getItem('selectedDashboard');
        if (savedDashboard) {
            showDashboard(savedDashboard);
        } else {
            showDashboard('dashboard1');
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const dashboardPanel = entry.target;
                    if (dashboardPanel.classList.contains('active')) {
                        const iframe = dashboardPanel.querySelector('iframe');
                        const panelId = dashboardPanel.id;
                        const loadingId = 'loading-' + panelId;

                        if (iframe && iframe.getAttribute('data-src') && !iframe.getAttribute(
                                'src')) {
                            showLoading(loadingId);
                            iframe.setAttribute('src', iframe.getAttribute('data-src'));
                        }
                    }
                    // เลิกติดตามหลังจากโหลดแล้ว
                    observer.unobserve(dashboardPanel);
                }
            });
        }, {
            threshold: 0.1
        });

        // เริ่มติดตามทุก dashboard panel
        document.querySelectorAll('.dashboard-panel').forEach(panel => {
            observer.observe(panel);
        });
    });
</script>
