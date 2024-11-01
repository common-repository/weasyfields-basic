<p class="post-attributes-label-wrapper page-template-label-wrapper">
    <label class="post-attributes-label">
        <?php echo esc_html__( 'HTML CODE:', 'weasyfields' ); ?>
    </label>
</p>
<textarea name="fields[0][htmlcode]" data-name="[htmlcode]" class="widefat weasyfields-input-null-dedector" placeholder="<?php echo esc_attr__( 'Please enter a value!', 'weasyfields '); ?>"><?php echo isset( $htmlcode ) ? esc_html( $htmlcode ) : null; ?></textarea>