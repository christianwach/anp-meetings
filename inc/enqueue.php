<?php

/**
 * WordPress Meetings Enqueue.
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.1.0
 * @package   WordPress_Meetings
 */

function wordpress_meetings_enqueue_scripts() {

	$hide_css = wordpress_meetings_get_option( 'anp_meetings_css', false );

	$post_types = array(
		'meeting',
		'proposal',
		'summary',
		'agenda'
	);

	$post_tax = array(
		'meeting_type',
		'meeting_tag',
		'proposal_status',
		'organization'
	);

	if ( ! $hide_css && ( is_singular( $post_types ) || is_post_type_archive( $post_types ) || is_tax( $post_tax ) ) ) {
		wp_enqueue_style(
			'wordpress-meetings',
			WORDPRESS_MEETINGS_PLUGIN_URL . 'assets/css/style.min.css',
			array( 'dashicons' )
		);
	}

}
add_action( 'wp_enqueue_scripts', 'wordpress_meetings_enqueue_scripts' );
