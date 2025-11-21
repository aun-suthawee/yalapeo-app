(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', initInnovationDashboard);

	let currentPage = 1;
	let isLoading = false;
	let hasMoreData = true;
	const currentFilters = {
		search: '',
		category: ''
	};
	let innovationLightboxController = null;

	function initInnovationDashboard() {
		const grid = document.getElementById('innovationGrid');
		if (!grid) {
			return;
		}

		toggleEmptyState(grid.children.length === 0);

		const searchInput = document.getElementById('innovationSearch');
		if (searchInput) {
			currentFilters.search = searchInput.value.trim();
		}

		const activeCategory = document.querySelector('#innovationCategories .category-chip.is-active');
		currentFilters.category = activeCategory ? (activeCategory.dataset.category || '') : '';

		const initialTotal = Number(grid.dataset.initialTotal || grid.children.length || 0);
		const initialCount = Number(grid.dataset.initialCount || grid.children.length || 0);
		hasMoreData = initialCount < initialTotal;

		initSearch();
		initCategoryFilters();
		initInfiniteScroll();
		initInnovationLightbox();

		if (innovationLightboxController) {
			innovationLightboxController.registerCards(document.querySelectorAll('.innovation-card'));
		}
	}

	function initSearch() {
		const searchInput = document.getElementById('innovationSearch');
		if (!searchInput) {
			return;
		}

		let debounceId;
		searchInput.addEventListener('input', (event) => {
			clearTimeout(debounceId);
			debounceId = setTimeout(() => {
				currentFilters.search = event.target.value.trim();
				resetAndReload();
			}, 450);
		});
	}

	function initCategoryFilters() {
		const buttons = document.querySelectorAll('#innovationCategories .category-chip');
		if (!buttons.length) {
			return;
		}

		buttons.forEach((button) => {
			button.addEventListener('click', () => {
				buttons.forEach((chip) => chip.classList.remove('is-active'));
				button.classList.add('is-active');
				currentFilters.category = button.dataset.category || '';
				resetAndReload();
			});
		});
	}

	function initInfiniteScroll() {
		window.addEventListener('scroll', () => {
			if (isLoading || !hasMoreData) {
				return;
			}

			const threshold = document.documentElement.scrollHeight * 0.8;
			if (window.innerHeight + window.scrollY >= threshold) {
				currentPage += 1;
				loadInnovations(false);
			}
		});
	}

	function initInnovationLightbox() {
		const modalEl = document.getElementById('innovationLightboxModal');
		if (!modalEl || typeof window.InnovationLightbox === 'undefined') {
			return;
		}

		innovationLightboxController = window.InnovationLightbox.create(modalEl);
		if (!innovationLightboxController || typeof innovationLightboxController.registerCards !== 'function') {
			innovationLightboxController = null;
		}
	}

	function resetAndReload() {
		currentPage = 1;
		hasMoreData = true;
		hideNoMoreData();

		const grid = document.getElementById('innovationGrid');
		if (grid) {
			grid.innerHTML = '';
		}

		loadInnovations(true);
	}

	function loadInnovations(isInitialLoad) {
		if (isLoading) {
			return;
		}

		const grid = document.getElementById('innovationGrid');
		if (!grid) {
			return;
		}

		isLoading = true;
		showLoading();

		const perPage = getPerPageCount();
		const params = new URLSearchParams({
			page: String(currentPage),
			perPage: String(perPage),
			search: currentFilters.search || '',
			category: currentFilters.category || ''
		});

		const endpoint = grid.dataset.endpoint || window.location.pathname;
		const url = new URL(endpoint, window.location.origin);
		['page', 'perPage', 'search', 'category'].forEach((key) => url.searchParams.delete(key));
		params.forEach((value, key) => {
			if (value) {
				url.searchParams.set(key, value);
			} else {
				url.searchParams.delete(key);
			}
		});

		fetch(url.toString(), {
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'Accept': 'application/json'
			},
			cache: 'no-store'
		})
			.then((response) => {
				if (!response.ok) {
					throw new Error(`HTTP ${response.status}`);
				}
				return response.json();
			})
			.then((payload) => {
				const innovations = Array.isArray(payload.innovations) ? payload.innovations : [];
				const replace = isInitialLoad || currentPage === 1;
				const newCards = renderInnovations(innovations, { replace });

				const totalCount = typeof payload.total === 'number'
					? payload.total
					: (replace ? innovations.length : grid.children.length);

				updateResultsCount(totalCount);

				hasMoreData = Boolean(payload.hasMore);
				if (hasMoreData) {
					hideNoMoreData();
				} else if (grid.children.length > 0) {
					showNoMoreData();
				}

				if (innovationLightboxController && newCards.length) {
					innovationLightboxController.registerCards(newCards);
				}
			})
			.catch((error) => {
				console.error('Innovation dashboard load failed:', error);
				if (!isInitialLoad) {
					currentPage = Math.max(1, currentPage - 1);
				}
			})
			.finally(() => {
				isLoading = false;
				hideLoading();
			});
	}

	function renderInnovations(items, options) {
		const grid = document.getElementById('innovationGrid');
		if (!grid) {
			return [];
		}

		const replace = Boolean(options && options.replace);
		if (replace) {
			grid.innerHTML = '';
		}

		if (!items.length) {
			toggleEmptyState(grid.children.length === 0);
			return [];
		}

		const assets = {
			placeholder: grid.dataset.placeholder || '',
			detailBase: grid.dataset.detailBase || '/sandbox/schools',
			imageBase: grid.dataset.imageBase || '/sandbox/innovation-image'
		};

		const fragment = document.createDocumentFragment();
		const newCards = [];

		items.forEach((item) => {
			const card = buildInnovationCard(item, assets);
			newCards.push(card);
			fragment.appendChild(card);
		});

		grid.appendChild(fragment);
		toggleEmptyState(false);
		return newCards;
	}

	function buildInnovationCard(item, assets) {
		const card = document.createElement('a');
		const schoolName = stripTags((item.school && item.school.name) || '') || 'ไม่พบข้อมูลโรงเรียน';
		const title = stripTags(item.title || '') || 'นวัตกรรม';
		const description = stripTags(item.description || '');
		const categoryName = item.category || 'ไม่ระบุหมวดหมู่';
		const detailBase = assets.detailBase.replace(/\/$/, '');
		const detailUrl = `${detailBase}/${item.school_id}/innovations?highlight=${encodeURIComponent(item.id)}`;
		const lightboxImages = buildLightboxImages(item, assets);
		const primaryImage = resolvePrimaryImage(item, assets, lightboxImages);

		card.className = 'innovation-card';
		card.href = detailUrl;
		card.setAttribute('role', 'button');
		card.setAttribute('tabindex', '0');
		card.setAttribute('aria-label', `ดูนวัตกรรม ${title}`);

		card.dataset.title = title.toLowerCase();
		card.dataset.category = categoryName.toLowerCase();
		card.dataset.school = schoolName.toLowerCase();
		card.dataset.description = description.toLowerCase();
		card.dataset.active = item.is_active ? '1' : '0';
		card.dataset.titleDisplay = title;
		card.dataset.schoolName = schoolName;
		card.dataset.categoryLabel = categoryName;
		card.dataset.detailUrl = detailUrl;
		card.dataset.images = JSON.stringify(lightboxImages);

		const yearMarkup = item.year
			? `<span class="meta-pill"><i class="far fa-calendar"></i> พ.ศ. ${escapeHtml(String(item.year))}</span>`
			: '';

		const descriptionMarkup = description
			? `<p class="innovation-description">${escapeHtml(limitText(description, 120))}</p>`
			: '';

		card.innerHTML = `
			<div class="card-figure">
				<img src="${escapeAttribute(primaryImage.src)}" alt="${escapeAttribute(title)}" loading="lazy">
				<span class="figure-badge">${escapeHtml(categoryName)}</span>
				<div class="figure-overlay"></div>
			</div>
			<div class="card-body">
				<div class="card-heading">
					<span class="school-name">${escapeHtml(schoolName)}</span>
					<h3 class="innovation-title">${escapeHtml(title)}</h3>
				</div>
				${descriptionMarkup}
				<div class="card-footer">
					${yearMarkup}
					<span class="meta-pill"><i class="far fa-clock"></i> ${escapeHtml(formatDate(item.created_at))}</span>
					<span class="cta">ดูรายละเอียด <i class="fas fa-arrow-right"></i></span>
				</div>
			</div>
		`;

		return card;
	}

	function buildLightboxImages(item, assets) {
		const imageBase = assets.imageBase.replace(/\/$/, '');
		const paths = Array.isArray(item.image_paths) ? item.image_paths : [];
		return paths
			.map((path) => {
				const fileName = extractFileName(path);
				if (!fileName) {
					return null;
				}
				const ext = fileName.split('.').pop();
				if (ext && ext.toLowerCase() === 'pdf') {
					return null;
				}
				const encoded = encodeURIComponent(fileName);
				return {
					url: `${imageBase}/${item.school_id}/${encoded}`,
					name: fileName
				};
			})
			.filter(Boolean);
	}

	function resolvePrimaryImage(item, assets, lightboxImages) {
		if (lightboxImages.length) {
			return { src: lightboxImages[0].url };
		}

		const placeholder = assets.placeholder || '';
		const paths = Array.isArray(item.image_paths) ? item.image_paths : [];
		if (!paths.length) {
			return { src: placeholder };
		}

		const fileName = extractFileName(paths[0]);
		if (!fileName) {
			return { src: placeholder };
		}

		const ext = fileName.split('.').pop();
		if (ext && ext.toLowerCase() === 'pdf') {
			return { src: placeholder };
		}

		const imageBase = assets.imageBase.replace(/\/$/, '');
		const encoded = encodeURIComponent(fileName);
		return { src: `${imageBase}/${item.school_id}/${encoded}` };
	}

	function extractFileName(path) {
		if (!path) {
			return '';
		}
		const segments = String(path).split('/');
		return segments.length ? segments.pop() : String(path);
	}

	function toggleEmptyState(isEmpty) {
		const grid = document.getElementById('innovationGrid');
		const emptyState = document.getElementById('innovationEmpty');

		if (grid) {
			grid.style.display = isEmpty ? 'none' : '';
		}

		if (emptyState) {
			emptyState.style.display = isEmpty ? 'block' : 'none';
		}
	}

	function showLoading() {
		let indicator = document.getElementById('innovationLoadingIndicator');
		if (!indicator) {
			indicator = document.createElement('div');
			indicator.id = 'innovationLoadingIndicator';
			indicator.className = 'loading-indicator';
			indicator.innerHTML = `
				<div class="spinner"></div>
				<p>กำลังโหลดนวัตกรรม...</p>
			`;
			const grid = document.getElementById('innovationGrid');
			if (grid && grid.parentNode) {
				grid.parentNode.insertBefore(indicator, grid.nextSibling);
			}
		}
		indicator.style.display = 'block';
	}

	function hideLoading() {
		const indicator = document.getElementById('innovationLoadingIndicator');
		if (indicator) {
			indicator.style.display = 'none';
		}
	}

	function showNoMoreData() {
		let message = document.getElementById('innovationNoMoreData');
		if (!message) {
			message = document.createElement('div');
			message.id = 'innovationNoMoreData';
			message.className = 'no-more-data';
			message.innerHTML = '<p>แสดงนวัตกรรมครบทั้งหมดแล้ว</p>';
			const indicator = document.getElementById('innovationLoadingIndicator');
			if (indicator && indicator.parentNode) {
				indicator.parentNode.insertBefore(message, indicator.nextSibling);
			}
		}
		message.style.display = 'block';
	}

	function hideNoMoreData() {
		const message = document.getElementById('innovationNoMoreData');
		if (message) {
			message.style.display = 'none';
		}
	}

	function updateResultsCount(count) {
		const countEl = document.getElementById('innovationResultsCount');
		if (countEl) {
			countEl.textContent = formatNumber(count);
		}
	}

	function getPerPageCount() {
		const width = window.innerWidth;
		if (width >= 1200) {
			return 16;
		}
		if (width >= 768) {
			return 12;
		}
		if (width >= 576) {
			return 8;
		}
		return 5;
	}

	function formatDate(dateString) {
		if (!dateString) {
			return '-';
		}
		const date = new Date(dateString);
		if (Number.isNaN(date.getTime())) {
			return '-';
		}
		const day = String(date.getDate()).padStart(2, '0');
		const month = String(date.getMonth() + 1).padStart(2, '0');
		const year = date.getFullYear();
		return `${day}/${month}/${year}`;
	}

	function formatNumber(value) {
		const number = Number(value) || 0;
		return new Intl.NumberFormat('th-TH').format(number);
	}

	function limitText(text, limit) {
		if (!text) {
			return '';
		}
		return text.length <= limit ? text : `${text.slice(0, limit)}...`;
	}

	function stripTags(html) {
		if (!html) {
			return '';
		}
		const temp = document.createElement('div');
		temp.innerHTML = html;
		return temp.textContent || temp.innerText || '';
	}

	function escapeHtml(text) {
		if (!text) {
			return '';
		}
		const replacements = {
			'&': '&amp;',
			'<': '&lt;',
			'>': '&gt;',
			'"': '&quot;',
			"'": '&#039;'
		};
		return String(text).replace(/[&<>"']/g, (char) => replacements[char] || char);
	}

	function escapeAttribute(text) {
		return escapeHtml(text);
	}
})();
