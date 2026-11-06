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