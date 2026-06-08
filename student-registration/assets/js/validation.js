/* =====================================================
   Client-side form validation
   ===================================================== */

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('studentForm');
    if (!form) return;

    /**
     * Show an error message for a given field.
     */
    function showError(fieldName, message) {
        const errorEl = form.querySelector('[data-error="' + fieldName + '"]');
        const inputEl = form.querySelector('[name="' + fieldName + '"]');
        if (errorEl) errorEl.textContent = message;
        if (inputEl) inputEl.classList.add('input-error');
    }

    /**
     * Clear all previous error states.
     */
    function clearErrors() {
        form.querySelectorAll('.error').forEach(function (el) {
            el.textContent = '';
        });
        form.querySelectorAll('.input-error').forEach(function (el) {
            el.classList.remove('input-error');
        });
    }

    form.addEventListener('submit', function (event) {
        clearErrors();
        let isValid = true;

        // Full Name
        const fullName = form.full_name.value.trim();
        if (fullName === '') {
            showError('full_name', 'Please enter your full name');
            isValid = false;
        }

        // Email
        const email = form.email.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === '') {
            showError('email', 'Please enter your email address');
            isValid = false;
        } else if (!emailRegex.test(email)) {
            showError('email', 'Please enter a valid email address');
            isValid = false;
        }

        // Phone — exactly 10 digits
        const phone = form.phone.value.trim();
        if (!/^\d{10}$/.test(phone)) {
            showError('phone', 'Phone number must contain 10 digits');
            isValid = false;
        }

        // Gender
        const gender = form.querySelector('input[name="gender"]:checked');
        if (!gender) {
            showError('gender', 'Please select your gender');
            isValid = false;
        }

        // Date of Birth
        if (form.dob.value === '') {
            showError('dob', 'Please select your date of birth');
            isValid = false;
        }

        // Country
        if (form.country.value === '') {
            showError('country', 'Please select your country');
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });

    // Live-clear an error as the user corrects a field.
    form.querySelectorAll('input, select, textarea').forEach(function (el) {
        el.addEventListener('input', function () {
            el.classList.remove('input-error');
            const name = el.getAttribute('name');
            const errorEl = form.querySelector('[data-error="' + name + '"]');
            if (errorEl) errorEl.textContent = '';
        });
    });
});
