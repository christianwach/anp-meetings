<!-- assets/templates/theme/wordpress-meetings/content-archive.php -->
<?php

$post_id = get_the_ID();
$post_type = get_post_type( $post_id );

// get meeting object
$meeting = wp_meetings_meeting_get_object( $post_id );

// if found, get meeting data
if ( $meeting !== false ) {

	// Meeting Date
	$date_raw = get_post_meta( $meeting->ID, '_wordpress_meetings_meeting_date', true );
	$meeting_date = mysql2date( get_option('date_format'), $date_raw, false );

	// Meeting Meta
	$organization = get_the_term_list( $meeting->ID, 'organization', '<span class="organization term">', ', ', '</span>' );
	$meeting_type = get_the_term_list( $meeting->ID, 'meeting_type', '<span class="meeting-type term">', ', ', '</span>' );
	$meeting_tags = get_the_term_list( $meeting->ID, 'meeting_tag', '<span class="meeting-tag term">', ', ', '</span>' );

}

// Proposal Meta
$approval_date = $meeting_date;
$effective_date = get_post_meta( $post_id, 'proposal_date_effective', true );
$proposal_status = get_the_term_list( $post_id, 'proposal_status', '<span class="proposal-status term">', ', ', '</span>' );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>

	<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>

	<div class="meta meeting-meta">
		<?php if ( ! empty( $meeting_date ) ) : ?>
			<span class="meta-label"><?php _e( 'Date:','wordpress-meetings'	); ?></span> <?php echo $meeting_date; ?>
		<?php endif; ?>
	</div>

	<div class="meta meeting-meta">
		<?php if ( ! empty( $organization ) ) : ?>
			<span class="meta-label"><?php _e( 'Organization:', 'wordpress-meetings' ); ?></span> <?php echo $organization; ?>
		<?php endif; ?>
	</div>

	<div class="meta meeting-meta">
		<?php if ( ! empty( $meeting_type ) ) : ?>
			<span class="meta-label"><?php _e( 'Type:', 'wordpress-meetings' ); ?></span> <?php echo $meeting_type; ?>
		<?php endif; ?>
	</div>

	<div class="meta meeting-meta">
		<?php if ( ! empty(	$meeting_tags ) ) : ?>
			<span class="meta-label"><?php _e( 'Tags:', 'wordpress-meetings'	); ?></span> <?php echo $meeting_tags; ?>
		<?php endif; ?>
	</div>

	<?php

	$file = 'wordpress-meetings/content-single-nav.php';
	$template_path = wp_meetings_template_get( $file );
	include( $template_path );

	?>

</article>
