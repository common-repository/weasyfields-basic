<?php 
if ( true === $dismissible ) {
    $dismissible = " is-dismissible";
} else {
    $dismissible = "";
}
?>
<div class="notice notice-<?php echo esc_attr( $type ); echo esc_attr( $dismissible ); ?>">
    <p><?php echo esc_html( $notice ); ?></p>
    <?php if ( ! is_admin() || true === $dismissible ) { ?>
        <span class="bp-close-notice">X</span>
    <?php } ?>
</div>