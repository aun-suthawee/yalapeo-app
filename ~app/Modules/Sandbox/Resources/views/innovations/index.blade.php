@extends('sandbox::layouts.master')

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
                            <div class="innovation-card {{ $innovation->is_active ? 'active' : 'inactive' }}"
                                data-category="{{ $innovation->category }}"
                                data-status="{{ $innovation->is_active ? '1' : '0' }}"
                                data-innovation-id="{{ $innovation->id }}"
                                data-innovation-title="{{ htmlspecialchars($innovation->title, ENT_QUOTES) }}"
                                data-innovation-description="{{ htmlspecialchars($innovation->description ?? '', ENT_QUOTES) }}"
                                data-innovation-category="{{ $innovation->category }}"
                                data-innovation-year="{{ $innovation->year }}"
                                data-innovation-active="{{ $innovation->is_active ? '1' : '0' }}"
                                data-innovation-files="{{ $innovation->image_paths ? json_encode($innovation->image_paths) : '[]' }}"
                                onclick="viewInnovationFast(this)" style="cursor: pointer;">

                                @if ($innovation->image_paths && count($innovation->image_paths) > 0)
                                    @php
                                        $firstFile = $innovation->image_paths[0];
                                        $fileName = basename($firstFile);
                                        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                        $isPDF = $extension === 'pdf';
                                    @endphp
                                    <div class="innovation-image {{ $isPDF ? 'pdf-container' : '' }}">
                                        @if ($isPDF)
                                            <!-- PDF Preview -->
                                            <div class="pdf-preview-card">
                                                <i class="fas fa-file-pdf pdf-icon"></i>
                                            </div>
                                        @else
                                            <!-- Image Preview -->
                                            @php
                                                $imageUrl = route('sandbox.innovation.image', [$school->id, $fileName]);
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="{{ $innovation->title }}" loading="lazy"
                                                style="width: 100%; height: 200px; object-fit: cover;"
                                                onerror="this.onerror=null; this.parentElement.classList.add('placeholder'); this.style.display='none';">
                                            @if (count($innovation->image_paths) > 1)
                                                <div class="file-count-badge">
                                                    <i class="fas fa-images"></i>
                                                    {{ count($innovation->image_paths) }}
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

    <!-- Innovation Detail Modal -->
    <div class="modal fade" id="innovationModal" tabindex="-1" aria-labelledby="innovationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="innovationModalLabel">รายละเอียดนวัตกรรม</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="innovationModalBody">
                    <!-- Content will be loaded here -->
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
    <script src="{{ asset('assets/common/js/school-management.js') }}"></script>
    <script>
        // Image load handler for fade-in animation
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.innovation-image img').forEach(img => {
                // Add loaded class when image finishes loading
                if (img.complete) {
                    img.classList.add('loaded');
                } else {
                    img.addEventListener('load', function() {
                        this.classList.add('loaded');
                    });
                }
            });

            const highlightId = @json(request('highlight'));
            if (highlightId) {
                setTimeout(() => {
                    const targetCard = document.querySelector(`.innovation-card[data-innovation-id="${highlightId}"]`);
                    if (targetCard) {
                        targetCard.classList.add('highlight-pulse');
                        targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        viewInnovationFast(targetCard);
                        setTimeout(() => targetCard.classList.remove('highlight-pulse'), 2400);
                    }
                }, 450);
            }
        });

        // Filtering functionality
        document.getElementById('filterCategory').addEventListener('change', function() {
            filterInnovations();
        });

        document.getElementById('filterStatus').addEventListener('change', function() {
            filterInnovations();
        });

        function filterInnovations() {
            const categoryFilter = document.getElementById('filterCategory').value;
            const statusFilter = document.getElementById('filterStatus').value;
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

                card.style.display = showCard ? 'block' : 'none';
            });
        }

        function viewInnovationFast(cardElement) {
            // Get data from card attributes (instant, no API call)
            const title = cardElement.dataset.innovationTitle;
            const description = cardElement.dataset.innovationDescription;
            const category = cardElement.dataset.innovationCategory;
            const year = cardElement.dataset.innovationYear;
            const isActive = cardElement.dataset.innovationActive === '1';
            const filesData = cardElement.dataset.innovationFiles;

            // Generate files HTML
            let filesHtml = '';
            if (filesData && filesData !== '[]') {
                try {
                    const filePaths = JSON.parse(filesData);
                    if (Array.isArray(filePaths) && filePaths.length > 0) {
                        if (filePaths.length === 1) {
                            // Single file
                            const filePath = filePaths[0];
                            const fileName = filePath.split('/').pop();
                            const extension = fileName.split('.').pop().toLowerCase();
                            const isPDF = extension === 'pdf';

                            if (isPDF) {
                                filesHtml = `
                            <div class="file-display">
                                <div class="pdf-viewer">
                                    <div class="pdf-header">
                                        <i class="fas fa-file-pdf text-danger"></i>
                                        <span class="file-name">${fileName}</span>
                                        <a href="/sandbox/innovation-image/{{ $school->id }}/${fileName}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-external-link-alt"></i> เปิดดู
                                        </a>
                                    </div>
                                    <div class="pdf-preview-container">
                                        <embed src="/sandbox/innovation-image/{{ $school->id }}/${fileName}" type="application/pdf" width="100%" height="400px" />
                                    </div>
                                </div>
                            </div>
                        `;
                            } else {
                                const imageUrl = `/sandbox/innovation-image/{{ $school->id }}/${fileName}`;
                                filesHtml =
                                    `<img src="${imageUrl}" alt="${title}" class="img-fluid mb-3 rounded" style="max-height: 400px; object-fit: contain; width: 100%;">`;
                            }
                        } else {
                            // Multiple files - show carousel
                            filesHtml = `
                        <div id="filesCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                            <div class="carousel-inner">`;

                            filePaths.forEach((filePath, index) => {
                                const fileName = filePath.split('/').pop();
                                const extension = fileName.split('.').pop().toLowerCase();
                                const isPDF = extension === 'pdf';

                                filesHtml += `
                            <div class="carousel-item ${index === 0 ? 'active' : ''}">`;

                                if (isPDF) {
                                    filesHtml += `
                                <div class="pdf-viewer">
                                    <div class="pdf-header">
                                        <i class="fas fa-file-pdf text-danger"></i>
                                        <span class="file-name">${fileName}</span>
                                        <a href="/sandbox/innovation-image/{{ $school->id }}/${fileName}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-external-link-alt"></i> เปิดดู
                                        </a>
                                    </div>
                                    <div class="pdf-preview-container">
                                        <embed src="/sandbox/innovation-image/{{ $school->id }}/${fileName}" type="application/pdf" width="100%" height="350px" />
                                    </div>
                                </div>
                            `;
                                } else {
                                    const imageUrl = `/sandbox/innovation-image/{{ $school->id }}/${fileName}`;
                                    filesHtml +=
                                        `<img src="${imageUrl}" alt="${title}" class="d-block w-100" style="max-height: 400px; object-fit: contain;">`;
                                }

                                filesHtml += `</div>`;
                            });

                            filesHtml += `
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#filesCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#filesCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            <div class="carousel-indicators">`;

                            filePaths.forEach((_, index) => {
                                filesHtml += `
                            <button type="button" data-bs-target="#filesCarousel" data-bs-slide-to="${index}" 
                                    ${index === 0 ? 'class="active" aria-current="true"' : ''} 
                                    aria-label="Slide ${index + 1}"></button>`;
                            });

                            filesHtml += `
                            </div>
                        </div>
                    `;
                        }
                    }
                } catch (e) {
                    console.error('Error parsing files data:', e);
                }
            }

            // Build modal content instantly
            document.getElementById('innovationModalBody').innerHTML = `
        <div class="innovation-detail-modal">
            ${filesHtml}
            <h4 class="mb-3">${title}</h4>
            ${category ? `<span class="badge bg-secondary mb-3">${category}</span>` : ''}
            ${description ? `<p class="text-muted" style="white-space: pre-wrap;">${description}</p>` : '<p class="text-muted">ไม่มีรายละเอียด</p>'}
            <hr>
            <div class="mt-3">
                ${year ? `<div class="mb-2"><i class="fas fa-calendar text-primary"></i> <strong>ปีที่เริ่มใช้งาน:</strong> พ.ศ. ${year}</div>` : ''}
                <div><i class="fas fa-toggle-${isActive ? 'on' : 'off'} text-${isActive ? 'success' : 'secondary'}"></i> <strong>สถานะ:</strong> ${isActive ? '<span class="text-success">ใช้งานอยู่</span>' : '<span class="text-muted">ไม่ใช้งาน</span>'}</div>
            </div>
        </div>
    `;

            // Show modal instantly (no delay!)
            new bootstrap.Modal(document.getElementById('innovationModal')).show();
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
