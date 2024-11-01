<?php defined( 'ABSPATH' ) || exit;

// Autoload classes.
spl_autoload_register( function( $class_name ) {
    $find    = array( 'weasyfields\\', '\\' );
    $replace = array( '', '/' );
    $class_name = str_replace( $find, $replace, strtolower( $class_name ) );
    $class_file = WEASYFIELDS_PATH . 'classes/' . $class_name . '.php';
    if ( true === file_exists( $class_file ) ) {
        require_once $class_file;
    }
});

// İnstall WeasyFields
require_once WEASYFIELDS_PATH . 'install.php';

// Load the entire WeasyFields with the Loader class.
add_action( 'plugins_loaded', function(){
    // If you have WooCommerce run it.
    if ( function_exists( 'WC' ) ) {
        new \WeasyFields\Loader;
    } else {
        add_action( 'admin_notices', function(){
            ?>
            <div class="notice notice-error">
                <p><?php echo esc_html__( 'The “WeasyFields” plugin cannot run without WooCommerce active. Please install and activate WooCommerce plugin.', 'weasyfields' ); ?></p>
            </div>
            <?php
        });
    }
});