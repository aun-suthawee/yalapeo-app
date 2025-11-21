(function (global) {
    'use strict';

    if (global.InnovationLightbox) {
        return;
    }

    function clamp(value, min, max) {
        return Math.min(Math.max(value, min), max);
    }

    function readDatasetJson(value) {
        if (!value) {
            return [];
        }

        if (Array.isArray(value)) {
            return value;
        }

        try {
            const parsed = JSON.parse(value);
            return Array.isArray(parsed) ? parsed : [];
        } catch (error) {
            console.warn('InnovationLightbox: unable to parse dataset JSON', error);
            return [];
        }
    }

    function Lightbox(modalEl, options) {
        this.modalEl = modalEl;
        this.options = Object.assign({
            zoomLevels: [1, 1.6, 2.2, 3]
        }, options || {});
        this.bootstrapModal = typeof bootstrap !== 'undefined'
            ? bootstrap.Modal.getOrCreateInstance(modalEl, { keyboard: true })
            : null;

        this.imageEl = modalEl.querySelector('#innovationLightboxImage');
        this.wrapperEl = modalEl.querySelector('#innovationLightboxImageWrapper');
        this.titleEl = modalEl.querySelector('#innovationLightboxTitle');
        this.schoolEl = modalEl.querySelector('#innovationLightboxSchool');
        this.categoryEl = modalEl.querySelector('#innovationLightboxCategory');
        this.bulletEl = modalEl.querySelector('.innovation-lightbox-meta-text .bullet');
        this.detailLink = modalEl.querySelector('#innovationLightboxDetailLink');
        this.feedbackEl = modalEl.querySelector('#innovationLightboxFeedback');
        this.prevBtn = modalEl.querySelector('#innovationLightboxPrev');
        this.nextBtn = modalEl.querySelector('#innovationLightboxNext');
        this.shareBtn = modalEl.querySelector('#innovationLightboxShare');

        this.zoomLevels = Array.isArray(this.options.zoomLevels) && this.options.zoomLevels.length
            ? this.options.zoomLevels
            : [1, 1.6, 2.2, 3];

        this.resetZoomState();

        this.state = {
            images: [],
            index: 0,
            title: '',
            school: '',
            category: '',
            detailUrl: ''
        };

        this.feedbackTimeoutId = null;
        this.pointerMoved = false;

        this.bindEvents();
    }

    Lightbox.prototype.bindEvents = function () {
        const modalEl = this.modalEl;

        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => {
                if (this.state.images.length <= 1) {
                    return;
                }
                this.showImageAt(this.state.index - 1);
            });
        }

        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => {
                if (this.state.images.length <= 1) {
                    return;
                }
                this.showImageAt(this.state.index + 1);
            });
        }

        if (this.shareBtn) {
            this.shareBtn.addEventListener('click', () => {
                this.handleShare();
            });
        }

        if (this.wrapperEl) {
            this.wrapperEl.setAttribute('role', 'button');
            this.wrapperEl.setAttribute('tabindex', '0');
            this.wrapperEl.setAttribute('aria-label', 'สลับการซูมของภาพ');
            this.wrapperEl.dataset.zoomLevel = '1';

            this.wrapperEl.addEventListener('click', (event) => {
                this.handleWrapperClick(event);
            });

            this.wrapperEl.addEventListener('keydown', (event) => {
                this.handleWrapperKeydown(event);
            });

            this.wrapperEl.addEventListener('pointerdown', (event) => {
                this.handlePointerDown(event);
            });

            this.wrapperEl.addEventListener('pointermove', (event) => {
                this.handlePointerMove(event);
            });

            this.wrapperEl.addEventListener('pointerup', (event) => {
                this.handlePointerEnd(event);
            });

            this.wrapperEl.addEventListener('pointercancel', (event) => {
                this.handlePointerEnd(event);
            });

            this.wrapperEl.addEventListener('pointerleave', (event) => {
                this.handlePointerEnd(event);
            });

            this.wrapperEl.addEventListener('wheel', (event) => {
                this.handleWheel(event);
            }, { passive: false });
        }

        modalEl.addEventListener('hidden.bs.modal', () => {
            this.clearFeedback();
            this.resetZoomState();
            this.state.images = [];
            this.state.index = 0;
            if (this.imageEl) {
                this.imageEl.src = '';
                this.imageEl.onload = null;
                this.imageEl.onerror = null;
            }
            window.removeEventListener('resize', this.handleResizeBound);
        });

        modalEl.addEventListener('keydown', (event) => {
            this.handleModalKeydown(event);
        });

        modalEl.addEventListener('shown.bs.modal', () => {
            this.clearFeedback();
            this.captureMeasurements();
            this.applyTransform();
            this.updateNavigation();
            if (this.shareBtn) {
                this.shareBtn.focus();
            }
            window.addEventListener('resize', this.handleResizeBound);
        });

        this.handleResizeBound = () => {
            if (!this.modalEl.classList.contains('show')) {
                return;
            }
            this.captureMeasurements();
            this.applyTransform();
        };
    };

    Lightbox.prototype.registerCard = function (card) {
        if (!card || card.dataset.lightboxBound === '1') {
            return;
        }

        card.dataset.lightboxBound = '1';

        card.addEventListener('click', (event) => {
            if (event.metaKey || event.ctrlKey) {
                return;
            }
            event.preventDefault();
            this.openFromCard(card);
        });

        card.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                this.openFromCard(card);
            }
        });
    };

    Lightbox.prototype.registerCards = function (selectorOrNodes) {
        if (!selectorOrNodes) {
            return;
        }

        let nodes = selectorOrNodes;
        if (typeof selectorOrNodes === 'string') {
            nodes = document.querySelectorAll(selectorOrNodes);
        }

        if (!nodes) {
            return;
        }

        nodes = nodes.length !== undefined ? nodes : [nodes];

        nodes.forEach((node) => this.registerCard(node));
    };

    Lightbox.prototype.parseImages = function (value, fallbackImg) {
        const raw = readDatasetJson(value);
        const items = [];

        raw.forEach((entry) => {
            if (!entry) {
                return;
            }

            if (typeof entry === 'string') {
                items.push({ url: entry, name: entry.split('/').pop() || 'image' });
                return;
            }

            if (entry.url) {
                items.push({ url: entry.url, name: entry.name || entry.url.split('/').pop() || 'image' });
            }
        });

        if (!items.length && fallbackImg) {
            items.push({
                url: fallbackImg.src,
                name: fallbackImg.alt || 'preview'
            });
        }

        return items;
    };

    Lightbox.prototype.openFromCard = function (card) {
        if (!card) {
            return;
        }

        const previewImg = card.querySelector('.card-figure img, .innovation-image img');
        const images = this.parseImages(card.dataset.images, previewImg);

        if (!images.length) {
            this.showFeedback('ไม่พบรูปภาพสำหรับนวัตกรรมนี้');
            return;
        }

        this.state.images = images;
        this.state.index = 0;
        this.state.title = card.getAttribute('data-title-display') || '';
        this.state.school = card.getAttribute('data-school-name') || '';
        this.state.category = card.getAttribute('data-category-label') || '';
        this.state.detailUrl = card.getAttribute('data-detail-url') || '';

        if (this.titleEl) {
            this.titleEl.textContent = this.state.title || 'นวัตกรรม';
        }

        if (this.schoolEl) {
            this.schoolEl.textContent = this.state.school;
            this.schoolEl.style.display = this.state.school ? 'inline' : 'none';
        }

        if (this.categoryEl) {
            this.categoryEl.textContent = this.state.category;
            this.categoryEl.style.display = this.state.category ? 'inline' : 'none';
        }

        if (this.bulletEl) {
            this.bulletEl.style.display = this.state.school && this.state.category ? 'inline-flex' : 'none';
        }

        if (this.detailLink) {
            if (this.state.detailUrl) {
                this.detailLink.href = this.state.detailUrl;
                this.detailLink.classList.remove('d-none');
            } else {
                this.detailLink.classList.add('d-none');
                this.detailLink.removeAttribute('href');
            }
        }

        this.showImageAt(0);
        if (this.bootstrapModal) {
            this.bootstrapModal.show();
        }
    };

    Lightbox.prototype.showImageAt = function (index) {
        if (!this.state.images.length || !this.imageEl) {
            return;
        }

        const normalizedIndex = ((index % this.state.images.length) + this.state.images.length) % this.state.images.length;
        this.state.index = normalizedIndex;

        const currentImage = this.state.images[this.state.index];
        if (!currentImage) {
            return;
        }

        const altParts = [this.state.title, this.state.school].filter(Boolean);

        this.resetZoomState();
        this.clearFeedback();

        this.imageEl.onload = () => {
            this.handleImageReady();
        };
        this.imageEl.onerror = () => {
            this.handleImageError();
        };
        this.imageEl.src = currentImage.url;
        this.imageEl.alt = altParts.length ? altParts.join(' - ') : 'innovation image';

        if (this.imageEl.complete && this.imageEl.naturalWidth) {
            this.handleImageReady();
        }
    };

    Lightbox.prototype.handleImageReady = function () {
        requestAnimationFrame(() => {
            this.captureMeasurements();
            this.applyTransform();
            this.updateNavigation();
        });
    };

    Lightbox.prototype.handleImageError = function () {
        this.showFeedback('ไม่สามารถโหลดรูปภาพได้');
    };

    Lightbox.prototype.handleShare = async function () {
        if (!this.state.images.length) {
            return;
        }

        const currentImage = this.state.images[this.state.index];
        if (!currentImage || !currentImage.url) {
            return;
        }

        const shareUrl = currentImage.url;
        this.clearFeedback();

        try {
            if (navigator.share) {
                await navigator.share({
                    title: this.state.title || 'Innovation image',
                    url: shareUrl
                });
                this.showFeedback('แชร์ลิงก์ภาพผ่านระบบแล้ว');
                return;
            }
        } catch (error) {
            // fallback will handle
        }

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(shareUrl)
                .then(() => {
                    this.showFeedback('คัดลอกลิงก์รูปภาพแล้ว');
                })
                .catch(() => {
                    this.fallbackCopy(shareUrl);
                });
        } else {
            this.fallbackCopy(shareUrl);
        }
    };

    Lightbox.prototype.fallbackCopy = function (url) {
        this.clearFeedback();
        const result = window.prompt('คัดลอกลิงก์รูปภาพ', url);
        if (result !== null) {
            this.showFeedback('คัดลอกลิงก์รูปภาพแล้ว');
        }
    };

    Lightbox.prototype.captureMeasurements = function () {
        if (!this.wrapperEl || !this.imageEl) {
            return;
        }

        const wrapperRect = this.wrapperEl.getBoundingClientRect();
        const imageRect = this.imageEl.getBoundingClientRect();
        const scale = this.zoomLevels[this.zoomIndex] || 1;

        if (!wrapperRect.width || !wrapperRect.height || !imageRect.width || !imageRect.height) {
            return;
        }

        this.baseDimensions = {
            width: imageRect.width / scale,
            height: imageRect.height / scale
        };

        this.wrapperDimensions = {
            width: wrapperRect.width,
            height: wrapperRect.height
        };
    };

    Lightbox.prototype.ensureMeasurements = function () {
        if (!this.wrapperDimensions.width || !this.baseDimensions.width) {
            this.captureMeasurements();
        }
    };

    Lightbox.prototype.calculatePanBounds = function (scale) {
        this.ensureMeasurements();
        if (!this.wrapperDimensions.width || !this.baseDimensions.width) {
            return { x: 0, y: 0 };
        }

        const width = this.baseDimensions.width * scale;
        const height = this.baseDimensions.height * scale;

        return {
            x: Math.max(0, (width - this.wrapperDimensions.width) / 2),
            y: Math.max(0, (height - this.wrapperDimensions.height) / 2)
        };
    };

    Lightbox.prototype.applyTransform = function () {
        if (!this.imageEl || !this.wrapperEl) {
            return;
        }

        const scale = this.zoomLevels[this.zoomIndex] || 1;
        const bounds = this.calculatePanBounds(scale);

        this.pan.x = clamp(this.pan.x, -bounds.x, bounds.x);
        this.pan.y = clamp(this.pan.y, -bounds.y, bounds.y);

        this.imageEl.style.transition = this.isPanning ? 'none' : 'transform 0.2s ease';
        this.imageEl.style.transform = `translate3d(${this.pan.x}px, ${this.pan.y}px, 0) scale(${scale})`;

        this.wrapperEl.classList.toggle('is-zoomed', scale > 1.0001);
        this.wrapperEl.classList.toggle('is-panning', !!this.isPanning);
        this.wrapperEl.dataset.zoomLevel = scale > 1.0001 ? scale.toFixed(2) : '1';
    };

    Lightbox.prototype.setZoomIndex = function (nextIndex, options) {
        const opts = Object.assign({ announce: true, anchor: null, force: false }, options || {});

        const clampedIndex = clamp(nextIndex, 0, this.zoomLevels.length - 1);
        const previousScale = this.zoomLevels[this.zoomIndex] || 1;
        const nextScale = this.zoomLevels[clampedIndex] || 1;
        const changed = clampedIndex !== this.zoomIndex;

        this.zoomIndex = clampedIndex;

        if (nextScale === 1) {
            this.pan = { x: 0, y: 0 };
        } else if (opts.anchor && previousScale !== nextScale) {
            const wrapperRect = this.wrapperEl ? this.wrapperEl.getBoundingClientRect() : null;
            if (wrapperRect) {
                const centerX = wrapperRect.left + wrapperRect.width / 2;
                const centerY = wrapperRect.top + wrapperRect.height / 2;
                const offsetX = opts.anchor.clientX - centerX;
                const offsetY = opts.anchor.clientY - centerY;
                const scaleFactor = nextScale / previousScale;
                this.pan.x = this.pan.x * scaleFactor + offsetX * (1 - scaleFactor);
                this.pan.y = this.pan.y * scaleFactor + offsetY * (1 - scaleFactor);
            }
        }

        this.ensureMeasurements();
        this.applyTransform();

        if ((changed || opts.force) && opts.announce) {
            if (nextScale === 1) {
                this.showFeedback('รีเซ็ตการซูมแล้ว');
            } else {
                this.showFeedback(`ระดับการซูม ${nextScale.toFixed(1)}×`);
            }
        } else if (!changed && opts.announce && nextScale > 1) {
            this.showFeedback('ถึงระดับซูมสูงสุดแล้ว');
        }

        return changed;
    };

    Lightbox.prototype.adjustZoom = function (delta, options) {
        const opts = Object.assign({ anchor: null, wrap: false, announce: true }, options || {});
        let targetIndex = this.zoomIndex + delta;

        if (opts.wrap) {
            if (targetIndex > this.zoomLevels.length - 1) {
                targetIndex = 0;
            }
            if (targetIndex < 0) {
                targetIndex = this.zoomLevels.length - 1;
            }
        }

        const changed = this.setZoomIndex(targetIndex, opts);

        if (!changed && opts.announce && !opts.wrap) {
            this.showFeedback(delta > 0 ? 'ถึงระดับซูมสูงสุดแล้ว' : 'ถึงระดับซูมต่ำสุดแล้ว');
        }
    };

    Lightbox.prototype.resetZoomState = function () {
        this.zoomIndex = 0;
        this.pan = { x: 0, y: 0 };
        this.panStart = { x: 0, y: 0 };
        this.pointerStart = { x: 0, y: 0 };
        this.activePointerId = null;
        this.isPanning = false;
        this.pointerMoved = false;
        this.baseDimensions = { width: 0, height: 0 };
        this.wrapperDimensions = { width: 0, height: 0 };

        if (this.wrapperEl) {
            this.wrapperEl.classList.remove('is-zoomed', 'is-panning');
            this.wrapperEl.dataset.zoomLevel = '1';
        }

        if (this.imageEl) {
            this.imageEl.style.transition = '';
            this.imageEl.style.transform = 'translate3d(0, 0, 0) scale(1)';
        }
    };

    Lightbox.prototype.handlePointerDown = function (event) {
        if (!this.wrapperEl) {
            return;
        }

        if (event.pointerType === 'mouse' && event.button !== 0) {
            return;
        }

        this.pointerMoved = false;

        if (this.zoomLevels[this.zoomIndex] === 1) {
            return;
        }

        this.isPanning = true;
        this.activePointerId = event.pointerId;
        this.panStart = Object.assign({}, this.pan);
        this.pointerStart = { x: event.clientX, y: event.clientY };

        try {
            this.wrapperEl.setPointerCapture(event.pointerId);
        } catch (error) {
            // ignore
        }

        if (event.pointerType === 'touch') {
            event.preventDefault();
        }

        this.wrapperEl.classList.add('is-panning');
    };

    Lightbox.prototype.handlePointerMove = function (event) {
        if (!this.isPanning || event.pointerId !== this.activePointerId) {
            return;
        }

        if (event.pointerType === 'touch') {
            event.preventDefault();
        }

        const deltaX = event.clientX - this.pointerStart.x;
        const deltaY = event.clientY - this.pointerStart.y;

        if (!this.pointerMoved && Math.abs(deltaX) + Math.abs(deltaY) > 4) {
            this.pointerMoved = true;
        }

        this.pan = {
            x: this.panStart.x + deltaX,
            y: this.panStart.y + deltaY
        };

        this.applyTransform();
    };

    Lightbox.prototype.handlePointerEnd = function (event) {
        if (this.activePointerId === null || event.pointerId !== this.activePointerId) {
            return;
        }

        if (event.pointerType === 'touch') {
            event.preventDefault();
        }

        this.isPanning = false;
        this.activePointerId = null;
        if (this.wrapperEl) {
            this.wrapperEl.classList.remove('is-panning');
            if (typeof this.wrapperEl.releasePointerCapture === 'function') {
                try {
                    this.wrapperEl.releasePointerCapture(event.pointerId);
                } catch (error) {
                    // ignore
                }
            }
        }

        this.applyTransform();
    };

    Lightbox.prototype.handleWrapperClick = function (event) {
        if (!this.state.images.length) {
            return;
        }

        if (event.target !== this.wrapperEl && event.target !== this.imageEl) {
            return;
        }

        if (this.pointerMoved) {
            this.pointerMoved = false;
            return;
        }

        const anchor = { clientX: event.clientX, clientY: event.clientY };

        if (event.shiftKey || event.altKey) {
            this.adjustZoom(-1, { anchor, announce: true });
        } else {
            this.adjustZoom(1, { anchor, wrap: true, announce: true });
        }
    };

    Lightbox.prototype.handleWrapperKeydown = function (event) {
        if (!this.state.images.length) {
            return;
        }

        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            if (event.shiftKey) {
                this.adjustZoom(-1, { announce: true });
            } else {
                this.adjustZoom(1, { wrap: true, announce: true });
            }
        } else if ((event.key === '0' || event.key === ')') && (event.ctrlKey || event.metaKey)) {
            event.preventDefault();
            this.setZoomIndex(0, { announce: true, force: true });
        }
    };

    Lightbox.prototype.handleWheel = function (event) {
        if (!this.state.images.length) {
            return;
        }

        event.preventDefault();
        const anchor = { clientX: event.clientX, clientY: event.clientY };
        if (event.deltaY < 0) {
            this.adjustZoom(1, { anchor, announce: true });
        } else {
            this.adjustZoom(-1, { anchor, announce: true });
        }
    };

    Lightbox.prototype.handleModalKeydown = function (event) {
        const key = event.key;

        if (key === 'ArrowLeft' && this.state.images.length > 1) {
            event.preventDefault();
            this.showImageAt(this.state.index - 1);
            return;
        }

        if (key === 'ArrowRight' && this.state.images.length > 1) {
            event.preventDefault();
            this.showImageAt(this.state.index + 1);
            return;
        }

        if (key === '+' || key === '=') {
            event.preventDefault();
            this.adjustZoom(1, { announce: true });
            return;
        }

        if (key === '-' || key === '_') {
            event.preventDefault();
            this.adjustZoom(-1, { announce: true });
            return;
        }

        if ((key === '0' || key === ')') && (event.ctrlKey || event.metaKey)) {
            event.preventDefault();
            this.setZoomIndex(0, { announce: true, force: true });
        }
    };

    Lightbox.prototype.clearFeedback = function () {
        if (!this.feedbackEl) {
            return;
        }
        if (this.feedbackTimeoutId) {
            clearTimeout(this.feedbackTimeoutId);
            this.feedbackTimeoutId = null;
        }
        this.feedbackEl.textContent = '';
    };

    Lightbox.prototype.showFeedback = function (message) {
        if (!this.feedbackEl) {
            return;
        }
        this.clearFeedback();
        this.feedbackEl.textContent = message;
        this.feedbackTimeoutId = setTimeout(() => {
            this.feedbackEl.textContent = '';
        }, 2500);
    };

    Lightbox.prototype.updateNavigation = function () {
        const multiple = this.state.images.length > 1;
        if (this.prevBtn) {
            this.prevBtn.classList.toggle('is-hidden', !multiple);
            this.prevBtn.disabled = !multiple;
        }
        if (this.nextBtn) {
            this.nextBtn.classList.toggle('is-hidden', !multiple);
            this.nextBtn.disabled = !multiple;
        }
    };

    const instances = new WeakMap();

    function resolveModalElement(modal) {
        if (!modal) {
            return null;
        }
        if (typeof modal === 'string') {
            return document.querySelector(modal);
        }
        return modal;
    }

    global.InnovationLightbox = {
        create(modal, options) {
            const modalEl = resolveModalElement(modal);
            if (!modalEl) {
                return null;
            }
            if (instances.has(modalEl)) {
                return instances.get(modalEl);
            }
            const instance = new Lightbox(modalEl, options);
            instances.set(modalEl, instance);
            return instance;
        }
    };
})(window);
