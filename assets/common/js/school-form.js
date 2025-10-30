// School Form JavaScript
document.addEventListener('DOMContentLoaded', function() {
    initSchoolForm();
});

function initSchoolForm() {
    // Initialize auto-calculation
    initAutoCalculation();
    
    // Initialize form validation
    initFormValidation();
    
    // Initialize form submission
    initFormSubmission();
}

function initAutoCalculation() {
    // Auto-calculate total students
    const maleStudentsInput = document.getElementById('male_students');
    const femaleStudentsInput = document.getElementById('female_students');
    const totalStudentsInput = document.getElementById('total_students');

    if (maleStudentsInput && femaleStudentsInput && totalStudentsInput) {
        [maleStudentsInput, femaleStudentsInput].forEach(input => {
            input.addEventListener('input', function() {
                const maleStudents = parseInt(maleStudentsInput.value) || 0;
                const femaleStudents = parseInt(femaleStudentsInput.value) || 0;
                const total = maleStudents + femaleStudents;
                
                totalStudentsInput.value = total.toLocaleString();
                totalStudentsInput.classList.add('auto-calc');
                
                setTimeout(() => {
                    totalStudentsInput.classList.remove('auto-calc');
                }, 1000);
            });
        });

        // Trigger calculation on page load
        maleStudentsInput.dispatchEvent(new Event('input'));
    }

    // Auto-calculate total teachers
    const maleTeachersInput = document.getElementById('male_teachers');
    const femaleTeachersInput = document.getElementById('female_teachers');
    const totalTeachersInput = document.getElementById('total_teachers');

    if (maleTeachersInput && femaleTeachersInput && totalTeachersInput) {
        [maleTeachersInput, femaleTeachersInput].forEach(input => {
            input.addEventListener('input', function() {
                const maleTeachers = parseInt(maleTeachersInput.value) || 0;
                const femaleTeachers = parseInt(femaleTeachersInput.value) || 0;
                const total = maleTeachers + femaleTeachers;
                
                totalTeachersInput.value = total.toLocaleString();
                totalTeachersInput.classList.add('auto-calc');
                
                setTimeout(() => {
                    totalTeachersInput.classList.remove('auto-calc');
                }, 1000);
            });
        });

        // Trigger calculation on page load
        maleTeachersInput.dispatchEvent(new Event('input'));
    }
}

function initFormValidation() {
    const form = document.getElementById('schoolForm');
    if (!form) return;

    const inputs = form.querySelectorAll('.form-control');
    
    inputs.forEach(input => {
        // Real-time validation
        input.addEventListener('blur', function() {
            validateField(this);
        });

        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });
}

function validateField(field) {
    const value = field.value.trim();
    const isRequired = field.hasAttribute('required');
    let isValid = true;
    let errorMessage = '';

    // Check if required field is empty
    if (isRequired && !value) {
        isValid = false;
        errorMessage = 'กรุณากรอกข้อมูลในช่องนี้';
    }

    // Type-specific validation
    switch (field.type) {
        case 'email':
            if (value && !isValidEmail(value)) {
                isValid = false;
                errorMessage = 'กรุณากรอกอีเมลที่ถูกต้อง';
            }
            break;
        case 'tel':
            if (value && !isValidPhone(value)) {
                isValid = false;
                errorMessage = 'กรุณากรอกเบอร์โทรศัพท์ที่ถูกต้อง';
            }
            break;
        case 'number':
            const numValue = parseInt(value);
            if (value && (isNaN(numValue) || numValue < 0)) {
                isValid = false;
                errorMessage = 'กรุณากรอกตัวเลขที่ถูกต้อง';
            }
            break;
    }

    // Update UI
    updateFieldValidation(field, isValid, errorMessage);
    return isValid;
}

function updateFieldValidation(field, isValid, errorMessage) {
    const formGroup = field.closest('.form-group');
    const existingFeedback = formGroup.querySelector('.invalid-feedback');

    if (isValid) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        if (existingFeedback) {
            existingFeedback.remove();
        }
    } else {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
        
        if (!existingFeedback && errorMessage) {
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = errorMessage;
            formGroup.appendChild(feedback);
        } else if (existingFeedback) {
            existingFeedback.textContent = errorMessage;
        }
    }
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPhone(phone) {
    // Thai phone number pattern
    const phoneRegex = /^[\d\-\s\(\)]{8,15}$/;
    return phoneRegex.test(phone);
}

function initFormSubmission() {
    const form = document.getElementById('schoolForm');
    const submitButton = form?.querySelector('button[type="submit"]');
    
    if (!form || !submitButton) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate all fields
        const inputs = form.querySelectorAll('.form-control[required]');
        let isFormValid = true;
        
        inputs.forEach(input => {
            if (!validateField(input)) {
                isFormValid = false;
            }
        });

        if (!isFormValid) {
            showErrorSummary('กรุณากรอกข้อมูลให้ครบถ้วนและถูกต้อง');
            return;
        }

        // Show loading state
        submitButton.classList.add('loading');
        submitButton.disabled = true;

        // Add animation to form
        form.classList.add('submitted');

        // Submit form after a short delay for UX
        setTimeout(() => {
            form.submit();
        }, 500);
    });
}

function showErrorSummary(message) {
    let errorSummary = document.querySelector('.error-summary');
    
    if (!errorSummary) {
        errorSummary = document.createElement('div');
        errorSummary.className = 'error-summary';
        
        const firstSection = document.querySelector('.form-section');
        firstSection.parentNode.insertBefore(errorSummary, firstSection);
    }
    
    errorSummary.innerHTML = `
        <h4><i class="fas fa-exclamation-triangle"></i> ข้อผิดพลาด</h4>
        <p>${message}</p>
    `;
    
    errorSummary.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// Add CSS for loading button animation
const style = document.createElement('style');
style.textContent = `
    .auto-calc {
        animation: highlight 0.5s ease;
    }
    
    @keyframes highlight {
        0% { background-color: rgba(46, 204, 113, 0.2); }
        100% { background-color: var(--light-gray); }
    }
    
    .form-control.is-valid {
        border-color: #28a745;
    }
    
    .form-control.is-valid:focus {
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
    }
    
    .success-feedback {
        color: #28a745;
        font-size: 0.8rem;
        margin-top: 0.5rem;
    }
`;
document.head.appendChild(style);