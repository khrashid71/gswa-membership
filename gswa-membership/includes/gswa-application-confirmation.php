<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Shortcode: [application_submission_confirmation]
 *
 * Displays the membership application confirmation message.
 */
function gswa_application_confirmation() {

    // Start output buffering.
    ob_start();

    // Get current logged-in user ID.
    $current_user_id = get_current_user_id();

    // Retrieve application success message.
    $success_message = get_transient(
        'gswa_form_success_' . $current_user_id
    );

    ?>

    <div class="gswa-confirmation-wrapper">

        <div class="gswa-confirmation-card">

            <div class="gswa-confirmation-icon">
                &#10004;
            </div>

            <h2>Application Submitted Successfully!</h2>

            <p class="gswa-confirmation-subtitle">
                Thank you for submitting your membership application
                to Gopalganj Social Welfare Association.
            </p>

            <?php if ( $success_message ) : ?>

                <div class="gswa-success-message">
                    <?php echo esc_html( $success_message ); ?>
                </div>

                <?php
                // Delete message after displaying it.
                delete_transient(
                    'gswa_form_success_' . $current_user_id
                );
                ?>

            <?php endif; ?>

            <div class="gswa-application-info">

                <h3>What happens next?</h3>

                <p>
                    Your application is now awaiting review by the
                    authorized GSWA committee.
                </p>

                <p>
                    <strong>Application Status:</strong>
                    <span class="gswa-pending-label">Now Pending,Further update Please Revisit Application Form -online </span>
                    
                </p>

                <p class="gswa-free-registration">
                    Online membership registration is FREE until
                    June 30, 2027.
                </p>

                <p>
                    No registration payment is required at this stage.
                </p>

            </div>

            <a class="gswa-home-button" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                Return to Home Page
            </a>

            <p class="gswa-confirmation-footer">
                Gopalganj Social Welfare Association
                <br>
                Friendship • Service • Peace • Development
            </p>

        </div>

    </div>

    <?php

    return ob_get_clean();
}

add_shortcode(
    'application_submission_confirmation',
    'gswa_application_confirmation'
);