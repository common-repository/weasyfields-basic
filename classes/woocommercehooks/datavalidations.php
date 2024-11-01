<?php

namespace WeasyFields\WooCommerceHooks;

/**
 * A trait that I use to verify and sanitize data sent for me, if any.
 * @since 1.0.0
 */
trait DataValidations
{
    /**
     * @var array It amount file data validations.
     */
    private $audit_file = array();
    /**
     * @var array It amount normal data validations.
     */
    private $audit_data = array();

    /**
     * Let's take the data of the fields for control.
     * @param string $data_key What name will the data come to us?
     * @param string $fields_key What data do we control?
     * @param string $fields_type It requests validation for what type of fields.
     * @param bool $passed Verification status.
     */
    protected function start_validation( $data_key, $fields_key, $fields_type, $passed = true )
    {
        if ( false !== $this->prepare_audits( $fields_key, $fields_type ) ) {

            // Is there any file data sent for me?
            $passed = $this->file_validation( $data_key, $passed );

            // Is there any post data sent for me?
            $passed = $this->data_validation( $data_key, $passed );

        }

        return $passed;
    }

    /**
     * Is there a requirement for the submitted data?
     * @param string $fields_key What data do we control?
     * @param string $fields_type It requests validation for what type of fields.
     * @return bool
     */
    protected function required_status( $fields_key, $fields_type )
    {
        $status = false;
        $fields = $this->get_fields( $fields_key );
        if ( $fields && $fields_type === $fields->fields_type ) {
            $fields = unserialize( $fields->fields_data );
            foreach ( $fields as $index => $value ) {
                if ( 'true' === $value['field_enabled'] ) {
                    if ( isset( $value['field_required'] ) && 'true' === $value['field_required'] ) {
                        $status = true;
                        break;
                    }
                }
            }
        }
        return $status;
    }

    /**
     * How your fields will be controlled get this data.
     * @param string $fields_key What data do we control?
     * @param string $fields_type It requests validation for what type of fields.
     * @return mixed
     */
    private function prepare_audits( $fields_key, $fields_type )
    {
        /**
         * Let's take the data of the fields for control.
         */
        $fields = $this->get_fields( $fields_key );

        // If the possible product fields have been removed.
        if ( $fields && $fields_type === $fields->fields_type ) {

            $fields = unserialize( $fields->fields_data );

            // Create an array of control values ​​for the control
            foreach ( $fields as $index => $value ) {
                
                if ( "true" === $value['field_enabled'] ) {
                    // For files
                    if ( 'file' === $value['field_key'] ) {

                        // They will always be
                        $this->audit_file[$index]['field_label']    = $value['field_label'];
                        $this->audit_file[$index]['field_required'] = $value['field_required'];
                        // It may also not happen.
                        if ( isset( $value['field_max_size'] ) ) {
                            $this->audit_file[$index]['field_max_size'] = $value['field_max_size'];
                        }
                        if ( isset( $value['file_permitted'] ) ) {
                            $this->audit_file[$index]['file_permitted'] = $value['file_permitted'];
                        }

                    // For normal data
                    } else {

                        if ( "headline" !== $value['field_key'] && "htmlcode" !== $value['field_key'] ) {
                            // They will always be
                            $this->audit_data[$index]['field_key']      = $value['field_key'];
                            $this->audit_data[$index]['field_label']    = $value['field_label'];
                            // It may also not happen.
                            if ( isset( $value['field_required'] ) ) {
                                $this->audit_data[$index]['field_required'] = $value['field_required'];
                            }
                            if ( isset( $value['phone_pattern'] ) ) {
                                $this->audit_data[$index]['phone_pattern'] = $value['phone_pattern'];
                            }
                            if ( isset( $value['field_validation'] ) ) {
                                $this->audit_data[$index]['field_validation'] = $value['field_validation'];
                            }
                        }
                    }
                } // Enabled status

            }

        } else {
            return false;
        } 
        // If the possible product fields have been removed.

    }

    /**
     * @param string $label The field to report.
     * @return void
     */
    private function required_notice( $label )
    {
        wc_add_notice( sprintf( esc_html__( 'The field named "%s" is required.', 'weasyfields' ), $label ), 'error' );
    }

    /**
     * @param string $data_key The name of the data array sent for me.
     * @param bool $passed status.
     * @return bool
     */
    private function file_validation( $data_key, $passed )
    {
        // Is there any file data sent for me?
        if ( isset( $_FILES[$data_key] ) ) {

            /**
             * If there is a data inconsistency.
             * And sanitize the filenames.
             */
            if ( count( $_FILES[$data_key]['name'] ) !== count( $this->audit_file ) ) {

                wc_add_notice(
                    esc_html__( 'An unexpected situation occurred, please fill in the fields again!','weasyfields' ),
                    'error'
                );
                unset( $_POST[$data_key] );
                unset( $_FILES[$data_key] );
                return false;

            } else {

                foreach ( $_FILES[$data_key]['name'] as $index => $value ) {
                    $_FILES[$data_key]['name'][$index]['field_label'] = sanitize_text_field( $this->audit_file[$index]['field_label'] );
                    $_FILES[$data_key]['name'][$index]['field_value'] = sanitize_file_name( $value['field_value'] );
                }    

            }
            
            // Transfer cleaned data to variable
            $data_files = $_FILES[$data_key];

            // Validation of files!
            foreach ( $data_files as $key => $value ) {

                // Open to get index from name array for control
                foreach ( $data_files['name'] as $index => $value_fake ) {

                    // A second validation check
                    if ( isset( $this->audit_file[$index] ) ) {

                        // For convenience
                        $audit = $this->audit_file[$index];
                        $files = $data_files[$key][$index];

                        // Check file requirement and the file type.
                        if ( "name" === $key ) {

                            if ( empty( $files['field_value'] ) && "true" === $audit['field_required'] ) {

                                $this->required_notice( $files['field_label'] );
                                return false;
                                
                            } else {

                                // Requirement. Don't take action  if the is empty because it has already been tested.
                                if ( isset( $audit['file_permitted'] ) && ! empty( $files['field_value'] ) ) {

                                    foreach ( $audit['file_permitted'] as $extension ) {
                                        if ( false !== strpos( $files['field_value'], $extension ) ) {
                                            break;
                                        } else {
                                            $extension_none = true;
                                        }
                                    }

                                    if ( isset( $extension_none ) ) {
                                        wc_add_notice(
                                            sprintf(
                                                esc_html__( 'The file type you uploaded to the area named "%s" is an invalid file type!', 'weasyfields' ),
                                                $audit['field_label']
                                            ),
                                            'error'
                                        );
                                        return false;
                                    }

                                } // isset file permitted

                            } // required status
                            
                        // Check file size
                        // Requirement. Don't take action  if the is empty because it has already been tested.
                        } elseif ( "size" === $key && ! empty( $files['field_value'] ) ) {

                            if ( isset( $audit['field_max_size'] ) ) {

                                $max_size = 1024 * 1024 * (int) $audit['field_max_size'];
                                if ( $max_size < $files['field_value'] ) {
                                    wc_add_notice(
                                        sprintf(
                                            esc_html__( 'The file named "%s" exceeds the maximum file size.', 'weasyfields' ),
                                            $audit['field_label']
                                        ),
                                        'error'
                                    );
                                    return false;
                                }

                            } // isset max sie

                        } 
                        
                    } // A second validation check

                } // Open to get index from name array for control

            } // Open incoming file string

        } // Is there a file? Is not there?

        return $passed;

    }

    /**
     * @param string $data_key The name of the data array sent for me.
     * @param bool $passed status.
     * @return bool
     */
    private function data_validation( $data_key, $passed )
    {
        if ( false !== $passed ) {

            // Is there any post data sent for me?
            if ( isset( $_POST[$data_key] ) ) {

                /**
                 * If there is a data inconsistency.
                 * And sterilize values
                 */

                if ( count( $_POST[$data_key] ) !== count( $this->audit_data ) ) {

                    wc_add_notice(
                        esc_html__( 'An unexpected situation occurred, please fill in the fields again!', 'weasyfields' ),
                        'error'
                    );
                    unset( $_POST[$data_key] );
                    unset( $_FILES[$data_key] );
                    return false;

                } else {

                    foreach ( $_POST[$data_key] as $index => $value ) {

                        $label = $this->audit_data[$index]['field_label'];
                        // if the data array is
                        if ( is_array( $value['field_value'] ) ) {

                            foreach ( $value['field_value'] as $opt_index => $opt_value ) {

                                $_POST[$data_key][$index]['field_label'] = sanitize_text_field( $label );
                                if ( empty( $opt_value ) ) {
                                    unset( $_POST[$data_key][$index]['field_value'][$opt_index] );
                                } else {
                                    $_POST[$data_key][$index]['field_value'][$opt_index] = sanitize_text_field( $opt_value );
                                }

                            }

                        } else {

                            $value = $value['field_value'];

                            // Check the data type.
                            if ( isset( $this->audit_data[$index]['field_validation'] ) ) {

                                $validation = $this->audit_data[$index]['field_validation'];

                                // is email
                                if ( "email" === $validation ) {

                                    $_POST[$data_key][$index]['field_label'] = sanitize_text_field( $label );
                                    $_POST[$data_key][$index]['field_value'] = sanitize_email( $value );

                                // is url
                                } elseif ( "url" === $validation ) {

                                    $_POST[$data_key][$index]['field_label'] = sanitize_text_field( $label );
                                    $_POST[$data_key][$index]['field_value'] = esc_url_raw( $value );

                                // other
                                } else {

                                    $_POST[$data_key][$index]['field_label'] = sanitize_text_field( $label );
                                    $_POST[$data_key][$index]['field_value'] = sanitize_text_field( $value );

                                }
                            } else {

                                $_POST[$data_key][$index]['field_label'] = sanitize_text_field( $label );
                                $_POST[$data_key][$index]['field_value'] = sanitize_text_field( $value );

                            }

                        }
                    }
                }

                // For convenience
                $data = $_POST[$data_key];
                
                // Let's open the control sequence
                foreach ( $this->audit_data as $index => $value ) {

                    // Is the field required first? Let's take a look at it.
                    if ( "true" === $value['field_required'] && empty( $data[$index]['field_value'] ) ) {
                        $this->required_notice( $value['field_label'] );
                        return false;
                    } 
                    
                    // Is verification requested?
                    // Requirement. Don't take action  if the is empty because it has already been tested.
                    if ( isset( $value['field_validation'] ) && ! empty( $data[$index]['field_value'] ) ) {

                        if ( ! is_email( $data[$index]['field_value'] ) && "email" === $value['field_validation'] ) {

                            wc_add_notice(
                                sprintf(
                                    esc_html__( 'An e-mail address must be entered in the field named "%s". The e-mail address you entered is an invalid e-mail address.', 'weasyfields' ),
                                    $value['field_label']
                                ),
                                'error'
                            );
                            return false;

                        } elseif (
                            "url" === $value['field_validation'] &&
                            ! empty( $data[$index]['field_value'] ) &&
                            false === $this->check_url( $data[$index]['field_value'] )
                            ) {

                            wc_add_notice(
                                sprintf(
                                    esc_html__( 'The field named "%s" should get a url address. The url address you entered is invalid.', 'weasyfields' ),
                                    $value['field_label']
                                ),
                                'error'
                            );
                            return false;

                        } elseif ( 
                            "phone" === $value['field_validation'] &&
                            ! empty( $value['phone_pattern'] ) &&
                            false === $this->check_phone( $data[$index]['field_value'] , $value['phone_pattern'] )
                            ) {

                            wc_add_notice(
                                sprintf(
                                    esc_html__( 'There was an invalid phone number entry for the field named "%s", please do as in the example.', 'weasyfields' ),
                                    $value['field_label']
                                ),
                                'error'
                            );
                            return false;

                        }
                    } // Is the field required first? Let's take a look at it.

                } // Let's open the control sequence

            } // Is there a data? Is not there?

        } else {
            $passed = false;
        }

        return $passed;
        
    }

}