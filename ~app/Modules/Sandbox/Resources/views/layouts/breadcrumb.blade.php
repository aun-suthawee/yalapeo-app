@php
    $routeName = Route::currentRouteName();
    $segments = request()->segments();
    $schoolId = request()->route('schoolId') ?? request()->route('id');
    $innovationId = request()->route('innovationId');
    $videoId = request()->route('videoId');
    $galleryId = request()->route('id'); // For gallery routes
    
    // Get school name if we have schoolId
    $schoolName = '';
    if ($schoolId) {
        try {
            $school = \Modules\Sandbox\Entities\School::find($schoolId);
            $schoolName = $school ? $school->name : "โรงเรียน #{$schoolId}";
        } catch (Exception $e) {
            $schoolName = "โรงเรียน #{$schoolId}";
        }
    }
    
    // Get innovation title if we have innovationId
    $innovationTitle = '';
    if ($innovationId) {
        try {
            $innovation = \Modules\Sandbox\Entities\SchoolInnovation::find($innovationId);
            $innovationTitle = $innovation ? $innovation->title : "นวัตกรรม #{$innovationId}";
        } catch (Exception $e) {
            $innovationTitle = "นวัตกรรม #{$innovationId}";
        }
    }
    
    // Get vision video title if we have videoId
    $videoTitle = '';
    if ($videoId) {
        try {
            $video = \Modules\Sandbox\Entities\SchoolVisionVideo::find($videoId);
            $videoTitle = $video ? $video->title : "วิดีโอ #{$videoId}";
        } catch (Exception $e) {
            $videoTitle = "วิดีโอ #{$videoId}";
        }
    }
    
    // Get gallery title if we have galleryId and it's a gallery route
    $galleryTitle = '';
    if ($galleryId && str_contains($routeName, 'galleries')) {
        try {
            $gallery = \Modules\Sandbox\Entities\SchoolGallery::find($galleryId);
            $galleryTitle = $gallery ? $gallery->title : "คลังภาพ #{$galleryId}";
        } catch (Exception $e) {
            $galleryTitle = "คลังภาพ #{$galleryId}";
        }
    }
    
    // Get publication title if we have id and it's a publication route
    $publicationId = request()->route('id');
    $publicationTitle = '';
    if ($publicationId && str_contains($routeName, 'publications')) {
        try {
            $publication = \Modules\Sandbox\Entities\Publication::find($publicationId);
            $publicationTitle = $publication ? $publication->title : "เอกสาร #{$publicationId}";
        } catch (Exception $e) {
            $publicationTitle = "เอกสาร #{$publicationId}";
        }
    }
    
    // Get school name for academic results if we have school parameter
    $academicResultSchool = request()->route('school');
    $academicResultSchoolName = '';
    $academicResultSchoolId = null;
    
    if ($academicResultSchool && str_contains($routeName, 'academic-results')) {
        // Check if $academicResultSchool is a School model instance or ID
        if (is_object($academicResultSchool)) {
            // It's already a School model instance (Laravel route model binding)
            $academicResultSchoolName = $academicResultSchool->name;
            $academicResultSchoolId = $academicResultSchool->id;
        } elseif (is_numeric($academicResultSchool)) {
            // It's an ID, fetch the school
            try {
                $schoolModel = \Modules\Sandbox\Entities\School::find($academicResultSchool);
                $academicResultSchoolName = $schoolModel ? $schoolModel->name : "โรงเรียน #{$academicResultSchool}";
                $academicResultSchoolId = $academicResultSchool;
            } catch (Exception $e) {
                $academicResultSchoolName = "โรงเรียน #{$academicResultSchool}";
                $academicResultSchoolId = $academicResultSchool;
            }
        }
    }
    
    // Get academic year for display (convert to Buddhist year)
    $academicYear = request()->get('year', 2025);
    $academicYearBE = $academicYear + 543;
@endphp

<nav class="sandbox-breadcrumb" aria-label="breadcrumb">
    <div class="container">
        <ol class="breadcrumb">
            <!-- Dashboard (Home) -->
            <li class="breadcrumb-item">
                <a href="{{ route('sandbox.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>

            @if (str_contains($routeName, 'schools'))
                <!-- Schools Section -->
                @if ($routeName === 'sandbox.schools.index')
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-school"></i>
                        จัดการโรงเรียน
                    </li>
                @elseif ($routeName === 'sandbox.schools.create')
                    <li class="breadcrumb-item">
                        <a href="{{ route('sandbox.schools.index') }}">
                            <i class="fas fa-school"></i>
                            จัดการโรงเรียน
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-plus"></i>
                        เพิ่มโรงเรียนใหม่
                    </li>
                @elseif (in_array($routeName, ['sandbox.schools.show', 'sandbox.schools.edit']))
                    <li class="breadcrumb-item">
                        <a href="{{ route('sandbox.schools.index') }}">
                            <i class="fas fa-school"></i>
                            จัดการโรงเรียน
                        </a>
                    </li>
                    @if ($routeName === 'sandbox.schools.show')
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-eye"></i>
                            {{ $schoolName }}
                        </li>
                    @elseif ($routeName === 'sandbox.schools.edit')
                        <li class="breadcrumb-item">
                            <a href="{{ route('sandbox.schools.show', $schoolId) }}">
                                <i class="fas fa-eye"></i>
                                {{ $schoolName }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-edit"></i>
                            แก้ไขข้อมูล
                        </li>
                    @endif
                @elseif (str_contains($routeName, 'innovations'))
                    <!-- Innovations Section -->
                    <li class="breadcrumb-item">
                        <a href="{{ route('sandbox.schools.index') }}">
                            <i class="fas fa-school"></i>
                            จัดการโรงเรียน
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('sandbox.schools.show', $schoolId) }}">
                            <i class="fas fa-eye"></i>
                            {{ $schoolName }}
                        </a>
                    </li>
                    
                    @if ($routeName === 'sandbox.schools.innovations')
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-lightbulb"></i>
                            นวัตกรรม
                        </li>
                    @elseif ($routeName === 'sandbox.schools.innovations.create')
                        <li class="breadcrumb-item">
                            <a href="{{ route('sandbox.schools.innovations', $schoolId) }}">
                                <i class="fas fa-lightbulb"></i>
                                นวัตกรรม
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-plus"></i>
                            เพิ่มนวัตกรรมใหม่
                        </li>
                    @elseif ($routeName === 'sandbox.schools.innovations.edit')
                        <li class="breadcrumb-item">
                            <a href="{{ route('sandbox.schools.innovations', $schoolId) }}">
                                <i class="fas fa-lightbulb"></i>
                                นวัตกรรม
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-edit"></i>
                            แก้ไข: {{ $innovationTitle }}
                        </li>
                    @endif
                @elseif (str_contains($routeName, 'vision-videos'))
                    <!-- Vision Videos Section -->
                    <li class="breadcrumb-item">
                        <a href="{{ route('sandbox.schools.index') }}">
                            <i class="fas fa-school"></i>
                            จัดการโรงเรียน
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('sandbox.schools.show', $schoolId) }}">
                            <i class="fas fa-eye"></i>
                            {{ $schoolName }}
                        </a>
                    </li>
                    
                    @if ($routeName === 'sandbox.schools.vision-videos.create')
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-plus"></i>
                            เพิ่มวิดีโอวิสัยทัศน์
                        </li>
                    @elseif ($routeName === 'sandbox.schools.vision-videos.edit')
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-edit"></i>
                            แก้ไข: {{ $videoTitle }}
                        </li>
                    @endif
                @elseif (str_contains($routeName, 'galleries'))
                    <!-- Galleries Section -->
                    <li class="breadcrumb-item">
                        <a href="{{ route('sandbox.schools.index') }}">
                            <i class="fas fa-school"></i>
                            จัดการโรงเรียน
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('sandbox.schools.show', $schoolId) }}">
                            <i class="fas fa-eye"></i>
                            {{ $schoolName }}
                        </a>
                    </li>
                    
                    @if ($routeName === 'sandbox.schools.galleries.index')
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-images"></i>
                            คลังภาพกิจกรรม
                        </li>
                    @elseif ($routeName === 'sandbox.schools.galleries.create')
                        <li class="breadcrumb-item">
                            <a href="{{ route('sandbox.schools.galleries.index', $schoolId) }}">
                                <i class="fas fa-images"></i>
                                คลังภาพกิจกรรม
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-plus"></i>
                            เพิ่มคลังภาพใหม่
                        </li>
                    @elseif ($routeName === 'sandbox.schools.galleries.show')
                        <li class="breadcrumb-item">
                            <a href="{{ route('sandbox.schools.galleries.index', $schoolId) }}">
                                <i class="fas fa-images"></i>
                                คลังภาพกิจกรรม
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-eye"></i>
                            {{ $galleryTitle }}
                        </li>
                    @elseif ($routeName === 'sandbox.schools.galleries.edit')
                        <li class="breadcrumb-item">
                            <a href="{{ route('sandbox.schools.galleries.index', $schoolId) }}">
                                <i class="fas fa-images"></i>
                                คลังภาพกิจกรรม
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="fas fa-edit"></i>
                            แก้ไข: {{ $galleryTitle }}
                        </li>
                    @endif
                @endif
            @elseif ($routeName === 'sandbox.vision-videos.all')
                <!-- All Vision Videos Page -->
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fas fa-video"></i>
                    วิดีโอวิสัยทัศน์ทั้งหมด
                </li>
            @elseif ($routeName === 'sandbox.infographic')
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fas fa-chart-pie"></i>
                    Infographic
                </li>
            @elseif (str_contains($routeName, 'publications'))
                <!-- Publications Section -->
                @if ($routeName === 'sandbox.publications.index')
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-file-pdf"></i>
                        เอกสารเผยแพร่
                    </li>
                @elseif ($routeName === 'sandbox.publications.create')
                    <li class="breadcrumb-item">
                        <a href="{{ route('sandbox.publications.index') }}">
                            <i class="fas fa-file-pdf"></i>
                            เอกสารเผยแพร่
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-plus"></i>
                        เพิ่มเอกสารใหม่
                    </li>
                @elseif ($routeName === 'sandbox.publications.show')
                    <li class="breadcrumb-item">
                        <a href="{{ route('sandbox.publications.index') }}">
                            <i class="fas fa-file-pdf"></i>
                            เอกสารเผยแพร่
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-eye"></i>
                        {{ $publicationTitle }}
                    </li>
                @elseif ($routeName === 'sandbox.publications.edit')
                    <li class="breadcrumb-item">
                        <a href="{{ route('sandbox.publications.index') }}">
                            <i class="fas fa-file-pdf"></i>
                            เอกสารเผยแพร่
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('sandbox.publications.show', $publicationId) }}">
                            <i class="fas fa-eye"></i>
                            {{ $publicationTitle }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-edit"></i>
                        แก้ไข
                    </li>
                @endif
            @elseif (str_contains($routeName, 'academic-results'))
                <!-- Academic Results Section -->
                @if ($routeName === 'sandbox.academic-results.index')
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-chart-line"></i>
                        ผลสัมฤทธิ์ทางการศึกษา
                    </li>
                @elseif ($routeName === 'sandbox.academic-results.edit')
                    <li class="breadcrumb-item">
                        <a href="{{ route('sandbox.academic-results.index', ['year' => request()->get('year', 2025)]) }}">
                            <i class="fas fa-chart-line"></i>
                            ผลสัมฤทธิ์ทางการศึกษา
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-edit"></i>
                        {{ $academicResultSchoolName }} ({{ $academicYearBE }})
                    </li>
                @endif
            @elseif (str_contains($routeName, 'experiments'))
                <!-- Experiments Section -->
                @if ($routeName === 'sandbox.experiments.index')
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-flask"></i>
                        Experiments
                    </li>
                @elseif ($routeName === 'sandbox.experiments.create')
                    <li class="breadcrumb-item">
                        <a href="{{ route('sandbox.experiments.index') }}">
                            <i class="fas fa-flask"></i>
                            Experiments
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-plus"></i>
                        สร้าง Experiment ใหม่
                    </li>
                @elseif ($routeName === 'sandbox.experiments.edit')
                    <li class="breadcrumb-item">
                        <a href="{{ route('sandbox.experiments.index') }}">
                            <i class="fas fa-flask"></i>
                            Experiments
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-edit"></i>
                        {{ $experiment->name ?? 'แก้ไข Experiment' }}
                    </li>
                @elseif ($routeName === 'sandbox.experiments.show')
                    <li class="breadcrumb-item">
                        <a href="{{ route('sandbox.experiments.index') }}">
                            <i class="fas fa-flask"></i>
                            Experiments
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-eye"></i>
                        {{ $experiment->name ?? 'ดู Experiment' }}
                    </li>
                @endif
            @endif
        </ol>
    </div>
</nav>