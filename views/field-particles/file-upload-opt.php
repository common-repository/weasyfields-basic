<p class="post-attributes-label-wrapper page-template-label-wrapper">
    <label class="post-attributes-label">
        <?php echo esc_html__( 'File max size (MB):', 'weasyfields' ); ?>
    </label>
</p>
<input type="number" name="fields[0][field_max_size]" data-name="[field_max_size]" class="widefat weasyfields-input-null-dedector" value="<?php echo isset( $field_max_size ) ? esc_attr( $field_max_size ) : 5; ?>" placeholder="<?php echo esc_attr__( 'Please enter a value!', 'weasyfields '); ?>" value="<?php echo isset( $field_label ) ? esc_attr( $field_label ) : null; ?>" min="1">

<p class="post-attributes-label-wrapper page-template-label-wrapper">
    <label class="post-attributes-label">
        <?php echo esc_html__( 'Select the file types to be allowed:', 'weasyfields' ); ?>
    </label>
    <br><?php echo esc_html__( 'Please choose at least one!', 'weasyfields' ); ?>
</p>
<?php 
if ( isset( $file_permitted ) ) {
    foreach ( $file_permitted as $key => $value ) {
        $file_permitted[$value] = $value;
    }
}
?>
<!-- PNG -->
<label class="weasyfields-label">
    <input type="checkbox" name="fields[0][file_permitted][]" data-name="[file_permitted][]" value=".png"<?php echo isset( $file_permitted['.png'] ) ? ' checked' : null; ?>> <?php echo esc_html__( 'PNG', 'weasyfields' ); ?>
</label>
<!-- JPG -->
<label class="weasyfields-label">
    <input type="checkbox" name="fields[0][file_permitted][]" data-name="[file_permitted][]" value=".jpg"<?php echo isset( $file_permitted['.jpg'] ) ? ' checked' : null; ?>> <?php echo esc_html__( 'JPG', 'weasyfields' ); ?>
</label>
<!-- JPEG -->
<label class="weasyfields-label">
    <input type="checkbox" name="fields[0][file_permitted][]" data-name="[file_permitted][]" value=".jpeg"<?php echo isset( $file_permitted['.jpeg'] ) ? ' checked' : null; ?>> <?php echo esc_html__( 'JPEG', 'weasyfields' ); ?>
</label>
<!-- ZİP -->
<label class="weasyfields-label">
    <input type="checkbox" name="fields[0][file_permitted][]" data-name="[file_permitted][]" value=".zip"<?php echo isset( $file_permitted['.zip'] ) ? ' checked' : null; ?>> <?php echo esc_html__( 'ZİP', 'weasyfields' ); ?>
</label>
<!-- DOCX ( Ms Word ) -->
<label class="weasyfields-label">
    <input type="checkbox" name="fields[0][file_permitted][]" data-name="[file_permitted][]" value=".docx"<?php echo isset( $file_permitted['.docx'] ) ? ' checked' : null; ?>> <?php echo esc_html__( 'DOCX', 'weasyfields' ); ?>
</label>
<!-- XLSX ( Ms Excel ) -->
<label class="weasyfields-label">
    <input type="checkbox" name="fields[0][file_permitted][]" data-name="[file_permitted][]" value=".xlsx"<?php echo isset( $file_permitted['.xlsx'] ) ? ' checked' : null; ?>> <?php echo esc_html__( 'TXT', 'weasyfields' ); ?>
</label>
<!-- PDF -->
<label class="weasyfields-label">
    <input type="checkbox" name="fields[0][file_permitted][]" data-name="[file_permitted][]" value=".pdf"<?php echo isset( $file_permitted['.pdf'] ) ? ' checked' : null; ?>> <?php echo esc_html__( 'PDF', 'weasyfields' ); ?>
</label>
<!-- TXT -->
<label class="weasyfields-label">
    <input type="checkbox" name="fields[0][file_permitted][]" data-name="[file_permitted][]" value=".txt"<?php echo isset( $file_permitted['.txt'] ) ? ' checked' : null; ?>> <?php echo esc_html__( 'TXT', 'weasyfields' ); ?>
</label>