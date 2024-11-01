<p class="post-attributes-label-wrapper page-template-label-wrapper">
    <label class="post-attributes-label">
        <?php echo esc_html__( 'Validation:', 'weasyfields' ); ?>
    </label>
</p>
<select name="fields[0][field_validation]" data-option="<?php echo isset( $field_validation ) ? esc_attr( $field_validation ) : null; ?>" data-name="[field_validation]" class="weasyfields-validation weasyfields-select-option-value widefat">
    <option value="none">
        <?php echo esc_html__( 'None', 'weasyfields' ); ?>
    </option>
    <option value="email">
        <?php echo esc_html__( 'Email', 'weasyfields' ); ?>
    </option>
    <option value="phone">
        <?php echo esc_html__( 'Phone', 'weasyfields' ); ?>
    </option>
    <option value="url">
        <?php echo esc_html__( 'Url', 'weasyfields' ); ?>
    </option>
</select>
<div class="weasyfields-phone-pattern">
    <p class="post-attributes-label-wrapper page-template-label-wrapper">
        <label class="post-attributes-label">
            <?php echo esc_html__( 'Phone pattern:', 'weasyfields' ); ?>
        </label>
    </p>
    <input type="text" name="fields[0][phone_pattern]" data-name="[phone_pattern]" class="widefat weasyfields-field-label" placeholder="<?php echo esc_attr__( 'Please enter a value!', 'weasyfields '); ?>" value="<?php echo isset( $phone_pattern ) ? esc_attr( $phone_pattern ) : null; ?>">
    <span>
        <?php 
            echo esc_html__( 'You can set this according to your country\'s phone number format. For detailed information, you can visit the address below. If you leave it blank, there will be no control.', 'weasyfields' ); 
            echo "<br>";
            echo '<a href="'.esc_url( 'https://www.w3schools.com/tags/att_input_type_tel.asp#:~:text=The%20%3Cinput%20type%3D%22tel,tag%20for%20best%20accessibility%20practices!' ).'">W3 Schools input pattern</a>';
        ?>
    </span>
    <p class="post-attributes-label-wrapper page-template-label-wrapper">
        <label class="post-attributes-label">
            <?php echo esc_html__( 'Sample number entry:', 'weasyfields' ); ?>
        </label>
        <br><?php echo esc_html__( 'Sample : 0536-665-64-94', 'weasyfields' ); ?>
    </p>
    <input type="text" name="fields[0][phone_sample]" data-name="[phone_sample]" class="widefat weasyfields-field-label" placeholder="<?php echo esc_attr__( 'Please enter a value!', 'weasyfields '); ?>" value="<?php echo isset( $phone_sample ) ? esc_attr( $phone_sample ) : null; ?>">
</div>