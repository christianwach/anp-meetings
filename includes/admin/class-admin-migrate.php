<?php

/**
 * WordPress Meetings Admin Migrate Class.
 *
 * A class that encapsulates admin migration functionality.
 *
 * @since 2.0.1
 */
 class WordPress_Meetings_Admin_Migrate extends WordPress_Meetings_Admin_Base {



	/**
	 * Constructor.
	 *
	 * @since 2.0.1
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
	 * @since 2.0.1
	 */
	public function admin_menu() {

		// check user permissions
		if ( ! current_user_can('manage_options') ) return false;

		// add the Admin page to the WordPress Settings menu
		$this->admin_page = add_options_page(
			__( 'WordPress Meetings: Migrate', 'wordpress-meetings' ), // page title
			__( 'Meetings: Migrate', 'wordpress-meetings' ), // menu title
			'manage_options', // required caps
			'wordpress_meetings_settings', // slug name
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
	 * @since 2.0.1
	 */
	public function page_migrate() {

		// check user permissions
		if ( ! current_user_can( 'manage_options' ) ) return;

		// get admin page URL
		$url = $this->page_get_url();

		// include template file
		include( WORDPRESS_MEETINGS_PATH . 'assets/templates/admin/migrate.php' );

	}



	/**
	 * Get admin page URL.
	 *
	 * @since 2.0.1
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
	 * Maybe save data.
	 *
	 * This is the callback from 'load-' . $this->admin_page which determines
	 * if there is data to be saved and parses it before calling the actual
	 * save method.
	 *
	 * @since 2.0.1
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
	 * @since 2.0.1
	 */
	public function settings_update() {

		// add global capabilites
		$this->plugin->add_capabilities();

		// migrate global settings
		$this->settings_update_global();

		// create term
		$this->term_create();

		// migrate settings for CPTs
		$this->settings_update_cpts();

		// deactivate ANP Meetings
		$anp = $this->find_plugin_by_name( 'Activist Network Meetings' );
		if ( $anp AND is_plugin_active( $anp ) ) {
			deactivate_plugins( $anp );
		}

		// get admin page URL
		$url = $this->page_get_url();
		$redirect = add_query_arg( 'updated', 'true', $url );

		// redirect to Settings page
		wp_redirect( $redirect );

	}



	/**
	 * Update Global Settings.
	 *
	 * @since 2.0.1
	 */
	public function settings_update_global() {

		// init with sensible default
		$hide_css = false;

		// get existing CSS setting if present
		if ( function_exists( 'anp_meetings_get_option' ) ) {
			$hide_css = anp_meetings_get_option( 'anp_meetings_css', false );
		}

		// convert to WordPress Meetings setting
		if ( ! $hide_css ) {
			$include_css = 'y';
		} else {
			$include_css = 'n';
		}

		// save
		$this->setting_set( 'include_css', $include_css );

		// save settings
		$this->settings_save();

	}



	/**
	 * Update Custom Post Types.
	 *
	 * As far as I can tell, it is only the Meeting date and Proposal dates that
	 * need migrating. I assume that the Meeting date metaboxes on Agendas and
	 * Summaries are mistakenly saving as metadata on those items and that they
	 * can be ignored.
	 *
	 * @since 2.0.1
	 */
	public function settings_update_cpts() {

		// get all meetings
		$meetings = get_posts( array(
			'post_status' => 'any',
			'post_type' => 'meeting',
		) );

		// migrate meeting metadata
		foreach( $meetings as $meeting ) {

			// migrate date
			$this->settings_update_cpt_date(
				$meeting, // post
				'meeting_date', // old key
				'_wordpress_meetings_meeting_date' // new key
			);

			// assign term to events linked to this meeting
			$this->assign_events_to_term( $meeting );

		}

		// get all proposals
		$proposals = get_posts( array(
			'post_status' => 'any',
			'post_type' => 'proposal',
		) );

		// migrate proposal metadata
		foreach( $proposals as $proposal ) {

			// migrate accepted date
			$this->settings_update_cpt_date(
				$proposal, // post
				'meeting_date', // old key (misnamed in ANP Meetings)
				'_wordpress_meetings_proposal_date_accepted' // new key
			);

			// migrate effective date
			$this->settings_update_cpt_date(
				$proposal, // post
				'proposal_date_effective', // old key
				'_wordpress_meetings_proposal_date_effective' // new key
			);

		}

	}



	/**
	 * Assign term to Events linked to a Meeting.
	 *
	 * @since 2.0.1
	 *
	 * @param WP_Post $meeting The WordPress Meeting post object.
	 */
	private function assign_events_to_term( $meeting ) {

		// bail if Event Organiser plugin is not present
		if ( ! defined( 'EVENT_ORGANISER_VER' ) ) return;

		// init title
		$title = __( 'Meetings', 'wordpress-meetings' );

		// try and match by term slug to see if a term exists
		$term = get_term_by( 'slug', sanitize_title( $title ), 'event-category' );

		// create the term if it doesn't exist
		if ( $term === false ) {
			$this->term_create();
		}

		// get connected events
		$connected_events = get_posts( array(
			'post_status' => 'any',
			'connected_type' => 'meeting_to_event',
			'connected_items' => $meeting,
			'nopaging' => true,
			'suppress_filters' => false,
		) );

		// loop, though there's only one
		foreach( $connected_events as $event ) {

			// no event data to update
			$event_data = array();

			// init post data
			$post_data = array();

			// define term as array
			$terms = array( absint( $term->term_id ) );

			// add to post data
			$post_data['tax_input'] = array(
				'event-category' => $terms,
			);

			// update the event
			$event_id = eo_update_event( $event->ID, $post_data, $event_data );

		}

	}



	/**
	 * Utility to migrate an ANP meeting date.
	 *
	 * @since 2.0.1
	 *
	 * @param WP_Post $post The WordPress post object.
	 */
	private function settings_update_cpt_date( $post, $old_key, $new_key ) {

		// get currently assigned date
		$old_date = get_post_meta( $post->ID, $old_key, true );

		// migrate if present
		if ( ! empty( $old_date ) ) {

			// check parts
			if ( $this->is_valid_date( $old_date ) ) {

				// get parts
				$parts = explode( '/', $old_date );

				// convert to WordPress Meetings format (yyyy-mm-dd)
				$new_date = $parts[2] . '-' . $parts[0] . '-' . $parts[1];

				// add first, but update if there is existing metadata
				if ( ! add_post_meta( $post->ID, $new_key, $new_date, true ) ) {
					update_post_meta( $post->ID, $new_key, $new_date );
				}

				// delete old meta
				delete_post_meta( $post->ID, $old_key );

			}

		}

	}



	/**
	 * Utility to check the format of an ANP Meetings date.
	 *
	 * @since 2.0.1
	 *
	 * @param str $date The date to test in mm/dd/yyyy format.
	 * @return bool $is_valid True if the date is valid, false otherwise.
	 */
	private function is_valid_date( $date ) {

		// assume invalid
		$is_valid = false;

		// get parts
		$parts = explode( '/', $date );

		// bail if not complete
		if ( count( $parts ) !== 3 ) return $is_valid;

		// check parts (dd/mm/yyyy)
		if ( wp_checkdate( $parts[0], $parts[1], $parts[2], $date ) ) {
			$is_valid = true;
		}

		// --<
		return $is_valid;

	}



} // class ends



