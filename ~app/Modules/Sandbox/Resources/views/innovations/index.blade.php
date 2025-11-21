@extends('sandbox::layouts.master')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'จัดการนวัตกรรม - ' . $school->name)

@section('stylesheet-content')
    <link rel="stylesheet" href="{{ asset('assets/common/css/school-management.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/css/innovation-shared.css') }}">
@endsection

@section('content')
    <div class="school-management-container">
        <div class="container">
            <!-- Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1 class="page-title">
                        <i class="fas fa-lightbulb"></i>
                        จัดการนวัตกรรม
                    </h1>
                    <p class="page-subtitle">{{ $school->name }}</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('sandbox.schools.show', $school->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> กลับ
                    </a>
                    @auth
                        <a href="{{ route('sandbox.schools.innovations.create', $school->id) }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> เพิ่มนวัตกรรมใหม่
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Innovations Statistics -->
            <div class="stats-cards">
                <div class="stat-card active">
                    <div class="stat-icon">✅</div>
                    <div class="stat-content">
                        <h3>ใช้งานอยู่</h3>
                        <div class="stat-number">{{ $school->innovations()->where('is_active', true)->count() }}</div>
                    </div>
                </div>
                <div class="stat-card inactive">
                    <div class="stat-icon">⏸️</div>
                    <div class="stat-content">
                        <h3>ไม่ใช้งาน</h3>
                        <div class="stat-number">{{ $school->innovations()->where('is_active', false)->count() }}</div>
                    </div>
                </div>
                <div class="stat-card total">
                    <div class="stat-icon">📊</div>
                    <div class="stat-content">
                        <h3>ทั้งหมด</h3>
                        <div class="stat-number">{{ $school->innovations()->count() }}</div>
                    </div>
                </div>
            </div>

            <!-- Innovations List -->
            <div class="innovations-section">
                <div class="section-header">
                    <h2 class="section-title">รายการนวัตกรรมทั้งหมด</h2>
                    <div class="section-filters">
                        <select class="form-control" id="filterCategory">
                            <option value="">ทุกหมวดหมู่</option>
                            <option value="การเรียนการสอน">การเรียนการสอน</option>
                            <option value="เทคโนโลยี">เทคโนโลยี</option>
                            <option value="การจัดการ">การจัดการ</option>
                            <option value="สิ่งแวดล้อม">สิ่งแวดล้อม</option>
                            <option value="สื่อการเรียนรู้">สื่อการเรียนรู้</option>
                            <option value="กิจกรรมนักเรียน">กิจกรรมนักเรียน</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                        <select class="form-control" id="filterStatus">
                            <option value="">ทุกสถานะ</option>
                            <option value="1">ใช้งานอยู่</option>
                            <option value="0">ไม่ใช้งาน</option>
                        </select>
                    </div>
                </div>

                @if ($school->innovations->count() > 0)
                    <div class="innovations-grid">
                        @foreach ($school->innovations->sortByDesc('created_at') as $innovation)
                            @php
                                $categoryLabel = $innovation->category ?: 'ไม่ระบุหมวดหมู่';
                                $detailUrl = auth()->check()
                                    ? route('sandbox.schools.innovations.edit', [$school->id, $innovation->id])
                                    : route('sandbox.schools.show', $school->id);
                                $imagePaths = collect($innovation->image_paths ?? []);
                                $firstPath = $imagePaths->first();
                                $firstFile = $firstPath ? basename($firstPath) : null;
                                $firstExtension = $firstFile ? strtolower(pathinfo($firstFile, PATHINFO_EXTENSION)) : null;
                                $isFirstPdf = $firstExtension === 'pdf';
                                $previewImageUrl = $firstFile && !$isFirstPdf ? route('sandbox.innovation.image', [$school->id, $firstFile]) : null;
                                $lightboxImages = $imagePaths
                                    ->map(function ($path) use ($school) {
                                        $file = $path ? basename($path) : null;
                                        if (!$file) {
                                            return null;
                                        }

                                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                        if ($ext === 'pdf') {
                                            return null;
                                        }

                                        return [
                                            'url' => route('sandbox.innovation.image', [$school->id, $file]),
                                            'name' => $file,
                                        ];
                                    })
                                    ->filter()
                                    ->values();
                                $lightboxImagesJson = $lightboxImages->toJson();
                            @endphp
                            <div class="innovation-card {{ $innovation->is_active ? 'active' : 'inactive' }}"
                                role="button" tabindex="0" data-lightbox="1"
                                aria-label="ดูนวัตกรรม {{ strip_tags($innovation->title ?? '') }}"
                                style="cursor: pointer;"
                                data-category="{{ $innovation->category ?? '' }}"
                                data-status="{{ $innovation->is_active ? '1' : '0' }}"
                                data-innovation-id="{{ $innovation->id }}"
                                data-innovation-title="{{ htmlspecialchars($innovation->title ?? '', ENT_QUOTES) }}"
                                data-innovation-description="{{ htmlspecialchars($innovation->description ?? '', ENT_QUOTES) }}"
                                data-innovation-category="{{ $innovation->category ?? '' }}"
                                data-innovation-year="{{ $innovation->year ?? '' }}"
                                data-innovation-active="{{ $innovation->is_active ? '1' : '0' }}"
                                data-innovation-files="{{ $innovation->image_paths ? json_encode($innovation->image_paths) : '[]' }}"
                                data-title="{{ Str::lower(strip_tags($innovation->title ?? '')) }}"
                                data-description="{{ Str::lower(strip_tags($innovation->description ?? '')) }}"
                                data-title-display="{{ e(strip_tags($innovation->title ?? '')) }}"
                                data-school-name="{{ e(strip_tags($school->name ?? '')) }}"
                                data-category-label="{{ e($categoryLabel) }}"
                                data-detail-url="{{ $detailUrl }}"
                                data-images="{{ e($lightboxImagesJson) }}">

                                @if ($previewImageUrl || $isFirstPdf)
                                    <div class="innovation-image {{ $isFirstPdf ? 'pdf-container' : '' }}">
                                        @if ($isFirstPdf)
                                            <div class="pdf-preview-card">
                                                <i class="fas fa-file-pdf pdf-icon"></i>
                                            </div>
                                        @else
                                            <img src="{{ $previewImageUrl }}" alt="{{ $innovation->title }}" loading="lazy"
                                                style="width: 100%; height: 200px; object-fit: cover;"
                                                onerror="this.onerror=null; this.parentElement.classList.add('placeholder'); this.style.display='none';">
                                            @if ($imagePaths->count() > 1)
                                                <div class="file-count-badge">
                                                    <i class="fas fa-images"></i>
                                                    {{ $imagePaths->count() }}
                                                </div>
                                            @endif
                                        @endif

                                        <div class="image-overlay">
                                            <div class="status-badge {{ $innovation->is_active ? 'active' : 'inactive' }}">
                                                {{ $innovation->is_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="innovation-image placeholder">
                                        <div class="placeholder-icon">
                                            <i class="fas fa-lightbulb"></i>
                                        </div>
                                        <div class="status-badge {{ $innovation->is_active ? 'active' : 'inactive' }}">
                                            {{ $innovation->is_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                                        </div>
                                    </div>
                                @endif

                                <div class="innovation-content">
                                    <h4 class="innovation-title">{{ $innovation->title }}</h4>

                                    @if ($innovation->category)
                                        <span class="innovation-category">{{ $innovation->category }}</span>
                                    @endif

                                    @if ($innovation->description)
                                        <p class="innovation-description">{{ Str::limit($innovation->description, 120) }}
                                        </p>
                                    @endif

                                    <div class="innovation-meta">
                                        @if ($innovation->year)
                                            <span class="innovation-year">
                                                <i class="fas fa-calendar"></i>
                                                พ.ศ. {{ $innovation->year }}
                                            </span>
                                        @endif
                                        <span class="innovation-date">
                                            <i class="fas fa-clock"></i>
                                            {{ $innovation->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>

                                    <div class="innovation-actions">
                                        @auth
                                            <a href="{{ route('sandbox.schools.innovations.edit', [$school->id, $innovation->id]) }}"
                                                class="btn btn-sm btn-warning" onclick="event.stopPropagation();">
                                                <i class="fas fa-edit"></i> แก้ไข
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="event.stopPropagation(); deleteInnovation({{ $innovation->id }}, '{{ addslashes($innovation->title) }}')">
                                                <i class="fas fa-trash"></i> ลบ
                                            </button>
                                        @else
                                            <div class="text-muted small">
                                                <i class="fas fa-hand-pointer"></i> คลิกเพื่อดูรายละเอียด
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-innovations">
                        <div class="empty-icon">💡</div>
                        <h3>ยังไม่มีนวัตกรรม</h3>
                        <p>{{ auth()->check() ? 'เริ่มต้นด้วยการเพิ่มนวัตกรรมแรกของโรงเรียน ' . $school->name : 'โรงเรียนนี้ยังไม่มีนวัตกรรมในระบบ' }}
                        </p>
                        @auth
                            <a href="{{ route('sandbox.schools.innovations.create', $school->id) }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> เพิ่มนวัตกรรมใหม่
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade innovation-lightbox" id="innovationLightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                <button type="button" class="innovation-lightbox-share" id="innovationLightboxShare"
                    aria-label="คัดลอกลิงก์ภาพ">
                    <i class="fas fa-share-alt"></i>
                </button>
                <div class="innovation-lightbox-main">
                    <button type="button" class="innovation-lightbox-nav innovation-lightbox-prev"
                        id="innovationLightboxPrev" aria-label="ภาพก่อนหน้า">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="innovation-lightbox-image-wrapper" id="innovationLightboxImageWrapper">
                        <img id="innovationLightboxImage" src="" alt="">
                    </div>
                    <button type="button" class="innovation-lightbox-nav innovation-lightbox-next"
                        id="innovationLightboxNext" aria-label="ภาพถัดไป">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="innovation-lightbox-feedback" id="innovationLightboxFeedback" role="status"
                    aria-live="polite"></div>
                <div class="innovation-lightbox-meta">
                    <div class="innovation-lightbox-meta-text">
                        <h3 id="innovationLightboxTitle"></h3>
                        <p>
                            <span id="innovationLightboxSchool"></span>
                            <span class="bullet">•</span>
                            <span id="innovationLightboxCategory"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">ยืนยันการลบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>คุณต้องการลบนวัตกรรม "<span id="deleteItemName"></span>" หรือไม่?</p>
                    <p class="text-danger"><small>การดำเนินการนี้ไม่สามารถย้อนกลับได้</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">ลบ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script-content')
    <script src="{{ asset('assets/common/js/innovation-lightbox.js') }}"></script>
    <script src="{{ asset('assets/common/js/school-management.js') }}"></script>
    <script>
        let innovationLightboxController = null;

        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.innovation-card[data-lightbox]');
            const modalEl = document.getElementById('innovationLightboxModal');

            if (modalEl && window.InnovationLightbox && typeof window.InnovationLightbox.create === 'function') {
                innovationLightboxController = window.InnovationLightbox.create(modalEl);
                if (innovationLightboxController && typeof innovationLightboxController.registerCards === 'function') {
                    innovationLightboxController.registerCards(cards);
                }
            }

            document.querySelectorAll('.innovation-image img').forEach((img) => {
                if (img.complete) {
                    img.classList.add('loaded');
                } else {
                    img.addEventListener('load', function() {
                        this.classList.add('loaded');
                    });
                }
            });

            const filterCategoryEl = document.getElementById('filterCategory');
            const filterStatusEl = document.getElementById('filterStatus');

            if (filterCategoryEl) {
                filterCategoryEl.addEventListener('change', filterInnovations);
            }

            if (filterStatusEl) {
                filterStatusEl.addEventListener('change', filterInnovations);
            }

            const highlightId = @json(request('highlight'));
            if (highlightId) {
                setTimeout(() => {
                    const targetCard = document.querySelector(`.innovation-card[data-innovation-id="${highlightId}"]`);
                    if (targetCard) {
                        targetCard.classList.add('highlight-pulse');
                        targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        if (innovationLightboxController && typeof innovationLightboxController.openFromCard === 'function') {
                            innovationLightboxController.openFromCard(targetCard);
                        }
                        setTimeout(() => targetCard.classList.remove('highlight-pulse'), 2400);
                    }
                }, 450);
            }

            filterInnovations();
        });

        function filterInnovations() {
            const categoryEl = document.getElementById('filterCategory');
            const statusEl = document.getElementById('filterStatus');
            const categoryFilter = categoryEl ? categoryEl.value : '';
            const statusFilter = statusEl ? statusEl.value : '';
            const cards = document.querySelectorAll('.innovation-card');

            cards.forEach(card => {
                const category = card.dataset.category || '';
                const status = card.dataset.status;

                let showCard = true;

                if (categoryFilter && category !== categoryFilter) {
                    showCard = false;
                }

                if (statusFilter && status !== statusFilter) {
                    showCard = false;
                }

                card.style.display = showCard ? '' : 'none';
            });
        }

        function deleteInnovation(id, title) {
            document.getElementById('deleteItemName').textContent = title;
            document.getElementById('deleteForm').action = `/sandbox/schools/{{ $school->id }}/innovations/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        function toggleStatus(id, newStatus) {
            if (confirm(`คุณต้องการ${newStatus ? 'เปิด' : 'ปิด'}ใช้งานนวัตกรรมนี้หรือไม่?`)) {
                fetch(`/sandbox/schools/{{ $school->id }}/innovations/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            is_active: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('เกิดข้อผิดพลาดในการอัปเดต');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                    });
            }
        }
    </script>
@endsection
