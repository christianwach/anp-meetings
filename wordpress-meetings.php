<?php /*
--------------------------------------------------------------------------------
Plugin Name: WordPress Meetings
Plugin URI: https://github.com/christianwach/wordpress-meetings
Description: Creates custom post types for Meetings with custom fields and custom taxonomies that can be used to store and display meeting notes/minutes, agendas, proposals and summaries.
Author: Pea, Glocal, needle
Author URI: http://glocal.coop
Version: 2.0.3
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
define( 'WORDPRESS_MEETINGS_VERSION', '2.0.3' );

// path to plugin directory
if ( ! defined( 'WORDPRESS_MEETINGS_PATH' ) ) {
	define( 'WORDPRESS_MEETINGS_PATH', plugin_dir_path( __FILE__ ) );
}

// URL of plugin directory
if ( ! defined( 'WORDPRESS_MEETINGS_URL' ) ) {
	define( 'WORDPRESS_MEETINGS_URL', plugin_dir_url( __FILE__ ) );
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
	 * Custom Post Types holder.
	 *
	 * @since 2.0
	 * @access public
	 * @var array $cpts The registered CPT objects.
	 */
	public $cpts;

	/**
	 * Custom Taxonomies holder.
	 *
	 * @since 2.0
	 * @access public
	 * @var array $taxs The registered Taxonomy objects.
	 */
	public $taxs;

	/**
	 * Template class.
	 *
	 * @since 2.0
	 * @access public
	 * @var object $template The Template object.
	 */
	public $template;

	/**
	 * Admin class.
	 *
	 * @since 2.0
	 * @access public
	 * @var object $admin The Admin object.
	 */
	public $admin;



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

		// only once please
		static $done;
		if ( $done === true ) return;

		// include files
		$this->include_files();

		// set up objects and references
		$this->setup_objects();

		// register hooks
		$this->register_hooks();

		/**
		 * Broadcast that this plugin is loaded.
		 *
		 * @since 2.0.1
		 */
		do_action( 'wordpress_meetings_initialised' );

		// set flag
		$done = true;

	}



	/**
	 * Include files.
	 *
	 * @since 2.0
	 */
	public function include_files() {

		// third-party plugin installer
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/admin/required-plugins.php' );

		// admin base
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/admin/class-admin-base.php' );

		// migrate settings if ANP Meetings is present
		if ( function_exists( 'anp_meetings_init' ) ) {
			include_once( WORDPRESS_MEETINGS_PATH . 'includes/admin/class-admin-migrate.php' );
			return;
		}

		// admin settings
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/admin/class-admin-settings.php' );

		// template class
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/wordpress-meetings-template.php' );

		// functions library
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/wordpress-meetings-functions.php' );

		// connections class
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/admin/class-p2p-connections.php' );

		// custom post types
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/cpts/class-cpt-base.php' );
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/cpts/class-cpt-meeting.php' );
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/cpts/class-cpt-agenda.php' );
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/cpts/class-cpt-summary.php' );
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/cpts/class-cpt-proposal.php' );
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/cpts/class-cpt-event.php' );

		// custom taxonomies
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/taxonomies/class-taxonomy-base.php' );
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/taxonomies/class-taxonomy-organization.php' );
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/taxonomies/class-taxonomy-meeting-tag.php' );
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/taxonomies/class-taxonomy-meeting-type.php' );
		include_once( WORDPRESS_MEETINGS_PATH . 'includes/taxonomies/class-taxonomy-proposal-status.php' );

	}



	/**
	 * Set up this plugin's objects.
	 *
	 * @since 2.0
	 */
	public function setup_objects() {

		// migrate settings if ANP Meetings is present
		if ( function_exists( 'anp_meetings_init' ) ) {
			$this->admin = new WordPress_Meetings_Admin_Migrate( $this );
			$this->admin->register_hooks();
			return;
		}

		// admin class
		$this->admin = new WordPress_Meetings_Admin_Settings( $this );
		$this->admin->register_hooks();

		// template class
		$this->template = new WordPress_Meetings_Template( $this );

		// connections class
		$this->connections = new WordPress_Meetings_Connections( $this );
		$this->connections->register_hooks();

		// custom post types
		$this->cpts = array();
		$this->cpts['meeting'] = new WordPress_Meetings_CPT_Meeting( $this );
		$this->cpts['agenda'] = new WordPress_Meetings_CPT_Agenda( $this );
		$this->cpts['summary'] = new WordPress_Meetings_CPT_Summary( $this );
		$this->cpts['proposal'] = new WordPress_Meetings_CPT_Proposal( $this );
		$this->cpts['event'] = new WordPress_Meetings_CPT_Event( $this );

		foreach( $this->cpts as $obj ) {
			$obj->register_hooks();
		}

		// custom taxonomies
		$this->taxs = array();
		$this->taxs['organization'] = new WordPress_Meetings_Taxonomy_Organization( $this );
		$this->taxs['meeting_tag'] = new WordPress_Meetings_Taxonomy_Meeting_Tag( $this );
		$this->taxs['meeting_type'] = new WordPress_Meetings_Taxonomy_Meeting_Type( $this );
		$this->taxs['proposal_status'] = new WordPress_Meetings_Taxonomy_Proposal_Status( $this );

		foreach( $this->taxs as $obj ) {
			$obj->register_hooks();
		}

	}



	/**
	 * Register hooks.
	 *
	 * @since 2.0
	 */
	public function register_hooks() {

		// bail if ANP Meetings is present
		if ( function_exists( 'anp_meetings_init' ) ) return;

		// map capabilities
		add_filter( 'map_meta_cap', array( $this, 'map_meta_cap' ), 10, 4 );

		// generate rewrite rules
		add_action( 'generate_rewrite_rules', array( $this, 'rewrite_rules' ) );

	}



	/**
	 * Do stuff on plugin activation.
	 *
	 * @since 2.0
	 */
	public function activate() {

		// bail if ANP Meetings is present
		if ( function_exists( 'anp_meetings_init' ) ) return;

		// add global capabilites
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



	/**
	 * Map Meetings capabilities.
	 *
	 * @since 2.0
	 *
	 * @param array $caps The existing capabilities array for the WordPress user.
	 * @param str $cap The capability in question.
	 * @param int $user_id The numerical ID of the WordPress user.
	 * @param array $args The additional arguments.
	 * @return array $caps The modified capabilities array for the WordPress user.
	 */
	public function map_meta_cap( $caps, $cap, $user_id, $args ) {

		// if editing, deleting, or reading a meeting, get the post and post type object
		if ( 'edit_meeting' == $cap OR 'delete_meeting' == $cap OR 'read_meeting' == $cap ) {
			$post = get_post( $args[0] );
			$post_type = get_post_type_object( $post->post_type );
			$caps = array();
		}

		// if editing a meeting, assign the required capability
		if ( 'edit_meeting' == $cap ) {
			if ( $user_id == $post->post_author ) {
				$caps[] = $post_type->cap->edit_posts;
			} else {
				$caps[] = $post_type->cap->edit_others_posts;
			}
		}

		// if deleting a meeting, assign the required capability
		elseif ( 'delete_meeting' == $cap ) {
			if ( $user_id == $post->post_author ) {
				$caps[] = $post_type->cap->delete_posts;
			} else {
				$caps[] = $post_type->cap->delete_others_posts;
			}
		}

		// if reading a meeting, assign the required capability
		elseif ( 'read_meeting' == $cap ) {
			if ( 'private' != $post->post_status ) {
				$caps[] = 'read';
			} elseif ( $user_id == $post->post_author ) {
				$caps[] = 'read';
			} else {
				$caps[] = $post_type->cap->read_private_posts;
			}
		}

		// --<
		return $caps;

	}



	/**
	 * Set up Custom Rewrite Rules.
	 *
	 * Creates rewrite rules for each meetings post type and custom taxonomy term.
	 *
	 * @since 1.0.9
	 *
	 * @param object $wp_rewrite
	 */
	public function rewrite_rules( $wp_rewrite ) {

		$rules = array();

		$post_types = get_post_types( array(
			'public'            => true,
			'_builtin'          => false,
			'capability_type'   => 'meeting'
		), 'objects' );

		$taxonomies = get_taxonomies( array(
			'public'            => true,
			'_builtin'          => false
		), 'objects' );

		foreach ( $post_types as $post_type ) {
			$post_type_name = $post_type->name;
			$post_type_slug = $post_type->rewrite['slug'];

			foreach ( $taxonomies as $taxonomy ) {

				if ( ! is_array( $taxonomy->object_type ) || empty( $taxonomy->object_type ) ) {
					return;
				}

				if ( ( count( $taxonomy->object_type ) > 1 && in_array( $post_type_name, $taxonomy->object_type ) ) || ( $taxonomy->object_type[0] == $post_type_name  ) ) {

					$terms = get_categories( array(
						'type'          => $post_type_name,
						'taxonomy'      => $taxonomy->name,
						'hide_empty'    => 0
					) );

					foreach ( $terms as $term ) {
						$rules[$post_type_slug . '/' . $term->slug . '/?$'] = 'index.php?post_type=' . $post_type_name . '&' . $term->taxonomy . '=' . $term->slug;
						$rules[$post_type_slug . '/' . $term->slug . '/page/?([0-9]{1,})/?$'] = 'index.php?post_type=' . $post_type_name . '&' . $term->taxonomy . '=' . $term->slug . '&paged=' . $wp_rewrite->preg_index( 1 );
					}
				}
			}
		}

		$wp_rewrite->rules = $rules + $wp_rewrite->rules;

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



