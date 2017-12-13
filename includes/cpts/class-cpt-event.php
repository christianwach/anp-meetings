<?php

/**
 * WordPress Meetings Event Class.
 *
 * A class that holds Event functionality for WordPress Meetings.
 *
 * This class is different to the others in that the 'event' CPT is created and
 * managed by the Event Organiser plugin. This class, therefore, extends the
 * functionality of this plugin to include the 'event' Post Type.
 *
 * @package WordPress_Meetings
 */
class WordPress_Meetings_CPT_Event {

	/**
	 * Plugin (calling) object.
	 *
	 * @since 2.0.1
	 * @access public
	 * @var object $plugin The plugin object.
	 */
	public $plugin;

	/**
	 * Custom Post Type name.
	 *
	 * @since 2.0.1
	 * @access public
	 * @var str $post_type_name The name of the Custom Post Type.
	 */
	public $post_type_name = 'event';



	/**
	 * Constructor.
	 *
	 * @since 2.0.1
	 *
	 * @param object $parent The parent object.
	 */
	public function __construct( $parent ) {

		// store
		$this->plugin = $parent;

	}



	/**
	 * Register WordPress hooks.
	 *
	 * @since 2.0.1
	 */
	public function register_hooks() {

		// bail if Event Organiser plugin is not present
		if ( ! defined( 'EVENT_ORGANISER_VER' ) ) return;

		// filter the params that new Events are created with
		add_filter( 'p2p_new_post_args', array( $this, 'event_before_create' ), 10, 3 );

		// receive callbacks when Meeting metadata is saved
		add_action( 'wordpress_meetings_cpt_meeting_meta_saved', array( $this, 'event_sync' ), 10, 2 );

		// keep Event status in sync with Meeting status
		add_action( 'wordpress_meetings_cpt_meeting_status', array( $this, 'event_status' ), 10, 3 );

		// add link to Meeting to Event content
		add_action( 'eventorganiser_additional_event_meta', array( $this, 'event_meta' ), 10 );

	}



	// #########################################################################



	/**
	 * Format an event when created via P2P.
	 *
	 * @since 2.0.1
	 *
	 * @param array $args The params used to create the event.
	 * @param object $ctype The P2P connection type object.
	 * @param int $post_id The ID of the meeting.
	 */
	public function event_before_create( $args, $ctype, $post_id ) {

		// only intercept events
		if ( $args['post_type'] !== 'event' ) return $args;

		// only intercept events created via meetings admin
		if ( 'meeting_to_event' !== $ctype->name ) return $args;
		if ( 'from' !== $ctype->get_direction() ) return $args;

		// get meeting
		$meeting = get_post( $post_id );

		// publish the event if the meeting is published
		if ( $meeting->post_status == 'publish' ) {
			$args['post_status'] = 'publish';
		}

		// add post-connection hook so we can set dates
		add_action( 'p2p_created_connection', array( $this, 'event_connected' ), 10, 1 );

		// --<
		return $args;

	}



	/**
	 * Sync Event attributes when a Meeting is saved.
	 *
	 * @since 2.0.1
	 *
	 * @param WP_Post $post The Meeting post object.
	 * @param array $metadata The Event metadata.
	 */
	public function event_sync( $post, $metadata ) {

		// get connected events
		$connected_events = get_posts( array(
			'post_status' => 'any',
			'connected_type' => 'meeting_to_event',
			'connected_items' => $post,
			'nopaging' => true,
			'suppress_filters' => false,
		) );

		// loop, though there's only one
		foreach( $connected_events as $event ) {

			// build data
			$args = array(
				'event_id' => $event->ID,
				'date' => $metadata['date'],
				'start_time' => $metadata['start_time'],
				'end_time' => $metadata['end_time'],
			);

			// update connected event
			$this->event_update( $args );

		}

	}



	/**
	 * Sync Event status when a Meeting is saved.
	 *
	 * @since 2.0.1
	 *
	 * @param string $new_status The new post status.
	 * @param string $old_status The old post status.
	 * @param WP_Post $post The Meeting post object.
	 */
	public function event_status( $new_status, $old_status, $post ) {

		// get connected events
		$connected_events = get_posts( array(
			'post_status' => 'any',
			'connected_type' => 'meeting_to_event',
			'connected_items' => $post,
			'nopaging' => true,
			'suppress_filters' => false,
		) );

		// loop, though there's only one
		foreach( $connected_events as $event ) {

			// no event data to update
			$event_data = array();

			// update Event published status
			$post_data = array(
				'post_status' => $post->post_status,
			);

			// update the event
			$event_id = eo_update_event( $event->ID, $post_data, $event_data );

		}

	}



	/**
	 * Add the start and end dates of an event when a connection is made.
	 *
	 * @since 2.0.1
	 *
	 * @param int $p2p_id The connection ID.
	 */
	public function event_connected( $p2p_id ) {

		// get new connection
		$connection = p2p_get_connection( $p2p_id );

		// only intercept events created via meetings (above)
		if ( 'meeting_to_event' !== $connection->p2p_type ) return;

		// get the meeting post object
		$meeting = get_post( $connection->p2p_from );

		// grab date from custom field
		$key = '_' . $this->plugin->cpts['meeting']->date_meta_key;
		$date = '';
		if ( get_post_meta( $meeting->ID, $key, true ) != '' ) {
			$date = get_post_meta( $meeting->ID, $key, true );
		}

		// grab start time from custom field
		$key = '_' . $this->plugin->cpts['meeting']->start_time_meta_key;
		$start_time = '';
		if ( get_post_meta( $meeting->ID, $key, true ) != '' ) {
			$start_time = get_post_meta( $meeting->ID, $key, true );
		}

		// grab end time from custom field
		$key = '_' . $this->plugin->cpts['meeting']->end_time_meta_key;
		$end_time = '';
		if ( get_post_meta( $meeting->ID, $key, true ) != '' ) {
			$end_time = get_post_meta( $meeting->ID, $key, true );
		}

		// bail if we don't have enough data
		if ( empty( $date ) OR empty( $start_time ) OR empty( $end_time ) ) return;

		// remove our hooks
		remove_action( 'save_post', array( $this, 'save_post' ), 1 );

		// build data
		$args = array(
			'event_id' => $connection->p2p_to,
			'date' => $date,
			'start_time' => $start_time,
			'end_time' => $end_time,
		);

		// update event
		$this->event_update( $args );

		// reset hooks
		add_action( 'save_post', array( $this, 'save_post' ), 1, 2 );

	}



	/**
	 * Update a connected event.
	 *
	 * @since 2.0.1
	 *
	 * @param array $args The arguments to pass to the event.
	 */
	private function event_update( $args ) {

		// construct start and end date in 'Y-m-d H:i:s' format
		$start_date = $args['date'] . ' ' . $args['start_time'] . ':00';
		$end_date = $args['date'] . ' ' . $args['end_time'] . ':00';

		// define schedule
		$event_data = array(

			// start date
			'start' => new DateTime( $start_date, eo_get_blog_timezone() ),

			// end date and end of schedule are the same
			'end' => new DateTime( $end_date, eo_get_blog_timezone() ),
			'schedule_last' => new DateTime( $end_date, eo_get_blog_timezone() ),

			// not repeating
			'frequency' => 1,
			'schedule' => 'once',

			// not "all day"
			'all_day' => 0,

		);

		// init post data
		$post_data = array();

		// init title
		$title = __( 'Meetings', 'wordpress-meetings' );

		// try and match by term slug to see if a term exists
		$term = get_term_by( 'slug', sanitize_title( $title ), 'event-category' );

		// if we have it
		if ( $term !== false ) {

			// define term as array
			$terms = array( absint( $term->term_id ) );

			// add to post data
			$post_data['tax_input'] = array(
				'event-category' => $terms,
			);

		}

		// update the event
		$event_id = eo_update_event( $args['event_id'], $post_data, $event_data );

	}



	/**
	 * Modify Event Archive Meta Content.
	 *
	 * @since 1.1.0
	 *
	 * @return string $content
	 */
	public function event_meta() {

		global $post;

		// init properties
		$this->meeting_type = '';
		$this->meeting_link = '';

		$post_id = get_the_ID();
		$post_type = get_post_type( $post_id );

		// get connected meetings
		$connected_meetings = get_posts( array(
			'post_status' => 'any',
			'connected_type' => 'meeting_to_event',
			'connected_items' => $post,
			'nopaging' => true,
			'suppress_filters' => false,
		) );

		// loop, though there's only one
		foreach( $connected_meetings as $meeting ) {

			// get meeting type
			$this->meeting_type = get_the_term_list(
				$meeting->ID, 'meeting_type', '<span class="meeting-type tag">', ', ', '</span>'
			);

			// construct link to meeting
			$this->meeting_link = '<a href="' . get_permalink( $meeting->ID ) . '">' .
				get_the_title( $meeting->ID ) .
			'</a>';

		}

		// use event meta template
		$file = 'wordpress-meetings/content-event-meta.php';
		$content = wp_meetings_template_buffer( $file );

		// print to screen
		echo $content;

	}



} // class ends



/**
 * Does the current Event have a Meeting type.
 *
 * @since 2.0.2
 *
 * @return bool $has_type True if the Event has a Meeting type, false otherwise.
 */
function wp_meetings_event_has_meeting_type() {

	// assume not
	$has_type = false;

	if ( ! empty( wordpress_meetings()->cpts['event']->meeting_type ) ) {
		$has_type = true;
	}

	// --<
	return $has_type;

}



/**
 * Echo the current Event's Meeting type markup.
 *
 * @since 2.0.2
 */
function wp_meetings_event_meeting_type() {
	echo wp_meetings_event_get_meeting_type();
}

/**
 * Retrieve the current Event's Meeting type markup.
 *
 * @since 2.0.2
 *
 * @return str $markup The current Event's Meeting type markup.
 */
function wp_meetings_event_get_meeting_type() {

	// assume none
	$markup = '';

	if ( ! empty( wordpress_meetings()->cpts['event']->meeting_type ) ) {
		$markup = wordpress_meetings()->cpts['event']->meeting_type;
	}

	// --<
	return $markup;

}



/**
 * Does the current Event have a Meeting link.
 *
 * @since 2.0.2
 *
 * @return bool $has_link True if the Event has a Meeting link, false otherwise.
 */
function wp_meetings_event_has_meeting_link() {

	// assume not
	$has_link = false;

	if ( ! empty( wordpress_meetings()->cpts['event']->meeting_link ) ) {
		$has_link = true;
	}

	// --<
	return $has_link;

}



/**
 * Echo the current Event's Meeting link markup.
 *
 * @since 2.0.2
 */
function wp_meetings_event_meeting_link() {
	echo wp_meetings_event_get_meeting_link();
}

/**
 * Retrieve the current Event's Meeting link markup.
 *
 * @since 2.0.2
 *
 * @return str $markup The current Event's Meeting link markup.
 */
function wp_meetings_event_get_meeting_link() {

	// assume none
	$markup = '';

	if ( ! empty( wordpress_meetings()->cpts['event']->meeting_link ) ) {
		$markup = wordpress_meetings()->cpts['event']->meeting_link;
	}

	// --<
	return $markup;

}



