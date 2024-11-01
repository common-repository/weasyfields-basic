<?php

foreach ( $fields as $index => $key ) {

    if ( "true" === $key['field_enabled'] ) {

        // Post key sent on my behalf.
        $data_key = strval( 'weasyfields_'.$id );

        // Prepare a name to collect the data as a string.
        $name = $data_key.'['.$index.'][field_value]';

        // If it is required to fill in the field.
        $required = null;
        if ( isset( $key['field_required'] ) && "true" === $key['field_required'] ) {
            $required = ' required';
        }
        
        // If it's the checkbox.
        $one_message = null;
        if ( "checkbox" === $key['field_key'] ) {
            $required = null;
            $name = $name.'[]';
            if ( 1 < count( $key['field_opt'] ) ) {
                // Please choose one. Message.
                if ( "checkbox" === $key['field_key'] && isset( $key['field_required'] ) && "true" === $key['field_required'] ) { 
                    $one_message = esc_html__( 'Please choose at least one!', 'weasyfields' );
                } 
            }
        }

        // To display values ​​if posted but adding to cart failed.
        $value = null;
        if ( isset( $_POST[$data_key] ) ) {
            if ( isset( $_POST[$data_key][$index] ) ) {
                $value = stripslashes( $_POST[$data_key][$index]['field_value'] );
            }
            
        }

        // Billing and shipping form data view
        if ( isset( $fixed_value ) ) {
            if ( isset( $fixed_value['data'][$index] ) ) {
                $value = stripslashes( $fixed_value['data'][$index]['field_value'] );
            }
        }

        // If there is a variable containing file validation data.
        $accept = null;
        if ( isset( $key['file_permitted'] ) ) {
            foreach ( $key['file_permitted'] as $permitted ) {
                $accept .= esc_attr( "$permitted," );
            }
            $accept = ' accept=' . rtrim( $accept, ',' );
        }

        // If there is a variable containing data validation data.
        $type         = $key['field_key']; // Type carries the input type.
        $phone_sample = null;
        $pattern      = null;
        if ( isset( $key['field_validation'] ) && "none" !== $key['field_validation'] ) {
            // Equalize to the selected verification type.
            $type = $key['field_validation'];
            // If it's a phone
            if ( "phone" === $key['field_validation'] ) {
                $type = 'tel';
                if ( ! empty( $key['phone_pattern'] ) ) {
                    $pattern = 'pattern="'.esc_attr( $key['phone_pattern'] ).'"';
                }
                if ( ! empty( $key['phone_sample'] ) ) {
                    $phone_sample = esc_html__( 'Sample: ', 'weasyfields' ) . $key['phone_sample'];
                }
            }
        }

        // Combine for convenience and good looks.
        $attrs = $pattern.$accept.$required;

        // Label
        $label = stripslashes( $key['field_label'] );

        // To display values ​​if posted but adding to cart failed.
        $shipping_and_billing = false;
        if ( 'file' === $type ) {
            if ( false !== strpos( $data_key, 'billing' ) ) {
                $shipping_and_billing = true; 
            } elseif ( false !== strpos( $data_key, 'shipping' ) ) {
                $shipping_and_billing = true;
            }
        }

        /**
         * Class and place holder
         */

        $class = null;
        if ( isset( $key['field_class'] ) ) {
            $class = $key['field_class'];
        }

        $placeholder = null;
        if ( 'file' !== $type && isset( $key['field_placeholder'] ) ) {
            $placeholder = stripslashes( $key['field_placeholder'] );
        }

        if ( "select" === $type ) { 
            ?>
                <p class="form-row">

                    <label>
                        <?php echo esc_html( $label ); ?>:
                        <?php if ( null !== $required ) { ?>
                            <abbr class="required" title="<?php echo esc_html__( 'Required', 'weasyfields' ); ?>">*</abbr>
                        <?php } ?>
                    </label>

                    <select name="<?php echo esc_attr( $name ); ?>" data-option="<?php echo esc_attr( $value ); ?>" class="weasyfields-select-option-value" class="<?php echo esc_attr( $class ); ?>"<?php echo esc_attr( $required ); ?>>

                        <?php foreach ( $key['field_opt'] as $opt ) : ?>
                            <option value="<?php echo esc_attr( $opt ); ?>">
                                <?php echo esc_html( $opt ); ?>
                            </option>    
                        <?php endforeach; ?>

                    </select>

                </p><!-- form-row -->
            <?php 
        } elseif ( "checkbox" === $type || "radio" === $type ) { 
            ?>
                <p class="form-row">

                    <label>
                        <?php echo esc_html( $label ); ?>:
                        <?php if ( null !== $required ) { ?>
                            <abbr class="required" title="<?php echo esc_html__( 'Required', 'weasyfields' ); ?>">*</abbr>
                        <?php } ?>
                    </label>

                    <input type="hidden" name="<?php echo esc_attr( $name ); ?>">

                    <?php foreach ( $key['field_opt'] as $opt_val ) :
                        
                        // Since value will be an array here, we open the array.
                        $opt_checked = null;
                        if ( is_array( $value ) ) :
                            foreach ( $value as $opt_value ) :
                                if ( $opt_value === $opt_val ) :
                                    $opt_checked = ' checked';
                                endif;
                            endforeach;
                        else :
                            if ( $value === $opt_val ) :
                                $opt_checked = ' checked';
                            endif;
                        endif;

                        ?>
                        <label>
                        
                            <input type="<?php echo esc_attr( $type ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( stripslashes( $opt_val ) ); ?>" class="<?php echo esc_attr( $class ); ?>"<?php echo esc_attr( $opt_checked ); echo esc_attr( $required ); ?>>
                            
                            <?php echo esc_html( stripslashes( $opt_val ) ); ?>
                            
                        </label>  

                    <?php endforeach; ?>

                    <?php echo esc_html( $one_message ); ?>
                    
                </p><!-- form-row -->
            <?php 
        } elseif ( "textarea" === $type ) { 
            ?>
                <p class="form-row">

                    <label>
                        <?php echo esc_html( $label ); ?>:
                        <?php if ( null !== $required ) { ?>
                            <abbr class="required" title="<?php echo esc_html__( 'Required', 'weasyfields' ); ?>">*</abbr>
                        <?php } ?>
                    </label>

                    <textarea name="<?php echo esc_attr( $name ); ?>" class="<?php echo esc_attr( $class ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>"<?php echo esc_attr( $required ); ?>><?php echo esc_html( stripslashes( $value ) ); ?></textarea>

                </p><!-- form-row -->
            <?php 
        } elseif ( "headline" === $type ) { 
            ?>
                <p class="form-row">
                    <h3 class="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $label ); ?></h3>
                </p><!-- form-row -->
            <?php 
        } elseif ( "htmlcode" === $type ) { 
            ?>
                <p class="form-row">
                    <div class="<?php echo esc_attr( $class ); ?>">
                        <?php echo wp_kses_post( $key['htmlcode'] ); ?>
                    </div>
                </p><!-- form-row -->
            <?php 
        } else { 
            if ( false === $shipping_and_billing ) :
            ?>
                <p class="form-row">
    
                    <label>
                        <?php echo esc_html( $label ); ?>:
                        <?php if ( null !== $required ) { ?>
                            <abbr class="required" title="<?php echo esc_html__( 'Required', 'weasyfields' ); ?>">*</abbr>
                        <?php } ?>
                    </label>
    
                    <input type="<?php echo esc_html( $type ); ?>" name="<?php echo esc_attr( $name ) ?>" value="<?php echo esc_attr( $value ); ?>" class="<?php echo esc_attr( $class ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" <?php echo esc_attr( $attrs ); ?>>
    
                    <?php echo esc_html( $phone_sample ); ?>
    
                </p><!-- form-row -->
            <?php
            else :
                ?>
                <p class="form-row">
                    <?php echo esc_html__( 'File format is not supported in Checkout fields.', 'weasyfields' ); ?>
                </p><!-- form-row -->
                <?php 
            endif;
        }
    }
}