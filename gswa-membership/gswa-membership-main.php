<?php
/**
 * Plugin Name: GSWA Membership
 * Plugin URI:  https://gswa.org.bd
 * Description: Custom membership management plugin for the GSWA website.
 * Version:     2.0.0
 * Author:      Kazi Harunar Rashid
 * License:     GPL-2.0-or-later
 */

/**
 * GSWA Website Enhancement R2.0
 *
 * Initial scope:
 * - Member Sign Up
 * - Member Sign In
 * - WordPress user authentication
 * - GSWA membership application workflow
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// Helpers
require_once plugin_dir_path( __FILE__ ) . 'includes/helpers.php';
// Centralized CSS and JavaScript enqueue
require_once plugin_dir_path( __FILE__ ) . 'includes/gswa-central-enqueue.php';
//Adding signup page
require_once plugin_dir_path( __FILE__ ) . 'includes/gswa-signup.php';
//adding above code for auto email on signin page

require_once plugin_dir_path( __FILE__ ) . 'includes/gswa-signin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/gswa-forgot-password.php';
// ADD THIS LINE to load the new membership application code:

require_once plugin_dir_path( __FILE__ ) . 'includes/gswa-mbr-application.php';
//membership form validation
require_once plugin_dir_path( __FILE__ ). 'includes/membership-form-validation.php';

add_action( 'admin_menu', 'gswa_membership_admin_menu' );

function gswa_membership_admin_menu() {

    add_menu_page(
        'GSWA Membership', // Page title
        'GSWA Membership', // Menu title
        'manage_options',  // Capability
        'gswa-membership', // Menu slug
        'gswa_membership_dashboard', // Callback function
        'dashicons-groups', // Icon
        25 // Position
    );
}


/**
 * GSWA Membership Dashboard
 */
function gswa_membership_dashboard() {
    ?>
    <div class="wrap">

        <h1>GSWA Membership</h1>

        <p>
            Welcome to the GSWA Membership Management System.
        </p>

        <p>
            <strong>Version:</strong> 2.0.0
        </p>

    </div>
    <?php
}



                