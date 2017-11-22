<?php

/**
 * WordPress Meetings Custom Taxonomy Base Class.
 *
 * A class that holds common Custom Taxonomy characteristics for WordPress Meetings.
 *
 * @package WordPress_Meetings
 */
class WordPress_Meetings_Taxonomy_Base {

	/**
	 * Plugin (calling) object.
	 *
	 * @since 2.0
	 * @access public
	 * @var object $plugin The plugin object.
	 */
	public $plugin;

	/**
	 * Taxonomy name.
	 *
	 * @since 2.0
	 * @access public
	 * @var str $taxonomy_name The name of the Custom Taxonomy.
	 */
	public $taxonomy_name = '';

	/**
	 * Custom Post Types.
	 *
	 * @since 2.0
	 * @access public
	 * @var array $post_types The Post Types to which this Taxonomy applies.
	 */
	public $post_types = array();



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

		// create taxonomy
		add_action( 'init', array( $this, 'taxonomy_create' ) );

	}



	/**
	 * Actions to perform on plugin activation.
	 *
	 * @since 2.0
	 */
	public function activate() {

		// pass through
		$this->taxonomy_create();

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
	 * Create our Custom Taxonomy.
	 *
	 * @since 2.0
	 */
	public function taxonomy_create() {}



} // class ends



