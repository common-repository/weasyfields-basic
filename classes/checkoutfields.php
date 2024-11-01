<?php

namespace WeasyFields;

/**
 * CheckoutFields class
 */
class CheckoutFields extends General
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
        add_action( 'admin_menu', function(){
            // Fields menu
            add_submenu_page(
                'weasyfields-settings',
                esc_html__( 'Checkout Fields', 'weasyfields' ),
                esc_html__( 'Checkout Fields', 'weasyfields' ),
                'manage_options',
                'weasyfields-checkout-fields',
                array(
                    $this,
                    'checkout_fields_page'
                )
            );
        });
    }

    /**
     * CheckoutFields page
     * @return void
     */
    public function checkout_fields_page()
    {
        $this->view( 'checkoutfields' );
    }
}