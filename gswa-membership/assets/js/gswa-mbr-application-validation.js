document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('gswa-membership-application');

    if (!form) {
        return;
    }

    // Validate the form before submission.
    form.addEventListener('submit', function (event) {

        let isValid = true;

        // 1. Fetch form elements.
        const eligibility = document.getElementById('eligibility');
        const upazila = document.getElementById('native_upazila');
        const memberType = document.getElementById('memberType');

        const applicantName = document.getElementById('applicant_full_name');
        const fatherName = document.getElementById('father_full_name');
        const motherName = document.getElementById('mother_full_name');
        const birthday = document.getElementById('birthday');
        const nid = document.getElementById('nid');
        const bloodGroup = document.getElementById('blood_group');
        const presentAddress = document.getElementById('presentAddress');
        const permanentAddress = document.getElementById('permanentAddress');
        const education = document.getElementById('mbr_education');
        const profession = document.getElementById('mbr_profession');
        const mobile = document.getElementById('mobile');
        //const email = document.getElementById('email');
        const declaration = document.getElementById('declaration');

        // 2. Validation patterns.
        const namePattern = /^[A-Za-z]+(?:[ '-][A-Za-z]+)*$/;
        
        const nidPattern = /^(?:\d{10}|\d{17})$/;

       /* if ( preg_match( '/^01[3-9]\d{8}$/', $mobile ) ) {
        return true;
    }*/

        // 3. Clear previous custom validation errors.
        form.querySelectorAll('input, select, textarea').forEach(function (field) {
            field.setCustomValidity('');
        });

        // 4. Eligibility validation.
        if (eligibility && !eligibility.value) {
            eligibility.setCustomValidity(
                'Please select your eligibility.'
            );
            isValid = false;
        }

        // 4a. Native upazila validation.
        if (upazila && !upazila.value) {
            upazila.setCustomValidity(
                'Please select your upazila.'
            );
            isValid = false;
        }

        // 5. Membership type validation.
        if (memberType && !memberType.value) {
            memberType.setCustomValidity(
                'Please select a membership type.'
            );
            isValid = false;
        }

        // 6. Applicant's name.
        if (applicantName) {
            const value = applicantName.value.trim();

            if (!value) {
                applicantName.setCustomValidity(
                    'Applicant name is required.'
                );
                isValid = false;
            } else if (!namePattern.test(value)) {
                applicantName.setCustomValidity(
                    'Enter a name using English letters, spaces, hyphens, or apostrophes.'
                );
                isValid = false;
            }
        }

        // 7. Father's name.
        if (fatherName) {
            const value = fatherName.value.trim();

            if (!value) {
                fatherName.setCustomValidity(
                    "Father's name is required."
                );
                isValid = false;
            } else if (!namePattern.test(value)) {
                fatherName.setCustomValidity(
                    "Enter your father's name using English characters only."
                );
                isValid = false;
            }
        }

        // 8. Mother's name.
        if (motherName) {
            const value = motherName.value.trim();

            if (!value) {
                motherName.setCustomValidity(
                    "Mother's name is required."
                );
                isValid = false;
            } else if (!namePattern.test(value)) {
                motherName.setCustomValidity(
                    "Enter your mother's name using  English characters only."
                );
                isValid = false;
            }
        }

        // 9. Date of birth and minimum age.
        if (birthday) {
            if (!birthday.value) {
                birthday.setCustomValidity(
                    'Please enter your date of birth.'
                );
                isValid = false;

            } else {
                const dob = new Date(birthday.value + 'T00:00:00');
                const today = new Date();

                today.setHours(0, 0, 0, 0);

                let age = today.getFullYear() - dob.getFullYear();

                const birthdayNotReached =
                    today.getMonth() < dob.getMonth() ||
                    (
                        today.getMonth() === dob.getMonth() &&
                        today.getDate() < dob.getDate()
                    );

                if (Number.isNaN(dob.getTime())) {
                    birthday.setCustomValidity(
                        'Please enter a valid date of birth.'
                    );
                    isValid = false;

                } else if (dob > today) {
                    birthday.setCustomValidity(
                        'Date of birth cannot be in the future.'
                    );
                    isValid = false;

                } else {
                    if (birthdayNotReached) {
                        age--;
                    }

                    if (age < 18) {
                        birthday.setCustomValidity(
                            'You must be at least 18 years old to apply.'
                        );
                        isValid = false;
                    }
                }
            }
        }

        // 10. NID number.
        if (nid) {
            const value = nid.value.trim();

            if (!value) {
                nid.setCustomValidity(
                    'NID number is required.'
                );
                isValid = false;
            } else if (!nidPattern.test(value)) {
                nid.setCustomValidity(
                    'Enter a valid-format NID containing exactly 10 or 17 digits.'
                );
                isValid = false;
            }
        }

        // 11. Blood group.
        if (bloodGroup && bloodGroup.value.trim()) {
            const validBloodGroups = [
                'A+', 'A-', 'B+', 'B-',
                'AB+', 'AB-', 'O+', 'O-'
            ];

            if (!validBloodGroups.includes(
                bloodGroup.value.trim().toUpperCase()
            )) {
                bloodGroup.setCustomValidity(
                    'Enter a valid blood group, such as A+, B-, or O+.'
                );
                isValid = false;
            }

                
        }

        // 12. Present address.
        if (presentAddress && !presentAddress.value.trim()) {
            presentAddress.setCustomValidity(
                'Present address is required.'
            );
            isValid = false;
        }

        // 13. Permanent address.
        if (permanentAddress && !permanentAddress.value.trim()) {
            permanentAddress.setCustomValidity(
                'Permanent address is required.'
            );
            isValid = false;
        }

        // 14. Education.
        if (education && !education.value.trim()) {
            education.setCustomValidity(
                'Education is required.'
            );
            isValid = false;
        }

        // 15. Profession.
        if (profession && !profession.value.trim()) {
            profession.setCustomValidity(
                'Profession is required.'
            );
            isValid = false;
        }

        // 16 & 17 takeout mobile and email validation put into signup file

        // 18. Declaration.
        if (declaration && !declaration.checked) {
            declaration.setCustomValidity(
                'You must accept the declaration before applying.'
            );
            isValid = false;
        }

        // 19. Stop submission and display the first validation error.
        if (!isValid) {
            event.preventDefault();
            form.reportValidity();
        }

    });

    // 20. Clear a field's custom error when the user edits it.
    form.querySelectorAll('input, select, textarea').forEach(function (field) {

        field.addEventListener('input', function () {
            field.setCustomValidity('');
        });

        field.addEventListener('change', function () {
            field.setCustomValidity('');
        });

    });

});

