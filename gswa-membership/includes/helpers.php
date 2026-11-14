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

/**
 * Normalize NID number.
 */
function gswa_normalize_nid( $nid ) {

    $nid = preg_replace( '/[\s-]+/', '', trim( (string) $nid ) );

    if ( empty( $nid ) || ! ctype_digit( $nid ) ) {
        return false;
    }

    return $nid;
}


/**
 * Derive separate encryption and fingerprint keys.
 */
function gswa_get_nid_key( $purpose ) {

    if (
        ! defined( 'GSWA_NID_MASTER_KEY' ) ||
        ! preg_match( '/^[a-fA-F0-9]{64}$/', GSWA_NID_MASTER_KEY )
    ) {
        return false;
    }

    $master_key = hex2bin( GSWA_NID_MASTER_KEY );

    if ( false === $master_key ) {
        return false;
    }

    return hash_hkdf(
        'sha256',
        $master_key,
        32,
        'gswa-nid-' . $purpose . '-v1'
    );
}

// Adding 4 functions to manage NID with security
//gswa_encrypt_nid();
//gswa_decrypt_nid();
//gswa_nid_fingerprint();
//gswa_nid_exists();
/**
 * 1. Encrypt NID using AES-256-GCM.
 */
function gswa_encrypt_nid( $nid ) {

    $nid = gswa_normalize_nid( $nid );

    if ( false === $nid ) {
        return false;
    }

    $key = gswa_get_nid_key( 'encryption' );

    if ( false === $key ) {
        return false;
    }

    $iv  = random_bytes( 12 );
    $tag = '';

    $encrypted = openssl_encrypt(
        $nid,
        'aes-256-gcm',
        $key,
        OPENSSL_RAW_DATA,
        $iv,
        $tag,
        '',
        16
    );

    if ( false === $encrypted ) {
        return false;
    }

    return 'v1:' . base64_encode(
        $iv . $tag . $encrypted
    );
}


/**
 * 2. Decrypt NID.
 */
function gswa_decrypt_nid( $encrypted_nid ) {

    if ( 0 !== strpos( $encrypted_nid, 'v1:' ) ) {
        return false;
    }

    $key = gswa_get_nid_key( 'encryption' );

    if ( false === $key ) {
        return false;
    }

    $data = base64_decode(
        substr( $encrypted_nid, 3 ),
        true
    );

    if ( false === $data || strlen( $data ) < 29 ) {
        return false;
    }

    $iv         = substr( $data, 0, 12 );
    $tag        = substr( $data, 12, 16 );
    $ciphertext = substr( $data, 28 );

    return openssl_decrypt(
        $ciphertext,
        'aes-256-gcm',
        $key,
        OPENSSL_RAW_DATA,
        $iv,
        $tag
    );
}


/**
 * 3. Generate HMAC-SHA256 NID fingerprint.
 */
function gswa_nid_fingerprint( $nid ) {

    $nid = gswa_normalize_nid( $nid );

    if ( false === $nid ) {
        return false;
    }

    $key = gswa_get_nid_key( 'fingerprint' );

    if ( false === $key ) {
        return false;
    }

    return hash_hmac(
        'sha256',
        $nid,
        $key
    );
}


/**
 * 4. Check whether NID already exists.
 *
 * Returns:
 * true  = NID exists
 * false = NID does not exist
 * WP_Error = verification failure
 */
function gswa_nid_exists( $nid, $exclude_application_id = 0 ) {

    global $wpdb;

    $fingerprint = gswa_nid_fingerprint( $nid );

    if ( false === $fingerprint ) {
        return new WP_Error(
            'gswa_nid_fingerprint_error',
            'Unable to generate NID fingerprint.'
        );
    }

    $table_name = $wpdb->prefix . 'gswa_applications';

    $sql = "SELECT application_id
            FROM {$table_name}
            WHERE nid_fingerprint = %s";

    $args = array( $fingerprint );

    if ( $exclude_application_id > 0 ) {

        $sql .= " AND application_id <> %d";

        $args[] = absint( $exclude_application_id );
    }

    $sql .= " LIMIT 1";

    $prepared_sql = $wpdb->prepare( $sql, $args );

    $result = $wpdb->get_var( $prepared_sql );

    if ( ! empty( $wpdb->last_error ) ) {
        return new WP_Error(
            'gswa_nid_database_error',
            'Unable to verify NID against the database.'
        );
    }

    return null !== $result;
}







