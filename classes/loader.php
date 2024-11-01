<?php

namespace WeasyFields;

/**
 * WeasyFields loader class
 */
class Loader extends General
{
    /**
     * Class construct
     * @return void
     */
    public function __construct()
    {
        if ( 'on' === $this->setting( 'activation_status' ) ) {
            // Run WooCommerce product hooks
            new WooCommerceHooks\ProductHooks;
        }
        
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

        if ( is_admin() ) {
            $this->wp_admin_process();
        } else {
            $this->frontend_process();
        }
    }

    /**
     * Wp-admin process
     * @return void
     */
    public function wp_admin_process()
    {
        // Run settings page
        new Settings;

        if ( 'on' === $this->setting( 'activation_status' ) ) {
            // Run fields page
            new Fields;
            // Run checkout fields page
            new CheckoutFields;
        }

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        $plugin_file = dirname( plugin_basename( WEASYFIELDS_PLUGIN_FILE ) ) . '/weasyfields.php';
        add_filter( 'plugin_action_links_' . $plugin_file, array( $this, 'settings_link' ) );
    }

    public function settings_link( $links ) {
		$settings_link = array(
			'<a style="font-weight: bold;" target="_blank" href="https://1.envato.market/NnxA2">' . esc_html__( 'Buy Premium', 'weasyfields' ) . '</a>',
		);
		return array_merge( $settings_link, $links );
	}

    /**
     * Frontend process
     * @return void
     */
    public function frontend_process()
    {
        add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
    }

    /**
     * Load plugin text domain
     * @return void
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain( 'weasyfields', false, WEASYFIELDS_LANG_DIR );
    }
    
    /**
     * js and css files for wp-admin
     * @return void
     */
    public function admin_enqueue_scripts()
    {
        wp_enqueue_script( 'weasyfields-admin', WEASYFIELDS_URL . 'assets/js/admin.js', array( 'jquery','jquery-ui-sortable' ), WEASYFIELDS_VERSION );
        wp_enqueue_style( 'weasyfields-admin', WEASYFIELDS_URL . 'assets/css/admin.min.css', array(), WEASYFIELDS_VERSION );
    }

    /**
     * js and css files for frontend
     * @return void
     */
    public function wp_enqueue_scripts()
    {
        wp_enqueue_script( 'weasyfields-frontend', WEASYFIELDS_URL . 'assets/js/frontend.js', array( 'jquery' ), WEASYFIELDS_VERSION );
    }

}