document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action*="gswa-executive-accounts"]');
    const fullName = document.getElementById('executive_full_name');
    const position = document.getElementById('executive_position');
    const jurisdiction = document.getElementById('executive_jurisdiction');
    const identifier = document.getElementById('executive_identifier');
    const usernamePreview = document.getElementById('executive_username_preview');
    const usernameMessage = document.getElementById('executive_username_message');
    const password = document.getElementById('executive_initial_password');

    // Stop safely if elements are not on the current page
    if (
        !form ||
        !fullName ||
        !position ||
        !jurisdiction ||
        !identifier ||
        !usernamePreview ||
        !usernameMessage ||
        !password
    ) {
        return;
    }

    // Helper: Create or retrieve an inline error element under a field
    function getErrorElement(field) {
        const errorId = field.id + '_error';
        let errorEl = document.getElementById(errorId);

        if (!errorEl) {
            errorEl = document.createElement('p');
            errorEl.id = errorId;
            errorEl.style.color = '#d63638'; // WordPress admin error red
            errorEl.style.marginTop = '4px';
            errorEl.style.fontWeight = '500';
            field.parentNode.appendChild(errorEl);
        }

        return errorEl;
    }

    function setFieldError(field, message) {
        const errorEl = getErrorElement(field);
        errorEl.textContent = message;
        field.style.borderColor = '#d63638';
    }

    function clearFieldError(field) {
        const errorEl = document.getElementById(field.id + '_error');
        if (errorEl) {
            errorEl.textContent = '';
        }
        field.style.borderColor = '';
    }

    // 1. Full Name Validation
    function validateFullName() {
        const val = fullName.value.trim();

        if (val === '') {
            setFieldError(fullName, 'Full Name is required.');
            return false;
        }
        if (val.length < 3) {
            setFieldError(fullName, 'Full Name must be at least 3 characters.');
            return false;
        }
        if (!/^[A-Za-z.\-\s]+$/.test(val)) {
            setFieldError(fullName, 'Full Name may only contain letters, spaces, dots, or hyphens.');
            return false;
        }

        clearFieldError(fullName);
        return true;
    }

    // 2. Position Validation
    function validatePosition() {
        if (position.value !== 'P' && position.value !== 'M') {
            setFieldError(position, 'Please select a valid Position.');
            return false;
        }

        clearFieldError(position);
        return true;
    }

    // 3. Jurisdiction Validation
    function validateJurisdiction() {
        const validJurisdictions = ['0', '1', '2', '3', '4', '5'];
        if (!validJurisdictions.includes(jurisdiction.value)) {
            setFieldError(jurisdiction, 'Please select a valid Jurisdiction.');
            return false;
        }

        clearFieldError(jurisdiction);
        return true;
    }

    // 4. Name Identifier Validation (5-10 letters only, no spaces/numbers)
    function validateIdentifier() {
        const val = identifier.value.trim();

        if (val === '') {
            setFieldError(identifier, 'Name Identifier is required.');
            return false;
        }
        if (!/^[A-Za-z]+$/.test(val)) {
            setFieldError(identifier, 'Use English letters only (A–Z). No spaces, numbers, or symbols.');
            return false;
        }
        if (val.length < 5 || val.length > 10) {
            setFieldError(identifier, 'Name Identifier must be between 5 and 10 letters.');
            return false;
        }

        clearFieldError(identifier);
        return true;
    }

    // 5. Initial Password Validation (Min 9 chars, uppercase, lowercase, number, special char)
    function validatePassword() {
        const val = password.value;

        if (val === '') {
            setFieldError(password, 'Initial Password is required.');
            return false;
        }
        if (val.length < 9) {
            setFieldError(password, 'Password must be at least 9 characters long.');
            return false;
        }
        if (/\s/.test(val)) {
            setFieldError(password, 'Password cannot contain spaces.');
            return false;
        }
        if (!/[A-Z]/.test(val) || !/[a-z]/.test(val) || !/[0-9]/.test(val) || !/[^A-Za-z0-9]/.test(val)) {
            setFieldError(
                password,
                'Include at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.'
            );
            return false;
        }

        clearFieldError(password);
        return true;
    }

    // Live Username Preview Generator
    function updateUsernamePreview() {
        const positionValue = position.value;
        const jurisdictionValue = jurisdiction.value;
        const identifierValue = identifier.value.trim();

        // Only generate preview if Position, Jurisdiction, and Identifier are valid
        if (
            positionValue === '' ||
            jurisdictionValue === '' ||
            !/^[A-Za-z]{5,10}$/.test(identifierValue)
        ) {
            usernamePreview.value = '';
            usernameMessage.textContent =
                'Select Position, Jurisdiction and enter a valid 5–10 letter Name Identifier to generate username.';
            return;
        }

        const formattedIdentifier =
            identifierValue.charAt(0).toUpperCase() +
            identifierValue.slice(1).toLowerCase();

        const username =
            positionValue.toLowerCase() +
            jurisdictionValue +
            formattedIdentifier;

        usernamePreview.value = username;
        usernameMessage.textContent =
            'This username will be used for the executive account.';
    }

    // Real-time event listeners
    fullName.addEventListener('blur', validateFullName);
    fullName.addEventListener('input', function () {
        if (fullName.value.trim() !== '') validateFullName();
    });

    position.addEventListener('change', function () {
        validatePosition();
        updateUsernamePreview();
    });

    jurisdiction.addEventListener('change', function () {
        validateJurisdiction();
        updateUsernamePreview();
    });

    identifier.addEventListener('input', function () {
        validateIdentifier();
        updateUsernamePreview();
    });

    password.addEventListener('input', validatePassword);

    // Final check before submitting the form
    form.addEventListener('submit', function (event) {
        const isNameValid = validateFullName();
        const isPosValid = validatePosition();
        const isJurValid = validateJurisdiction();
        const isIdValid = validateIdentifier();
        const isPassValid = validatePassword();

        if (!isNameValid || !isPosValid || !isJurValid || !isIdValid || !isPassValid) {
            event.preventDefault();

            // Focus on the first invalid field
            const firstInvalid = [fullName, position, jurisdiction, identifier, password].find(
                (el) => el.style.borderColor !== ''
            );
            if (firstInvalid) {
                firstInvalid.focus();
            }
        }
    });

    // Run once on load in case browser auto-fills fields
    updateUsernamePreview();
});
