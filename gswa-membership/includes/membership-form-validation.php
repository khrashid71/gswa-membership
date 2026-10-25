<?php
/*function gswa_validate_international_mobile( $mobile ) {

    $mobile = trim( $mobile );

    return (bool) preg_match(
        '/^\+[1-9]\d{1,14}$/',
        $mobile
    );

    
}*/

function gswa_validate_mobile( $mobile ) {

    $mobile = trim( $mobile );

    // Bangladesh domestic format: 01XXXXXXXXX
    if ( preg_match( '/^01[3-9]\d{8}$/', $mobile ) ) {
        return true;
    }

    // International format: + followed by 1–15 digits,
    // with the first digit after + being nonzero.
    if ( preg_match( '/^\+[1-9]\d{1,14}$/', $mobile ) ) {
        return true;
    }

    return false;
}
function gswa_process_member_application() {
    if ( ! isset( $_POST['gswa_application_submit'] ) ) {
        return;
    }

    // Nonce verification
    if ( ! isset( $_POST['gswa_application_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gswa_application_nonce'] ) ), 'gswa_application_action' ) ) {
        wp_die( 'Security verification failed. Please refresh the page and try again.' );
    }

    // Sanitize input text fields
    $applicant_name = sanitize_text_field( $_POST['applicant_full_name'] ?? '' );
    $father_name    = sanitize_text_field( $_POST['father_full_name'] ?? '' );
    $mother_name    = sanitize_text_field( $_POST['mother_full_name'] ?? '' );
    $date_of_birth  = sanitize_text_field( $_POST['birthday'] ?? '' );
    $national_id    = sanitize_text_field( $_POST['nid'] ?? '' );
    $blood_group    = sanitize_text_field( $_POST['blood_group'] ?? '' );
    //$email          = sanitize_text_field( $_POST['email'] ?? '' );
     $email = sanitize_email(wp_unslash( $_POST['email'] ?? '' )
    );
    
    $mobile = '';
    if ( isset( $_POST['mobile'] ) && is_string( $_POST['mobile'] ) ) {
        $mobile = sanitize_text_field( wp_unslash( $_POST['mobile'] ) );
    }

    // Validations
    if ( empty( $applicant_name ) || empty( $father_name ) || empty( $mother_name ) || empty( $mobile ) || empty( $email ) ) {
        wp_die( 'Please complete all required fields.' );
    }

    if ( ! preg_match( "/^[A-Za-z\s'-]+$/", $applicant_name ) ) {
        wp_die( 'Applicant Name must contain English letters only.' );
    }
    if ( ! preg_match( "/^[A-Za-z\s'-]+$/", $father_name ) ) {
        wp_die( 'Father Name must contain English letters only.' );
    }
    if ( ! preg_match( "/^[A-Za-z\s'-]+$/", $mother_name ) ) {
        wp_die( 'Mother Name must contain English letters only.' );
    }
    if ( ! is_email( $email ) ) {
        wp_die( 'Please enter a valid email address.' );
    }
  
    
        if ( ! gswa_validate_mobile( $mobile ) ) {
        wp_die( 'Please enter a valid Bangladesh domestic or international mobile number.' );
    }
    
    if ( ! preg_match( '/^(?:[0-9]{10}|[0-9]{17})$/', $national_id ) ) {
    wp_die( 'Please enter a valid 10-digit or 17-digit NID number.' );
}
    // ==================== PHP File Validation ====================
    $allowed_exts = array( 'jpg', 'jpeg', 'png', 'webp' );
    
    // 1. Check NID Photo Upload
    if ( ! isset( $_FILES['nid_photo'] ) || $_FILES['nid_photo']['error'] !== UPLOAD_ERR_OK ) {
        wp_die( 'NID photo is required and must be uploaded without errors.' );
    }
    $nid_file = $_FILES['nid_photo'];
    $nid_ext  = strtolower( pathinfo( $nid_file['name'], PATHINFO_EXTENSION ) );
    
    if ( ! in_array( $nid_ext, $allowed_exts, true ) ) {
        wp_die( 'Invalid NID photo format. Allowed formats: JPG, JPEG, PNG, and WebP.' );
    }
    if ( $nid_file['size'] < ( 100 * 1024 ) || $nid_file['size'] > ( 500 * 1024 ) ) {
        wp_die( 'NID photo size must be between 100 KB and 500 KB.' );
    }

    // 2. Check Passport Photo Upload
    if ( ! isset( $_FILES['pp_photo'] ) || $_FILES['pp_photo']['error'] !== UPLOAD_ERR_OK ) {
        wp_die( 'Passport size photo is required and must be uploaded without errors.' );
    }
    $pp_file = $_FILES['pp_photo'];
    $pp_ext  = strtolower( pathinfo( $pp_file['name'], PATHINFO_EXTENSION ) );
    
    if ( ! in_array( $pp_ext, $allowed_exts, true ) ) {
        wp_die( 'Invalid Passport photo format. Allowed formats: JPG, JPEG, PNG, and WebP.' );
    }
    if ( $pp_file['size'] < ( 20 * 1024 ) || $pp_file['size'] > ( 300 * 1024 ) ) {
        wp_die( 'Passport photo size must be between 20 KB and 300 KB.' );
    }

    // ==================== Handle Uploads securely using WordPress APIs ====================
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }

    // These overrides allow us to bypass standard dashboard forms checks
    $upload_overrides = array( 'test_form' => false );

    // Process NID upload
    $nid_upload_result = wp_handle_upload( $nid_file, $upload_overrides );
    if ( isset( $nid_upload_result['error'] ) ) {
        wp_die( 'Error uploading NID photo: ' . esc_html( $nid_upload_result['error'] ) );
    }

    // Process Passport Photo upload
    $pp_upload_result = wp_handle_upload( $pp_file, $upload_overrides );
    if ( isset( $pp_upload_result['error'] ) ) {
        wp_die( 'Error uploading Passport photo: ' . esc_html( $pp_upload_result['error'] ) );
    }

    // Successfully uploaded file URLs are now accessible here:
    $nid_photo_url = $nid_upload_result['url']; 
    $pp_photo_url  = $pp_upload_result['url'];

    // Debugging logs to verify paths
    error_log( 'NID Saved At: ' . $nid_photo_url );
    error_log( 'PP Photo Saved At: ' . $pp_photo_url );

    // Your data persistence logic (e.g. wp_insert_post or custom DB inserts) goes here
}
add_action( 'init', 'gswa_process_member_application' );
?>