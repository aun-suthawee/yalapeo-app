@extends('sandbox::layouts.master')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'วิดีโอวิสัยทัศน์การศึกษาทั้งหมด')

@section('stylesheet-content')
    <style>
        .vision-all-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 3rem 0;
        }

        .vision-header {
            text-align: center;
            color: white;
            margin-bottom: 3rem;
        }

        .vision-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .vision-header p {
            font-size: 1.125rem;
            opacity: 0.95;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: white;
            color: #667eea;
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 2rem;
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            color: #667eea;
        }

        .vision-videos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .vision-video-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .vision-video-card:hover,
        .vision-video-card:focus-visible {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.18);
        }

        .vision-video-card:focus-visible {
            outline: 3px solid rgba(102, 126, 234, 0.35);
            outline-offset: 4px;
        }

        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            background: #000;
        }

        .video-wrapper iframe,
        .video-wrapper video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .video-info {
            padding: 1.5rem;
        }

        .video-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .video-school {
            font-size: 0.9rem;
            color: #667eea;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .video-description {
            font-size: 0.875rem;
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .video-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            font-size: 0.8rem;
        }

        .video-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-youtube {
            background: #ff0000;
            color: white;
        }

        .badge-facebook {
            background: #1877f2;
            color: white;
        }

        .badge-tiktok {
            background: #000000;
            color: white;
        }

        .badge-google_drive {
            background: #0f9d58;
            color: white;
        }

        .badge-other {
            background: #6c757d;
            color: white;
        }

        .video-date {
            color: #95a5a6;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .vision-modal-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
            margin-top: 1.5rem;
        }

        .vision-modal-meta .badge {
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #f1f3f5;
            color: #2c3e50;
        }

        .vision-modal-description {
            margin-top: 1rem;
            font-size: 0.95rem;
            line-height: 1.6;
            color: #2c3e50;
        }

        .vision-infinite-loader {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            color: white;
            padding: 1.5rem 0;
        }

        .vision-infinite-loader .spinner-border {
            width: 2rem;
            height: 2rem;
            border-width: 0.2rem;
        }

        .vision-infinite-loader.active {
            display: flex;
        }

        .vision-load-end {
            color: rgba(255, 255, 255, 0.9);
            text-align: center;
            padding: 1rem 0 2rem;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
        }

        .pagination {
            background: white;
            border-radius: 30px;
            padding: 0.5rem 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .pagination .page-link {
            color: #667eea;
            border: none;
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: #667eea;
            color: white;
        }

        .pagination .page-item.active .page-link {
            background: #667eea;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #ccc;
        }

        .empty-state {
            text-align: center;
            color: white;
            padding: 4rem 2rem;
        }

        .empty-state h3 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 1.125rem;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .vision-all-container {
                padding: 2rem 0;
            }

            .vision-header h1 {
                font-size: 1.75rem;
                flex-direction: column;
            }

            .vision-videos-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .vision-modal-description {
                font-size: 0.9rem;
            }
        }
    </style>
@endsection

@section('script-content')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const gridEl = document.getElementById('visionVideosGrid');
            const modalEl = document.getElementById('visionVideoModal');
            const loaderEl = document.getElementById('visionInfiniteLoader');
            const endEl = document.getElementById('visionInfiniteEnd');
            const sentinelEl = document.getElementById('visionVideosSentinel');

            if (!gridEl) {
                return;
            }

            const typeMeta = {
                youtube: { label: 'YouTube', className: 'badge-youtube', iconClass: 'fab fa-youtube' },
                facebook: { label: 'Facebook', className: 'badge-facebook', iconClass: 'fab fa-facebook' },
                tiktok: { label: 'TikTok', className: 'badge-tiktok', iconClass: 'fab fa-tiktok' },
                google_drive: { label: 'Google Drive', className: 'badge-google_drive', iconClass: 'fab fa-google-drive' },
                other: { label: 'Video', className: 'badge-other', iconClass: 'fas fa-video' }
            };

            const modalInstance = modalEl ? new bootstrap.Modal(modalEl) : null;
            const iframeEl = modalEl ? modalEl.querySelector('#visionVideoModalIframe') : null;
            const titleEl = modalEl ? modalEl.querySelector('#visionVideoModalTitle') : null;
            const schoolBadge = modalEl ? modalEl.querySelector('#visionVideoModalSchool .label') : null;
            const typeBadge = modalEl ? modalEl.querySelector('#visionVideoModalType') : null;
            const dateBadge = modalEl ? modalEl.querySelector('#visionVideoModalDate .label') : null;
            const descriptionEl = modalEl ? modalEl.querySelector('#visionVideoModalDescription') : null;

            function updateTypeBadge(badgeEl, type) {
                if (!badgeEl) {
                    return;
                }

                const meta = typeMeta[type] || typeMeta.other;
                badgeEl.className = 'video-type-badge';
                badgeEl.classList.add(meta.className);
                badgeEl.innerHTML = '';

                const iconElement = document.createElement('i');
                iconElement.className = meta.iconClass;
                badgeEl.appendChild(iconElement);
                badgeEl.appendChild(document.createTextNode(' ' + meta.label));
            }

            function openModalForCard(card) {
                if (!modalInstance || !card) {
                    return;
                }

                const title = card.dataset.videoTitle || '';
                const school = card.dataset.videoSchool || '-';
                const description = card.dataset.videoDescription || '';
                const dateText = card.dataset.videoDate || '-';
                const embedUrl = card.dataset.videoEmbed || '';
                const type = card.dataset.videoType || 'other';

                if (titleEl) {
                    titleEl.textContent = title;
                }
                if (schoolBadge) {
                    schoolBadge.textContent = school;
                }
                updateTypeBadge(typeBadge, type);
                if (dateBadge) {
                    dateBadge.textContent = dateText;
                }
                if (descriptionEl) {
                    const trimmed = description.trim();
                    descriptionEl.textContent = trimmed;
                    descriptionEl.style.display = trimmed ? 'block' : 'none';
                }
                if (iframeEl) {
                    iframeEl.src = embedUrl;
                }

                modalInstance.show();
            }

            function stopPropagationIn(node) {
                if (!node) {
                    return;
                }
                node.addEventListener('click', (event) => event.stopPropagation());
                node.addEventListener('keydown', (event) => event.stopPropagation());
            }

            function attachCardHandlers(cards) {
                cards.forEach((card) => {
                    if (card.dataset.handlersAttached === 'true') {
                        return;
                    }

                    card.addEventListener('click', () => openModalForCard(card));
                    card.addEventListener('keydown', (event) => {
                        if (event.key === 'Enter' || event.key === ' ') {
                            event.preventDefault();
                            openModalForCard(card);
                        }
                    });

                    card.dataset.handlersAttached = 'true';

                    card.querySelectorAll('[data-stop-propagation="true"], [data-stop-propagation="true"] a, [data-stop-propagation="true"] button')
                        .forEach(stopPropagationIn);
                });
            }

            attachCardHandlers(gridEl.querySelectorAll('.vision-video-card'));

            if (modalEl) {
                modalEl.addEventListener('hidden.bs.modal', function () {
                    if (iframeEl) {
                        iframeEl.src = '';
                    }
                });
            }

            let isLoading = false;
            const observer = sentinelEl && 'IntersectionObserver' in window
                ? new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            loadNextPage();
                        }
                    });
                }, {
                    rootMargin: '250px 0px'
                })
                : null;

            if (observer && sentinelEl) {
                observer.observe(sentinelEl);
            }

            async function loadNextPage() {
                const nextPageUrl = gridEl.dataset.nextPage;
                if (!nextPageUrl || isLoading) {
                    if (!nextPageUrl && observer && sentinelEl) {
                        observer.unobserve(sentinelEl);
                    }
                    return;
                }

                isLoading = true;
                if (loaderEl) {
                    loaderEl.classList.add('active');
                }

                try {
                    const response = await fetch(nextPageUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newGrid = doc.getElementById('visionVideosGrid');

                    if (newGrid) {
                        const newCards = Array.from(newGrid.querySelectorAll('.vision-video-card'));
                        newCards.forEach((card) => {
                            card.dataset.handlersAttached = 'false';
                            gridEl.appendChild(card);
                        });
                        attachCardHandlers(newCards);
                        const newNextPage = newGrid.dataset.nextPage || '';
                        gridEl.dataset.nextPage = newNextPage;
                    } else {
                        gridEl.dataset.nextPage = '';
                    }

                    if (!gridEl.dataset.nextPage) {
                        if (observer && sentinelEl) {
                            observer.unobserve(sentinelEl);
                        }
                        if (endEl) {
                            endEl.style.display = 'block';
                        }
                    }
                } catch (error) {
                    console.error('Error loading more videos:', error);
                } finally {
                    isLoading = false;
                    if (loaderEl) {
                        loaderEl.classList.remove('active');
                    }
                }
            }
        });
    </script>
@endsection

@section('content')
    <div class="vision-all-container">
        <div class="container">
            <a href="{{ route('sandbox.dashboard') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                กลับสู่หน้าหลัก
            </a>

            <div class="vision-header">
                <h1>
                    <i class="fas fa-video"></i>
                    วิดีโอวิสัยทัศน์การศึกษาทั้งหมด
                </h1>
                <p>รวมวิดีโอวิสัยทัศน์และแนวทางการพัฒนาการศึกษาจากทุกโรงเรียน</p>
            </div>

            @if ($videos->count() > 0)
                <div class="vision-videos-grid" id="visionVideosGrid" data-next-page="{{ $videos->nextPageUrl() }}">
                    @foreach ($videos as $video)
                        @php
                            $videoDescription = $video->description ? strip_tags($video->description) : '';
                            $videoDateDisplay = $video->created_at->format('d/m/Y H:i น.');
                        @endphp
                        <div class="vision-video-card"
                            data-video-type="{{ $video->video_type }}"
                            data-video-title="{{ $video->title }}"
                            data-video-school="{{ $video->school->name }}"
                            data-video-description="{{ e($videoDescription) }}"
                            data-video-date="{{ $videoDateDisplay }}"
                            data-video-embed="{{ $video->embed_url }}"
                            tabindex="0"
                            role="button"
                            aria-label="เปิดวิดีโอ {{ $video->title }}">
                            <div class="video-wrapper">
                                <iframe src="{{ $video->embed_url }}" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen loading="lazy">
                                </iframe>
                            </div>
                            <div class="video-info">
                                <h3 class="video-title">{{ $video->title }}</h3>
                                <p class="video-school">
                                    <i class="fas fa-school"></i>
                                    {{ $video->school->name }}
                                </p>
                                @if ($video->description)
                                    <p class="video-description">{{ Str::limit(strip_tags($video->description), 140) }}</p>
                                @endif
                                <div class="video-meta">
                                    <span class="video-type-badge badge-{{ $video->video_type }}">
                                        @if ($video->video_type == 'youtube')
                                            <i class="fab fa-youtube"></i> YouTube
                                        @elseif($video->video_type == 'facebook')
                                            <i class="fab fa-facebook"></i> Facebook
                                        @elseif($video->video_type == 'tiktok')
                                            <i class="fab fa-tiktok"></i> TikTok
                                        @elseif($video->video_type == 'google_drive')
                                            <i class="fab fa-google-drive"></i> Google Drive
                                        @else
                                            <i class="fas fa-video"></i> Video
                                        @endif
                                    </span>
                                    <span class="video-date">
                                        <i class="far fa-clock"></i>
                                        {{ $video->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                @auth
                                    <div class="video-actions" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e9ecef;" data-stop-propagation="true">
                                        <a href="{{ route('sandbox.schools.vision-videos.edit', [$video->school_id, $video->id]) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i> แก้ไข
                                        </a>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="vision-infinite-loader" id="visionInfiniteLoader">
                    <div class="spinner-border text-light" role="status"></div>
                    <span>กำลังโหลดวิดีโอเพิ่มเติม...</span>
                </div>
                <div class="vision-load-end" id="visionInfiniteEnd" style="display: none;">
                    🎉 คุณดูครบทุกวิดีโอแล้ว
                </div>
                <div id="visionVideosSentinel" aria-hidden="true"></div>
            @else
                <div class="empty-state">
                    <h3>ยังไม่มีวิดีโอวิสัยทัศน์</h3>
                    <p>ระบบจะแสดงวิดีโอเมื่อมีการเพิ่มข้อมูลเข้าสู่ระบบ</p>
                    @auth
                        <a href="{{ route('sandbox.schools.index') }}" class="btn btn-light mt-3">
                            <i class="fas fa-plus"></i> เพิ่มวิดีโอวิสัยทัศน์
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade vision-video-modal" id="visionVideoModal" tabindex="-1"
        aria-labelledby="visionVideoModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="visionVideoModalTitle">ดูวิสัยทัศน์การศึกษา</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="ratio ratio-16x9">
                        <iframe id="visionVideoModalIframe" src="" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    </div>
                    <div class="vision-modal-meta">
                        <span class="badge" id="visionVideoModalSchool">
                            <i class="fas fa-school"></i>
                            <span class="label">โรงเรียน</span>
                        </span>
                        <span class="video-type-badge badge-other" id="visionVideoModalType"></span>
                        <span class="badge" id="visionVideoModalDate">
                            <i class="far fa-clock"></i>
                            <span class="label">-</span>
                        </span>
                    </div>
                    <p class="vision-modal-description" id="visionVideoModalDescription" style="display: none;"></p>
                </div>
            </div>
        </div>
    </div>
@endsection
