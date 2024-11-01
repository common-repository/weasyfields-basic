<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo esc_html__( 'Fields', 'weasyfields' ); ?>
    </h1>
    <a href="<?php echo esc_url( $this->url . '&action=add-new' ); ?>" class="page-title-action">Add New</a>
    <hr class="wp-header-end">

    <?php 
        if ( isset( $_GET['no-found'] ) ){
            $this->notice( 'error', esc_html__( 'Related fields could not be found. You have been directed here.', 'weasyfields' ), true );
        }
    ?>
    <?php $fields_list->display(); ?>
</div>