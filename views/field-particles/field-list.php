<p class="postattributes-label-wrapper page-template-label-wrapper">
    <label for="weasyfields-field-type" class="post-attributes-label">
        <?php echo esc_html__( 'Field type', 'weasyfields' ); ?>
    </label>
</p>
<select id="weasyfields-field-type" class="widefat">
    <option value="text">
        <?php echo esc_html__( 'Text ( Single text area )', 'weasyfields' ); ?>
    </option>
    <option value="textarea">
        <?php echo esc_html__( 'Textarea ( Paragraph text area )', 'weasyfields' ); ?>
    </option>
    <option value="select" disabled>
        <?php echo esc_html__( 'Select ( Drop down ) ( Premium version )', 'weasyfields' ); ?>
    </option>
    <option value="checkbox" disabled>
        <?php echo esc_html__( 'Checkbox ( Premium version )', 'weasyfields' ); ?>
    </option>
    <option value="radio" disabled>
        <?php echo esc_html__( 'Radio button ( Premium version )', 'weasyfields' ); ?>
    </option>
    <option value="date" disabled>
        <?php echo esc_html__( 'Date ( Premium version )', 'weasyfields' ); ?>
    </option>
    <option value="time" disabled>
        <?php echo esc_html__( 'Time ( Premium version )', 'weasyfields' ); ?>
    </option>
    <option value="color" disabled>
        <?php echo esc_html__( 'Color picker ( Premium version )', 'weasyfields' ); ?>
    </option>
    <option value="file" disabled>
        <?php echo esc_html__( 'File upload ( Premium version )', 'weasyfields' ); ?>
    </option>
    <option value="headline" disabled>
        <?php echo esc_html__( 'Headline ( Premium version )', 'weasyfields' ); ?>
    </option>
    <option value="htmlcode" disabled>
        <?php echo esc_html__( 'HTML CODE ( Premium version )', 'weasyfields' ); ?>
    </option>
</select>