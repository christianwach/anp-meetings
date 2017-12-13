<!-- assets/templates/theme/wordpress-meetings/content-single-footer.php -->
<?php

$post_id = get_the_ID();
$post_type = get_post_type( $post_id );

// get meeting object
$meeting = wp_meetings_meeting_get_object( $post_id );

// if found
if ( $meeting !== false ) {

	// get proposals
	$connected_proposals = get_posts( array(
		'connected_type' => 'meeting_to_proposal',
		'connected_items' => $meeting,
		'nopaging' => true,
		'suppress_filters' => false,
	) );

}

?>

<footer class="entry-footer">

	<?php if ( ! empty( $connected_proposals ) ) : ?>

		<h3 id="proposals"><?php echo sprintf( __( 'Proposals for %s', 'wordpress-meetings' ), esc_html( $meeting->post_title ) ); ?></h3>

		<ul class="proposal-links">
			<?php foreach( $connected_proposals as $proposal ) : ?>
				<?php $statuses = wp_get_post_terms( $proposal->ID, 'proposal_status', array( 'fields' => 'names' ) ); ?>
				<?php $status = ( ! empty( $statuses ) ) ? $statuses[0] : ''; ?>
				<li class="proposal-link">
					<a href="<?php echo get_permalink( $proposal->ID ); ?>"><?php echo esc_html( $proposal->post_title ); ?></a>
					<?php if ( $status ) : ?>
						<span class="proposal-status">
							<span class="meta-label"><?php _e( 'Status', 'wordpress-meetings' ); ?></span>
							<?php echo $status; ?>
						</span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>

	<?php endif; ?>

</footer>
