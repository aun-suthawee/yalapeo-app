<!-- Education Sandbox Section -->
<section id="education-sandbox" class="py-5"
    style="background: linear-gradient(135deg, #ff6b35 0%, #f9ca24 100%); position: relative; overflow: hidden;">

    <div class="container
        position-relative">
        <div class="row align-items-center">
            <!-- Content Column -->
            <div class="col-lg-8">
                <div class="text-white">
                    <!-- Badge -->
                    <div class="mb-3">
                        <span class="badge bg-white text-dark px-3 py-2 rounded-pill shadow-sm"
                            style="font-size: 0.9rem; font-weight: 600;">
                            <i class="fas fa-graduation-cap me-2"></i>Education Sandbox
                        </span>
                    </div>

                    <!-- Title -->
                    <h2 class="display-5 fw-bold mb-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                        โรงเรียนพื้นที่นวัตกรรม 2568
                    </h2>

                    <!-- Subtitle -->
                    <p class="lead mb-4" style="color: rgba(255,255,255,0.95); font-size: 1.3rem;">
                        Education Sandbox จังหวัดยะลา
                    </p>

                    <!-- Description -->
                    <p class="mb-4" style="color: rgba(255,255,255,0.9); font-size: 1.1rem; line-height: 1.6;">
                        สำรวจข้อมูล Infographic ของ 54 โรงเรียนในจังหวัดยะลา พร้อมระบบค้นหาและกรองข้อมูลแบบ Interactive
                    </p>

                    <!-- Features List -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-school text-white me-2" style="font-size: 1.1rem;"></i>
                                <span style="color: rgba(255,255,255,0.95);">54 โรงเรียน 6 สังกัด</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-chart-bar text-white me-2" style="font-size: 1.1rem;"></i>
                                <span style="color: rgba(255,255,255,0.95);">Infographic รายโรงเรียน</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-search text-white me-2" style="font-size: 1.1rem;"></i>
                                <span style="color: rgba(255,255,255,0.95);">ระบบค้นหาแบบ Real-time</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-filter text-white me-2" style="font-size: 1.1rem;"></i>
                                <span style="color: rgba(255,255,255,0.95);">กรองข้อมูลตามสังกัด</span>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('home.school-infographic-sandbox') }}"
                            class="btn btn-light btn-lg px-4 py-3 shadow-lg"
                            style="border-radius: 50px; font-weight: 600; transition: all 0.3s ease; border: none;">
                            <i class="fas fa-rocket me-2"></i>เข้าเยี่ยมชม Infographics
                        </a>
                    </div>
                </div>
            </div>

            <!-- Image/Icon Column -->
            <div class="col-lg-4 text-center">
                <div class="position-relative">
                    <!-- Main Icon/Illustration -->
                    <div class="bg-white rounded-circle shadow-lg mx-auto mb-4"
                        style="width: 200px; height: 200px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-chalkboard-teacher" style="font-size: 4rem; color: #ff6b35;"></i>
                    </div>

                    <!-- Floating Elements -->
                    <div class="position-absolute"
                        style="top: 20px; right: 20px; animation: float 3s ease-in-out infinite;">
                        <div class="bg-white rounded-circle shadow p-3">
                            <i class="fas fa-chart-line" style="color: #f9ca24; font-size: 1.5rem;"></i>
                        </div>
                    </div>

                    <div class="position-absolute"
                        style="bottom: 20px; left: 20px; animation: float 3s ease-in-out infinite 1.5s;">
                        <div class="bg-white rounded-circle shadow p-3">
                            <i class="fas fa-graduation-cap" style="color: #27ae60; font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Additional Info Section (Hidden by default, shown when "เรียนรู้เพิ่มเติม" is clicked) -->
<section id="education-sandbox-info" class="py-5 bg-light" style="display: none;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h3 class="mb-4">เกี่ยวกับ Education Sandbox</h3>
                <p class="lead text-muted mb-4">
                    แพลตฟอร์มนี้ได้รับการพัฒนาขึ้นเพื่อนำเสนอข้อมูลและผลงานของโรงเรียนในพื้นที่นวัตกรรม จังหวัดยะลา
                    ในรูปแบบที่เข้าใจง่ายและน่าสนใจ
                </p>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="text-center">
                            <i class="fas fa-search text-primary mb-3" style="font-size: 2.5rem;"></i>
                            <h5>ค้นหาง่าย</h5>
                            <p class="text-muted">ค้นหาโรงเรียนได้ทันทีด้วยระบบ Real-time Search</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="text-center">
                            <i class="fas fa-filter text-success mb-3" style="font-size: 2.5rem;"></i>
                            <h5>กรองข้อมูล</h5>
                            <p class="text-muted">จัดกลุ่มตามสังกัดเพื่อความสะดวกในการเรียกดู</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="text-center">
                            <i class="fas fa-chart-bar text-warning mb-3" style="font-size: 2.5rem;"></i>
                            <h5>ข้อมูลครบถ้วน</h5>
                            <p class="text-muted">Infographic แสดงผลงานและข้อมูลสำคัญของแต่ละโรงเรียน</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('home.school-infographic-sandbox') }}" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-arrow-right me-2"></i>เริ่มใช้งานเลย
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    #education-sandbox .btn-light:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        background: #ffffff !important;
    }

    #education-sandbox .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #education-sandbox .display-5 {
            font-size: 2rem;
        }

        #education-sandbox .lead {
            font-size: 1.1rem;
        }

        #education-sandbox .bg-white.rounded-circle {
            width: 150px !important;
            height: 150px !important;
        }

        #education-sandbox .bg-white.rounded-circle i {
            font-size: 3rem !important;
        }
    }
</style>