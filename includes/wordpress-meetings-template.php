<?php

/**
 * WordPress Meetings Template Class.
 *
 * A class that encapsulates templating functionality for WordPress Meetings.
 *
 * @package WordPress_Meetings
 */
class WordPress_Meetings_Template {

	/**
	 * Plugin (calling) object.
	 *
	 * @since 2.0
	 * @access public
	 * @var object $plugin The plugin object.
	 */
	public $plugin;

	/**
	 * Relative path to front-end templates base directory.
	 *
	 * @since 2.0
	 * @access public
	 * @var str $templates_public_dir The relative path to front-end templates directory.
	 */
	public $templates_public_dir = 'assets/templates/theme';

	/**
	 * Relative path to admin templates base directory.
	 *
	 * @since 2.0
	 * @access public
	 * @var str $templates_admin_dir The relative path to admin templates directory.
	 */
	public $templates_admin_dir = 'assets/templates/admin';



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



	// #########################################################################



	/**
	 * Find a template given a relative path.
	 *
	 * Example: 'wordpress-meetings/content-single.php'
	 *
	 * @since 2.0
	 *
	 * @param str $template_path The relative path to the template.
	 * @return str|bool $full_path The absolute path to the template, or false on failure.
	 */
	public function find_file( $template_path ) {

		// get stack
		$stack = $this->template_stack();

		// constuct templates array
		$templates = array();
		foreach( $stack As $location ) {
			$templates[] = trailingslashit( $location ) . $template_path;
		}

		// let's look for it
		$full_path = false;
		foreach ( $templates AS $template ) {
			if ( file_exists( $template ) ) {
				$full_path = $template;
				break;
			}
		}

		// --<
		return $full_path;

	}



	/**
	 * Construct template stack.
	 *
	 * @since 2.0
	 *
	 * @return array $stack The stack of locations to look for a template in.
	 */
	public function template_stack() {

		// define paths
		$template_dir = get_stylesheet_directory();
		$parent_template_dir = get_template_directory();
		$plugin_template_directory = WORDPRESS_MEETINGS_PATH . $this->templates_public_dir;

		// construct stack
		$stack = array( $template_dir, $parent_template_dir, $plugin_template_directory );

		/**
		 * Allow stack to be filtered.
		 *
		 * @since 2.0
		 *
		 * @param array $stack The default template stack.
		 * @return array $stack The filtered template stack.
		 */
		$stack = apply_filters( 'wordpress_meetings_template_stack', $stack );

		// sanity check
		$stack = array_unique( $stack );

		// --<
		return $stack;

	}



} // class ends



/**
 * Get a template file.
 *
 * @since 2.0
 *
 * @param str $file The relative path to the template file.
 * @param str $template The absolute path to the template file.
 */
function wp_meetings_template_get( $file ) {

	// get template
	$template = wordpress_meetings()->template->find_file( $file );

	// --<
	return $template;

}



/**
 * Buffer a template file.
 *
 * @since 2.0
 *
 * @param str $file The relative path to the template file.
 * @param str $template The absolute path to the template file.
 */
function wp_meetings_template_buffer( $file ) {

	// get template part
	$template = wp_meetings_template_get( $file );

	// buffer the template part
	ob_start();
	include( $template );
	$content = ob_get_contents();
	ob_end_clean();

	// --<
	return $content;

}



