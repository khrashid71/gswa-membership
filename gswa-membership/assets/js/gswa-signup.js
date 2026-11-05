


document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('gswa-signup');

    if (!form) {
        return;
    }

    form.addEventListener('submit', function (event) {

        let isValid = true;

        // 1. Fetch form elements.

        const firstName = document.getElementById('gswa_first_name');
        const lastName = document.getElementById('gswa_last_name');
        const email = document.getElementById('user_email');
        const mobile = document.getElementById('phone');

        const password = form.querySelector('[name="gswa_password"]');
        const confirmPassword = form.querySelector('[name="gswa_confirm_password"]');

        // 2. Validation patterns.

        const namePattern = /^[A-Za-z]+(?:[ '-][A-Za-z]+)*$/;

        const normalizedMobile = mobile.value.replace(/[\s().-]/g, '');

        //const globalPhoneRegex = /^\+?[0-9]{7,15}$/;
        const globalPhoneRegex = /^\+?[0-9]\d{6,14}$/;
        


        // 3. First Name validation.

        if (firstName) {

            const value = firstName.value.trim();

            if (!value) {

                firstName.setCustomValidity(
                    'First name is required.'
                );

                isValid = false;

            } else if (!namePattern.test(value)) {

                firstName.setCustomValidity(
                    'Enter English letters, spaces, hyphens or apostrophes only.'
                );

                isValid = false;
            }
        }


        // 4. Last Name validation.

        if (lastName) {

            const value = lastName.value.trim();

            if (!value) {

                lastName.setCustomValidity(
                    'Last name is required.'
                );

                isValid = false;

            } else if (!namePattern.test(value)) {

                lastName.setCustomValidity(
                    'Enter English letters, spaces, hyphens or apostrophes only.'
                );

                isValid = false;
            }
        }


        // 5. Mobile validation.

        if (mobile) {

            if (!normalizedMobile) {

                mobile.setCustomValidity(
                    'Mobile number is required.'
                );

                isValid = false;

            } else if (!globalPhoneRegex.test(normalizedMobile)) {

                mobile.setCustomValidity(
                    'Please enter a valid phone number.'
                );

                isValid = false;
            }
        }


        // 6. Email validation.

        if (email) {

            if (!email.value.trim()) {

                email.setCustomValidity(
                    'Email address is required.'
                );

                isValid = false;

            } else if (!email.validity.valid) {

                email.setCustomValidity(
                    'Please enter a valid email address.'
                );

                isValid = false;
            }
        }


        // 7. Password validation.

        if (password) {

            if (!password.value) {

                password.setCustomValidity(
                    'Password is required.'
                );

                isValid = false;

            } else if (password.value.length < 8) {

                password.setCustomValidity(
                    'Password must contain at least 8 characters.'
                );

                isValid = false;
            }
        }


        // 8. Confirm Password validation.

        if (confirmPassword && password) {

            if (!confirmPassword.value) {

                confirmPassword.setCustomValidity(
                    'Please confirm your password.'
                );

                isValid = false;

            } else if (confirmPassword.value !== password.value) {

                confirmPassword.setCustomValidity(
                    'Passwords do not match.'
                );

                isValid = false;
            }
        }


        // 9. Stop submission if validation fails.

        if (!isValid) {

            event.preventDefault();

            form.reportValidity();
        }

    });


    // 10. Clear custom validation errors when editing fields.

    form.querySelectorAll('input').forEach(function (field) {

        field.addEventListener('input', function () {
            field.setCustomValidity('');
        });

        field.addEventListener('change', function () {
            field.setCustomValidity('');
        });

    });

});

