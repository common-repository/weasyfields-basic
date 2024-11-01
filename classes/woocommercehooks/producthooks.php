<?php 

namespace WeasyFields\WooCommerceHooks;

class ProductHooks extends \WeasyFields\General
{
    use DataValidations;

    /**
     * Class construct
     */
    public function __construct()
    {
        $this->setDB();
        if ( is_admin() ) {
            $this->admin_hooks();
        } else {
            $this->frontend_hooks();
        }
    }

    /**
     * Processes that will run on WooCommerce back-end.
     * @return void
     */
    public function admin_hooks()
    {
        // Actions
        add_action( 
            'woocommerce_product_options_advanced',
            array( $this, 'product_custom_fields' )
        );
        add_action( 
            'woocommerce_process_product_meta',
            array( $this, 'product_custom_fields_save' ), 10, 1
        );
        add_action( 'woocommerce_after_order_itemmeta',
            array( $this, 'product_order_page_data_preview' ), 10, 3
        );
    }
    
    /**
     * Processes that will run on WooCommerce front-end.
     * @return void
     */
    public function frontend_hooks()
    {
        // Filters
        add_filter(
            'woocommerce_add_to_cart_validation', 
            array( $this, 'product_add_to_cart_validation' ), 10, 4 
        );
        add_filter( 'woocommerce_add_cart_item_data',
            array( $this, 'product_add_to_cart_item_data' ), 10, 2
        );
        add_filter( 'woocommerce_get_item_data', 
            array( $this, 'product_show_data_in_cart' ), 10, 2
        );
        // Actions
        add_action( 
            'woocommerce_before_add_to_cart_button',
            array( $this, 'product_custom_fields_display' )
        );
        add_action( 'woocommerce_checkout_create_order_line_item',
            array( $this, 'product_update_order_item_meta' ), 20, 4 
        );
        add_action( 'woocommerce_remove_cart_item',
            array( $this, 'product_cart_item_remove_delete_file' ), 10, 2
        );
        add_action( 'woocommerce_email_after_order_table', 
            array( $this, 'product_new_order_email_meta_data' ), 10, 4
        );
    }

    /**
     * List my fields in meta field on WooCommerce wp-admin add and update product page.
     * @return void
     */
    public function product_custom_fields()
    {
        $result = $this->db->get_results( "SELECT * FROM `{$this->table}` WHERE `fields_type` = 'product'" );

        $fields['none'] = esc_html__( 'None', 'weasyfields' );
        foreach ( $result as $key ) {
            $fields[$key->fields_key] = esc_html( $key->fields_title );
        }

        woocommerce_wp_select( 
			array( 
				'id'      => 'weasyfields_product_fields', 
				'label'   => esc_html__( 'Select the product areas', 'weasyfields' ), 
				'value'   => get_post_meta( get_the_ID(), 'weasyfields_product_fields', true ),
                'options' => $fields
            )
        );
    }

    /**
     * Record the ID of the fields selected for the product.
     * @param $post_id ID of the related product
     * @return void
     */
    public function product_custom_fields_save( $post_id )
    {
        // prepare
        $fields  = isset( $_POST['weasyfields_product_fields'] ) ? sanitize_text_field( $_POST['weasyfields_product_fields'] ) : '';
        // save
        $product = wc_get_product( $post_id );
        $product->update_meta_data( 'weasyfields_product_fields', $fields );
        $product->save();
    }

    /**
     * It shows the fields set for the product in the add to cart section on the product page.
     * @return void
     */
    public function product_custom_fields_display()
    {
        global $post;
        $product    = wc_get_product( $post->ID );
        $fields_key = $product->get_meta( 'weasyfields_product_fields' );
        $fields     = $this->get_fields( $fields_key );
        // If the possible product fields have been removed.
        if ( $fields && 'product' === $fields->fields_type ) {
            $fields = unserialize( $fields->fields_data );
            $this->view_i( 'fields-frontend', array( 'fields' => $fields, 'id' => $post->ID ) );
        }
    }

     /**
     * @param bool $passed status.
     * @param integer $product_id Product ID to be added to the cart
     * @return bool
     */
    public function product_add_to_cart_validation( $passed, $product_id )
    {   
        // ID of those who came for me
        $data_key = strval( 'weasyfields_'.$product_id );

        // Let's get the data of the relevant fields
        $product    = wc_get_product( $product_id );
        $fields_key = $product->get_meta( 'weasyfields_product_fields' );

        /**
         * Let's stop here as there is no place to fill when product fields are removed.
         */
        if ( empty( $fields_key ) ) {
            return true;
        }

        /**
         * If there are product fields but the user has not filled them in or adds to the cart with ajax, let know.
         */
        if ( 'none' !== $fields_key && true === $this->required_status( $fields_key, 'product' ) ) {
            if ( ! isset( $_POST[$data_key] ) ) {
                wc_add_notice(
                    esc_html__( 'Please fill in the product fields before adding to cart.', 'weasyfields' ),
                    'error'
                );
                return false;
            }
        }

        $passed = $this->start_validation( $data_key, $fields_key, 'product', $passed );
        
        // Continue if no error
        return $passed;
    }

    /**
     * Let's add your data to the cart.
     * @param null $cart_item null variable
     * @param integer $product_id ID of the related product
     * @return array $cart_item it carries my data.
     */
    public function product_add_to_cart_item_data( $cart_item, $product_id )
    {
        // ID of those who came for me
        $data_key = strval( 'weasyfields_'.$product_id );

        // Is there any file data sent for me?
        if ( isset( $_FILES[$data_key] ) ) {
            $cart_item[$data_key]['files'] = $this->file_upload( $data_key );
            // Remove data after adding it to the cart.
            unset( $_FILES[$data_key] );
        }

        // Is there any post data sent for me?
        if ( isset( $_POST[$data_key] ) ) {
            $cart_item[$data_key]['data'] = $_POST[$data_key];
            // Remove data after adding it to the cart.
            unset( $_POST[$data_key] );
        }

        // Avoid merging items
        $cart_item['unique_key'] = md5( microtime().rand() );

        return $cart_item;
    }

    /**
     * Add meta while creating orders.
     * @param object $item created order
     * @param mixed $cart_item_key order key in the cart
     * @param array $values data from cart
     * @return void
     */
    public function product_update_order_item_meta( $item, $cart_item_key, $values )
    {
        $data_key = strval( 'weasyfields_'.$item->legacy_values['product_id'] );
        if ( isset( $values[$data_key] ) ) {
            $item->update_meta_data( 'weasyfields_data',  $values[$data_key] );
        }
    }

    /**
     * Show my data in the cart.
     * @param array $cart_item_data move my data to show in the cart.
     * @param array $cart_item data from cart
     * @return array $cart_item_data move my data to show in the cart.
     */
    public function product_show_data_in_cart( $cart_item_data, $cart_item )
    {
        $data_key = strval( 'weasyfields_'.$cart_item['product_id'] );

        if ( isset( $cart_item[$data_key] ) ) {
            if ( isset( $cart_item[$data_key]['data'] ) ) {
                $data_array = $cart_item[$data_key]['data'];
                foreach ( $data_array as $data ) {
                    if ( ! empty( $data['field_value'] ) ) {
                        if ( is_array( $data['field_value'] ) ) {
                            $value = '';
                            foreach ( $data['field_value'] as $val ) {
                                $value .= "-$val" . PHP_EOL;
                            }
                        } else {
                            $value = $data['field_value'];
                        }
                        $cart_item_data[] = array(
                            'name'  => $data['field_label'],
                            'value' => $value
                        );
                    }
                }
            }
            if ( isset( $cart_item[$data_key]['files'] ) ) {
                $files = $cart_item[$data_key]['files'];
                foreach ( $files as $file ) {
                    if ( null !== $file['fake_name'] ) {
                        $cart_item_data[] = array(
                            'name'  => $file['label'],
                            'value' => $file['fake_name']
                        );
                    }
                }
            } 
        }

        return $cart_item_data;
    }

    /**
     * If the order in the cart is deleted, delete the file uploaded with that order.
     * @param mixed $cart_item_key Related order in the cart
     * @param object $cart all of the cart
     * @return void
     */
    public function product_cart_item_remove_delete_file( $cart_item_key, $cart ) 
    {
        $item = $cart->cart_contents[$cart_item_key];
        if ( isset( $item[ strval( 'weasyfields_'.$item['product_id'] ) ] ) ) {
            $data = $item[ strval( 'weasyfields_'.$item['product_id'] ) ];
            if ( isset( $data['files'] ) ) {
                foreach ( $data['files'] as $key => $value ) {
                    if ( file_exists( $value['path'] ) ) {
                        unlink( $value['path'] );
                    }
                }
            }
        }
    }

    /**
     * When the order is created, show the data in the document sent to the admin email.
     * @param object $order related order
     * @param object $email
     * @return void
     */
    public function product_new_order_email_meta_data( $order, $sent_to_admin, $plain_text, $email )
    {
        // On "new order" email notifications
        if ( 'new_order' === $email->id ) {
            foreach ( $order->get_items() as $item ) {
                $data = $item->get_meta( 'weasyfields_data' );
                $this->data_list_preview_creator( $data, true );
            }
        }
    }

    /**
     * Show data on the wp-admin order page.
     * @param object $item related order
     * @return void
     */
    public function product_order_page_data_preview( $item_id, $item )
    {
        // If a deletion is requested and the relevant file exists, delete it.
        if ( isset( $_GET['weasyfields_delete_file'] ) && file_exists( $_GET['weasyfields_delete_file'] ) ) {
            unlink( $_GET['weasyfields_delete_file'] );
        }
        $data = $item->get_meta( 'weasyfields_data' );
        $this->data_list_preview_creator( $data );
    }

}