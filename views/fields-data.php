<?php
if ( $view_data ) {
    ?>
    <h4><?php echo esc_html__( 'Fields data ( WeasyFields )', 'weasyfields' ); ?></h4>
    <dl class="weasyfields-product-page-data-list">
    <?php
    $kses = array( 'br' => array() );
    foreach ( $view_data as $key => $value ) {
        if ( 'data' === $value['type'] ) {
            ?>
                <dt><?php echo esc_html( $value['label'] ); ?>:</dt>
                <dd><p><?php echo wp_kses( $value['value'], $kses ); ?></p></dd>
            <?php
            } elseif ( 'file' === $value['type'] ) {
            ?>
                <dt><?php echo esc_html( $value['label'] ); ?>:</dt>
                <dd><p><?php echo wp_kses( $value['value'], $kses ); ?></p></dd>
                <dd>
                    <p>
                        <a href="<?php echo esc_url( $value['url'] ); ?>" target="_blank" class="button">
                            <?php echo esc_html__( 'Open File', 'weasyfields' ); ?>
                        </a>
                    </p>
                </dd>
            <?php
            if ( false === $mail_send ) {
                $url = admin_url();
                if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) {
                    if ( isset( $_GET['post'] ) ) {
                        $url = admin_url( 'post.php?post='.$_GET['post'].'&action=edit&weasyfields_delete_file=' .$value['path'] );
                    }
                }
                ?> 
                <dd>
                    <p>
                        <a href="<?php echo esc_url( $url ); ?>" class="button">
                        <?php echo esc_html__( 'Delete File', 'weasyfields' ); ?>
                        </a>
                    </p>
                </dd>
                <?php
            }
        } elseif ( 'deleted-file' === $value['type'] ) {
            ?>
                <dt><?php echo esc_html( $value['label'] ); ?>:</dt>
                <dd><p><?php echo esc_html__( 'Deleted file', 'weasyfields' ); ?></p></dd>
                <dd>
                    <p><?php echo esc_html__( 'This file may have been deleted or moved!', 'weasyfields' ); ?>
                    </p>
                </dd>
            <?php
        } elseif ( 'null-file' === $value['type'] ) {
            ?>
                <dt><?php echo esc_html( $value['label'] ); ?>:</dt>
                <dd><p><?php echo esc_html__( 'Null file', 'weasyfields' ); ?></p></dd>
                <dd>
                    <p><?php echo esc_html__( 'The user has not selected a file.', 'weasyfields' ); ?>
                    </p>
                </dd>
            <?php
        }
    }
    ?>
    </dl>
    <?php
}
