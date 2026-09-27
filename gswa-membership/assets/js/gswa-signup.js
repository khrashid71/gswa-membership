document.addEventListener('DOMContentLoaded', function () {

    const firstName = document.querySelector('[name="gswa_first_name"]');

    if (firstName) {
        firstName.addEventListener('blur', function () {

            if (!/^[A-Za-z\s'-]+$/.test(this.value)) {
                this.setCustomValidity(
                    'Please enter English letters only.'
                );
            } else {
                this.setCustomValidity('');
            }

        });
    }

});