<?php

namespace WeasyFields;

/**
 * Settings class
 */
class Settings extends General
{
    // Checkout fields page url
    public $url;

    /**
     * Class construct
     * @return void
     */
    public function __construct()
    {
        $this->setDB();
        
        // Current page url
        $this->url = admin_url( 'admin.php?page=weasyfields-settings' );

        add_action( 'admin_menu', function(){
            // General menu
            add_menu_page(
                esc_html__( 'WeasyFields', 'weasyfields' ),
                esc_html__( 'WeasyFields', 'weasyfields' ),
                'manage_options',
                'weasyfields-settings',
                array(
                    $this,
                    'settings_page'
                ),
                'dashicons-editor-table',
                25
            );
            // Settins menu
            add_submenu_page(
                'weasyfields-settings',
                esc_html__( 'Settings', 'weasyfields' ),
                esc_html__( 'Settings', 'weasyfields' ),
                'manage_options',
                'weasyfields-settings',
                array(
                    $this,
                    'settings_page'
                )
            );
        });
    }

    /**
     * WeasyFields settings page
     * @return void
     */
    public function settings_page()
    {      
        $this->view( 'settings' );
    }
}