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

		// bail if not our CPT archive
		if ( ! is_post_type_archive( $this->post_type_name ) ) {
			return $template_path;
		}

		// use template
		$file = 'wordpress-meetings/archive.php';
		$template_path = wordpress_meetings_template_get( $file );

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

		// bail if not one of our CPT pages
		if ( ! is_singular( $this->post_type_name ) AND ! is_post_type_archive( $this->post_type_name ) ) {
			return $content;
		}

		// archive template
		if ( is_post_type_archive( $this->post_type_name ) ) {
			$file = 'wordpress-meetings/content-archive.php';
			$content = wordpress_meetings_template_buffer( $file );
		}

		// singular template
		if ( is_singular( $this->post_type_name ) ) {

			global $post;

			// header template
			$file = 'wordpress-meetings/content-single.php';
			$header = wordpress_meetings_template_buffer( $file );

			$body = wpautop( $post->post_content );

			// footer template
			$file = 'wordpress-meetings/single-footer.php';
			$footer = wordpress_meetings_template_buffer( $file );

			$content = $header . $body . $footer;

		}

		// --<
		return $content;

	}



} // class ends



