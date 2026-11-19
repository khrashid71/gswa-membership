<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Shortcode: [gswa_application_status]
 */
add_shortcode( 'gswa_application_status', 'gswa_display_application_status' );


function gswa_display_application_status() {

    // Ensure applicant is logged in.
    if ( ! is_user_logged_in() ) {

        return '<p>Please sign in to view your application status.</p>';
    }

    global $wpdb;

    // Current logged-in user ID.
    $user_id = get_current_user_id();

    // Applications table.
    $table_name = $wpdb->prefix . 'gswa_applications';

    // Retrieve the current user's application.
    $application = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT application_number,
                    member_type,
                    native_upazila,
                    applicant_full_name,
                    mobile_number,
                    application_status
             FROM {$table_name}
             WHERE user_id = %d
             ORDER BY application_id DESC
             LIMIT 1",
            $user_id
        )
    );

    // No application found.
    if ( ! $application ) {

        return '<p>No membership application was found.</p>';
    }

    ob_start();

    ?>

    <div class="gswa-application-status">

        <h3>Membership Application Status</h3>

        <table>

            <tr>
                <th>Application ID</th>
                <td>
                    <?php echo esc_html( $application->application_number ); ?>
                </td>
            </tr>

            <tr>
                <th>Member Type</th>
                <td>
                    <?php echo esc_html( $application->member_type ); ?>
                </td>
            </tr>

            <tr>
                <th>Native Upazila</th>
                <td>
                    <?php echo esc_html( $application->native_upazila ); ?>
                </td>
            </tr>

            

            <tr>
                <th>Full Name</th>
                <td>
                    <?php echo esc_html( $application->applicant_full_name ); ?>
                </td>
            </tr>

            <tr>
                <th>Mobile Number</th>
                <td>
                    <?php echo esc_html( $application->mobile_number ); ?>
                </td>
            </tr>

            <tr>
                <th>Application Status</th>
                <td>
                    <strong>
                        <?php echo esc_html( $application->application_status ); ?>
                    </strong>
                </td>
            </tr>

        </table>

    </div>

    <?php

    return ob_get_clean();
}