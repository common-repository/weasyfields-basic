<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo esc_html__( 'Add new fields', 'weasyfields' ); ?>
    </h1>
    <hr class="wp-header-end">
            
    <div id="weasyfields-error-handler">
        <div class="weasyfields-null-error">
            <p>
                <?php echo esc_html__( 'Please fill in the required fields, the relevant fields are marked.', 'weasyfields' ); ?>
            </p>
        </div>
    </div><!-- #weasyfields-error-handler -->
                
    <div id="weasyfields-field-type-list">
        <?php $this->field_listining(); ?>
    </div><!-- #weasyfields-field-type-list -->
    
    <form id="weasyfields-fields-form" data-null-msg="<?php echo esc_attr__( 'Please add a field on the right.', 'weasyfields' ); ?>" action="<?php echo esc_url( $this->url ); ?>&action=add-new" class="weasyfields-form-space" method="post">
        <input type="hidden" name="action" value="add">
        <?php wp_nonce_field( 'weasyfields-nonce', 'nonce' );  ?>
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">

                <div id="post-body-content">
                    <div id="titlediv">
                        <div id="titlewrap">
                            <input type="text" name="title" size="30" id="title" class="weasyfields-input-null-dedector" spellcheck="true" autocomplete="off" placeholder="<?php echo esc_html__( 'Add title', 'weasyfields' ); ?>">
                        </div>
                    </div>
                </div><!-- #post-body-content -->

                <div id="postbox-container-1" class="postbox-container">
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2><?php echo esc_html__( 'Buy premium', 'weasyfields' ); ?></h2>
                        </div>
                        <div class="inside">
                            <div class="minor-publishing">
                                            
                            <a href="https://1.envato.market/NnxA2" title="Buy Premium"><img src="<?php echo esc_url( WEASYFIELDS_URL ) ?>assets/img/sale-premium.png" alt="Buy Premium"></a>
                            
                            </div><!-- .minor-publishing -->
                        </div><!-- .inside -->
                    </div><!-- .postbox -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2><?php echo esc_html__( 'Save fields', 'weasyfields' ); ?></h2>
                        </div>
                        <div class="inside">
                            <div class="minor-publishing">

                                <!-- weasyfields-fields-type -->
                                <p class="post-attributes-label-wrapper page-template-label-wrapper">
                                    <label class="post-attributes-label">
                                        <?php echo esc_html__( 'Fields type', 'weasyfields' ); ?>
                                    </label>
                                </p>
                                <select name="type" class="widefat">
                                    <option value="product">
                                        <?php echo esc_html__( 'Product', 'weasyfields' ); ?>
                                    </option>
                                    <option value="checkout" disabled>
                                        <?php echo esc_html__( 'Checkout ( Premium version )', 'weasyfields' ); ?>
                                    </option>
                                </select>
                                <!-- weasyfields-fields-type -->

                                <!-- weasyfields-field-actions -->
                                <div class="minor-publishing-actions" style="padding:10px 0 0 0">
                                    <div class="weasyfields-action-buttons">
                                        <button type="button" class="button button-primary" id="weasyfields-save-or-update-action">
                                            <?php echo esc_html__( 'Save fields', 'weasyfields' ); ?>
                                        </button>
                                    </div>
                                    <div class="clear"></div>
                                </div><!-- .minor-publishing-actions -->
                                <!-- weasyfields-field-actions -->

                            </div><!-- .minor-publishing -->
                        </div><!-- .inside -->
                    </div><!-- .postbox -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2><?php echo esc_html__( 'Add new field', 'weasyfields' ); ?></h2>
                        </div>
                        <div class="inside">
                            <div class="minor-publishing">
                                            
                                <?php $this->view( 'field-particles/field-list' ); ?>
                                            
                                <!-- weasyfields-field-actions -->
                                <div class="minor-publishing-actions" style="padding:10px 0 0 0">
                                    <div class="weasyfields-action-buttons">
                                        <button type="button" class="button" id="weasyfields-add-field">
                                            <?php echo esc_html__( 'Add field', 'weasyfields' ); ?>
                                        </button>
                                    </div>
                                    <div class="clear"></div>
                                </div><!-- .minor-publishing-actions -->
                                <!-- weasyfields-field-actions -->

                            </div><!-- .minor-publishing -->
                        </div><!-- .inside -->
                    </div><!-- .postbox -->
                </div><!-- #postbox-container-1 -->

                <div id="postbox-container-2" class="postbox-container">
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2><?php echo esc_html__( 'Fields', 'weasyfields' ); ?></h2>
                        </div>
                        <div class="inside">
                            <ul class="fields sortable ui-sortable" id="weasyfields-field-list">
                                <div class="weasyfields-null-msg">
                                    <?php echo esc_html__( 'Please add a field on the right.', 'weasyfields' );
                                    ?>
                                </div>
                            </ul><!-- .fields -->
                        </div><!-- .inside -->
                    </div><!-- .postbox -->
                </div><!-- .postbox-container-2 -->

            </div><!-- #bost-body -->
            <div class="clear"></div>
        </div><!-- #poststuff -->
    </form><!-- #weasyfields-add-field-form -->
</div><!-- .wrap -->