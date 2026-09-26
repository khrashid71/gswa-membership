<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode('gswa_forgot_password','gswa_forgot_password_form'
);
/**
 * GSWA Forgot Password Form
 */
function gswa_forgot_password_form() {

    ob_start();

    // Process submitted email.
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

        // Verify nonce.
        if (
            ! isset( $_POST['gswa_forgot_password_nonce'] ) ||
            ! wp_verify_nonce(
                $_POST['gswa_forgot_password_nonce'],
                'gswa_forgot_password_action'
            )
        ) {
            echo '<p>Security verification failed.</p>';
        } else {

            // Get and sanitize email.
            $email = sanitize_email( $_POST['gswa_email'] ?? '' );

            if ( empty( $email ) ) {

                echo '<p>Please enter your email address.</p>';

            } elseif ( ! is_email( $email ) ) {

                echo '<p>Please enter a valid email address.</p>';

            } else {

                // Ask WordPress to start the password-reset process.
                $result = retrieve_password( $email );

                if ( $result ) {

                    echo '<p>Password reset instructions have been sent to your email address.</p>';

                } else {

                    echo '<p>Unable to process the password reset request.</p>';
                }

                    //adding new code for testing mailpit
                    




                    //end adding here
            }
        }
    }
    ?>

    <h2>GSWA Forgot Password</h2>

    <form method="post">

        <?php wp_nonce_field(
            'gswa_forgot_password_action',
            'gswa_forgot_password_nonce'
        ); ?>

        <p>
            <label for="gswa_email">Email Address</label><br>

            <input
                type="email"
                name="gswa_email"
                id="gswa_email"
                required
            >
        </p>

        <p>
            <input
                type="submit"
                value="Send Reset Link"
            >
        </p>

    </form>

    <?php

    return ob_get_clean();
}


    