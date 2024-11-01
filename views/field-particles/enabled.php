<label class="weasyfields-label">
    <input type="hidden" name="fields[0][field_enabled]" data-name="[field_enabled]" class="weasyfields-checkbox-value" value="<?php echo isset( $field_enabled ) ? esc_attr( $field_enabled ) : 'false'; ?>">
    <input type="checkbox" class="weasyfields-checkbox-true-false" value="true"<?php $field_enabled = isset( $field_enabled ) ? $field_enabled : null; if ( "true" === $field_enabled ) { echo " checked"; } ?>> <?php echo esc_html__( 'Enabled field', 'weasyfields' ); ?>
</label>