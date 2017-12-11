<?php

global $post;

$post_id = get_the_ID();
$post_type = get_post_type( $post_id );

// get connected meetings
$connected_meetings = get_posts( array(
	'post_status' => 'any',
	'connected_type' => 'meeting_to_event',
	'connected_items' => $post,
	'nopaging' => true,
	'suppress_filters' => false,
) );

// loop, though there's only one
foreach( $connected_meetings as $meeting ) {

	// get meeting type
	$meeting_type = get_the_term_list( $meeting->ID, 'meeting_type', '<span class="meeting-type tag">', ', ', '</span>' );

	// construct link to meeting
	$link = '<a href="' . get_permalink( $meeting->ID ) . '">' . get_the_title( $meeting->ID ) . '</a>';

}

?><!-- assets/templates/theme/wordpress-meetings/content-event-meta.php -->
<?php if ( ! empty( $link ) ) : ?>
	<li><strong><?php _e( 'Meeting:', 'wordpress-meetings' ); ?></strong> <?php echo $link; ?></li>
<?php endif; ?>

<?php if ( ! empty( $meeting_type ) ) : ?>
	<li><strong><?php _e( 'Meeting Type:', 'wordpress-meetings' ); ?></strong> <?php echo $meeting_type; ?></li>
<?php endif; ?>
