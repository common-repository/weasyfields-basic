<p class="post-attributes-label-wrapper page-template-label-wrapper">
    <label class="post-attributes-label">
        <?php echo esc_html__( 'Placeholder:', 'weasyfields' ); ?>
    </label>
</p>
<input type="text" name="fields[0][field_placeholder]" data-name="[field_placeholder]" class="widefat weasyfields-field-label" placeholder="<?php echo esc_attr__( 'You can leave it blank if you want.', 'weasyfields '); ?>" value="<?php echo isset( $field_placeholder ) ? esc_attr( stripslashes( $field_placeholder ) ) : null; ?>">