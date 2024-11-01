<li class="weasyfields-field-item menu-item menu-item-custom menu-item-edit-inactive">

    <!-- field key -->
    <input type="hidden" name="fields[0][field_key]" data-name="[field_key]" value="text">

    <!-- item bar top -->
    <?php $this->view_i( 'field-particles/item-bar-top', isset( $field ) ? $field : array() ); ?>
    <?php echo esc_html__( 'Text ( Single text area )', 'weasyfields' ); ?>
    <?php $this->view_i( 'field-particles/item-bar-bot' ); ?>
    <!-- item bar bot -->

    <div class="menu-item-settings">

        <!-- Enabled and Required -->
        <?php $this->view_i( 'field-particles/enabled', isset( $field ) ? $field : array() ); ?>
        <?php $this->view_i( 'field-particles/required', isset( $field ) ? $field : array() ); ?>
        <?php $this->view_i( 'field-particles/validation', isset( $field ) ? $field : array() ); ?>
        <!-- Enabled and Required -->

        <!-- Label -->
        <?php $this->view_i( 'field-particles/label', isset( $field ) ? $field : array() ); ?>
        <!-- Label -->

        <!-- Class and Placeholder -->
        <?php $this->view_i( 'field-particles/class', isset( $field ) ? $field : array() ); ?>
        <?php $this->view_i( 'field-particles/placeholder', isset( $field ) ? $field : array() ); ?>
        <!-- Class and Placeholder -->

        <a href="#" class="weasyfields-remove-field"><?php echo esc_html__( 'Remove field', 'weasyfields' ); ?></a>
        <div class="clear"></div>

    </div><!-- .menu-items-settings -->

</li>