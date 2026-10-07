<?php
/**
 * Plugin Name: GSWA Membership
 * Plugin URI:  https://gswa.org.bd
 * Description: Custom membership management plugin for the GSWA website.
 * Version:     1.0.0
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
require_once plugin_dir_path( __FILE__ ) . 'includes/enqueue.php';

//adding above code for auto email on signin page

require_once plugin_dir_path( __FILE__ ) . 'includes/gswa-signin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/gswa-forgot-password.php';
// ADD THIS LINE to load the new membership application code:
require_once plugin_dir_path( __FILE__ ) . 'includes/gswa-mbr-application.php';
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
            <strong>Version:</strong> 1.0.0
        </p>

    </div>
    <?php
}

/**
 * Create a custom hook gswa_signup
 */
add_shortcode( 'gswa_signup', 'gswa_membership_signup_form' );
//Mobile validation
function gswa_validate_mobile( $mobile ) {

    return (bool) preg_match( '/^01[3-9]\d{8}$/', $mobile );
}



function gswa_membership_signup_form() {

    ob_start();

    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

    if (
        ! isset( $_POST['gswa_signup_nonce'] ) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['gswa_signup_nonce'] ) ),
            'gswa_signup_action'
        )
    ) {
        wp_die( 'Security verification failed. Please refresh the page and try again.' );
    }

    // Continue processing...

    // Sanitize input
    $first_name = sanitize_text_field(
        wp_unslash( $_POST['gswa_first_name'] ?? '' )
    );

    $last_name = sanitize_text_field(
        wp_unslash( $_POST['gswa_last_name'] ?? '' )
    );

    $email = sanitize_email(
        wp_unslash( $_POST['gswa_email'] ?? '' )
    );



    $mobile = sanitize_text_field(
        wp_unslash( $_POST['phone'] ?? '' )
    );
    //input contains only numbers and valid phone symbols
        //$mobile = preg_replace( '/[^0-9+() -]/', '', $mobile );

    $password = $_POST['gswa_password'] ?? '';
    $confirm_password = $_POST['gswa_confirm_password'] ?? '';


// Validate required fields-added mobile
if ( empty( $first_name ) ||
     empty( $last_name ) || 
     empty( $email )||
     empty($mobile) || 
     empty( $password ) ) {

    echo '<p>Please complete all required fields.</p>';

   } elseif ( ! preg_match( "/^[A-Za-z\s'-]+$/", $first_name ) ) {

    echo '<p>First Name must contain English letters only.</p>';

} elseif ( ! preg_match( "/^[A-Za-z\s'-]+$/", $last_name ) ) {

    echo '<p>Last Name must contain English letters only.</p>';

} elseif ( ! is_email( $email ) ) {

    echo '<p>Please enter a valid email address.</p>';

//validation mobileno
} elseif ( ! gswa_validate_mobile( $mobile ) ) {

    echo '<p>Please enter a valid Bangladesh mobile number.</p>';

 }elseif ( email_exists( $email ) ) {

    echo '<p>This email address is already registered.</p>';

} elseif ( $password !== $confirm_password ) {

    echo '<p>Passwords do not match.</p>';

} elseif ( strlen( $password ) < 8 ) {

    echo '<p>Password must be at least 8 characters long.</p>';

} else {

    // Create WordPress user - wp-users is a corefile
    $user_id = wp_insert_user(
        array(
            'user_login' => $email,
            'user_email' => $email,
            'user_pass'  => $password,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'role'       => 'subscriber',
        )
    );




    //adding signin_token
    if ( ! is_wp_error( $user_id ) ) {

    update_user_meta(
        $user_id,
        'gswa_mobile',
        $mobile
    );

    /*
     * Store the newly registered email temporarily.
     * The email will NOT appear in the URL.
     */

    $signin_token = wp_generate_password(
        32,
        false,
        false
    );

    set_transient(
        'gswa_signin_' . $signin_token,
        $email,
        10 * MINUTE_IN_SECONDS
    );

    /*
     * Redirect to the GSWA Sign In page.
     * Only the temporary token appears in the URL.
     */

    $signin_url = add_query_arg(
        array(
            'registered'   => '1',
            'signin_token' => $signin_token,
        ),
        home_url( '/sign-in/' )
    );

    wp_safe_redirect( $signin_url );
    exit;

    //adding end

    
    
}else{
    echo '<p>Unable to create your account. Please try again.</p>';
}

    
}

       // Display test values
       /*
       echo '<p>First Name: ' . esc_html( $first_name ) . '</p>';
       echo '<p>Last Name: ' . esc_html( $last_name ) . '</p>';
       echo '<p>Email: ' . esc_html( $email ) . '</p>';
       echo '<p>Mobileno: ' . esc_html( $mobile ) . '</p>';
       */

 
    }
    // 2. Render the Form HTML (Inside the buffer)
    ?>

    <div class="gswa-membership-signup">

        <h3>GSWA Membership Sign Up</h3>

        <form method="post" action="">
            <!-- Correct implementation of the WP Nonce field -->
<?php wp_nonce_field( 'gswa_signup_action', 'gswa_signup_nonce' ); ?>
            <p>
                <label for="gswa_first_name">First Name</label><br>
                <!--<input type="text" name="gswa_first_name"  required>-->
                <input type="text" name="gswa_first_name" pattern="[A-Za-z\s'-]+" title="Please enter English letters only." required>
            </p>

            <p>
                <label for="gswa_last_name">Last Name</label><br>
                <input type="text" name="gswa_last_name" pattern="[A-Za-z\s'-]+" title="Please enter English letters only." required>
            </p>

            <p>
                <label for="gswa_email">Email</label><br>
                <input type="email" name="gswa_email" required>
            </p>
            <p>
                <!-- Added Mobileno-->
                <label for="user_mobile">Mobileno</label><br>
                <input type="tel" name="phone" required>
            </p>

            <p>
                <label for="gswa_password">Password</label><br>
                <input type="password" name="gswa_password" required>
            </p>

            <p>
                <label for="gswa_confirm_password">Confirm Password</label><br>
                <input type="password" name="gswa_confirm_password" required>
            </p>

            <p>
                <button type="submit">Sign Up</button>
            </p>

        </form>

    </div>

    <?php
   // Return the clean buffer content
    return ob_get_clean();
}
