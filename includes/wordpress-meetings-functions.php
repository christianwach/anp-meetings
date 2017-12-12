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
 * Return markup to link to an Agenda connected to a Meeting.
 *
 * @since 1.0.0
 *
 * @param int $post_id The numeric ID of the Meeting.
 * @return string $content The rendered list item showing the Agenda.
 */
function meeting_get_agenda( $post_id ) {

	// build query (do not show drafts)
	$query_args = array(
		'connected_type' => 'meeting_to_agenda',
		'connected_items' => intval( $post_id ),
		'nopaging' => true
	);

	// get agendas
	$agendas = get_posts( $query_args );

	// return empty string if there's no Agenda
	if ( empty( $agendas ) ) {
		return;
	}

	// one-to-one means there's only a single Agenda
	$agenda = $agendas[0];

	// get post type name, fall back to Agenda title
	$post_type_obj = get_post_type_object( get_post_type( $post->ID ) );
	$post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : $agenda->post_title;

	// construct title attribute
	$title = sprintf( __( 'View %s', 'wordpress-meetings' ), $post_type_name );

	// construct list item
	$content = sprintf(
		'<li class="agenda-link"><a href="%1$s" rel="bookmark" title="%2$s"><span class="link-text">%3$s</span></a></li>',
		get_permalink( $summary->ID ),
		esc_attr( $title ),
		esc_html( $title )
	);

	/**
	 * Allow content be filtered.
	 *
	 * @since 1.0
	 *
	 * @param str $content The rendered list item.
	 * @param int $post_id The numeric ID of the Meeting.
	 * @return str $content The modified list item.
	 */
	return apply_filters( 'meeting_get_agenda_content', $content, $post_id );

}



/**
 * Return markup to link to a Summary connected to a Meeting.
 *
 * @since 1.0.0
 *
 * @param int $post_id The numeric ID of the Meeting.
 * @return string $content The rendered list item showing the Summary.
 */
function meeting_get_summary( $post_id ) {

	// build query (do not show drafts)
	$query_args = array(
		'connected_type' => 'meeting_to_summary',
		'connected_items' => intval( $post_id ),
		'nopaging' => true
	);

	// get summaries
	$summaries = get_posts( $query_args );

	// return empty string if there's no Summary
	if ( empty( $summaries ) ) {
		return '';
	}

	// one-to-one means there's only a single Summary
	$summary = $summaries[0];

	// get post type name, fall back to Summary title
	$post_type_obj = get_post_type_object( get_post_type( $summary->ID ) );
	$post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : $summary->post_title;

	// construct title attribute
	$title = sprintf( __( 'View %s', 'wordpress-meetings' ), $post_type_name );

	// construct list item
	$content = sprintf(
		'<li class="summary-link"><a href="%1$s" rel="bookmark" title="%2$s"><span class="link-text">%3$s</span></a></li>',
		get_permalink( $summary->ID ),
		esc_attr( $title ),
		esc_html( $title )
	);

	/**
	 * Allow content be filtered.
	 *
	 * @since 1.0
	 *
	 * @param str $content The rendered list item.
	 * @param int $post_id The numeric ID of the Meeting.
	 * @return str $content The modified list item.
	 */
	return apply_filters( 'meeting_get_summary_content', $content, $post_id );

}



/**
 * Return markup to link to the Proposals connected to a Meeting.
 *
 * At present, this does not function as expected. The UI probably needs a
 * rethink, since there can be multiple Proposals per Meeting.
 *
 * @since 1.0.0
 *
 * @param int $post_id The numeric ID of the Meeting.
 * @return string $content The rendered list showing the Proposals.
 */
function meeting_get_proposal( $post_id ) {

	// build query (do not show drafts)
	$query_args = array(
		'connected_type' => 'meeting_to_proposal',
		'connected_items' => intval( $post_id ),
		'nopaging' => true
	);

	// get proposals
	$proposals = get_posts( $query_args );

	// return empty string if there are no Proposals
	if ( empty( $proposals ) ) {
		return '';
	}

	$url = array(
		'connected_type' => 'meeting_to_proposal',
		'connected_items' => intval( $post_id ),
		'connected_direction' => 'from'
	);

	// construct list item
	$content = sprintf(
		'<li class="proposal-link"><a href="%1$s" rel="bookmark" title="View %2$s"><span class="link-text">%3$s</span></a></li>',
		esc_url( add_query_arg( $url ) ),
		( 1 == count( $proposals ) ) ? __( 'Proposal', 'wordpress-meetings' ) : __( 'Proposals', 'wordpress-meetings' ),
		( 1 == count( $proposals ) ) ? __( 'Proposal', 'wordpress-meetings' ) : __( 'Proposals', 'wordpress-meetings' )
	);

	/**
	 * Allow content be filtered.
	 *
	 * @since 1.0
	 *
	 * @param str $content The rendered list item.
	 * @param int $post_id The numeric ID of the Meeting.
	 * @return str $content The modified list item.
	 */
	return apply_filters( 'meeting_get_proposal_content', $content, $post_id );

}



/**
 * Return markup to link to an Event connected to a Meeting.
 *
 * @since 1.0.0
 *
 * @param int $post_id The numeric ID of the Meeting.
 * @return string $content The rendered list item showing the Event.
 */
 function meeting_get_event( $post_id ) {

	// build query (do not show drafts)
	$query_args = array(
		'connected_type' => 'meeting_to_event',
		'connected_items' => intval( $post_id ),
		'nopaging' => true
	);

	// get events
	$events = get_posts( $query_args );

	// return empty string if there's no Event
	if ( empty( $events ) ) {
		return '';
	}

	// one-to-one means there's only a single Event
	$event = $events[0];

	// get post type name, fall back to Event title
	$post_type_obj = get_post_type_object( get_post_type( $event->ID ) );
	$post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : $event->post_title;

	// construct title attribute
	$title = sprintf( __( 'View %s', 'wordpress-meetings' ), $post_type_name );

	// construct list item
	$content = sprintf(
		'<li class="event-link"><a href="%1$s" rel="bookmark" title="%2$s"><span class="link-text">%3$s</span></a></li>',
		get_permalink( $event->ID ),
		esc_attr( $title ),
		esc_html( $title )
	);

	/**
	 * Allow content be filtered.
	 *
	 * @since 1.0
	 *
	 * @param str $content The rendered list item.
	 * @param int $post_id The numeric ID of the Meeting.
	 * @return str $content The modified list item.
	 */
	return apply_filters( 'meeting_get_event_content', $content, $post_id );

}



