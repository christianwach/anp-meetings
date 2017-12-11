<?php

/**
 * WordPress Meetings Uninstall.
 *
 * Remove anything that this plugin installs.
 *
 * @since 2.0.1
 */

// kick out if uninstall not called from WordPress
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// delete options
delete_option( 'wordpress_meetings_version' );
delete_option( 'wordpress_meetings_settings' );



