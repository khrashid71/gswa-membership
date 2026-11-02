<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Create the GSWA membership applications table.
 *
 * Database table:
 * {WordPress prefix}gswa_applications
 */
function gswa_membership_create_tables() {

    global $wpdb;

    $schema_version = '1.0.2';

    // Avoid unnecessary schema checks on every page load.
    if (
        get_option( 'gswa_membership_db_version' )
        === $schema_version
    ) {
        return;
    }

    // Load WordPress's database upgrade functions.
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // Use the actual WordPress database prefix.
    $table_name = $wpdb->prefix . 'gswa_applications';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE {$table_name} (

        application_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,

        application_number varchar(30) NOT NULL,

        user_id bigint(20) unsigned NOT NULL,

        eligibility varchar(30) NOT NULL,

        native_upazila varchar(50) NOT NULL,

        member_type varchar(30) NOT NULL,

        applicant_full_name varchar(150) NOT NULL,

        father_full_name varchar(150) NOT NULL,

        mother_full_name varchar(150) NOT NULL,

        date_of_birth date NOT NULL,

        blood_group varchar(5) DEFAULT NULL,

        present_address text NOT NULL,

        permanent_address text NOT NULL,

        education varchar(150) NOT NULL,

        profession varchar(150) NOT NULL,

        mobile_number varchar(20) NOT NULL,

        email varchar(150) NOT NULL,

        passport_photo_path varchar(255) DEFAULT NULL,

        declaration_accepted tinyint(1) NOT NULL DEFAULT 0,

        application_status varchar(20) NOT NULL DEFAULT 'pending',

        submitted_at datetime NOT NULL,

        updated_at datetime NOT NULL,

        PRIMARY KEY  (application_id),

        UNIQUE KEY application_number (application_number),

        KEY user_id (user_id),

        KEY application_status (application_status)

    ) {$charset_collate};";

    // Create or update the database table.
    dbDelta( $sql );

    // Verify that the table exists before saving the version.
    $table_exists = $wpdb->get_var(
        $wpdb->prepare(
            'SHOW TABLES LIKE %s',
            $wpdb->esc_like( $table_name )
        )
    );

    if ( $table_exists === $table_name ) {

        update_option(
            'gswa_membership_db_version',
            $schema_version
        );

    }
}

/**
 * Run the database setup when WordPress loads.
 */
add_action(
    'plugins_loaded',
    'gswa_membership_create_tables'
);