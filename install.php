<?php defined( 'ABSPATH' ) || exit;

/**
 * Plugin activate hook
 */
register_activation_hook( WEASYFIELDS_PLUGIN_FILE, function() {
    
    global $wpdb;

    if ( ! function_exists('dbDelta') )
    {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    }

    // Get $wpdb charset collate set charset
    if ( $wpdb->has_cap( 'collation' ) )
    {
        $charset = $wpdb->get_charset_collate();
    }

    /**
     * weasyfields table
     */
    $weasyfields = $wpdb->prefix . "weasyfields";
    if ( ! $wpdb->get_var("SHOW TABLES LIKE '{$weasyfields}'") !== $weasyfields )
    {
        $sql = "CREATE TABLE IF NOT EXISTS `{$weasyfields}` (
            `fields_id` BIGINT(20) AUTO_INCREMENT,
            `fields_title` text NOT NULL,
            `fields_key` varchar(200) NOT NULL,
            `fields_type` varchar(50) NOT NULL,
            `fields_status` varchar(50) NOT NULL,
            `fields_data` longtext NOT NULL,
            `fields_created_date` timestamp DEFAULT NOW(),
            PRIMARY KEY (`fields_id`),
            UNIQUE KEY `fields_key` (`fields_key`)
        ) {$charset};";

        dbDelta( $sql );
    }
    
    // Add weasyfields option column
    if ( empty( get_option( 'weasyfields_settings' ) ) ) {
        // Default settings
        $settings = array(
            'activation_status' => 'on',
            'data_deletion_status' => ''
        );
        add_option( 'weasyfields_settings', $settings );
    }
    
    if ( empty( get_option( 'weasyfields_billing_fields' ) ) ) {
        add_option( 'weasyfields_billing_fields', null );
    }

    if ( empty( get_option( 'weasyfields_shipping_fields' ) ) ) {
        add_option( 'weasyfields_shipping_fields', null );
    }

    update_option( 'weasyfields_db_version', '1.0' );     

});