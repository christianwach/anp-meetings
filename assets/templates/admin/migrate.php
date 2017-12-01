<!-- assets/templates/admin/migrate.php -->
<div class="wrap">

	<h1><?php _e( 'WordPress Meetings Migration', 'wordpress-meetings' ); ?></h1>

	<?php if ( isset( $messages ) AND ! empty( $messages ) ) echo $messages; ?>

	<form method="post" id="wordpress_meetings_migrate_form" action="<?php echo $url; ?>">

		<?php wp_nonce_field( 'wordpress_meetings_migrate_action', 'wordpress_meetings_migrate_nonce' ); ?>

		<hr>

		<p><?php _e( 'To continue using this plugin, we need to migrate the ANP Meetings settings.', 'wordpress-meetings' ); ?></p>

		<p><?php _e( 'After this is done, please deactivate the ANP Meetings plugin to continue to use Meetings.', 'wordpress-meetings' ); ?></p>

		<hr>

		<p class="submit">
			<input class="button-primary" type="submit" id="wordpress_meetings_migrate_submit" name="wordpress_meetings_migrate_submit" value="<?php esc_attr_e( 'Migrate', 'wordpress-meetings' ); ?>" />
		</p>

	</form>

</div><!-- /.wrap -->



