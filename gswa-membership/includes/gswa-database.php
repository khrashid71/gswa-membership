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

/**
 * Create the GSWA membership application reviews table.
 *
 * Database table:
 * {WordPress prefix}gswa_application_reviews
 */
function gswa_membership_create_review_table() {

    global $wpdb;

    $schema_version = '1.0.0';

    // Use a separate version option for the review table.
    if (
        get_option( 'gswa_membership_review_db_version' )
        === $schema_version
    ) {
        return;
    }

    // Load WordPress database upgrade functions.
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // Use actual WordPress table prefix.
    $table_name = $wpdb->prefix . 'gswa_application_reviews';

    $applications_table = $wpdb->prefix . 'gswa_applications';

    $charset_collate = $wpdb->get_charset_collate();

    // Review table structure.
    // Foreign key will be added separately.
    $sql = "CREATE TABLE {$table_name} (

        review_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,

        application_id bigint(20) unsigned NOT NULL,

        ec_meeting_reference varchar(100) NOT NULL,

        ec_meeting_date date NOT NULL,

        ec_meeting_time time DEFAULT NULL,

        ec_meeting_type varchar(20) NOT NULL,

        ec_meeting_location varchar(255) DEFAULT NULL,

        ec_meeting_platform varchar(50) DEFAULT NULL,

        ec_meeting_link text DEFAULT NULL,

        decision varchar(20) NOT NULL,

        decision_remarks text DEFAULT NULL,

        reviewed_by bigint(20) unsigned NOT NULL,

        reviewed_at datetime NOT NULL,

        PRIMARY KEY  (review_id),

        KEY application_id (application_id),

        KEY decision (decision)

    ) ENGINE=InnoDB {$charset_collate};";

    // Create or update the review table.
    dbDelta( $sql );

    // Verify that the review table exists.
    $table_exists = $wpdb->get_var(
        $wpdb->prepare(
            'SHOW TABLES LIKE %s',
            $wpdb->esc_like( $table_name )
        )
    );

    if ( $table_exists !== $table_name ) {
        return;
    }

    // Verify that both tables use InnoDB.
    $parent_engine = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT ENGINE
            FROM information_schema.TABLES
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = %s",
            $applications_table
        )
    );

    $review_engine = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT ENGINE
            FROM information_schema.TABLES
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = %s",
            $table_name
        )
    );

    if (
        strtolower( (string) $parent_engine ) !== 'innodb'
        || strtolower( (string) $review_engine ) !== 'innodb'
    ) {
        error_log( 'GSWA: InnoDB engine verification failed.' );
        return;
    }

    // Check whether the foreign key already exists.
    $foreign_key_exists = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = %s
            AND COLUMN_NAME = 'application_id'
            AND REFERENCED_TABLE_NAME = %s
            AND REFERENCED_COLUMN_NAME = 'application_id'",
            $table_name,
            $applications_table
        )
    );

    // Add foreign key only if it does not already exist.
    if ( ! $foreign_key_exists ) {

        $constraint_name = 'fk_gswa_application_reviews';

        $result = $wpdb->query(
            "ALTER TABLE `{$table_name}`
            ADD CONSTRAINT `{$constraint_name}`
            FOREIGN KEY (`application_id`)
            REFERENCES `{$applications_table}` (`application_id`)
            ON DELETE RESTRICT
            ON UPDATE CASCADE"
        );

        if ( false === $result ) {

            error_log(
                'GSWA review table foreign key error: '
                . $wpdb->last_error
            );

            return;
        }
    }

    // Save version only after successful table and FK verification.
    update_option(
        'gswa_membership_review_db_version',
        $schema_version
    );
}

/**
 * Run review table setup when WordPress loads.
 */
add_action(
    'plugins_loaded',
    'gswa_membership_create_review_table'
);