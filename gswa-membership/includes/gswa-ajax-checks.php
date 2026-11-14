<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GSWA AJAX: Check email availability.
 */

add_action(
    'wp_ajax_nopriv_gswa_check_email',
    'gswa_ajax_check_email'
);

add_action(
    'wp_ajax_gswa_check_email',
    'gswa_ajax_check_email'
);


function gswa_ajax_check_email() {

    // Verify AJAX nonce.
    if (
        ! isset( $_POST['nonce'] ) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['nonce'] ) ),
            'gswa_ajax_nonce'
        )
    ) {
        wp_send_json_error(
            array( 'message' => 'Security verification failed.' )
        );
    }

    // Check email input.
    if ( ! isset( $_POST['email'] ) ) {

        wp_send_json_error(
            array( 'message' => 'Email address is required.' )
        );
    }

    $email = sanitize_email( wp_unslash( $_POST['email'] ) );

    // Validate email format.
    if ( ! is_email( $email ) ) {

        wp_send_json_error(
            array( 'message' => 'Invalid email address.' )
        );
    }

    // Check whether email already exists.
    $exists = email_exists( $email );

    wp_send_json_success(
        array(
            'exists' => (bool) $exists
        )
    );
}


/**
 * GSWA AJAX: Check mobile availability.
 */

add_action(
    'wp_ajax_nopriv_gswa_check_mobile',
    'gswa_ajax_check_mobile'
);

add_action(
    'wp_ajax_gswa_check_mobile',
    'gswa_ajax_check_mobile'
);


function gswa_ajax_check_mobile() {

    // Verify AJAX nonce.
    if (
        ! isset( $_POST['nonce'] ) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['nonce'] ) ),
            'gswa_ajax_nonce'
        )
    ) {
        wp_send_json_error(
            array( 'message' => 'Security verification failed.' )
        );
    }

    // Check mobile input.
    if ( ! isset( $_POST['mobile'] ) ) {

        wp_send_json_error(
            array( 'message' => 'Mobile number is required.' )
        );
    }

    $mobile = sanitize_text_field(
        wp_unslash( $_POST['mobile'] )
    );

    // Validate mobile number.
    if ( ! gswa_validate_mobile( $mobile ) ) {

        wp_send_json_error(
            array( 'message' => 'Invalid mobile number.' )
        );
    }

    // Check whether mobile already exists.
    $exists = gswa_mobile_exists( $mobile );

    wp_send_json_success(
        array(
            'exists' => (bool) $exists
        )
    );
}


/**
 * GSWA AJAX: Check NID availability.
 */

// Register AJAX action for logged-in users.
add_action(
    'wp_ajax_gswa_check_nid',
    'gswa_ajax_check_nid'
);


function gswa_ajax_check_nid() {

    // 1. Verify AJAX nonce.
    if (
        ! isset( $_POST['nonce'] ) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['nonce'] ) ),
            'gswa_ajax_nonce'
        )
    ) {
        wp_send_json_error(
            array( 'message' => 'Security verification failed.' )
        );
    }

    // 2. Check NID input.
    if ( ! isset( $_POST['nid'] ) ) {

        wp_send_json_error(
            array( 'message' => 'NID number is required.' )
        );
    }

    $raw_nid = sanitize_text_field(
        wp_unslash( $_POST['nid'] )
    );

    // 3. Normalize NID.
    $normalized_nid = gswa_normalize_nid( $raw_nid );

    if ( ! $normalized_nid ) {

        wp_send_json_error(
            array( 'message' => 'Invalid NID format. Enter 10 or 17 digits.' )
        );
    }

    // 4. Generate NID fingerprint.
    $nid_fingerprint = gswa_nid_fingerprint( $normalized_nid );

    if ( ! $nid_fingerprint ) {

        wp_send_json_error(
            array( 'message' => 'Unable to verify NID.' )
        );
    }

    // 5. Check NID fingerprint in application table.
    global $wpdb;

    $table_name = $wpdb->prefix . 'gswa_applications';

    $exists = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT 1 FROM {$table_name}
             WHERE nid_fingerprint = %s
             LIMIT 1",
            $nid_fingerprint
        )
    );

    // 6. Return AJAX response.
    wp_send_json_success(
        array(
            'exists' => (bool) $exists
        )
    );
}