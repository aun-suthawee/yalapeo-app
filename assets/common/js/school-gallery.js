/**
 * School Gallery JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Extract and preview Folder ID
    const driveUrlInput = document.getElementById('drive_folder_url');
    if (driveUrlInput) {
        driveUrlInput.addEventListener('input', function() {
            const url = this.value.trim();
            const folderId = extractFolderId(url);
            
            // Show preview or validation message
            const existingPreview = document.getElementById('folder-id-preview');
            if (existingPreview) {
                existingPreview.remove();
            }

            if (url && folderId) {
                const previewDiv = document.createElement('div');
                previewDiv.id = 'folder-id-preview';
                previewDiv.className = 'alert alert-success mt-2';
                previewDiv.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    <strong>Folder ID ที่ตรวจพบ:</strong> <code>${folderId}</code>
                `;
                this.parentElement.appendChild(previewDiv);
            } else if (url) {
                const errorDiv = document.createElement('div');
                errorDiv.id = 'folder-id-preview';
                errorDiv.className = 'alert alert-danger mt-2';
                errorDiv.innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>ไม่พบ Folder ID</strong> กรุณาตรวจสอบ URL อีกครั้ง
                `;
                this.parentElement.appendChild(errorDiv);
            }
        });
    }

    // Confirm delete with custom message
    const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const galleryTitle = this.closest('.gallery-card')?.querySelector('.gallery-title')?.textContent.trim();
            if (galleryTitle) {
                e.preventDefault();
                if (confirm(`คุณแน่ใจหรือไม่ที่จะลบคลังภาพ "${galleryTitle}"?\n\nการดำเนินการนี้ไม่สามารถย้อนกลับได้`)) {
                    this.submit();
                }
            }
        });
    });

    // Smooth scroll to top when navigating
    const navLinks = document.querySelectorAll('.breadcrumb a, .btn');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.getAttribute('href') && !this.getAttribute('href').startsWith('#')) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });

    // Handle iframe loading
    const galleryIframe = document.querySelector('.gallery-iframe');
    if (galleryIframe) {
        galleryIframe.addEventListener('load', function() {
            console.log('Gallery iframe loaded successfully');
        });

        galleryIframe.addEventListener('error', function() {
            const loadingOverlay = document.getElementById('galleryLoading');
            if (loadingOverlay) {
                loadingOverlay.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>เกิดข้อผิดพลาด</strong><br>
                        ไม่สามารถโหลดคลังภาพได้ กรุณาตรวจสอบว่าโฟลเดอร์ใน Google Drive ได้ตั้งค่าให้เป็น Public แล้ว
                    </div>
                `;
            }
        });
    }

    // Form validation enhancement
    const forms = document.querySelectorAll('form[action*="galleries"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังบันทึก...';
                
                // Re-enable after 5 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> บันทึก';
                }, 5000);
            }
        });
    });

    // Display order input helper
    const displayOrderInput = document.getElementById('display_order');
    if (displayOrderInput) {
        displayOrderInput.addEventListener('input', function() {
            const value = parseInt(this.value);
            if (value < 0) {
                this.value = 0;
            }
        });
    }

    // Add tooltip to buttons
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

/**
 * Extract Folder ID from Google Drive URL
 * @param {string} url - Google Drive folder URL or ID
 * @returns {string|null} - Folder ID or null if not found
 */
function extractFolderId(url) {
    if (!url) return null;
    
    // Pattern: https://drive.google.com/drive/folders/FOLDER_ID or FOLDER_ID?usp=sharing
    const folderPattern = /\/folders\/([a-zA-Z0-9_-]+)/;
    const match = url.match(folderPattern);
    
    if (match && match[1]) {
        return match[1];
    }
    
    // If already just the ID (alphanumeric with dashes and underscores)
    if (/^[a-zA-Z0-9_-]+$/.test(url.trim())) {
        return url.trim();
    }
    
    return null;
}

/**
 * Copy text to clipboard
 * @param {string} text - Text to copy
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('คัดลอกลง clipboard แล้ว');
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}
