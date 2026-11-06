<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue GSWA Membership styles and scripts.
 */

function gswa_membership_enqueue_assets() {

    // Signup page
    if ( is_page( 'sign-up-3' ) ) {

        wp_enqueue_script(
            'gswa-signup-ajax',
            plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/gswa-signup-ajax.js',
            array(),
            '1.0.2',
            true
        );

        // Pass AJAX URL and nonce to JavaScript.
        wp_localize_script(
            'gswa-signup-ajax',
            'gswaAjax',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'gswa_ajax_nonce' ),
            )
        );

        wp_enqueue_script(
            'gswa-signup',
            plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/gswa-signup.js',
            array(),
            '1.0.1',
            true
        );
    }

    // Membership application page
    if ( is_page( 'membership-application' ) ) {

        wp_enqueue_script(
            'gswa-application-validation',
            plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/gswa-mbr-application-validation.js',
            array(),
            '1.0.0',
            true
        );
    }

}

add_action( 'wp_enqueue_scripts', 'gswa_membership_enqueue_assets' );
