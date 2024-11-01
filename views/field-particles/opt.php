<p class="post-attributes-label-wrapper page-template-label-wrapper">
    <label class="post-attributes-label">
        <?php echo esc_html__( 'Options:', 'weasyfields' ); ?>
    </label>
</p>
<a href="#" class="weasyfields-add-opt"><?php echo esc_html__( 'Add option', 'weasyfields' ); ?></a>
<ul class="weasyfields-field-item-opt-wrapper">
    <?php 
        if ( isset( $field_opt ) ) {
            foreach ( $field_opt as $value ) {
                ?> 
                <li class="weasyfields-field-item-opt">
                    <input type="text" name="fields[0][field_opt][]" data-name="[field_opt][]" class="weasyfields-input-null-dedector widefat" placeholder="<?php echo esc_attr__( 'Please enter a value!', 'weasyfields '); ?>" value="<?php echo esc_attr( stripslashes( $value ) ); ?>">
                </li>
                <?php
            }
        } else {
            ?>
            <li class="weasyfields-field-item-opt">
                <input type="text" name="fields[0][field_opt][]" data-name="[field_opt][]" class="weasyfields-input-null-dedector widefat" placeholder="<?php echo esc_attr__( 'Please enter a value!', 'weasyfields '); ?>">
            </li>
            <?php
        }
    ?>
        
</ul>