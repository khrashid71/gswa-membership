<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GSWA Executive Account Management
 *
 * Manages President and Managing Director accounts.
 */

/**
 * Register Executive Accounts submenu.
 */
function gswa_executive_accounts_admin_menu() {

    add_submenu_page(
        'gswa-membership',
        'GSWA Executive Account Management', //Page Title
        'Executive Accounts', // Menu Title
        'manage_options',   //Capability
        'gswa-executive-accounts', //Menu slug
        'gswa_executive_accounts_dashboard' //callback function
    );

     
}

/**
 * Executive Accounts dashboard. call back function
 */

/**
 * Executive Accounts dashboard.
 */
function gswa_executive_accounts_dashboard() {

    // Security check.
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'You are not allowed to access this page.' );
    }

    // Retrieve executive accounts.
    $executives = get_users(
        array(
            'meta_key'     => 'gswa_exec_position',
            'meta_compare' => 'EXISTS',
            'orderby'      => 'ID',
            'order'        => 'ASC',
        )
    );

    ?>

    <div class="wrap">

        <h1>GSWA Executive Account Management</h1>

        <p>
            Manage Presidents and Managing Directors
            of Central/District and Upazila committees.
        </p>

        <hr>

        <h2>Executive Accounts</h2>

        <p>
            <!--<a href="#" class="button button-primary">
                Add New Executive
            </a>-->
           <button
                    type="button"
                    id="gswa-toggle-executive-form"
                    class="button button-primary"
                    aria-expanded="false"
                >
           Add New Executive
          </button>
            
            
        </p>

        <table class="widefat striped">

            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Position</th>
                    <th>Jurisdiction</th>
                    <th>Tenure</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                <?php if ( ! empty( $executives ) ) : ?>

                    <?php foreach ( $executives as $executive ) : ?>

                        <?php

                        $position = get_user_meta(
                            $executive->ID,
                            'gswa_exec_position',
                            true
                        );

                        $jurisdiction = get_user_meta(
                            $executive->ID,
                            'gswa_exec_jurisdiction',
                            true
                        );

                        $start_date = get_user_meta(
                            $executive->ID,
                            'gswa_exec_start_date',
                            true
                        );

                        $end_date = get_user_meta(
                            $executive->ID,
                            'gswa_exec_end_date',
                            true
                        );

                        $status = get_user_meta(
                            $executive->ID,
                            'gswa_exec_status',
                            true
                        );

                        $positions = array(
                            'P' => 'President',
                            'M' => 'Managing Director',
                        );

                        $jurisdictions = array(
                            '0' => 'Central / District',
                            '1' => 'Muksudpur',
                            '2' => 'Kashiani',
                            '3' => 'Gopalganj Sadar',
                            '4' => 'Kotalipara',
                            '5' => 'Tungipara',
                        );

                        ?>

                        <tr>

                            <td>
                                <?php echo esc_html( $executive->ID ); ?>
                            </td>

                            <td>
                                <?php echo esc_html( $executive->user_login ); ?>
                            </td>

                            <td>
                                <?php echo esc_html( $executive->display_name ); ?>
                            </td>

                            <td>
                                <?php
                                echo esc_html(
                                    $positions[ $position ] ?? 'Unknown'
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo esc_html(
                                    $jurisdictions[ (string) $jurisdiction ] ?? 'Unknown'
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo esc_html( $start_date ?: 'Not set' );
                                ?>
                                –
                                <?php
                                echo esc_html( $end_date ?: 'Not set' );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo esc_html(
                                    $status ?: 'Not set'
                                );
                                ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else : ?>

                    <tr>
                        <td colspan="7">
                            No executive accounts found.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

        <div id="gswa-add-executive-form" style="display: none;">
        <?php
        //function call from gswa-add-new-executive.php
        gswa_render_add_executive_form();
       ?>
       </div>

    </div> <!--wrap end here-->

    <script>
document.addEventListener('DOMContentLoaded', function () {

    const toggleButton = document.getElementById(
        'gswa-toggle-executive-form'
    );

    const executiveForm = document.getElementById(
        'gswa-add-executive-form'
    );

    toggleButton.addEventListener('click', function () {

        const isHidden = executiveForm.style.display === 'none';

        executiveForm.style.display = isHidden ? 'block' : 'none';

        toggleButton.textContent = isHidden
            ? 'Close Executive Form'
            : 'Add New Executive';

        toggleButton.setAttribute(
            'aria-expanded',
            isHidden ? 'true' : 'false'
        );

    });

});
</script>

    <?php 
}
add_action(
    'admin_menu',
    'gswa_executive_accounts_admin_menu',20  // gswa_membership_admin_menu -priority by default 10
);