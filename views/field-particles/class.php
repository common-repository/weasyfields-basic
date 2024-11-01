<p class="post-attributes-label-wrapper page-template-label-wrapper">
    <label class="post-attributes-label">
        <?php echo esc_html__( 'Class:', 'weasyfields' ); ?>
    </label>
</p>
<input type="text" name="fields[0][field_class]" data-name="[field_class]" class="widefat weasyfields-field-label" placeholder="<?php echo esc_attr__( 'You can leave it blank if you want.', 'weasyfields '); ?>" value="<?php echo isset( $field_class ) ? esc_attr( $field_class ) : null; ?>">
<?php echo esc_html__( 'If you write more than one class, leave a space between them.', 'weasyfields' ); ?>