<?php

/**
 * WordPress Meetings Custom Post Type Class.
 *
 * A class that holds common Custom Post Type characteristics for WordPress Meetings.
 *
 * @package WordPress_Meetings
 */
class WordPress_Meetings_CPT_Common {

	/**
	 * Plugin (calling) object.
	 *
	 * @since 2.0
	 * @access public
	 * @var object $plugin The plugin object.
	 */
	public $plugin;

	/**
	 * Custom Post Type name.
	 *
	 * @since 2.0
	 * @access public
	 * @var str $post_type_name The name of the Custom Post Type.
	 */
	public $post_type_name = '';



	/**
	 * Constructor.
	 *
	 * @since 2.0
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
	 * @since 2.0
	 */
	public function register_hooks() {

		// always register post type
		add_action( 'init', array( $this, 'post_type_create' ) );

		// make sure our feedback is appropriate
		add_filter( 'post_updated_messages', array( $this, 'post_type_messages' ) );

		// maybe add stylesheet
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

	}



	/**
	 * Actions to perform on plugin activation.
	 *
	 * @since 2.0
	 */
	public function activate() {

		// pass through
		$this->post_type_create();

		// go ahead and flush
		flush_rewrite_rules();

	}



	/**
	 * Actions to perform on plugin deactivation (NOT deletion).
	 *
	 * @since 2.0
	 */
	public function deactivate() {

		// flush rules to reset
		flush_rewrite_rules();

	}



	// #########################################################################



	/**
	 * Create our Custom Post Type.
	 *
	 * @since 2.0
	 */
	public function post_type_create() {}



	/**
	 * Override messages for a custom post type.
	 *
	 * @since 2.0
	 *
	 * @param array $messages The existing messages.
	 * @return array $messages The modified messages.
	 */
	public function post_type_messages( $messages ) {

		// --<
		return $messages;

	}



	/**
	 * Map common capabilities.
	 *
	 * @since 2.0
	 *
	 * @return array $capabilities The common capabilities.
	 */
	public function capabilities() {

		// set default mappings
		$capabilities = array(
			'publish_posts'         => 'publish_meetings',
			'edit_posts'            => 'edit_meetings',
			'edit_others_posts'     => 'edit_others_meetings',
			'delete_posts'          => 'delete_meetings',
			'delete_others_posts'   => 'delete_others_meetings',
			'read_private_posts'    => 'read_private_meetings',
			'edit_post'             => 'edit_meeting',
			'delete_post'           => 'delete_meeting',
			'read_post'             => 'read_meeting',
		);

		/**
		 * Allow filtering of capabilities.
		 *
		 * @since 2.0
		 *
		 * @param array $capabilities The default caps.
		 * @return array $capabilities The modified caps.
		 */
		return apply_filters( 'wordpress_meetings_global_capabilities', $capabilities );

	}



	/**
	 * Enqueue styles.
	 *
	 * @since 2.0
	 */
    public function enqueue_styles() {

		// bail if not one of our CPT pages
		if ( ! is_singular( $this->post_type_name ) AND ! is_post_type_archive( $this->post_type_name ) ) {
			return;
		}

		// use common function
		wordpress_meetings_enqueue_styles();

	}



} // class ends



