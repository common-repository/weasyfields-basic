<?php 

namespace WeasyFields;

/**
 * WeasyFields abstract general use class
 */
abstract class General
{   
    /**
     * @var object $db WordPress database management class Variable holding $ wpdb.
     */
    protected $db;
    /**
     * @var string $table The name of the table where our fields are kept.
     */
    protected $table;

    /**
     * In order not to use global $ wpdb in the function all the time, to define the database where we will do the operation.
     */
    protected function setDB()
    {
        // Everything is a whole
        global $wpdb;
        $this->db    = &$wpdb;
        $this->table = $this->db->prefix . "weasyfields";
    }

    /**
     * Create unique key
     * @param string $fields_key
     * @return string
     */
    protected function unique_key( $fields_key )
    {
        $fields_keys = $this->db->get_col( "SELECT `fields_key` FROM `{$this->table}`" );
        if ( in_array( $fields_key, $fields_keys ) ){
            $number = (int) substr( $fields_key, -1 );
            $number++;
            $end_two = (string) substr( $fields_key, -2 );
            if ( false !== strpos( $end_two, '-' ) ) {
                $fields_key = str_replace( $end_two, '-', $fields_key );
                $fields_key = (string) $fields_key . $number;
            } else {
                $fields_key = (string) $fields_key ."-". $number;
            }
            return $this->unique_key( $fields_key );
        } else {
            return $fields_key;
        }

    }

    /**
     * @param string $view_name Directory name within the folder
     * @return void
     */
    protected function view( $view_name, $args = array() )
    {
        extract( $args );
        require_once WEASYFIELDS_VIEW_DIR . $view_name . '.php';
    }

    /**
     * @param string $view_name Directory name within the folder
     * view incude type
     * @return void
     */
    protected function view_i( $view_name, $args = array() )
    {
        extract( $args );
        include WEASYFIELDS_VIEW_DIR . $view_name . '.php';
    }

    /**
     * @param string $type error, success more
     * @param string $notice notice to be given
     * @param bool $dismissible in-dismissible button show and hide
     * @return void
     */
    protected function notice( $type, $notice, $dismissible = false )
    {
        $args = array( 'type' => $type, 'notice' => $notice, 'dismissible' => $dismissible );
        $this->view_i( 'notice', $args );
    }   

     /**
     * @param string $url url adress
     * @return bool
     */
    protected function check_url( $url )
    {
        $pattern = '%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu';
        $result = preg_match( $pattern, $url );
        if ( 1 === $result ) {
            return true;
        } else {
            return false;
        }
    }

     /**
     * @param string $phone phone number
     * @param string $pattern regex pattern
     * @return bool
     */
    protected function check_phone( $phone, $pattern )
    {
        $pattern = '/'.$pattern.'/m';
        preg_match_all( $pattern, $phone, $phone_output, PREG_SET_ORDER, 0 );
        if ( $phone_output[0][0] === $phone ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Easy use for get_option
     * @param string $setting
     * @return mixed
     */
    protected function setting( $setting )
    {
        $settings = get_option( 'weasyfields_settings' ); 
        if ( isset( $settings[$setting] ) ) :
            return $settings[$setting];
        else :
            return null;
        endif;
    }

    /**
     * List the prepared fields in the view.
     * @return void
     */
    protected function field_listining()
    {
        $admin_fields = WEASYFIELDS_VIEW_DIR . '/fields';
        $fields_files = scandir( $admin_fields );
        foreach ( $fields_files as $field_file ) {
            if ( false !== strpos( $field_file, '.php' ) ) {
                $field_file_name = str_replace( '.php', '', $field_file );
                $field_file = trailingslashit( $admin_fields ) . $field_file;
                if ( file_exists( $field_file ) ) {
                    echo '<div id="weasyfields-list-'.esc_attr( $field_file_name ).'">';
                    require_once $field_file;
                    echo '</div>';
                }
            }
        }
    }

    /**
     * @param string $fields_key
     * @return object
     */
    protected function get_fields( $fields_key )
    {
        $query = $this->db->prepare( "SELECT * FROM `{$this->table}` WHERE `fields_status` = 'publish' AND `fields_key` = '%1s'", $fields_key );
        return $this->db->get_row( $query );
    }

    /**
     * If a file is being sent for me and if requested to upload this file and output a data.
     * @param string $data_key For which one comes data.
     */
    protected function file_upload( $data_key )
    {
        $files = array();
        foreach ( $_FILES[$data_key]['name'] as $index => $value ) {

            $tmp_name = $_FILES[$data_key]['tmp_name'][$index]['field_value'];
            $label    = $value['field_label'];
            // Requirement. Don't take action  if the is empty because it has already been tested.
            if ( ! empty( $tmp_name ) ) {
                
                $uploaded_file = wp_upload_bits( $value['field_value'], null, @file_get_contents( $tmp_name ) );
                $file_name     = basename( $uploaded_file['file'] );
    
                $files[$index] = array(
                    'path'      => str_replace( '\\', '/', $uploaded_file['file'] ),
                    'name'      => $file_name,
                    'url'       => $uploaded_file['url'],
                    'label'     => $label,
                    'fake_name' => $value['field_value']
                );
            } else {
                $files[$index] = array(
                    'label'     => $label,
                    'fake_name' => null,
                    'path'      => null
                );
            }
        }
        return $files;
    }

    /**
     * Get the html output of the fields created for the orders.
     * @param array @data related order
     * @param bool @mail_send To hide a field if it goes to the mail.
     * @return string
     */
    protected function data_list_preview_creator( $data, $mail_send = false )
    {
        $view_data = array();
        if ( $data ) {
            if ( isset( $data['data'] ) ) {
                $data_array = $data['data'];
                foreach ( $data_array as $data_val ) {
                    if ( ! empty( $data_val['field_value'] ) ) {
                        if ( is_array( $data_val['field_value'] ) ) {
                            $value = '';
                            foreach ( $data_val['field_value'] as $val ) {
                                $value .= "-$val<br>";
                            }
                        } else {
                            $value = $data_val['field_value'];
                        }
                    } else {
                        $value = esc_html__( 'Null', 'weasyfields' );
                    }
                    $view_data[] = array(
                        'type'  => 'data',
                        'label' => $data_val['field_label'],
                        'value' => $value
                    );
                }
            }
            
            if ( isset( $data['files'] ) ) {
                $files = $data['files'];
                foreach ( $files as $file ) {
                    if ( null !== $file['path'] ) {
                        if ( file_exists( $file['path'] ) ) {
                            $view_data[] = array(
                                'type'  => 'file',
                                'label' => $file['label'],
                                'value' => $file['name'],
                                'url'   => $file['url'],
                                'path'  => $file['path']
                            );
                        } else {
                            $view_data[] = array(
                                'label' => $file['label'],
                                'type'  => 'deleted-file'
                            );
                        }
                    } else {
                        $view_data[] = array(
                            'label' => $file['label'],
                            'type'  => 'null-file'
                        );
                    }
                }
            } 
        }

        ob_start();
        $this->view_i( 'fields-data', array( 'view_data' => $view_data, 'mail_send' => $mail_send ) );
        echo ob_get_clean();
        
    }

}