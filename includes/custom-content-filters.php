<?php

/**
 * WordPress Meetings Content Filters.
 *
 * @author	Pea, Glocal
 * @license   GPL-2.0+
 * @link	  http://glocal.coop
 * @since	 1.0.0
 * @package   WordPress_Meetings
 */



/**
 * Content Filter.
 *
 * Modify `the_content` to display custom post meta data above and below content.
 *
 * @uses the_content
 *
 * @param string $content
 * @return string $content
 */
if ( ! function_exists( 'wordpress_meetings_content_filter' ) ) {

	function wordpress_meetings_content_filter( $content ) {

		if ( is_admin() || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		$post_types = array(
			'meeting',
			'proposal',
			'summary',
			'agenda',
			'event'
		);

		$post_tax = array(
			'organization',
			'meeting_type',
			'meeting_tag',
			'proposal_status',
		);

		if ( ( is_post_type_archive( array( 'meeting' ) ) || is_tax( array( 'organization', 'meeting_type', 'meeting_tag' ) ) ) && in_the_loop() ) {

			global $post;

			ob_start();
			include( WORDPRESS_MEETINGS_PLUGIN_DIR . 'assets/templates/content-archive.php' );
			$content = ob_get_contents();
			ob_end_clean();

		}

		if ( ( is_post_type_archive( $post_types ) || is_tax( $post_tax ) ) && in_the_loop() ) {

			global $post;

			ob_start();
			include( WORDPRESS_MEETINGS_PLUGIN_DIR . 'assets/templates/content-archive.php' );
			$content = ob_get_contents();
			ob_end_clean();

		}

		if ( is_singular( $post_types ) && in_the_loop() ) {

			global $post;

			ob_start();
			include( WORDPRESS_MEETINGS_PLUGIN_DIR . 'assets/templates/content-single.php' );
			$header = ob_get_contents();
			ob_end_clean();

			$body = wpautop( $post->post_content );

			ob_start();
			include( WORDPRESS_MEETINGS_PLUGIN_DIR . 'assets/templates/single-footer.php' );
			$footer = ob_get_contents();
			ob_end_clean();

			$content = $header . $body . $footer;

		}

		return $content;

	}

	add_filter( 'the_content', 'wordpress_meetings_content_filter' );

}



/**
 * Modify Event Arhive Meta Content.
 *
 * @since 1.1.0
 *
 * @return string $content
 */
function wordpress_meetings_event_meta_content() {

	global $post;

	ob_start();
	include( WORDPRESS_MEETINGS_PLUGIN_DIR . 'assets/templates/content-event-meta.php' );
	$content = ob_get_contents();
	ob_end_clean();

	return $content;

}

add_action( 'eventorganiser_additional_event_meta', 'wordpress_meetings_event_meta_content' );


