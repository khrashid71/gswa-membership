<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add  Executives for admin panel
 *
 * Manages President and Managing Director accounts.
 */

/**
 * Render Add New Executive Form.
 */
function gswa_render_add_executive_form() {

    // Administrator permission check.
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'You are not allowed to access this page.' );
    }

    ?>

    <div class="wrap">

        <h2>Add New Executive</h2>

        <form method="post"
              action="<?php echo esc_url( admin_url( 'admin.php?page=gswa-executive-accounts' ) ); ?>">

            <?php
            // WordPress security nonce.
            wp_nonce_field(
                'gswa_add_executive_action',
                'gswa_add_executive_nonce'
            );
            ?>
           <!--This hidden field tells PHP that the Executive Account form has been submitted.-->
            <input
                type="hidden"
                name="gswa_add_executive_submit"
                value="1"
            >

            <table class="form-table">

                <!-- Full Name -->
                <tr>
                    <th>
                        <label for="executive_full_name">
                            Full Name
                        </label>
                    </th>

                    <td>
                        <input
                            type="text"
                            id="executive_full_name"
                            name="executive_full_name"
                            class="regular-text"
                            required
                        >
                    </td>
                </tr>

                <!-- Position -->
                <tr>
                    <th>
                        <label for="executive_position">
                            Position
                        </label>
                    </th>

                    <td>
                        <select
                            id="executive_position"
                            name="executive_position"
                            required
                        >
                            <option value="">Select Position</option>
                            <option value="P">President</option>
                            <option value="M">Managing Director</option>
                        </select>
                    </td>
                </tr>

                <!-- Jurisdiction -->
                <tr>
                    <th>
                        <label for="executive_jurisdiction">
                            Jurisdiction
                        </label>
                    </th>

                    <td>
                        <select
                            id="executive_jurisdiction"
                            name="executive_jurisdiction"
                            required
                        >
                            <option value="">Select Jurisdiction</option>
                            <option value="0">Central / District</option>
                            <option value="1">Muksudpur</option>
                            <option value="2">Kashiani</option>
                            <option value="3">Gopalganj Sadar</option>
                            <option value="4">Kotalipara</option>
                            <option value="5">Tungipara</option>
                        </select>
                    </td>
                </tr>

                <!-- Name Identifier -->
                <tr>
                    <th>
                        <label for="executive_identifier">
                            Name Identifier
                        </label>
                    </th>

                    <td>
                        <input
                            type="text"
                            id="executive_identifier"
                            name="executive_identifier"
                            class="regular-text"
                            minlength="5"
                            maxlength="10"
                            placeholder="Example: Krashid"
                            required
                        >

                        <p class="description">
                            Enter the short name identifier
                            used to generate the executive username.
                        </p>
                    </td>
                </tr>

                <!-- Username Preview -->
                <tr>
                    <th>
                        <label for="executive_username_preview">
                            Username Preview
                        </label>
                    </th>

                    <td>
                        <input
                            type="text"
                            id="executive_username_preview"
                            class="regular-text"
                            value=""
                            readonly
                        >

                        <p
                            id="executive_username_message"
                            class="description"
                        >
                            Select Position, Jurisdiction and enter Name Identifier
                            to generate username.
                        </p>
                    </td>
                </tr>
                <!-- Initial Password -->
                <tr>
                    <th>
                        <label for="executive_initial_password">
                            Initial Password
                        </label>
                    </th>

                    <td>
                        <input
                            type="password"
                            id="executive_initial_password"
                            name="executive_initial_password"
                            class="regular-text"
                            minlength="9"
                            autocomplete="new-password"
                            required
                        >

                        <p class="description">
                            Administrator creates the initial password.
                            Minimum 9 characters required.
                        </p>
                    </td>
                </tr>

            </table>

            <?php
            submit_button( 'Create Executive' );
            ?>

        </form>

    </div>

    
  
   

    <?php
}

//Adding here
/**
 * Handle Executive Form Submission.
 */
function gswa_handle_add_executive_submission() {

    // Process only our executive form.
    if (
        ! isset( $_POST['gswa_add_executive_submit'] ) ||
        '1' !== $_POST['gswa_add_executive_submit']
    ) {
        return;
    }

    // Administrator permission check.
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'You are not allowed to perform this action.' );
    }

    // Verify security nonce.
    check_admin_referer(
        'gswa_add_executive_action',
        'gswa_add_executive_nonce'
    );

    // Retrieve submitted values.
    $full_name = isset( $_POST['executive_full_name'] )
        ? sanitize_text_field( wp_unslash( $_POST['executive_full_name'] ) )
        : '';

    $position = isset( $_POST['executive_position'] )
        ? sanitize_text_field( wp_unslash( $_POST['executive_position'] ) )
        : '';

    $jurisdiction = isset( $_POST['executive_jurisdiction'] )
        ? sanitize_text_field( wp_unslash( $_POST['executive_jurisdiction'] ) )
        : '';

    $identifier = isset( $_POST['executive_identifier'] )
        ? sanitize_text_field( wp_unslash( $_POST['executive_identifier'] ) )
        : '';

    // Validate Full Name.
    if ( '' === $full_name ) {
        wp_die( 'Please enter the executive full name.' );
    }

    // Validate Position.
    if ( ! in_array( $position, array( 'P', 'M' ), true ) ) {
        wp_die( 'Please select a valid executive position.' );
    }

    // Validate Jurisdiction.
    $valid_jurisdictions = array( '0', '1', '2', '3', '4', '5' );

    if ( ! in_array( $jurisdiction, $valid_jurisdictions, true ) ) {
        wp_die( 'Please select a valid jurisdiction.' );
    }

    // Validate Name Identifier.
    if (
        ! preg_match( '/^[A-Za-z]{5,10}$/', $identifier )){

        wp_die(
            'Name Identifier must contain 5–10 English letters only.'
        );
    }


       $identifier = ucfirst( strtolower( $identifier ) );

    // Generate WordPress username.
    $username = $position . $jurisdiction . $identifier;

    // Check for duplicate username.
    if ( username_exists( $username ) ) {
        wp_die(
            'This username already exists: ' . esc_html( $username )
        );
    }

    // Validation successful. No account created yet.
    wp_die(
        'Validation successful! Username ' .
        esc_html( $username ) .
        ' is available. No account has been created.'
    );
}

add_action(
    'admin_init',
    'gswa_handle_add_executive_submission'
);