
document.addEventListener('DOMContentLoaded', function () {

    const emailInput  = document.getElementById('gswa_email');
    const mobileInput = document.getElementById('phone');

    const emailMessage  = document.getElementById('gswa-email-feedback');
    const mobileMessage = document.getElementById('gswa-mobile-feedback');

    if (!emailInput || !mobileInput || !emailMessage || !mobileMessage) {
        return;
    }

    // Debounce timers
    let emailTimer;
    let mobileTimer;

    // Track previously checked values
    let lastEmailChecked = '';
    let lastMobileChecked = '';

    /**
     * Display validation message.
     */
    function showMessage(element, message, status) {

        element.textContent = message;
        element.className = 'gswa-ajax-message ' + status;

    }

    /**
     * Check email availability through WordPress AJAX.
     */
    async function checkEmail() {

        const email = emailInput.value.trim();

        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showMessage(emailMessage, '', '');
            lastEmailChecked = '';
            return;
        }

        if (email === lastEmailChecked) {
            return;
        }

        showMessage(emailMessage, 'Checking email...', 'checking');

        const formData = new URLSearchParams();

        formData.append('action', 'gswa_check_email');
        formData.append('nonce', gswaAjax.nonce);
        formData.append('email', email);

        try {

            const response = await fetch(gswaAjax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData.toString()
            });

            console.log('HTTP status:', response.status);
            console.log('Response content type:', response.headers.get('content-type'));

            //const result = await response.json();
            const responseText = await response.text();

            console.log('RAW RESPONSE:', JSON.stringify(responseText));

            const result = JSON.parse(responseText);
            //adding console here
            console.log('AJAX result:', result);
            console.log('Email exists:', result.data.exists);

            if (!response.ok || !result.success) {
                throw new Error('Email availability check failed.');
            }

            lastEmailChecked = email;

            if (result.data.exists) {

                showMessage(
                    emailMessage,
                    'Email already exists!',
                    'error'
                );

            } else {

                showMessage(
                    emailMessage,
                    'Email is available.',
                    'success'
                );

            }

        } catch (error) {
            
            showMessage(
                emailMessage,
                'Unable to verify email. Please try again.',
                'error'
            );

            console.error('Email AJAX error:', error);

        }

    }


    /**
     * Check mobile availability through WordPress AJAX.
     */
    async function checkMobile() {

        const mobile = mobileInput.value.trim();

        if (!mobile) {
            showMessage(mobileMessage, '', '');
            lastMobileChecked = '';
            return;
        }

        if (mobile === lastMobileChecked) {
            return;
        }

        showMessage(mobileMessage, 'Checking mobile...', 'checking');

        const formData = new URLSearchParams();

        formData.append('action', 'gswa_check_mobile');
        formData.append('nonce', gswaAjax.nonce);
        formData.append('mobile', mobile);

        try {

            const response = await fetch(gswaAjax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData.toString()
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error('Mobile availability check failed.');
            }

            lastMobileChecked = mobile;

            if (result.data.exists) {

                showMessage(
                    mobileMessage,
                    'Mobile number already exists!',
                    'error'
                );

            } else {

                showMessage(
                    mobileMessage,
                    'Mobile number is available.',
                    'success'
                );

            }

        } catch (error) {

            showMessage(
                mobileMessage,
                'Unable to verify mobile. Please try again.',
                'error'
            );

            console.error('Mobile AJAX error:', error);

        }

    }


    // Email input event: debounce 500 milliseconds.
    emailInput.addEventListener('input', function () {

        clearTimeout(emailTimer);
        lastEmailChecked = '';

        emailTimer = setTimeout(checkEmail, 500);

    });

    // Email blur event.
    emailInput.addEventListener('blur', function () {

        clearTimeout(emailTimer);
        checkEmail();

    });


    // Mobile input event: debounce 500 milliseconds.
    mobileInput.addEventListener('input', function () {

        clearTimeout(mobileTimer);
        lastMobileChecked = '';

        mobileTimer = setTimeout(checkMobile, 500);

    });

    // Mobile blur event.
    mobileInput.addEventListener('blur', function () {

        clearTimeout(mobileTimer);
        checkMobile();

    });

});

