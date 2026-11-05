<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}



/**
 * Enqueue GSWA Membership styles and scripts
 */



function gswa_membership_enqueue_assets() {

  wp_enqueue_script( 
        'gswa-signup', 
        plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/gswa-signup.js', 
        array(), 
        '1.0.1', 
        true 
    );
    
    wp_enqueue_script( 
        'gswa-application-validation', 
        plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/gswa-mbr-application-validation.js', 
        array(), 
        '1.0.0', 
        true 
    );

    error_log( 'GSWA Membership assets enqueued.' );
    
}
add_action( 'wp_enqueue_scripts', 'gswa_membership_enqueue_assets' );


