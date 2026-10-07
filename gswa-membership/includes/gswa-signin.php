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
     * Normal visitor → email field remains blank.
     */
    $email = '';

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

            $email = sanitize_email( $stored_email );

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
         * Get submitted email.
         */
        $email = sanitize_email(
            wp_unslash(
                $_POST['gswa_email'] ?? ''
            )
        );


        /*
         * Get submitted password.
         *
         * Do NOT sanitize the password.
         * Passwords can legally contain special characters.
         */
        $password = $_POST['gswa_password'] ?? '';


        /*
         * Validate required fields.
         */
        if ( empty( $email ) || empty( $password ) ) {

            echo '<p class="gswa-error">';
            echo 'Please enter your email and password.';
            echo '</p>';

        } elseif ( ! is_email( $email ) ) {

            echo '<p class="gswa-error">';
            echo 'Please enter a valid email address.';
            echo '</p>';

        } else {

            /*
             * Authenticate the WordPress user.
             */
            $user = wp_signon(
                array(
                    'user_login'    => $email,
                    'user_password' => $password,
                    'remember'      => false,
                ),
                is_ssl()
            );


            /*
             * Check authentication result.
             */
            if ( is_wp_error( $user ) ) {

                echo '<p class="gswa-error">';
                echo 'Invalid email or password. Please try again.';
                echo '</p>';

            } else {

                /*
                 * Sign In successful.
                 *
                 * Redirect to GSWA Member Application.
                 */
                $application_url = home_url(
                    '/member-application/'
                );

                wp_safe_redirect( $application_url );
                exit;
            }
        }
    }

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
                <label for="gswa_email">
                    Email
                </label><br>

                <input
                    type="email"
                    id="gswa_email"
                    name="gswa_email"
                    value="<?php echo esc_attr( $email ); ?>"
                    autocomplete="username"
                    required
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