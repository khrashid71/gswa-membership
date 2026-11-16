<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

//GSWA Payment Management shortcode
function gswa_payment_management() {
    return '<div class="gswa-payment">
                <h3>Payment-Member Application Registration Fee-Upazilla: 50.00 taka</h3>
                
                <h3>Payment-Member Application Registration Fee-Central/Zilla: 100.00 taka</h3>
                <h2><strong></strong>Payment options-Bikash & Nagod</strong></h2>
            </div>';
}
add_shortcode('gswa_payment', 'gswa_payment_management');

$current_user_id = get_current_user_id();

$success_message = get_transient(
    'gswa_form_success_' . $current_user_id
);

if ( $success_message ) {

    echo '<div class="gswa-success-message">';
    echo esc_html( $success_message );
    echo '</div>';

    delete_transient(
        'gswa_form_success_' . $current_user_id
    );
}