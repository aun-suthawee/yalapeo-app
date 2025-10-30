// School Innovation Form JavaScript
document.addEventListener('DOMContentLoaded', function() {
    initializeFileUpload();
    initializeFormValidation();
    initializeFormInteractions();
});

// File Upload Handler (Images and PDFs)
function initializeFileUpload() {
    const fileInput = document.getElementById('files');
    const filePreviewContainer = document.getElementById('filePreviewContainer');
    
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            console.log('Files selected:', files.length);
            
            if (files.length === 0) {
                // Reset to default state when no files
                const uploadInfo = document.querySelector('.file-upload-info span');
                const uploadWrapper = document.querySelector('.file-upload-wrapper');
                if (uploadInfo) {
                    uploadInfo.textContent = 'เลือกไฟล์ (JPG, PNG, GIF, WEBP, PDF ขนาดไม่เกิน 5MB ต่อไฟล์ สูงสุด 5 ไฟล์)';
                }
                if (uploadWrapper) {
                    uploadWrapper.classList.remove('files-selected');
                }
                filePreviewContainer.innerHTML = '';
                return;
            }
            
            // Validate file count
            if (files.length > 5) {
                showAlert('สามารถอัปโหลดได้สูงสุด 5 ไฟล์', 'error');
                fileInput.value = '';
                return;
            }
            
            // Clear previous previews
            filePreviewContainer.innerHTML = '';
            
            let validFiles = [];
            
            files.forEach((file, index) => {
                console.log(`Processing file ${index + 1}:`, file.name, file.type);
                
                // Validate file type
                const isImage = file.type.startsWith('image/');
                const isPDF = file.type === 'application/pdf';
                
                if (!isImage && !isPDF) {
                    showAlert(`ไฟล์ ${file.name} ไม่ใช่รูปภาพหรือ PDF`, 'error');
                    return;
                }
                
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    showAlert(`ไฟล์ ${file.name} มีขนาดเกิน 5MB`, 'error');
                    return;
                }
                
                validFiles.push(file);
                createFilePreview(file, index, filePreviewContainer);
            });
            
            // Update upload info text
            const uploadInfo = document.querySelector('.file-upload-info span');
            const uploadWrapper = document.querySelector('.file-upload-wrapper');
            if (uploadInfo) {
                if (validFiles.length > 0) {
                    uploadInfo.textContent = `เลือกแล้ว: ${validFiles.length} ไฟล์`;
                    if (uploadWrapper) uploadWrapper.classList.add('files-selected');
                } else {
                    uploadInfo.textContent = 'เลือกไฟล์ (JPG, PNG, GIF, WEBP, PDF ขนาดไม่เกิน 5MB ต่อไฟล์ สูงสุด 5 ไฟล์)';
                    if (uploadWrapper) uploadWrapper.classList.remove('files-selected');
                }
            }
        });
    }
}

// Create file preview
function createFilePreview(file, index, container) {
    const previewDiv = document.createElement('div');
    previewDiv.className = 'file-preview-item';
    previewDiv.dataset.index = index;
    
    const isImage = file.type.startsWith('image/');
    const isPDF = file.type === 'application/pdf';
    
    if (isImage) {
        // Image preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewDiv.innerHTML = `
                <div class="file-preview-content">
                    <img src="${e.target.result}" alt="Preview" class="preview-image">
                    <div class="file-info">
                        <span class="file-name">${file.name}</span>
                        <span class="file-size">${formatFileSize(file.size)}</span>
                        <span class="file-type-badge image">รูปภาพ</span>
                    </div>
                    <button type="button" class="remove-file-btn" onclick="removeFilePreview(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    } else if (isPDF) {
        // PDF preview - show icon immediately
        previewDiv.innerHTML = `
            <div class="file-preview-content">
                <div class="pdf-preview">
                    <i class="fas fa-file-pdf pdf-icon"></i>
                    <canvas class="pdf-thumbnail" id="pdf-canvas-${index}" style="display: none;"></canvas>
                </div>
                <div class="file-info">
                    <span class="file-name">${file.name}</span>
                    <span class="file-size">${formatFileSize(file.size)}</span>
                    <span class="file-type-badge pdf">PDF</span>
                </div>
                <button type="button" class="remove-file-btn" onclick="removeFilePreview(${index})">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        // Try to generate PDF thumbnail (optional, fallback to icon if fails)
        setTimeout(() => {
            generatePDFThumbnail(file, `pdf-canvas-${index}`);
        }, 100);
    }
    
    container.appendChild(previewDiv);
}

// Generate PDF thumbnail using PDF.js (if available)
function generatePDFThumbnail(file, canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const pdfIcon = canvas.parentElement.querySelector('.pdf-icon');
    
    // Check if PDF.js is available
    if (typeof pdfjsLib !== 'undefined') {
        const fileReader = new FileReader();
        fileReader.onload = function() {
            try {
                const typedarray = new Uint8Array(this.result);
                
                pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
                    pdf.getPage(1).then(function(page) {
                        const viewport = page.getViewport({ scale: 0.3 });
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        canvas.style.display = 'block';
                        
                        const renderContext = {
                            canvasContext: ctx,
                            viewport: viewport
                        };
                        
                        page.render(renderContext).promise.then(function() {
                            // Hide PDF icon when thumbnail is ready
                            if (pdfIcon) pdfIcon.style.display = 'none';
                        });
                    });
                }).catch(function(error) {
                    console.log('Error loading PDF:', error);
                    // Keep PDF icon visible
                    canvas.style.display = 'none';
                });
            } catch (error) {
                console.log('Error processing PDF file:', error);
                canvas.style.display = 'none';
            }
        };
        fileReader.readAsArrayBuffer(file);
    } else {
        // PDF.js not available, just show icon
        console.log('PDF.js not available, showing icon only');
        canvas.style.display = 'none';
    }
}

// Remove file preview
function removeFilePreview(index) {
    const previewItem = document.querySelector(`[data-index="${index}"]`);
    if (previewItem) {
        previewItem.remove();
    }
    
    // Update file input (remove file from list)
    const fileInput = document.getElementById('files');
    if (fileInput) {
        const dt = new DataTransfer();
        const files = fileInput.files;
        
        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }
        
        fileInput.files = dt.files;
        
        // Update upload info
        const uploadInfo = document.querySelector('.file-upload-info span');
        const uploadWrapper = document.querySelector('.file-upload-wrapper');
        if (uploadInfo) {
            if (dt.files.length > 0) {
                uploadInfo.textContent = `เลือกแล้ว: ${dt.files.length} ไฟล์`;
                if (uploadWrapper) uploadWrapper.classList.add('files-selected');
            } else {
                uploadInfo.textContent = 'เลือกไฟล์ (JPG, PNG, GIF, WEBP, PDF ขนาดไม่เกิน 5MB ต่อไฟล์ สูงสุด 5 ไฟล์)';
                if (uploadWrapper) uploadWrapper.classList.remove('files-selected');
            }
        }
    }
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Remove Image Preview
function removeImagePreview() {
    const fileInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const uploadInfo = document.querySelector('.file-upload-info span');
    
    fileInput.value = '';
    imagePreview.style.display = 'none';
    
    if (uploadInfo) {
        uploadInfo.textContent = 'เลือกไฟล์รูปภาพ (JPG, PNG, GIF ขนาดไม่เกิน 5MB)';
    }
}

// Form Validation
function initializeFormValidation() {
    const form = document.querySelector('.innovation-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });
        
        // Real-time validation
        const requiredFields = form.querySelectorAll('input[required], textarea[required], select[required]');
        requiredFields.forEach(field => {
            field.addEventListener('blur', function() {
                validateField(field);
            });
            
            field.addEventListener('input', function() {
                if (field.classList.contains('is-invalid')) {
                    validateField(field);
                }
            });
        });
    }
}

// Validate individual field
function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let errorMessage = '';
    
    // Check required fields
    if (field.required && !value) {
        isValid = false;
        errorMessage = 'กรุณากรอกข้อมูลในช่องนี้';
    }
    
    // Specific field validations
    switch (field.name) {
        case 'title':
            if (value && value.length < 3) {
                isValid = false;
                errorMessage = 'ชื่อนวัตกรรมต้องมีอย่างน้อย 3 ตัวอักษร';
            } else if (value && value.length > 255) {
                isValid = false;
                errorMessage = 'ชื่อนวัตกรรมต้องไม่เกิน 255 ตัวอักษร';
            }
            break;
            
        case 'description':
            if (value && value.length > 2000) {
                isValid = false;
                errorMessage = 'รายละเอียดต้องไม่เกิน 2000 ตัวอักษร';
            }
            break;
            
        case 'year':
            if (value) {
                const year = parseInt(value);
                const currentYear = new Date().getFullYear() + 543; // Convert to Buddhist year
                const maxYear = 2573; // พ.ศ. 2573 = ค.ศ. 2030
                const minYear = currentYear - 20; // ย้อนหลัง 20 ปี
                
                if (year > maxYear) {
                    isValid = false;
                    errorMessage = 'ปีต้องไม่เกิน พ.ศ. 2573';
                } else if (year < minYear) {
                    isValid = false;
                    errorMessage = `ปีต้องไม่น้อยกว่า พ.ศ. ${minYear}`;
                }
            }
            break;
    }
    
    // Update field appearance
    if (isValid) {
        field.classList.remove('is-invalid');
        hideFieldError(field);
    } else {
        field.classList.add('is-invalid');
        showFieldError(field, errorMessage);
    }
    
    return isValid;
}

// Show field error
function showFieldError(field, message) {
    let errorElement = field.parentNode.querySelector('.invalid-feedback');
    
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'invalid-feedback';
        field.parentNode.appendChild(errorElement);
    }
    
    errorElement.textContent = message;
}

// Hide field error
function hideFieldError(field) {
    const errorElement = field.parentNode.querySelector('.invalid-feedback');
    if (errorElement) {
        errorElement.remove();
    }
}

// Validate entire form
function validateForm() {
    const form = document.querySelector('.innovation-form');
    const requiredFields = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        showAlert('กรุณาตรวจสอบข้อมูลที่กรอกให้ถูกต้อง', 'error');
        // Focus on first invalid field
        const firstInvalidField = form.querySelector('.is-invalid');
        if (firstInvalidField) {
            firstInvalidField.focus();
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    
    return isValid;
}

// Form Interactions
function initializeFormInteractions() {
    // Character counter for textarea
    const textarea = document.getElementById('description');
    if (textarea) {
        addCharacterCounter(textarea, 2000);
    }
    
    // Auto-resize textarea
    autoResizeTextarea(textarea);
    
    // Form reset confirmation
    const resetButton = document.querySelector('button[type="reset"]');
    if (resetButton) {
        resetButton.addEventListener('click', function(e) {
            if (!confirm('คุณต้องการล้างข้อมูลทั้งหมดหรือไม่?')) {
                e.preventDefault();
            } else {
                removeImagePreview();
                hideAllErrors();
            }
        });
    }
}

// Add character counter
function addCharacterCounter(textarea, maxLength) {
    const counter = document.createElement('div');
    counter.className = 'character-counter';
    counter.style.textAlign = 'right';
    counter.style.fontSize = '0.8rem';
    counter.style.color = 'var(--dark-gray)';
    counter.style.marginTop = '0.25rem';
    
    const updateCounter = () => {
        const remaining = maxLength - textarea.value.length;
        counter.textContent = `${textarea.value.length}/${maxLength} ตัวอักษร`;
        
        if (remaining < 100) {
            counter.style.color = 'var(--danger-red)';
        } else if (remaining < 300) {
            counter.style.color = 'var(--warning-orange)';
        } else {
            counter.style.color = 'var(--dark-gray)';
        }
    };
    
    textarea.addEventListener('input', updateCounter);
    textarea.parentNode.appendChild(counter);
    updateCounter();
}

// Auto-resize textarea
function autoResizeTextarea(textarea) {
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.max(120, this.scrollHeight) + 'px';
        });
    }
}

// Hide all form errors
function hideAllErrors() {
    const form = document.querySelector('.innovation-form');
    const invalidFields = form.querySelectorAll('.is-invalid');
    const errorElements = form.querySelectorAll('.invalid-feedback');
    
    invalidFields.forEach(field => field.classList.remove('is-invalid'));
    errorElements.forEach(error => error.remove());
}

// Show alert message
function showAlert(message, type = 'info') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.dynamic-alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create alert element
    const alert = document.createElement('div');
    alert.className = `alert alert-${type === 'error' ? 'danger' : type} dynamic-alert`;
    alert.style.position = 'fixed';
    alert.style.top = '20px';
    alert.style.right = '20px';
    alert.style.zIndex = '9999';
    alert.style.maxWidth = '400px';
    alert.style.boxShadow = 'var(--shadow-hover)';
    
    const icon = type === 'error' ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle';
    alert.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <i class="${icon}"></i>
            <span>${message}</span>
            <button onclick="this.parentNode.parentNode.remove()" 
                    style="background: none; border: none; font-size: 1.2rem; cursor: pointer; margin-left: auto;">×</button>
        </div>
    `;
    
    document.body.appendChild(alert);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

// Loading state management
function setFormLoading(isLoading) {
    const submitButton = document.querySelector('button[type="submit"]');
    const form = document.querySelector('.innovation-form');
    
    if (isLoading) {
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังบันทึก...';
        form.style.opacity = '0.7';
        form.style.pointerEvents = 'none';
    } else {
        submitButton.disabled = false;
        submitButton.innerHTML = '<i class="fas fa-save"></i> บันทึกนวัตกรรม';
        form.style.opacity = '1';
        form.style.pointerEvents = 'auto';
    }
}

// Handle form submission with loading state
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.innovation-form');
    if (form) {
        form.addEventListener('submit', function() {
            if (validateForm()) {
                setFormLoading(true);
            }
        });
    }
});