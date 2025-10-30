// Publications JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // File input preview
    const pdfFileInput = document.getElementById('pdf_file');
    const filePreview = document.getElementById('file-preview');
    
    if (pdfFileInput) {
        pdfFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Validate file type
                if (file.type !== 'application/pdf') {
                    alert('กรุณาเลือกไฟล์ PDF เท่านั้น');
                    e.target.value = '';
                    filePreview.classList.remove('show');
                    return;
                }
                
                // Validate file size (10MB)
                const maxSize = 10 * 1024 * 1024; // 10MB in bytes
                if (file.size > maxSize) {
                    alert('ขนาดไฟล์เกิน 10 MB กรุณาเลือกไฟล์ที่มีขนาดเล็กกว่า');
                    e.target.value = '';
                    filePreview.classList.remove('show');
                    return;
                }
                
                // Show file preview
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                filePreview.innerHTML = `
                    <div class="file-info">
                        <div class="file-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="file-details">
                            <div class="file-name">${file.name}</div>
                            <div class="file-size">ขนาด: ${fileSizeMB} MB</div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFileInput()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                filePreview.classList.add('show');
            } else {
                filePreview.classList.remove('show');
            }
        });
    }

    // Form validation
    const form = document.getElementById('publicationForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            
            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>กำลังบันทึก...';
        });
    }

    // Auto-hide alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            if (bootstrap && bootstrap.Alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);
});

// Clear file input
function clearFileInput() {
    const fileInput = document.getElementById('pdf_file');
    const filePreview = document.getElementById('file-preview');
    
    if (fileInput) {
        fileInput.value = '';
    }
    if (filePreview) {
        filePreview.classList.remove('show');
    }
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}
