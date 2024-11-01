<?php defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Plugin uninstall process
if ( 'on' === get_option( 'weasyfields_settings' )['data_deletion_status'] ) {
    delete_option( 'weasyfields_settings' );
    delete_option( 'weasyfields_billing_fields' );
    delete_option( 'weasyfields_shipping_fields' );
    delete_option( 'weasyfields_db_version' );
    // delete plugin database tables
    global $wpdb;
    $weasyfields = $wpdb->prefix . "weasyfields";
    $wpdb->query( "DROP TABLE IF EXISTS $weasyfields" );
}