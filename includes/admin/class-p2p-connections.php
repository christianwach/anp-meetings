<?php

/**
 * WordPress Meetings Connections Class.
 *
 * A class that holds P2P connection definitions for WordPress Meetings.
 *
 * @since 2.0.2
 *
 * @package WordPress_Meetings
 */
 class WordPress_Meetings_Connections {

	/**
	 * Plugin (calling) object.
	 *
	 * @since 2.0.2
	 * @access public
	 * @var object $plugin The plugin object.
	 */
	public $plugin;



	/**
	 * Constructor.
	 *
	 * @since 2.0.2
	 *
	 * @param object $parent The parent object.
	 */
	public function __construct( $parent ) {

		// store
		$this->plugin = $parent;

	}



	/**
	 * Register hooks.
	 *
	 * @since 2.0.2
	 */
	public function register_hooks() {

		// add connection types
		add_action( 'p2p_init', array( $this, 'add_connection_types' ) );

		// define metabox order
		add_filter( 'p2p_connectable_args', array( $this, 'box_order' ), 10, 3 );

	}



	/**
	 * Adds connection types.
	 *
	 * @since 2.0.2
	 */
	public function add_connection_types() {

		// connect Meetings and Agendas
		p2p_register_connection_type( array(
			'name' => 'meeting_to_agenda',
			'from' => 'meeting',
			'to' => 'agenda',
			'reciprocal' => true,
			'cardinality' => 'one-to-one',
			'admin_column' => true,
			'admin_dropdown' => 'to',
			'title' => array(
				'from' => __( 'Agenda', 'wordpress-meetings' ),
				'to' => __( 'Meeting', 'wordpress-meetings' )
			),
			'from_labels' => array(
				'singular_name' => __( 'Meeting', 'wordpress-meetings' ),
				'search_items' => __( 'Search meetings', 'wordpress-meetings' ),
				'not_found' => __( 'No meetings found.', 'wordpress-meetings' ),
				'create' => __( 'Add meeting', 'wordpress-meetings' ),
			),
			'to_labels' => array(
				'singular_name' => __( 'Agenda', 'wordpress-meetings' ),
				'search_items' => __( 'Search agendas', 'wordpress-meetings' ),
				'not_found' => __( 'No agendas found.', 'wordpress-meetings' ),
				'create' => __( 'Add agenda', 'wordpress-meetings' ),
			),
		) );

		// connect Meetings and Summaries
		p2p_register_connection_type( array(
			'name' => 'meeting_to_summary',
			'from' => 'meeting',
			'to' => 'summary',
			'reciprocal' => true,
			'cardinality' => 'one-to-one',
			'admin_column' => true,
			'admin_dropdown' => 'to',
			'title' => array(
				'from' => __( 'Summary', 'wordpress-meetings' ),
				'to' => __( 'Meeting', 'wordpress-meetings' )
			),
			'from_labels' => array(
				'singular_name' => __( 'Meeting', 'wordpress-meetings' ),
				'search_items' => __( 'Search meetings', 'wordpress-meetings' ),
				'not_found' => __( 'No meetings found.', 'wordpress-meetings' ),
				'create' => __( 'Add meeting', 'wordpress-meetings' ),
			),
			'to_labels' => array(
				'singular_name' => __( 'Summary', 'wordpress-meetings' ),
				'search_items' => __( 'Search summaries', 'wordpress-meetings' ),
				'not_found' => __( 'No summaries found.', 'wordpress-meetings' ),
				'create' => __( 'Add summary', 'wordpress-meetings' ),
			),
		) );

		// connect Meetings and Proposals
		p2p_register_connection_type( array(
			'name' => 'meeting_to_proposal',
			'from' => 'meeting',
			'to' => 'proposal',
			'reciprocal' => true,
			'cardinality' => 'one-to-many',
			'admin_column' => true,
			'admin_dropdown' => 'any',
			'sortable' => 'any',
			'title' => array(
				'from' => __( 'Proposals', 'wordpress-meetings' ),
				'to' => __( 'Meeting', 'wordpress-meetings' )
			),
			'from_labels' => array(
				'singular_name' => __( 'Meeting', 'wordpress-meetings' ),
				'search_items' => __( 'Search meetings', 'wordpress-meetings' ),
				'not_found' => __( 'No meetings found.', 'wordpress-meetings' ),
				'create' => __( 'Add meeting', 'wordpress-meetings' ),
			),
			'to_labels' => array(
				'singular_name' => __( 'Proposal', 'wordpress-meetings' ),
				'search_items' => __( 'Search proposals', 'wordpress-meetings' ),
				'not_found' => __( 'No proposals found.', 'wordpress-meetings' ),
				'create' => __( 'Add proposal', 'wordpress-meetings' ),
			),
		) );

		// bail if Event Organiser plugin is not present
		if ( ! defined( 'EVENT_ORGANISER_VER' ) ) return;

		// connect Meetings and Events
		p2p_register_connection_type( array(
			'name' => 'meeting_to_event',
			'from' => 'meeting',
			'to' => 'event',
			'reciprocal' => true,
			'cardinality' => 'one-to-one',
			'admin_column' => true,
			'admin_dropdown' => 'any',
			'sortable' => 'any',
			'title' => array(
				'from' => __( 'Event', 'wordpress-meetings' ),
				'to' => __( 'Meeting', 'wordpress-meetings' )
			),
			'from_labels' => array(
				'singular_name' => __( 'Meeting', 'wordpress-meetings' ),
				'search_items' => __( 'Search meetings', 'wordpress-meetings' ),
				'not_found' => __( 'No meetings found.', 'wordpress-meetings' ),
				'create' => __( 'Add meeting', 'wordpress-meetings' ),
			),
			'to_labels' => array(
				'singular_name' => __( 'Event', 'wordpress-meetings' ),
				'search_items' => __( 'Search events', 'wordpress-meetings' ),
				'not_found' => __( 'No events found.', 'wordpress-meetings' ),
				'create' => __( 'Add event', 'wordpress-meetings' ),
			),
		) );

	}



	/**
	 * Order posts alphabetically in the P2P connections box.
	 *
	 * @since 2.0.2
	 *
	 * @param array $args The params used to create the event.
	 * @param object $ctype The P2P connection type object.
	 * @param int $post_id The ID of the meeting.
	 */
	public function box_order( $args, $ctype, $post_id ) {

		// relevant types
		$types = array( 'meeting_to_agenda', 'meeting_to_summary', 'meeting_to_proposal' );

		// bail if not a connection type we're interested in
		if ( ! in_array( $ctype->name, $types ) ) return $args;

		// set order
		$args['orderby'] = 'title';
		$args['order'] = 'asc';

		// --<
		return $args;

	}



} // class ends



