<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

//Helper function for mobile make unique 


/**
 * Check if a mobile number is already registered.
 *
 * @param string $mobile The mobile number to check.
 * @return bool True if it exists, false otherwise.
 */

function gswa_mobile_exists( $mobile ) {
    if ( empty( $mobile ) ) {
        return false;
    }

    $users = get_users( array(
        'meta_key'   => 'gswa_mobile',
        'meta_value' => $mobile,
        'number'     => 1,
        'fields'     => 'ID',
    ) );

    return ! empty( $users );
}



/**
 * Write message to PHP error log -helper.php
 */
function gswa_membership_log( $message ) {

    if ( is_array( $message ) || is_object( $message ) ) {
        $message = print_r( $message, true );
    }

    error_log( '[GSWA-Mebership] ' . $message );

    
}





