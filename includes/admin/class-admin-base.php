<?php

/**
 * WordPress Meetings Admin Base Class.
 *
 * A class that holds common Custom Post Type characteristics for WordPress Meetings.
 *
 * @since 2.0
 *
 * @package WordPress_Meetings
 */
 class WordPress_Meetings_Admin_Base {

	/**
	 * Plugin (calling) object.
	 *
	 * @since 2.0
	 * @access public
	 * @var object $plugin The plugin object.
	 */
	public $plugin;

	/**
	 * Plugin version.
	 *
	 * @since 2.0
	 * @access public
	 * @var str $plugin_version The plugin version.
	 */
	public $plugin_version;

	/**
	 * Admin page.
	 *
	 * @since 2.0
	 * @access public
	 * @var str $admin_page The Admin page reference.
	 */
	public $admin_page;

	/**
	 * Settings data.
	 *
	 * @since 2.0
	 * @access public
	 * @var array $settings The plugin settings data.
	 */
	public $settings = array();



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

		// load settings
		$this->settings = $this->settings_get();

		// load plugin version
		$this->plugin_version = $this->version_get();

	}



	/**
	 * Register hooks on plugin init.
	 *
	 * @since 2.0
	 */
	public function register_hooks() {

		// do upgrade tasks when plugin is loaded
		add_action( 'wordpress_meetings_initialised', array( $this, 'upgrade_tasks' ) );

		// add menu item
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

	}



	/**
	 * Perform upgrade tasks.
	 *
	 * @since 2.0
	 */
	public function upgrade_tasks() {

		// bail if no upgrade is needed
		if ( version_compare( $this->plugin_version, WORDPRESS_MEETINGS_VERSION, '>=' ) ) {
			return;
		}

		/**
		 * Broadcast plugin upgrade.
		 *
		 * @since 2.0
		 *
		 * @param str $plugin_version The previous plugin version.
		 * @param str WORDPRESS_MEETINGS_VERSION The current plugin version.
		 */
		do_action( 'wordpress_meetings_upgrade', $this->plugin_version, WORDPRESS_MEETINGS_VERSION );

		/*
		// flush rules late
		add_action( 'init', 'flush_rewrite_rules', 100 );

		// if the current version is less than x.x.x and we're upgrading to x.x.x+
		if (
			version_compare( $this->plugin_version, '2.0', '<' ) AND
			version_compare( WORDPRESS_MEETINGS_VERSION, '2.0', '>=' )
		) {

			// do something

		}
		*/

		// if the current version is less than 2.0.1 and we're upgrading to 2.0.2+
		if (
			version_compare( $this->plugin_version, '2.0.1', '<' ) AND
			version_compare( WORDPRESS_MEETINGS_VERSION, '2.0.1', '>=' )
		) {

			// add term
			add_action( 'init', array( $this, 'term_create' ) );

		}

		// save settings
		$this->settings_save();

		// store new version
		$this->version_set();

	}



	//##########################################################################



	/**
	 * Add this plugin's Admin Page to the WordPress admin menu.
	 *
	 * This must be overloaded in the child class.
	 *
	 * @since 2.0
	 */
	public function admin_menu() {}



	/**
	 * Initialise plugin help.
	 *
	 * @since 2.0
	 */
	public function admin_head() {

		// get screen object
		$screen = get_current_screen();

		// pass to help method
		$this->admin_help( $screen );

	}



	/**
	 * Adds help copy to our admin page.
	 *
	 * @since 2.0
	 *
	 * @param object $screen The existing WordPress screen object.
	 * @return object $screen The amended WordPress screen object.
	 */
	public function admin_help( $screen ) {

		// kick out if not our screen
		if ( $screen->id != $this->admin_page ) {
			return $screen;
		}

		// add a help tab
		$screen->add_help_tab( array(
			'id' => 'wordpress_meetings_help',
			'title' => __( 'WordPress Meetings', 'wordpress-meetings' ),
			'content' => $this->admin_help_text(),
		));

		// --<
		return $screen;

	}



	/**
	 * Get HTML-formatted help text for the admin screen.
	 *
	 * @since 2.0
	 *
	 * @return string $help The help text formatted as HTML.
	 */
	public function admin_help_text() {

		// stub help text, to be developed further
		$help = '<p>' . __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vel iaculis leo. Fusce eget erat vitae justo vestibulum tincidunt efficitur id nunc. Vivamus id quam tempus, aliquam tortor nec, volutpat nisl. Ut venenatis aliquam enim, a placerat libero vehicula quis. Etiam neque risus, vestibulum facilisis erat a, tincidunt vestibulum nulla. Sed ultrices ante nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent maximus purus ac lacinia vulputate. Aenean ex quam, aliquet id feugiat et, cursus vel magna. Cras id congue ipsum, vel consequat libero.', 'wordpress-meetings' ) . '</p>';

		// --<
		return $help;

	}



	//##########################################################################



	/**
	 * Store the plugin version.
	 *
	 * @since 2.0
	 */
	public function version_set() {

		// store version
		update_option( 'wordpress_meetings_version', WORDPRESS_MEETINGS_VERSION );

	}



	/**
	 * Get the current plugin version.
	 *
	 * @since 2.0
	 */
	public function version_get() {

		// retrieve version
		return get_option( 'wordpress_meetings_version', '' );

	}



	//##########################################################################



	/**
	 * Initialise plugin settings.
	 *
	 * @since 2.0
	 */
	public function settings_init() {

		// add settings option if it does not exist
		if ( 'fgffgs' == get_option( 'wordpress_meetings_settings', 'fgffgs' ) ) {
			add_option( 'wordpress_meetings_settings', $this->settings_get_default() );
		}

	}



	/**
	 * Get current plugin settings.
	 *
	 * @since 2.0
	 *
	 * @return array $settings The array of settings, keyed by setting name.
	 */
	public function settings_get() {

		// get settings option
		return get_option( 'wordpress_meetings_settings', $this->settings_get_default() );

	}



	/**
	 * Store plugin settings.
	 *
	 * @since 2.0
	 *
	 * @param array $settings The array of settings, keyed by setting name.
	 */
	public function settings_set( $settings ) {

		// update settings option
		update_option( 'wordpress_meetings_settings', $settings );

	}



	/**
	 * Save plugin settings.
	 *
	 * @since 2.0
	 */
	public function settings_save() {

		// sanity check
		if ( empty( $this->settings ) ) return;

		// save current state of settings array
		$this->settings_set( $this->settings );

	}



	/**
	 * Get default plugin settings.
	 *
	 * @since 2.0
	 *
	 * @return array $settings The array of settings, keyed by setting name.
	 */
	public function settings_get_default() {

		// init return
		$settings = array();

		// include CSS by default
		$settings['include_css'] = 'y';

		/**
		 * Allow defaults to be filtered.
		 *
		 * @since 2.0
		 *
		 * @param array $settings The default settings array.
		 * @return array $settings The modified settings array.
		 */
		return apply_filters( 'wordpress_meetings_default_settings', $settings );

	}



	/**
	 * Return a value for a specified setting.
	 *
	 * @since 2.0
	 *
	 * @param str $setting_name The name of the setting.
	 * @param mixed $default The default value of the setting.
	 * @return mixed $setting The actual value of the setting.
	 */
	public function setting_get( $setting_name = '', $default = false ) {

		// get setting
		return ( array_key_exists( $setting_name, $this->settings ) ) ? $this->settings[$setting_name] : $default;

	}



	/**
	 * Set a value for a specified setting.
	 *
	 * @since 2.0
	 *
	 * @param str $setting_name The name of the setting.
	 * @param mixed $value The value of the setting.
	 */
	public function setting_set( $setting_name = '', $value = '' ) {

		// set setting
		$this->settings[$setting_name] = $value;

	}



	/**
	 * Unset a specified setting.
	 *
	 * @since 2.0
	 *
	 * @param str $setting_name The name of the setting.
	 */
	public function setting_unset( $setting_name = '' ) {

		// delete setting
		unset( $this->settings[$setting_name] );

	}



	//##########################################################################



	/**
	 * Get WordPress plugin reference by name.
	 *
	 * This is required because we never know for sure what the enclosing directory
	 * is called.
	 *
	 * @since 2.0.1
	 *
	 * @param str $plugin_name The name of the plugin.
	 * @return str $path_to_plugin The path to the plugin or false on failure.
	 */
	public function find_plugin_by_name( $plugin_name = '' ) {

		// kick out if no param supplied
		if ( $plugin_name == '' ) return false;

		// init path
		$path_to_plugin = false;

		// ensure function is available
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// get plugins
		$plugins = get_plugins();

		// because the key is the path to the plugin file, we have to find the
		// key by iterating over the values (which are arrays) to find the
		// plugin with the name we want. Doh!
		foreach( $plugins AS $key => $plugin ) {

			// is it ours?
			if ( $plugin['Name'] == $plugin_name ) {

				// now get the key, which is our path
				$path_to_plugin = $key;
				break;

			}

		}

		// --<
		return $path_to_plugin;

	}



	/**
	 * Create an EO event-category term.
	 *
	 * @since 2.0.1
	 */
	public function term_create() {

		// bail if Event Organiser plugin is not present
		if ( ! defined( 'EVENT_ORGANISER_VER' ) ) return;

		// init title
		$title = __( 'Meetings', 'wordpress-meetings' );

		// try and match by term slug to see if a term exists
		$term = get_term_by( 'slug', sanitize_title( $title ), 'event-category' );

		// bail if we already have one
		if ( $term !== false ) return;

		// construct args
		$args = array(
			'slug' => sanitize_title( $title ),
			'description'=> __( 'A category for Events associated with Meetings.', 'wordpress-meetings' ),
		);

		// insert term
		$result = wp_insert_term( $title, 'event-category', $args );

		// if all goes well, we get: array( 'term_id' => 12, 'term_taxonomy_id' => 34 )
		// if something goes wrong, we get a WP_Error object
		if ( is_wp_error( $result ) ) {

			$e = new Exception;
			$trace = $e->getTraceAsString();
			error_log( print_r( array(
				'method' => __METHOD__,
				'message' => __( 'Could not create term.', 'wordpress-meetings' ),
				'result' => $result,
				'backtrace' => $trace,
			), true ) );

		}

	}



} // class ends



