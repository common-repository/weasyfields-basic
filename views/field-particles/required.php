<label class="weasyfields-label">
    <input type="hidden" name="fields[0][field_required]" data-name="[field_required]" class="weasyfields-checkbox-value" value="<?php echo isset( $field_required ) ? esc_attr( $field_required ) : 'false'; ?>">
    <input type="checkbox" class="weasyfields-checkbox-true-false"<?php $field_required = isset( $field_required ) ? $field_required : null; if ( "true" === $field_required ) { echo " checked"; } ?>> <?php echo esc_html__( 'Required field', 'weasyfields' ); ?>
</label>