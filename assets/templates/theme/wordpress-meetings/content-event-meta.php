<!-- assets/templates/theme/wordpress-meetings/content-event-meta.php -->
<?php if ( wordpress_meetings_event_has_meeting_link() ) : ?>
	<li><strong><?php _e( 'Meeting:', 'wordpress-meetings' ); ?></strong> <?php wordpress_meetings_event_meeting_link(); ?></li>
<?php endif; ?>

<?php if ( wordpress_meetings_event_has_meeting_type() ) : ?>
	<li><strong><?php _e( 'Meeting Type:', 'wordpress-meetings' ); ?></strong> <?php wordpress_meetings_event_meeting_type(); ?></li>
<?php endif; ?>
