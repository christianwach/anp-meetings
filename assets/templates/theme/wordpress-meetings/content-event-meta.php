<!-- assets/templates/theme/wordpress-meetings/content-event-meta.php -->
<?php if ( wp_meetings_event_has_meeting_link() ) : ?>
	<li><strong><?php _e( 'Meeting:', 'wordpress-meetings' ); ?></strong> <?php wp_meetings_event_meeting_link(); ?></li>
<?php endif; ?>

<?php if ( wp_meetings_event_has_meeting_type() ) : ?>
	<li><strong><?php _e( 'Meeting Type:', 'wordpress-meetings' ); ?></strong> <?php wp_meetings_event_meeting_type(); ?></li>
<?php endif; ?>
