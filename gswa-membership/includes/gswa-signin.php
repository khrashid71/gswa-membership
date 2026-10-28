<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GSWA Membership Sign In
 */

add_shortcode( 'gswa_signin', 'gswa_membership_signin_form' );



function gswa_membership_signin_form() {

    ob_start();

    /*
     * Default:
     * Normal visitor → login field remains blank.
     */
    $login_identifier = '';

    /*
     * If user has just completed registration,
     * retrieve the temporary email using the token.
     */
    if (
        isset( $_GET['registered'] ) &&
        '1' === $_GET['registered'] &&
        isset( $_GET['signin_token'] )
    ) {

        $signin_token = sanitize_text_field(
            wp_unslash( $_GET['signin_token'] )
        );

        $transient_key = 'gswa_signin_' . $signin_token;

        $stored_email = get_transient( $transient_key );

        if ( false !== $stored_email ) {

            $login_identifier = sanitize_email( $stored_email );

            /*
             * This is a one-time handoff.
             * Remove the transient after retrieving it.
             */
            delete_transient( $transient_key );
        }
    }


    /*
     * Display registration success message.
     */
    if (
        isset( $_GET['registered'] ) &&
        '1' === $_GET['registered']
    ) {

        echo '<p class="gswa-success">';
        echo 'Registration successful! Please sign in to your GSWA account.';
        echo '</p>';
    }


    /*
     * Process Sign In form.
     */
    if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {

        /*
         * Verify nonce.
         */
        if (
            ! isset( $_POST['gswa_signin_nonce'] ) ||
            ! wp_verify_nonce(
                sanitize_text_field(
                    wp_unslash( $_POST['gswa_signin_nonce'] )
                ),
                'gswa_signin_action'
            )
        ) {

            wp_die(
                'Security verification failed. Please refresh the page and try again.'
            );
        }


        /*
         * Get submitted email or mobile number.
         */
        
 $login_identifier = sanitize_text_field(
    wp_unslash(
        $_POST['gswa_login'] ?? ''
    )
);

/*
 * Get submitted password.
 *
 * Do NOT sanitize the password.
 */
$password = $_POST['gswa_password'] ?? '';

/*
 * Validate required fields.
 */
if ( empty( $login_identifier ) || empty( $password ) ) {

    echo '<p class="gswa-error">';
    echo 'Please enter your email or mobile number and password.';
    echo '</p>';

} else {

    /*
     * Determine whether the identifier is an email
     * or a mobile number.
     */
    if ( is_email( $login_identifier ) ) {

        /*
         * Email login:
         * The current registration code uses email
         * as the WordPress username.
         */
        $user = get_user_by(
            'email',
            $login_identifier
        );

    } else {

        /*
         * Mobile login:
         * Find the WordPress user whose gswa_mobile
         * user meta matches the submitted number.
         */
        $users = get_users(
            array(
                'meta_key'   => 'gswa_mobile',
                'meta_value' => $login_identifier,
                'number'     => 2,
                'fields'     => 'all',
            )
        );

        /*
         * Accept the mobile number only if exactly
         * one matching account is found.
         */
        $user = ( 1 === count( $users ) )
            ? $users[0]
            : false;
    }

    /*
     * Authenticate using the identified WordPress
     * username and the submitted password.
     */
    if ( ! $user ) {

        echo '<p class="gswa-error">';
        echo 'Invalid email/mobile number or password. Please try again.';
        echo '</p>';

    } else {

        $authenticated_user = wp_signon(
            array(
                'user_login'    => $user->user_login,
                'user_password' => $password,
                'remember'      => false,
            ),
            is_ssl()
        );

        /*
         * Check authentication result.
         */
        if ( is_wp_error( $authenticated_user ) ) {

            echo '<p class="gswa-error">';
            echo 'Invalid email/mobile number or password. Please try again.';
            echo '</p>';

        } else {

            /*
             * Sign In successful.
             * Redirect to GSWA Member Application.
             */
            wp_safe_redirect(
                home_url( '/member-application/' )
            );

            exit;
        } // Close Sign In Successful
    } //Close authenticated_user = wp_signon
 } //Close Determine whether the identifier is an email
   //or a mobile number.

} //Close POST SERVER REQUEST_METHOD
    ?>

    <div class="gswa-membership-signin">

        <h3>GSWA Membership Sign In</h3>

        <form method="post" action="">

            <?php
            wp_nonce_field(
                'gswa_signin_action',
                'gswa_signin_nonce'
            );
            ?>

            <p>
                <label for="gswa_login">
                    Email or Mobile Number
                </label><br>

                <input
                    type="text"
                    id="gswa_login"
                    name="gswa_login"
                    value="<?php echo esc_attr( $login_identifier ?? '' ); ?>"
                    autocomplete="username"
                    requireds
                >
            </p>


            <p>
                <label for="gswa_password">
                    Password
                </label><br>

                <input
                    type="password"
                    id="gswa_password"
                    name="gswa_password"
                    autocomplete="current-password"
                    required
                >
            </p>


            <p>
                <a
                    href="<?php echo esc_url( home_url( '/forgot-password/' ) ); ?>"
                    id="forget-password"
                >
                    Forgot Password?
                </a>
            </p>


            <p>
                <button type="submit">
                    Sign In
                </button>
            </p>

        </form>

    </div>

    <?php

    return ob_get_clean();
}