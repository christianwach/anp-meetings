<?php /*
--------------------------------------------------------------------------------
Plugin Name: WordPress Meetings
Plugin URI: https://github.com/christianwach/wordpress-meetings
Description: Creates custom post types for Meetings with custom fields and custom taxonomies that can be used to store and display meeting notes/minutes, agendas, proposals and summaries.
Author: Pea, Glocal, needle
Author URI: http://glocal.coop
Version: 2.0
License: GPLv3
Text Domain: wordpress-meetings
Domain Path: /languages
--------------------------------------------------------------------------------
*/




// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}



// plugin version
define( 'WORDPRESS_MEETINGS_VERSION', '2.0' );

// path to plugin directory
if ( ! defined( 'WORDPRESS_MEETINGS_PLUGIN_DIR' ) ) {
    define( 'WORDPRESS_MEETINGS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// URL of plugin directory
if ( ! defined( 'WORDPRESS_MEETINGS_PLUGIN_URL' ) ) {
    define( 'WORDPRESS_MEETINGS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}



/**
 * WordPress Meetings Class.
 *
 * A class that encapsulates plugin functionality.
 *
 * @since 2.0
 */
class WordPress_Meetings {

	/**
	 * Constructor.
	 *
	 * @since 2.0
	 */
	public function __construct() {

		// translation
		add_action( 'plugins_loaded', array( $this, 'enable_translation' ) );

		// initialise
		add_action( 'plugins_loaded', array( $this, 'initialise' ) );

	}



	/**
	 * Initialise this plugin.
	 *
	 * @since 2.0
	 */
	public function initialise() {

		// include files
		$this->include_files();

		// set up objects and references
		$this->setup_objects();

		// register hooks
		$this->register_hooks();

	}



	/**
	 * Include files.
	 *
	 * @since 2.0
	 */
	public function include_files() {

		include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/required-plugins.php' );

		// NB: this will load the class if spl_autoload is defined :(
		if ( ! class_exists( 'Gamajo_Template_Loader' ) ) {
			include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'vendor/gamajo/template-loader/class-gamajo-template-loader.php' );
		}

		include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'admin/class-options.php' );

		include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-meeting.php' );
		include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-agenda.php' );
		include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-summary.php' );
		include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-proposal.php' );

		include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-fields.php' );

		include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/post-type-connections.php' );
		include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-content-filters.php' );
		include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-pre-get-filters.php' );

		include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/enqueue.php' );
		include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/render-functions.php' );
		include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-search-filters.php' );
		include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-rewrite.php' );

	}



	/**
	 * Set up this plugin's objects.
	 *
	 * @since 2.0
	 */
	public function setup_objects() {

	}



	/**
	 * Register hooks.
	 *
	 * @since 2.0
	 */
	public function register_hooks() {

		if ( function_exists( 'p2p_register_connection_type' ) ) {
			add_action( 'p2p_init', 'wordpress_meetings_connection_types' );
		}

	}



	/**
	 * Do stuff on plugin activation.
	 *
	 * @since 2.0
	 */
	public function activate() {

		// copied from old plugin
		wordpress_organization_taxonomy();
		wordpress_meetings_type();
		wordpress_meetings_tag();
		wordpress_proposals_status_taxonomy();
		wordpress_meetings_post_type();
		wordpress_meetings_agenda_post_type();
		wordpress_proposals_post_type();
		wordpress_summary_post_type();

		$this->add_capabilities();

		flush_rewrite_rules();

	}



	/**
	 * Do stuff on plugin deactivation.
	 *
	 * @since 2.0
	 */
	public function deactivate() {

		flush_rewrite_rules();

	}



	/**
	 * Load translation files.
	 *
	 * A good reference on how to implement translation in WordPress:
	 * http://ottopress.com/2012/internationalization-youre-probably-doing-it-wrong/
	 *
	 * @since 2.0
	 */
	public function enable_translation() {

		// enable translation
		load_plugin_textdomain(
			'wordpress-meetings', // unique name
			false, // deprecated argument
			dirname( plugin_basename( __FILE__ ) ) . '/languages/' // relative path to files
		);

	}



	/**
	 * Add Custom Capabilities.
	 *
	 * @since 2.0
	 *
	 * @uses get_role()
	 * @uses has_cap()
	 * @uses add_cap()
	 */
	public function add_capabilities() {

		global $wp_roles;
		$roles = $wp_roles->roles;

		$capabilities = array(
			'edit_meeting',
			'read_meeting',
			'delete_meeting',
			'edit_meetings',
			'edit_others_meetings',
			'publish_meetings',
			'read_private_meetings',
		);

		/**
		 * Allow filtering of custom capabilities.
		 *
		 * @since 1.0.9
		 *
		 * @param array $capabilities The default caps.
		 * @return array $capabilities The modified caps.
		 */
		$capabilities = apply_filters( 'wordpress_meetings_add_capabilities', $capabilities );

		foreach( $roles as $role_name => $display_name ) {
			$role = $wp_roles->get_role( $role_name );
			if ( $role->has_cap( 'publish_posts' ) ) {
				foreach( $capabilities as $capability ) {
					$role->add_cap( $capability );
				}
			}
		}

	}



} // class ends



// declare as global
global $wordpress_meetings_plugin;

// init plugin
$wordpress_meetings_plugin = new WordPress_Meetings;

// activation
register_activation_hook( __FILE__, array( $wordpress_meetings_plugin, 'activate' ) );

// deactivation
register_deactivation_hook( __FILE__, array( $wordpress_meetings_plugin, 'deactivate' ) );



/**
 * Utility to get a reference to this plugin.
 *
 * @since 2.0
 *
 * @return object $wordpress_meetings_plugin The plugin reference.
 */
function wordpress_meetings() {

	// return instance
	global $wordpress_meetings_plugin;
	return $wordpress_meetings_plugin;

}



/**
 * Plugin Capabilities.
 *
 * @since 1.0.9
 */
function wordpress_meetings_capabilities() {

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
	 * @since 1.0.9
	 *
	 * @param array $capabilities The default caps.
	 * @return array $capabilities The modified caps.
	 */
    return apply_filters( 'wordpress_meetings_global_capabilities', $capabilities );

}



