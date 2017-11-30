<!-- assets/templates/admin/settings.php -->
<div class="wrap">

	<h1><?php _e( 'WordPress Meetings Settings', 'wordpress-meetings' ); ?></h1>

	<?php if ( isset( $messages ) AND ! empty( $messages ) ) echo $messages; ?>

	<form method="post" id="wordpress_meetings_settings_form" action="<?php echo $url; ?>">

		<?php wp_nonce_field( 'wordpress_meetings_settings_action', 'wordpress_meetings_settings_nonce' ); ?>

		<hr>

		<table class="form-table">

			<tr>
				<th scope="row"><?php _e( 'Include styles', 'wordpress-meetings' ); ?></th>
				<td>
					<input type="checkbox" class="settings-checkbox" name="wordpress_meetings_include_css" id="wordpress_meetings_include_css" value="1"<?php echo $include_css; ?> />
					<label class="wordpress_meetings_settings_label" for="wordpress_meetings_include_css"><?php _e( 'Check this to include the stylesheet supplied with this plugin.', 'wordpress-meetings' ); ?></label>
				</td>
			</tr>

		</table>

		<hr>

		<p class="submit">
			<input class="button-primary" type="submit" id="wordpress_meetings_settings_submit" name="wordpress_meetings_settings_submit" value="<?php esc_attr_e( 'Save Changes', 'wordpress-meetings' ); ?>" />
		</p>

	</form>

</div><!-- /.wrap -->



