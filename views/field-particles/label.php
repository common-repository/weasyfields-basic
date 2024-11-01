<p class="post-attributes-label-wrapper page-template-label-wrapper">
    <label class="post-attributes-label">
        <?php echo esc_html__( 'Label:', 'weasyfields' ); ?>
    </label>
</p>
<input type="text" name="fields[0][field_label]" data-name="[field_label]" class="widefat weasyfields-input-null-dedector weasyfields-field-label" placeholder="<?php echo esc_attr__( 'Please enter a value!', 'weasyfields '); ?>" value="<?php echo isset( $field_label ) ? esc_attr( stripslashes( $field_label ) ) : null; ?>">