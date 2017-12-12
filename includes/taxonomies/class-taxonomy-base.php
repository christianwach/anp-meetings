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

		// flush rewrite rules on taxonomy change
		add_action( 'edited_' . $this->taxonomy_name, 'flush_rewrite_rules' );

		// maybe add stylesheet
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		// override archive template
		add_filter( 'template_include', array( $this, 'archive_template' ) );

		// override the content
		add_filter( 'the_content', array( $this, 'the_content' ) );

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



	/**
	 * Enqueue styles.
	 *
	 * @since 2.0
	 */
	public function enqueue_styles() {

		// bail when not required
		if ( ! is_tax( $this->taxonomy_name ) ) {
			return;
		}

		// use common function
		wp_meetings_enqueue_styles();

	}



	/**
	 * Archive Template override.
	 *
	 * Templates can be overridden by putting a template file of the same name
	 * in a folder called "wordpress-meetings" in your active theme.
	 *
	 * @since 2.0
	 *
	 * @param str $template_path The existing path to the template.
	 * @return str $template_path The modified path to the template.
	 */
	public function archive_template( $template_path ) {

		// bail when not required
		if ( ! is_tax( $this->taxonomy_name ) ) {
			return $template_path;
		}

		// use template
		$file = 'wordpress-meetings/archive.php';
		$template_path = wp_meetings_template_get( $file );

		// --<
		return $template_path;

	}



	/**
	 * Content override.
	 *
	 * @since 2.0
	 *
	 * @param str $content The existing content.
	 * @return str $content The modified content.
	 */
	public function the_content( $content ) {

		// only parse main content
		if ( is_admin() || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		// bail when not required
		if ( ! is_tax( $this->taxonomy_name ) ) {
			return $content;
		}

		// archive template
		$file = 'wordpress-meetings/content-archive.php';
		$content = wp_meetings_template_buffer( $file );

		// --<
		return $content;

	}



} // class ends



