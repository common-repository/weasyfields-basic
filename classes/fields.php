<?php

namespace WeasyFields;

/**
 * Fields class
 */
class Fields extends General
{
    // Fields page url
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
                esc_html__( 'Fields', 'weasyfields' ),
                esc_html__( 'Fields', 'weasyfields' ),
                'manage_options',
                'weasyfields-fields',
                array(
                    $this,
                    'fields_page'
                )
            );
        });
    }

    /**
     * Fields page
     * @return void
     */
    public function fields_page()
    {
        // Current page url
        $this->url = admin_url( 'admin.php?page=weasyfields-fields' );

        if ( isset( $_POST['action'] ) ){
            if ( ! isset( $_POST['nonce'] ) || ! @wp_verify_nonce( $_POST['nonce'], 'weasyfields-nonce' ) ) {
                $this->notice( 'error', esc_html__( 'Sorry something went wrong.', 'weasyfields' ), true );
            } else {

                // prepare
                $title  = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : null;
                $type   = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : null;
                $status = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : null;

                $process_status     = true;
                $fields_placeholder = isset( $_POST['fields'] ) ? $_POST['fields'] : array();
                $fields = array();
                foreach ( $fields_placeholder as $index => $array ) {
                    // data array open
                    foreach ( $array as $key => $value ) {
                        if ( "htmlcode" === $key ) {
                            // if it's html_code
                            $fields[$index][$key] = wp_kses_post( $value );
                        } elseif ( "field_opt" === $key || "file_permitted" === $key ) {
                            //if it's field_opt or filed_permitted
                            foreach ( $value as $opt_index => $opt_value ) {
                                $fields[$index][$key][] = sanitize_text_field( $opt_value );
                            }
                        } else {
                            // If normal
                            $fields[$index][$key] = sanitize_text_field( $value );
                        }
                    }
                    if ( 'file' === $array['field_key'] ) {
                        if ( ! isset( $array['file_permitted'] ) ) {
                            $this->notice( 'error', esc_html__( 'Please select at least one file extension.', 'weasyfields' ), true );
                            $process_status = false;
                        } elseif ( ! isset( $array['field_max_size'] ) ) {
                            $this->notice( 'error', esc_html__( 'Please enter the maximum file size value.', 'weasyfields' ), true );
                            $process_status = false;
                        } elseif ( 0 >= absint( $array['field_max_size'] ) ) {
                            $this->notice( 'error', esc_html__( 'Maximum file size value should be at least 1.', 'weasyfields' ), true );
                            $process_status = false;
                        }
                    }
                }

                if ( true === $process_status && "add" === $_POST['action'] ) {

                    // Is there a key with the same name?
                    $fields_key = sanitize_title( $title );
                    $fields_key = $this->unique_key( $fields_key );

                    $data = array(
                        "fields_title"  => $title,
                        "fields_key"    => $fields_key,
                        "fields_type"   => $type,
                        "fields_status" => 'publish',
                        "fields_data"   => serialize( $fields )
                    );
        
                    $result = $this->db->insert( $this->table, $data );

                    if ( false !== $result ) {
                        wp_redirect( $this->url . '&added&action=edit&id=' . $this->db->insert_id );
                    } else {
                        $this->notice( 'error', esc_html__( 'Sorry something went wrong.', 'weasyfields' ), true );
                    }

                } elseif ( true === $process_status && "update" === $_POST['action'] ) {
            
                    $data = array(
                        "fields_title"  => $title,
                        "fields_type"   => $type,
                        "fields_status" => $status,
                        "fields_data"   => serialize( $fields )
                    );

                    $where = array(
                        'fields_id' => absint( $_GET['id'] )
                    );
            
                    $result = $this->db->update( $this->table, $data, $where );

                    if ( false !== $result ) {
                        $this->notice( 'success', esc_html__( 'Fields updated successfully.', 'weasyfields' ), true );
                    } else {
                        $this->notice( 'error', esc_html__( 'Sorry something went wrong.', 'weasyfields' ), true );
                    }

                }
            }

        } // end action process
        
        if ( isset( $_GET['action'] ) ) {

            if ( "edit" === $_GET['action'] ) {

                $id    = absint( $_GET['id'] );
                $query = $this->db->prepare( "SELECT * FROM `{$this->table}` WHERE `fields_id` = %d", $id );
                $col   = $this->db->get_row( $query );
                
                if ( null === $col ) {
                    wp_redirect( $this->url . '&no-found' );
                } else {

                    $data = array(
                        'title'  => $col->fields_title,
                        'fields' => unserialize( $col->fields_data ),
                        'type'   => $col->fields_type,
                        'status' => $col->fields_status,
                        'id'     => $col->fields_id
                    );
    
                    if ( isset( $_GET['added'] ) ){
                        $this->notice( 'success', esc_html__( 'Fields have been saved successfully.', 'weasyfields' ), true );
                    }
                    if ( 'unpublish' === $col->fields_status ) {
                        $this->notice( 'error', esc_html__( 'This area is not publish at the moment and you can publish it by publishing it.', 'weasyfields' ) );
                    }
                    
                    $this->view( 'fields-page/edit', $data );
                }

            } elseif ( "add-new" === $_GET['action'] ) {
                
                $this->view( 'fields-page/add-new' );

            } elseif ( 'delete' === $_GET['action'] ) {

                $where = array(
                    'fields_id' => absint( $_GET['id'] )
                );
        
                $this->db->delete( $this->table, $where );
                
                wp_redirect( $this->url . '&deleted' );

            }

        } else {
            if ( isset( $_GET['deleted'] ) ) {
                $this->notice( 'success', esc_html__( 'Fields deleted successfully.', 'weasyfields' ), true );
            }
            $fields_list = new FieldsList();
            $fields_list->prepare_items();
            $this->view( 'fields-page/list', array( 'fields_list' => $fields_list ) );
        }

    }
}