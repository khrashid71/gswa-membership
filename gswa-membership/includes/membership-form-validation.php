<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

//adding here
/**
 * Validate mobile number format.
 */
function gswa_validate_mobile( $mobile ) {
    $mobile = trim( $mobile );
    $normalizedMobile = preg_replace( '/[\s\-().]/', '', $mobile );

    if ( preg_match( '/^01[3-9]\d{8}$/', $normalizedMobile ) ) {
        return true;
    }

    if ( preg_match( '/^\+[1-9]\d{1,14}$/', $normalizedMobile ) ) {
        return true;
    }

    return false;
}

/**
 * Main form processing logic.
 */

//add end here

/**
 * Process GSWA Membership Application Submissions securely mapping to custom schema.
 */
add_action( 'template_redirect', 'gswa_handle_member_application_submission' );

function gswa_handle_member_application_submission() {
    // Check if our unique application form is actively posted
    if ( ! isset( $_POST['gswa_application_nonce'] ) || ! isset( $_POST['eligibility'] ) ) {
        return;
    }

    // Security check: Nonce Verification
    if ( ! wp_verify_nonce( $_POST['gswa_application_nonce'], 'gswa_application_action' ) ) {
        wp_die( __( 'Security evaluation failed. Please reload the page.', 'gswa-membership' ), __( 'Error', 'gswa-membership' ), array( 'response' => 403 ) );
    }

    // Authorization check: User must be authenticated
    if ( ! is_user_logged_in() ) {
        wp_die( __( 'You must sign in before saving this application.', 'gswa-membership' ), __( 'Unauthorized', 'gswa-membership' ), array( 'response' => 401 ) );
    }

    global $wpdb;
    $current_user = wp_get_current_user();
    $errors       = array();

    // 1. Sanitize and Collect All Schema Map Post Payloads
    $eligibility       = sanitize_text_field( $_POST['eligibility'] ?? '' );
    $native_upazila    = sanitize_text_field( $_POST['native_upazila'] ?? '' );
    $member_type       = sanitize_text_field( $_POST['memberType'] ?? '' );
    $full_name         = sanitize_text_field( $_POST['applicant_full_name'] ?? '' );
    $father_name       = sanitize_text_field( $_POST['father_full_name'] ?? '' );
    $mother_name       = sanitize_text_field( $_POST['mother_full_name'] ?? '' );
    $dob               = sanitize_text_field( $_POST['birthday'] ?? '' );
    $blood_group       = sanitize_text_field( $_POST['blood_group'] ?? null );
    $raw_nid           = sanitize_text_field( $_POST['nid'] ?? '' );
    $present_address   = sanitize_textarea_field( $_POST['presentAddress'] ?? '' );
    $permanent_address = sanitize_textarea_field( $_POST['permanentAddress'] ?? '' );
    $education         = sanitize_text_field( $_POST['mbr_education'] ?? '' );
    $profession        = sanitize_text_field( $_POST['mbr_profession'] ?? '' );
    $mobile_number     = sanitize_text_field( $_POST['mobile'] ?? '' );
    $email             = sanitize_email( $_POST['email'] ?? '' );
        
    $declaration       = isset( $_POST['declaration'] ) ? 1 : 0;

    // 2. Validate Schema Integrity Constraints (NOT NULL fields)
    $required_fields = array(
        'Eligibility'         => $eligibility,
        'Native Upazila'      => $native_upazila,
        'Membership Type'         => $member_type,
        'Applicant Full Name' => $full_name,
        'Father Full Name'    => $father_name,
        'Mother Full Name'    => $mother_name,
        'Date of Birth'       => $dob,
        'National ID (NID) Number'=>$raw_nid,
        //'Passport Size Photo' =>
        'presentAddress'      => $present_address,
        'permanentAddress'    => $permanent_address,
        'mbr_education'       => $education,
        'mb_profession'       => $profession,
        'mobile_number'       => $mobile_number,
        'email'               => $email,
    );

    foreach ( $required_fields as $field_key => $value ) {
        if ( empty( $value ) ) {
            $errors[] = sprintf( __( 'The field "%s" is required.', 'gswa-membership' ), ucwords( str_replace( '_', ' ', $field_key ) ) );
        }
    }

    if ( ! is_email( $email ) ) {
        $errors[] = __( 'Please enter a valid email address.', 'gswa-membership' );
    }

    if ( 0 === $declaration ) {
        $errors[] = __( 'You must accept the declaration rules to submit your request.', 'gswa-membership' );
    }

    // 3. Cryptographic Validation via NID Helpers
    $normalized_nid = gswa_normalize_nid( $raw_nid );
    
  
// Check duplicate NID registration entries using fingerprint lookup helper
   if ( ! $normalized_nid ) {
        $errors[] = __( 'Invalid National ID format. Use digits only.', 'gswa-membership' );
    }
    $nid_check = gswa_nid_exists( $normalized_nid );
    if ( is_wp_error( $nid_check ) ) {
        wp_die( esc_html( $nid_check->get_error_message() ) );
    } elseif ( true === $nid_check ) {
        wp_die( 'An application with this National ID has already been submitted.' );
    }

    

    // 4. Fallback execution on failed validations
    if ( ! empty( $errors ) ) {
        set_transient( 'gswa_form_errors_' . $current_user->ID, $errors, 45 );
        return;
    }

    // 5. Encrypt Sensitive Security Values
    $nid_encrypted   = gswa_encrypt_nid( $normalized_nid );
    $nid_fingerprint = gswa_nid_fingerprint( $normalized_nid );

    if ( ! $nid_encrypted || ! $nid_fingerprint ) {
        gswa_membership_log( 'Security system encryption failure for user ID: ' . $current_user->ID );
        set_transient( 'gswa_form_errors_' . $current_user->ID, array( __( 'Cryptographic initialization failed.', 'gswa-membership' ) ), 45 );
        return;
    }

    // 6. Handle Passport Photo Upload File Streams safely via WordPress core
    $passport_photo_path = null;
    if ( ! empty( $_FILES['pp_photo']['name'] ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        
        $override   = array( 'test_form' => false );
        $file_array = $_FILES['pp_photo'];
        
        // Match standard profile image mime boundaries
        $uploaded_file = wp_handle_upload( $file_array, $override );

        if ( isset( $uploaded_file['error'] ) ) {
            set_transient( 'gswa_form_errors_' . $current_user->ID, array( __( 'Photo Upload Error: ', 'gswa-membership' ) . $uploaded_file['error'] ), 45 );
            return;
        } else {
            $passport_photo_path = $uploaded_file['url']; // Saves relative system URL or system absolute directory
        }
    }

    // 7. Auto-Generate Custom Unique Tracking ID Number
    $timestamp          = time();
    $rand_suffix        = mt_rand( 1000, 9999 );
    $application_number = 'GSWA-' . strtoupper( substr( $member_type, 0, 2 ) ) . '-' . $timestamp . '-' . $rand_suffix;

    // 8. DB Execute Query Map Object Array
    $table_name = $wpdb->prefix . 'gswa_applications';
    $mysql_now  = current_time( 'mysql' );

    $insert_data = array(
        'application_number'   => $application_number,
        'user_id'              => $current_user->ID,
        'eligibility'          => $eligibility,
        'native_upazila'       => $native_upazila,
        'member_type'          => $member_type,
        'applicant_full_name'  => $full_name,
        'father_full_name'     => $father_name,
        'mother_full_name'     => $mother_name,
        'date_of_birth'        => $dob,
        'blood_group'          => $blood_group,
        'present_address'      => $present_address,
        'permanent_address'    => $permanent_address,
        'education'            => $education,
        'profession'           => $profession,
        'mobile_number'        => $mobile_number,
        'email'                => $email,
        'passport_photo_path'  => $passport_photo_path,
        'declaration_accepted' => $declaration,
        'application_status'   => 'pending',
        'submitted_at'         => $mysql_now,
        'updated_at'           => $mysql_now,
        'nid_encrypted'        => $nid_encrypted,
        'nid_fingerprint'      => $nid_fingerprint,
    );

    $insert_format = array(
        '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
        '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s',
        '%s', '%s', '%s'
    );

    $inserted = $wpdb->insert( $table_name, $insert_data, $insert_format );

    if ( false === $inserted ) {
        gswa_membership_log( 'Database writing failure tracking log errors: ' . $wpdb->last_error );
        set_transient( 'gswa_form_errors_' . $current_user->ID, array( __( 'A database writing event error aborted your execution process.', 'gswa-membership' ) ), 45 );
        return;
    }

    // 9. Redirect Safely to prevent double POST processing issues on page refreshes
    set_transient( 'gswa_form_success_' . $current_user->ID, sprintf( __( 'Application submitted! Your tracking ID is: %s', 'gswa-membership' ), $application_number ), 45 );
    wp_safe_redirect( add_query_arg( 'status', 'success', wp_get_referer() ) );
    exit;
}
