<?php

/**
 * WordPress Meetings Admin Migrate Class.
 *
 * A class that encapsulates admin migration functionality.
 *
 * @since 2.0
 */
 class WordPress_Meetings_Admin_Migrate extends WordPress_Meetings_Admin_Base {



	/**
	 * Constructor.
	 *
	 * @since 2.0
	 *
	 * @param object $parent The parent object.
	 */
	public function __construct( $parent ) {

		// store plugin reference
		parent::__construct( $parent );

	}



	//##########################################################################



	/**
	 * Add this plugin's Admin Page to the WordPress admin menu.
	 *
	 * @since 2.0
	 */
	public function admin_menu() {

		// check user permissions
		if ( ! current_user_can('manage_options') ) return false;

		// add the Admin page to the WordPress Settings menu
		$this->admin_page = add_options_page(
			__( 'WordPress Meetings: Migrate', 'wordpress-meetings' ), // page title
			__( 'Meetings', 'wordpress-meetings' ), // menu title
			'manage_options', // required caps
			'wordpress_meetings_migrate', // slug name
			array( $this, 'page_migrate' ) // callback
		);

		// maybe save settings on page load
		add_action( 'load-' . $this->admin_page, array( $this, 'settings_parse' ) );

		// add help text to UI
		add_action( 'admin_head-' . $this->admin_page, array( $this, 'admin_head' ) );

		/*
		// add scripts and styles
		add_action( 'admin_print_scripts-' . $this->admin_page, array( $this, 'admin_js' ) );
		add_action( 'admin_print_styles-' . $this->admin_page, array( $this, 'admin_css' ) );
		*/

	}



	//##########################################################################



	/**
	 * Show Admin page.
	 *
	 * @since 2.0
	 */
	public function page_migrate() {

		// check user permissions
		if ( ! current_user_can( 'manage_options' ) ) return;

		// get admin page URL
		$url = $this->page_get_url();

		// init checkbox
		$include_css = '';
		if ( $this->setting_get( 'include_css', 'y' ) == 'y' ) $include_css = ' checked="checked"';

		// include template file
		include( WORDPRESS_MEETINGS_PATH . 'assets/templates/admin/migrate.php' );

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
		$this->url = menu_page_url( 'wordpress_meetings_migrate', false );

		// --<
		return $this->url;

	}



	//##########################################################################



	/**
	 * Maybe save data.
	 *
	 * This is the callback from 'load-' . $this->admin_page which determines
	 * if there is data to be saved and parses it before calling the actual
	 * save method.
	 *
	 * @since 2.0
	 */
	public function settings_parse() {

		// bail if no post data
		if ( empty( $_POST ) ) return;

		// check that we trust the source of the request
		check_admin_referer( 'wordpress_meetings_migrate_action', 'wordpress_meetings_migrate_nonce' );

		// check that our sumbit button was clicked
		if ( ! isset( $_POST['wordpress_meetings_migrate_submit'] ) ) return;

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

		// construct Migrate page URL
		$url = $this->page_get_url();
		$redirect = add_query_arg( 'updated', 'true', $url );

		// prevent reload weirdness
		wp_redirect( $redirect );

	}



} // class ends



