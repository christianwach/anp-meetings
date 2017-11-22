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
function wordpress_meetings_meeting_title() {

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
 * Construct the title to display the post type and meeting type rather than post title.
 *
 * @since 2.0
 *
 * @param str $connection_type The connection type.
 * @return str $title The modified title.
 */
function wordpress_meetings_cpt_title( $connection_type ) {

	global $post;

	// init parts array
	$title_parts = array();

	$post_type_object = get_post_type_object( get_post_type( $post->ID ) );
	$post_type_name = $post_type_object->labels->singular_name;

	// add title if present
	if ( ! empty( $post_type_name ) ) {
		array_push( $title_parts, '<span class="post-type">' . $post_type_name . '</span>' );
	}

	// the query
	$meetings = new WP_Query( $args );

	// define query args
	$query_args = array(
		'connected_type' => $connection_type,
		'connected_items' => get_queried_object(),
		'connected_direction' => 'to',
		'nopaging' => true,
		'no_found_rows' => true,
	);

	// the query
	$query = new WP_Query( $query_args );

	// how did we do?
	if ( $query->have_posts() ) {

		// get associated meeting
		while ( $query->have_posts() ) {
			$query->the_post();
			$meeting_id = get_the_ID();
		}

		// prevent weirdness
		wp_reset_postdata();

	}

	// formatted meeting date
	$meeting_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $meeting_id, 'meeting_date', true ) ) );

	// get organization terms
	$org_terms = wp_get_post_terms( $meeting_id, 'organization', array(
		'fields' => 'names'
	) );
	$org_terms = ( ! empty( $org_terms ) ) ? $org_terms[0] : '' ;

	// get meeting type terms
	$type_terms = wp_get_post_terms( $meeting_id, 'meeting_type', array(
		'fields' => 'names'
	) );
	$type_terms = ( ! empty( $type_terms ) ) ? $type_terms[0] : '';

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


