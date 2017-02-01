<?php

/*
 * Content Variables - DO NOT REMOVE
 * Variables that can be used in the template
 */
$post_id = $post->ID;
$post_type = get_post_type( $post_id );
?>

<?php if( 'meeting' == $post_type ) : ?>

    <div class="meta meeting-meta"><?php echo ( !empty( $meeting_type ) ) ? __( 'Type: ', 'meetings'  ) . $meeting_type : '' ; ?></div>

    <?php $events =  (function_exists( 'meeting_get_event' ) ) ? meeting_get_event( $post_id ) : ''; ?>

    <?php if( $events ) : ?>

        <ul class="connected-content">

          <?php echo ( $events ) ? $events : ''; ?>

        </ul>

    <?php endif; ?>

<?php endif; ?>
