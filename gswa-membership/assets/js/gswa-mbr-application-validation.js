document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById(
    'gswa-membership-application');
     
    if (!form) {
        return;
    }
    
    
    
    // Get form fields
    const eligibility = document.getElementById('eligibility');
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
    const email = document.getElementById('email');

    const declaration = document.getElementById('declaration');


    // Validate the form before submission
    form.addEventListener('submit', function (event) {

        let isValid = true;

        // Applicant's name pattern
        const namePattern = /^[A-Za-z]+(?:[ '-][A-Za-z]+)*$/;

        // International mobile number pattern
        const mobilePattern = /^\+[1-9]\d{1,14}$/;

        // NID pattern: exactly 10 or 17 digits
        const nidPattern = /^(?:\d{10}|\d{17})$/;


        // Clear previous custom errors
        form.querySelectorAll('input, select, textarea').forEach(function (field) {
            field.setCustomValidity('');
        });


        // 1. Eligibility
        if (!eligibility.value) {
            eligibility.setCustomValidity(
                'Please select your eligibility.'
            );
            isValid = false;
        }


        // 2. Membership type
        if (!memberType.value) {
            memberType.setCustomValidity(
                'Please select a membership type.'
            );
            isValid = false;
        }


        // 3. Applicant's name
        if (!applicantName.value.trim()) {
            applicantName.setCustomValidity(
                'Applicant name is required.'
            );
            isValid = false;

        } else if (!namePattern.test(applicantName.value.trim())) {
            applicantName.setCustomValidity(
                'Enter a name using English letters, spaces, hyphens, or apostrophes.'
            );
            isValid = false;
        }


        // 4. Father's name
        if (!fatherName.value.trim()) {
            fatherName.setCustomValidity(
                "Father's name is required."
            );
            isValid = false;

        } else if (!namePattern.test(fatherName.value.trim())) {
            fatherName.setCustomValidity(
                "Enter your father's name using the permitted characters."
            );
            isValid = false;
        }


        // 5. Mother's name
        if (!motherName.value.trim()) {
            motherName.setCustomValidity(
                "Mother's name is required."
            );
            isValid = false;

        } else if (!namePattern.test(motherName.value.trim())) {
            motherName.setCustomValidity(
                "Enter your mother's name using the permitted characters."
            );
            isValid = false;
        }


        // 6. Date of birth: minimum age 18
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

            if (dob > today) {
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


        // 7. NID number
        if (!nid.value.trim()) {
            nid.setCustomValidity(
                'NID number is required.'
            );
            isValid = false;

        } else if (!nidPattern.test(nid.value.trim())) {
            nid.setCustomValidity(
                'Enter a valid-format NID containing exactly 10 or 17 digits.'
            );
            isValid = false;
        }


        // 8. Blood group: optional
        const validBloodGroups = [
            'A+', 'A-', 'B+', 'B-',
            'AB+', 'AB-', 'O+', 'O-'
        ];

        if (
            bloodGroup.value.trim() &&
            !validBloodGroups.includes(
                bloodGroup.value.trim().toUpperCase()
            )
        ) {
            bloodGroup.setCustomValidity(
                'Enter a valid blood group, such as A+, B-, or O+.'
            );
            isValid = false;
        }


        // 9. Present address
        if (!presentAddress.value.trim()) {
            presentAddress.setCustomValidity(
                'Present address is required.'
            );
            isValid = false;
        }


        // 10. Permanent address
        if (!permanentAddress.value.trim()) {
            permanentAddress.setCustomValidity(
                'Permanent address is required.'
            );
            isValid = false;
        }


        // 11. Education
        if (!education.value.trim()) {
            education.setCustomValidity(
                'Education is required.'
            );
            isValid = false;
        }


        // 12. Profession
        if (!profession.value.trim()) {
            profession.setCustomValidity(
                'Profession is required.'
            );
            isValid = false;
        }


        // 13. Mobile number
        const normalizedMobile = mobile.value.replace(/\s+/g, '');

        if (!normalizedMobile) {
            mobile.setCustomValidity(
                'Mobile number is required.'
            );
            isValid = false;

        } else if (!mobilePattern.test(normalizedMobile)) {
            mobile.setCustomValidity(
                'Enter your mobile number with country code, such as +8801712345678.'
            );
            isValid = false;
        }


        // 14. Email
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


        // 15. Declaration
        if (!declaration.checked) {
            declaration.setCustomValidity(
                'You must accept the declaration before applying.'
            );
            isValid = false;
        }


        // Prevent submission if custom validation fails
        if (!isValid) {
            event.preventDefault();

            form.reportValidity();
        }

    });


    // Clear custom errors when the user edits a field
    form.querySelectorAll('input, select, textarea').forEach(function (field) {

        field.addEventListener('input', function () {
            field.setCustomValidity('');
        });

        field.addEventListener('change', function () {
            field.setCustomValidity('');
        });

    });

});