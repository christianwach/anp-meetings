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



/**
 * Enqueue stylesheet.
 *
 * @since 2.0
 */
function wordpress_meetings_enqueue_styles() {

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
if ( ! function_exists( 'wordpress_meetings_pre_get_posts' ) ) {

	function wordpress_meetings_pre_get_posts( $query ) {

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

	//add_action( 'pre_get_posts', 'wordpress_meetings_pre_get_posts' );

}



/**
 * Add Post-2-Post Connection Types.
 *
 * The action is tied to the wordpress_meetings_init() function.
 *
 * @since 0.1.0
 *
 * @uses p2p_register_connection_type()
 */
function wordpress_meetings_connection_types() {

	p2p_register_connection_type( array(
		'name' => 'meeting_to_agenda',
		'from' => 'meeting',
		'to' => 'agenda',
		'reciprocal' => true,
		'cardinality' => 'one-to-one',
		'admin_column' => true,
		'admin_dropdown' => 'to',
		'title' => array( 'from' => __( 'Agenda', 'wordpress-meetings' ), 'to' => __( 'Meeting', 'wordpress-meetings' ) ),
	) );

	p2p_register_connection_type( array(
		'name' => 'meeting_to_summary',
		'from' => 'meeting',
		'to' => 'summary',
		'reciprocal' => true,
		'cardinality' => 'one-to-one',
		'admin_column' => true,
		'admin_dropdown' => 'to',
		'title' => array( 'from' => __( 'Summary', 'wordpress-meetings' ), 'to' => __( 'Meeting', 'wordpress-meetings' ) ),
	) );

	p2p_register_connection_type( array(
		'name' => 'meeting_to_proposal',
		'from' => 'meeting',
		'to' => 'proposal',
		'reciprocal' => true,
		'cardinality' => 'one-to-many',
		'admin_column' => true,
		'admin_dropdown' => 'any',
		'sortable' => 'any',
		'title' => array( 'from' => __( 'Proposals', 'wordpress-meetings' ), 'to' => __( 'Meeting', 'wordpress-meetings' ) ),
	) );

	/*
	 * Register Connection Between Meeting and Event Post types.
	 *
	 * @since 0.9.1
	 *
	 * @uses post_type_exists()
	 * @uses p2p_register_connection_type()
	 * @link https://github.com/scribu/wp-posts-to-posts/wiki/p2p_register_connection_type
	 */
	if ( post_type_exists( 'event' ) ) {
	  p2p_register_connection_type( array(
		  'name' => 'meeting_to_event',
		  'from' => 'meeting',
		  'to' => 'event',
		  'reciprocal' => true,
		  'cardinality' => 'one-to-one',
		  'admin_column' => true,
		  'admin_dropdown' => 'any',
		  'sortable' => 'any',
		  'title' => array( 'from' => __( 'Event', 'wordpress-meetings' ), 'to' => __( 'Meeting', 'wordpress-meetings' ) ),
	  ) );
	}

}

add_action( 'p2p_init', 'wordpress_meetings_connection_types' );



/**
 * ADMIN CONNECTION.
 *
 * Order posts alphabetically in the P2P connections box.
 */
function wordpress_connection_box_order( $args, $ctype, $post_id ) {
	if ( ( 'meeting_to_agenda' == $ctype->name || 'meeting_to_summary' == $ctype->name || 'meeting_to_proposal' == $ctype->name ) ) {
		$args['orderby'] = 'title';
		$args['order'] = 'asc';
	}

	return $args;
}

add_filter( 'p2p_connectable_args', 'wordpress_connection_box_order', 10, 3 );



/**
 * Agenda.
 *
 * Render agenda associated with content.
 */
function meeting_get_agenda( $post_id ) {

	$query_args = array(
		'connected_type' => 'meeting_to_agenda',
		'connected_items' => intval( $post_id ),
		'nopaging' => true
	);

	$agendas = get_posts( $query_args );

	if ( empty( $agendas ) ) {
		return;
	}

	$post = $agendas[0];

	$post_type_obj = get_post_type_object( get_post_type( $post->ID ) );
	$post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : '';

	$content = sprintf( '<li class="agenda-link"><a href="%s" rel="bookmark" title="View %s"><span class="link-text">%s</span></a></li>',
	  get_post_permalink( $post->ID ),
	  ( $post_type_name ) ? $post_type_name : $post->post_title,
	  ( $post_type_name ) ? $post_type_name : $post->post_title
	);

	// Filter added to allow content be overriden
	return apply_filters( 'meeting_get_agenda_content', $content, $post_id );

}



/**
 * Summary.
 *
 * Render summary associated with content.
 */
function meeting_get_summary( $post_id ) {

	$query_args = array(
		'connected_type' => 'meeting_to_summary',
		'connected_items' => intval( $post_id ),
		'nopaging' => true
	);

	$summaries = get_posts( $query_args );

	if ( empty( $summaries ) ) {
	  return;
	}

	$post = $summaries[0];

	$post_type_obj = get_post_type_object( get_post_type( $post->ID ) );
	$post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : '';

	$content = sprintf( '<li class="summary-link"><a href="%s" rel="bookmark" title="View %s"><span class="link-text">%s</span></a></li>',
	  get_post_permalink( $post->ID ),
	  ( $post_type_name ) ? esc_attr( $post_type_name ) : esc_attr( $post->post_title ),
	  ( $post_type_name ) ? $post_type_name : $post->post_title
	);

	// Filter added to allow content be overriden
	return apply_filters( 'meeting_get_summary_content', $content, $post_id );

}



/**
 * Proposal.
 *
 * Render proposal associated with content.
 */
function meeting_get_proposal( $post_id ) {

	$query_args = array(
		'connected_type' => 'meeting_to_proposal',
		'connected_items' => intval( $post_id ),
		'nopaging' => true
	);

	$proposals = get_posts( $query_args );

	if ( empty( $proposals ) ) {
	  return;
	}

	$url = array(
		'connected_type' => 'meeting_to_proposal',
		'connected_items' => intval( $post_id ),
		'connected_direction' => 'from'
	);

	$content = sprintf( '<li class="proposal-link"><a href="%s" rel="bookmark" title="View %s"><span class="link-text">%s</span></a></li>',
	  esc_url( add_query_arg( $url ) ),
	  ( 1 == count( $proposals ) ) ? __( 'Proposal', 'wordpress-meetings' ) : __( 'Proposals', 'wordpress-meetings' ),
	  ( 1 == count( $proposals ) ) ? __( 'Proposal', 'wordpress-meetings' ) : __( 'Proposals', 'wordpress-meetings' )
	);

	// Filter added to allow content be overriden
	return apply_filters( 'meeting_get_proposal_content', $content, $post_id );

}



/**
 * Get Events.
 *
 * Return event connected to post.
 *
 * @since 1.0.0
 *
 * @param  int $post_id
 * @return string event
 */
 function meeting_get_event( $post_id ) {

	 $query_args = array(
		 'connected_type' => 'meeting_to_event',
		 'connected_items' => intval( $post_id ),
		 'nopaging' => true
	 );

	 $events = get_posts( $query_args );

	 if ( empty( $events ) ) {
	   return;
	 }

	 $post = $events[0];

	 $post_type_obj = get_post_type_object( get_post_type( $post->ID ) );
	 $post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : '';

	 $content = sprintf( '<li class="event-link"><a href="%s" rel="bookmark" Title="View %s"><span class="link-text">%s</span></a></li>',
	   get_post_permalink( $post->ID ),
	   ( $post_type_name ) ? $post_type_name : $post->post_title,
	   ( $post_type_name ) ? $post_type_name : $post->post_title
	 );

	 // Filter added to allow content be overriden
	 return apply_filters( 'meeting_get_event_content', $content, $post_id );

}



/**
 * Modify Event Archive Meta Content.
 *
 * @since 1.1.0
 *
 * @return string $content
 */
function wordpress_meetings_event_meta_content() {

	global $post;

	// use template
	$file = 'wordpress-meetings/content-event-meta.php';
	$content = wordpress_meetings_template_buffer( $file );

	// --<
	return $content;

}

add_action( 'eventorganiser_additional_event_meta', 'wordpress_meetings_event_meta_content' );



