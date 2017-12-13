<?php

/**
 * WordPress Meetings Functions.
 *
 * Functions for the WordPress Meetings plugin.
 *
 * @package WordPress_Meetings
 * @since 2.0
 */



/**
 * Construct the title to display the organization and meeting type rather than post title.
 *
 * @since 2.0
 *
 * @return str $title The modified title.
 */
function wp_meetings_meeting_title() {

	global $post;

	// init parts array
	$title_parts = array();

	// get organization terms
	$org_terms = wp_get_post_terms( $post->ID, 'organization', array(
		'fields' => 'names'
	) );
	$org_terms = ( ! empty( $org_terms ) ) ? $org_terms[0] : '' ;

	// get meeting type terms
	$type_terms = wp_get_post_terms( $post->ID, 'meeting_type', array(
		'fields' => 'names'
	) );
	$type_terms = ( ! empty( $type_terms ) ) ? $type_terms[0] : '';

	// if there are no terms, use post title
	if ( empty( $org_terms ) AND empty( $type_terms ) ) {
		return $post->post_title;
	}

	// add terms if present
	if ( ! empty( $org_terms ) ) {
		array_push( $title_parts, '<span class="organization">' . $org_terms . '</span>' );
	}
	if ( ! empty( $type_terms ) ) {
		array_push( $title_parts, '<span class="type">' . $type_terms . '</span>' );
	}

	// concatenate
	$title = implode( ' - ', $title_parts );

	// --<
	return $title;

}



/**
 * Construct the title to display the post type and meeting title rather than post title.
 *
 * @since 2.0
 *
 * @param str $connection_type The connection type.
 * @return str $title The modified title.
 */
function wp_meetings_cpt_title( $connection_type ) {

	global $post;

	// get CPT name
	$post_type_object = get_post_type_object( get_post_type( $post->ID ) );
	$post_type_name = $post_type_object->labels->singular_name;

	// init Meeting title
	$meeting_title = __( 'Meeting', 'wordpress-meetings' );

	// get connected meetings
	$connected_meetings = get_posts( array(
		'connected_type' => $connection_type,
		'connected_items' => get_queried_object(),
		'connected_direction' => 'to',
		'nopaging' => true,
		'no_found_rows' => true,
		'suppress_filters' => false,
	) );

	// loop, though there will only be one
	foreach( $connected_meetings as $meeting ) {
		$meeting_title = $meeting->post_title;
	}

	// construct title
	$title = sprintf(
		__( '%1$s for %2$s' ),
		esc_html( $post_type_name ),
		esc_html( $meeting_title )
	);

	// --<
	return $title;

}



/**
 * Enqueue stylesheet.
 *
 * @since 2.0
 */
function wp_meetings_enqueue_styles() {

	// only do this once
	static $done;
	if ( $done ) return;

	// bail if disabled via admin setting
	$include_css = wordpress_meetings()->admin->setting_get( 'include_css', 'y' );
	if ( $include_css != 'y' ) return;

	// do enqueue
	wp_enqueue_style(
		'wordpress-meetings',
		WORDPRESS_MEETINGS_URL . 'assets/css/style.min.css',
		array( 'dashicons' ), // dependencies
		WORDPRESS_MEETINGS_VERSION, // version
		'all' // media
	);

	// set flag
	$done = true;

}



// #############################################################################
// Below are functions migrated from other files
// #############################################################################



/**
 * CUSTOM POST TYPE QUERY.
 *
 * Modify query parameters for meeting post archive, meeting_tag archive or meeting_type archive.
 *
 * Commented out @since 1.0.9
 */
if ( ! function_exists( 'wp_meetings_pre_get_posts' ) ) {

	function wp_meetings_pre_get_posts( $query ) {

		// Do not modify queries in the admin or other queries (like nav)
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		// If meeting post archive, meeting_tag archive or meeting_type archive
		if ( ( is_post_type_archive( array( 'meeting', 'summary', 'agenda' ) ) || is_tax( 'meeting_tag' ) || is_tax( 'meeting_type' ) ) ) {

			set_query_var( 'orderby', 'meta_value' );
			set_query_var( 'meta_key', 'meeting_date' );
			set_query_var( 'order', 'DESC' );

		}

		return $query;

	}

	//add_action( 'pre_get_posts', 'wp_meetings_pre_get_posts' );

}



