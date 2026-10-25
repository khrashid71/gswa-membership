<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}



/**
 * Enqueue GSWA Membership styles and scripts
 */
function gswa_membership_enqueue_assets() {

    // CSS
    wp_enqueue_style(
        'gswa-membership',
        plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/02-gswa-signin.css',
        array(),
        '1.0.0'
    );

    // JavaScript: Signup Form validation
    wp_enqueue_script(
        'gswa-signup',
        plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/gswa-signup.js',
        array(),
        '1.0.0',
        true
    );

    // JavaScript: Membership Application Form Validation 
    wp_enqueue_script( 'gswa-application-validation', 
    plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/gswa-application-validation.js', 
    array(), '1.0.0',
     true );
}

add_action( 'wp_enqueue_scripts', 'gswa_membership_enqueue_assets' );