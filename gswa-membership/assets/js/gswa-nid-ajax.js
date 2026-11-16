document.addEventListener('DOMContentLoaded', function () {

    const nidInput = document.getElementById('nid');

    const nidMessage = document.getElementById('gswa-nid-feedback');

    if (!nidInput || !nidMessage) {
        return;
    }

    let nidTimer;

    let lastNidChecked = '';

    /**
     * Display validation message.
     */
    function showMessage(element, message, status) {

        element.textContent = message;

        element.className = 'gswa-ajax-message ' + status;

    }


    /**
     * Check NID availability through WordPress AJAX.
     */
    async function checkNid() {

        const nid = nidInput.value.trim();

        // Validate NID format.
        if (!/^(?:[0-9]{10}|[0-9]{17})$/.test(nid)) {

            showMessage(
                nidMessage,
                nid ? 'Invalid no-Enter a valid 10 or 17 digit NID.' : '',
                nid ? 'error' : ''
            );

            lastNidChecked = '';

            return;
        }

        if (nid === lastNidChecked) {
            return;
        }

        showMessage(
            nidMessage,
            'Checking NID...',
            'checking'
        );

        const formData = new URLSearchParams();

        formData.append('action', 'gswa_check_nid');

        formData.append('nonce', gswaNidAjax.nonce);

        formData.append('nid', nid);

        try {

            const response = await fetch(gswaNidAjax.ajax_url, {

                method: 'POST',

                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },

                body: formData.toString()

            });

            const result = await response.json();

            if (!response.ok || !result.success) {

                throw new Error('NID verification failed.');

            }

            // Ignore response if input changed during request.
            if (nidInput.value.trim() !== nid) {
                return;
            }

            lastNidChecked = nid;

            if (result.data.exists) {

                showMessage(
                    nidMessage,
                    'NID is already registered!',
                    'error'
                );

            } else {

                showMessage(
                    nidMessage,
                    'NID is not register yet.',
                    'success'
                );

            }

        } catch (error) {

            showMessage(
                nidMessage,
                'Unable to verify NID. Please try again.',
                'error'
            );

            console.error('NID AJAX error:', error);

        }

    }


    // NID input event: debounce 500 milliseconds.
    nidInput.addEventListener('input', function () {

        clearTimeout(nidTimer);

        lastNidChecked = '';

        showMessage(nidMessage, '', '');

        nidTimer = setTimeout(checkNid, 500);

    });


    // NID blur event.
    nidInput.addEventListener('blur', function () {

        clearTimeout(nidTimer);

        checkNid();

    });

});