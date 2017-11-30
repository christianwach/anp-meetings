<?php

/**
 * WordPress Meetings Admin Class.
 *
 * A class that encapsulates admin functionality.
 *
 * @since 2.0
 */
 class WordPress_Meetings_Admin {

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
	 * Settings page.
	 *
	 * @since 2.0
	 * @access public
	 * @var str $settings_page The Settings page reference.
	 */
	public $settings_page;

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

		// perform any upgrade tasks
		$this->upgrade_tasks();

	}



	/**
	 * Perform activation tasks.
	 *
	 * @since 2.0
	 */
	public function activate() {

		// store plugin version
		$this->version_set();

		// add settings option
		$this->settings_init();

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

		// save settings
		$this->settings_save();

		// store new version
		$this->version_set();

	}



	/**
	 * Register hooks on plugin init.
	 *
	 * @since 2.0
	 */
	public function register_hooks() {

		// add menu item
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

	}



	//##########################################################################



	/**
	 * Add this plugin's Settings Page to the WordPress admin menu.
	 *
	 * @since 2.0
	 */
	public function admin_menu() {

		// check user permissions
		if ( ! current_user_can('manage_options') ) return false;

		// add the Settings page to the WordPress Settings menu
		$this->settings_page = add_options_page(
			__( 'WordPress Meetings: Settings', 'wordpress-meetings' ), // page title
			__( 'Meetings', 'wordpress-meetings' ), // menu title
			'manage_options', // required caps
			'wordpress_meetings_settings', // slug name
			array( $this, 'page_settings' ) // callback
		);

		// maybe save settings on page load
		add_action( 'load-' . $this->settings_page, array( $this, 'settings_parse' ) );

		// add help text to UI
		add_action( 'admin_head-' . $this->settings_page, array( $this, 'admin_head' ) );

		/*
		// add scripts and styles
		add_action( 'admin_print_scripts-' . $this->settings_page, array( $this, 'admin_js' ) );
		add_action( 'admin_print_styles-' . $this->settings_page, array( $this, 'admin_css' ) );
		*/

	}



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
		if ( $screen->id != $this->settings_page ) {
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
		return get_option( 'wordpress_meetings_version', WORDPRESS_MEETINGS_VERSION );

	}



	//##########################################################################



	/**
	 * Show General Settings page.
	 *
	 * @since 2.0
	 */
	public function page_settings() {

		// check user permissions
		if ( ! current_user_can( 'manage_options' ) ) return;

		// get admin page URL
		$url = $this->page_get_url();

		// init checkbox
		$include_css = '';
		if ( $this->setting_get( 'include_css', 'y' ) == 'y' ) $include_css = ' checked="checked"';

		// include template file
		include( WORDPRESS_MEETINGS_PATH . 'assets/templates/admin/settings.php' );

	}



	/**
	 * Get admin page URL.
	 *
	 * @since 2.0
	 *
	 * @return array $admin_url The admin page URL.
	 */
	public function page_get_url() {

		// only calculate once
		if ( isset( $this->url ) ) {
			return $this->url;
		}

		// construct admin page URL
		$this->url = menu_page_url( 'wordpress_meetings_settings', false );

		// --<
		return $this->url;

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
	 * Maybe save general settings.
	 *
	 * This is the callback from 'load-' . $this->settings_page which determines
	 * if there are settings to be saved and parses them before calling the
	 * actual save method.
	 *
	 * @since 2.0
	 */
	public function settings_parse() {

		// bail if no post data
		if ( empty( $_POST ) ) return;

		// check that we trust the source of the request
		check_admin_referer( 'wordpress_meetings_settings_action', 'wordpress_meetings_settings_nonce' );

		// check that our sumbit button was clicked
		if ( ! isset( $_POST['wordpress_meetings_settings_submit'] ) ) return;

		// okay, now update
		$this->settings_update();

	}



	/**
	 * Update Settings.
	 *
	 * @since 2.0
	 */
	public function settings_update() {

		// include CSS
		$include_css = $this->setting_get( 'include_css', 'y' );
		if ( isset( $_POST['wordpress_meetings_include_css'] ) ) {
			$include_css = 'y';
		} else {
			$include_css = 'n';
		}
		$this->setting_set( 'include_css', $include_css );

		// save settings
		$this->settings_save();

		// construct Settings page URL
		$url = $this->page_get_url();
		$redirect = add_query_arg( 'updated', 'true', $url );

		// prevent reload weirdness
		wp_redirect( $redirect );

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
	 * @return mixed $default The default value of the setting.
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
	 */
	public function setting_set( $setting_name = '', $value = '' ) {

		// set setting
		$this->settings[$setting_name] = $value;

	}



	/**
	 * Unset a specified setting.
	 *
	 * @since 2.0
	 */
	public function setting_unset( $setting_name = '' ) {

		// delete setting
		unset( $this->settings[$setting_name] );

	}



} // class ends



