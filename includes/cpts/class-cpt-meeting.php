<?php

/**
 * WordPress Meetings Custom Post Type Class.
 *
 * A class that encapsulates a Custom Post Type for WordPress Meetings.
 *
 * @package WordPress_Meetings
 */
class WordPress_Meetings_CPT_Meeting extends WordPress_Meetings_CPT_Common {

	/**
	 * Custom Post Type name.
	 *
	 * @since 2.0
	 * @access public
	 * @var str $post_type_name The name of the Custom Post Type.
	 */
	public $post_type_name = 'meeting';

	/**
	 * Meeting Date meta key.
	 *
	 * @since 2.0
	 * @access public
	 * @var str $date_meta_key The meta key for the Meeting Date.
	 */
	public $date_meta_key = 'wordpress_meetings_meeting_date';

	/**
	 * Meeting Start Time meta key.
	 *
	 * @since 2.0.1
	 * @access public
	 * @var str $start_time_meta_key The meta key for the Meeting Start Time.
	 */
	public $start_time_meta_key = 'wordpress_meetings_meeting_start_time';

	/**
	 * Meeting End Time meta key.
	 *
	 * @since 2.0.1
	 * @access public
	 * @var str $end_time_meta_key The meta key for the Meeting End Time.
	 */
	public $end_time_meta_key = 'wordpress_meetings_meeting_end_time';



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



	/**
	 * Register WordPress hooks.
	 *
	 * @since 2.0
	 */
	public function register_hooks() {

		// common hooks
		parent::register_hooks();

		// amend admin table
		add_filter( 'manage_edit-' . $this->post_type_name . '_columns', array( $this, 'columns_amend' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'columns_populate' ) );

		// filter the title
		//add_filter( 'the_title', array( $this, 'title_filter' ), 10, 2 );

		// remove unwanted metaboxes
		add_action( 'admin_menu', array( $this, 'metaboxes_remove' ) );

		// add meta boxes
		add_action( 'add_meta_boxes', array( $this, 'metaboxes_add' ) );

		// intercept save
		add_action( 'save_post', array( $this, 'save_post' ), 1, 2 );

		// intercept status changes
		add_action( 'transition_post_status', array( $this, 'post_type_status' ), 10, 3 );

	}



	// #########################################################################



	/**
	 * Create our Custom Post Type.
	 *
	 * @since 2.0
	 */
	public function post_type_create() {

		/**
		 * Allow customization of the default post type slug.
		 *
		 * @since 2.0
		 *
		 * @param str $slug The default slug.
		 * @return str $slug The modified slug.
		 */
		$slug = apply_filters( 'wordpress_meetings_cpt_' . $this->post_type_name . '_slug', $this->post_type_name );

		/**
		 * Allow customization of the default capabilities.
		 *
		 * @since 2.0
		 *
		 * @param array $capabilities The default capabilities.
		 * @return array $capabilities The modified capabilities.
		 */
		$capabilities = apply_filters( 'wordpress_meetings_cpt_' . $this->post_type_name . '_caps', $this->capabilities() );

		$labels = array(
			'name'                => _x( 'Meetings', 'Post Type General Name', 'wordpress-meetings' ),
			'singular_name'       => _x( 'Meeting', 'Post Type Singular Name', 'wordpress-meetings' ),
			'menu_name'           => __( 'Meetings', 'wordpress-meetings' ),
			'name_admin_bar'      => __( 'Meeting', 'wordpress-meetings' ),
			'parent_item_colon'   => __( 'Parent Meeting:', 'wordpress-meetings' ),
			'all_items'           => __( 'All Meetings', 'wordpress-meetings' ),
			'add_new_item'        => __( 'Add New Meeting', 'wordpress-meetings' ),
			'add_new'             => __( 'Add New Meeting', 'wordpress-meetings' ),
			'new_item'            => __( 'New Meeting', 'wordpress-meetings' ),
			'edit_item'           => __( 'Edit Meeting', 'wordpress-meetings' ),
			'update_item'         => __( 'Update Meeting', 'wordpress-meetings' ),
			'view_item'           => __( 'View Meeting', 'wordpress-meetings' ),
			'search_items'        => __( 'Search Meetings', 'wordpress-meetings' ),
			'not_found'           => __( 'Not found', 'wordpress-meetings' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'wordpress-meetings' ),
		);

		$rewrite = array(
			'slug' => $slug,
			'with_front' => false,
			'pages' => true,
			'feeds' => true,
		);

		$supports = array(
			'title',
			'editor',
			'excerpt',
			'author',
			'comments',
			'custom-fields',
			'wpcom-markdown',
			'revisions',
		);

		$taxonomies = array(
			'organization',
			'meeting_type',
			'meeting_tag',
		);

		$config = array(
			'label' => __( 'Meeting', 'wordpress-meetings' ),
			'description' => __( 'Custom post type for meetings', 'wordpress-meetings' ),
			'labels' => $labels,
			'supports' => $supports,
			'taxonomies' => $taxonomies,
			'hierarchical' => false,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 20,
			'menu_icon' => 'dashicons-clipboard',
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'can_export' => true,
			'has_archive' => 'meetings',
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'query_var' => $slug,
			'rewrite' => $rewrite,
			'show_in_rest' => true,
			'rest_base' => $slug,
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'capability_type' => 'meeting',
			'capabilities' => $capabilities,
			'map_meta_cap' => true,
		);

		/**
		 * Allow customization of the default post type configuration.
		 *
		 * @since 2.0
		 *
		 * @param array $config The default config params.
		 * @param str $slug The default slug.
		 * @return array $config The modified config params.
		 */
		$config = apply_filters( 'wordpress_meetings_cpt_' . $this->post_type_name . '_config', $config, $slug );

		register_post_type( $this->post_type_name, $config );

	}



	/**
	 * Override messages for a custom post type.
	 *
	 * @since 2.0
	 *
	 * @param array $messages The existing messages.
	 * @return array $messages The modified messages.
	 */
	public function post_type_messages( $messages ) {

		// access relevant globals
		global $post, $post_ID;

		// define custom messages for our custom post type
		$messages[$this->post_type_name] = array(

			// unused - messages start at index 1
			0 => '',

			// item updated
			1 => sprintf(
				__( 'Meeting updated. <a href="%s">View meeting</a>', 'wordpress-meetings' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// custom fields
			2 => __( 'Custom field updated.', 'wordpress-meetings' ),
			3 => __( 'Custom field deleted.', 'wordpress-meetings' ),
			4 => __( 'Meeting updated.', 'wordpress-meetings' ),

			// item restored to a revision
			5 => isset( $_GET['revision'] ) ?

					// revision text
					sprintf(
						// translators: %s: date and time of the revision
						__( 'Meeting restored to revision from %s', 'wordpress-meetings' ),
						wp_post_revision_title( (int) $_GET['revision'], false )
					) :

					// no revision
					false,

			// item published
			6 => sprintf(
				__( 'Meeting published. <a href="%s">View meeting</a>', 'wordpress-meetings' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// item saved
			7 => __( 'Meeting saved.', 'wordpress-meetings' ),

			// item submitted
			8 => sprintf(
				__( 'Meeting submitted. <a target="_blank" href="%s">Preview meeting</a>', 'wordpress-meetings' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			),

			// item scheduled
			9 => sprintf(
				__( 'Meeting scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview meeting</a>', 'wordpress-meetings' ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i' ),
				strtotime( $post->post_date ) ),
				esc_url( get_permalink( $post_ID ) )
			),

			// draft updated
			10 => sprintf(
				__( 'Meeting draft updated. <a target="_blank" href="%s">Preview meeting</a>', 'wordpress-meetings' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			)

		);

		// --<
		return $messages;

	}



	/**
	 * Intercept status transition for Meetings.
	 *
	 * @since 2.0.1
	 *
	 * @param string $new_status The new post status.
	 * @param string $old_status The old post status.
	 * @param WP_Post $post The Meeting post object.
	 */
	public function post_type_status( $new_status, $old_status, $post ) {

		// bail if not this post type
		if ( $this->post_type_name !== $post->post_type ) return;

		// remove our hook
		remove_action( 'save_post', array( $this, 'save_post' ), 1 );

		/**
		 * Broadcast that the Meeting has transitioned status.
		 *
		 * @since 2.0.1
		 *
		 * @param string $new_status The new post status.
		 * @param string $old_status The old post status.
		 * @param WP_Post $post The Meeting post object.
		 */
		do_action( 'wordpress_meetings_cpt_' . $this->post_type_name . '_status', $new_status, $old_status, $post );

		// rehook
		add_action( 'save_post', array( $this, 'save_post' ), 1, 2 );

	}



	/**
	 * Amend the columns shown in the listing table for this CPT.
	 *
	 * @since 2.0
	 *
	 * @param array $columns The existing columns.
	 * @return array $columns The modified columns.
	 */
	public function columns_amend( $columns ) {

		// add date
		$columns['meeting_date'] = __( 'Date', 'wordpress-meetings' );

		// remove default columns
		unset( $columns['comments'] );
		unset( $columns['glocal_post_thumb'] );
		unset( $columns['date'] );
		unset( $columns['author'] );

		// --<
		return $columns;

	}



	/**
	 * Populate a column shown in the listing table for this CPT.
	 *
	 * @since 2.0
	 *
	 * @param array $column The existing current column.
	 */
	public function columns_populate( $column ) {

		// bail if not date column
		if ( 'meeting_date' !== $column ) return;

		// get the date
		$meeting_date = esc_html( get_post_meta( get_the_ID(), '_' . $this->date_meta_key, true ) );

		// show it
		echo $meeting_date;

	}



	// #########################################################################



	/**
	 * Hide any unwanted metaboxes.
	 *
	 * @since 2.0
	 *
	 * @link https://codex.wordpress.org/Function_Reference/remove_meta_box
	 */
	public function metaboxes_remove() {

		// remove custom fields metabox
		remove_meta_box( 'postcustom', $this->post_type_name, 'side' );

	}



	/**
	 * Adds metaboxes to admin screens.
	 *
	 * @since 2.0
	 */
	public function metaboxes_add() {

		// add Meeting Info meta box
		add_meta_box(
			'wordpress_meetings_meeting_info',
			__( 'Meeting Information', 'wordpress-meetings' ),
			array( $this, 'metabox_info' ),
			$this->post_type_name,
			'side', // column: options are 'normal' and 'side'
			'high' // vertical placement: options are 'core', 'high', 'low'
		);

	}



	/**
	 * Adds a metabox to CPT edit screens.
	 *
	 * @since 2.0
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function metabox_info( $post ) {

		// enqueue date picker
		wp_enqueue_script( 'jquery-ui-datepicker' );

		// enqueue time picker
		wp_enqueue_script(
			'jquery-timepicker',
			WORDPRESS_MEETINGS_URL . 'assets/js/timepicker/jquery.timepicker.min.js',
			array( 'jquery' ), // dependencies
			WORDPRESS_MEETINGS_VERSION // version
		);

		// trigger date and time pickers
		add_action( 'admin_footer', array( $this, 'metabox_js' ) );

		// give datepicker some style
		wp_enqueue_style(
			'wordpress_meetings_datepicker',
			WORDPRESS_MEETINGS_URL . 'assets/css/datepicker/jquery-ui.min.css',
			array(), // dependencies
			WORDPRESS_MEETINGS_VERSION, // version
			'all' // media
		);

		// give timepicker some style
		wp_enqueue_style(
			'wordpress_meetings_timepicker',
			WORDPRESS_MEETINGS_URL . 'assets/css/timepicker/jquery.timepicker.min.css',
			array(), // dependencies
			WORDPRESS_MEETINGS_VERSION, // version
			'all' // media
		);

		// set key
		$key = '_' . $this->date_meta_key;

		// init date with today
		$date = date( 'Y-m-d' );

		// if the custom field already has a value, grab it
		if ( get_post_meta( $post->ID, $key, true ) != '' ) {
			$date = get_post_meta( $post->ID, $key, true );
		}

		// set key
		$key = '_' . $this->start_time_meta_key;

		// init start time with now rounded to nearest 15 min
		$start_time_obj = $this->rounded_time();
		$start_time = $start_time_obj->format( 'H:i' );

		// if the custom field already has a value, grab it
		if ( get_post_meta( $post->ID, $key, true ) != '' ) {
			$start_time = get_post_meta( $post->ID, $key, true );
		}

		// set key
		$key = '_' . $this->end_time_meta_key;

		// init end time with start time plus 1 hour
		$end_time_obj = $start_time_obj->add( new DateInterval( 'PT1H' ) );
		$end_time = $end_time_obj->format( 'H:i' );

		// if the custom field already has a value, grab it
		if ( get_post_meta( $post->ID, $key, true ) != '' ) {
			$end_time = get_post_meta( $post->ID, $key, true );
		}

		// include template file
		include( WORDPRESS_MEETINGS_PATH . 'assets/templates/admin/metabox-meeting-info.php' );

	}



	/**
	 * Trigger date picker on our elements.
	 *
	 * @since 2.0
	 */
	public function metabox_js() {
		?>
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('.wp_datepicker').datepicker({
				dateFormat : 'yy-mm-dd',
				defaultDate : '<?php echo date( 'Y-m-d' ); ?>'
			});
			jQuery('.wp_timepicker').timepicker({
				'scrollDefault' : 'now',
				'step' : 15,
				'timeFormat' : 'H:i'
			});
		});
		</script>
		<?php
	}



	// #########################################################################



	/**
	 * Stores our additional params.
	 *
	 * @since 2.0
	 *
	 * @param integer $post_id The ID of the post (or revision).
	 * @param WP_Post $post The post object.
	 */
	public function save_post( $post_id, $post ) {

		// if no post, kick out
		if ( ! $post ) return;

		// is this an auto save routine?
		if ( defined( 'DOING_AUTOSAVE' ) AND DOING_AUTOSAVE ) return;

		// check permissions
		if ( ! current_user_can( 'edit_meeting', $post->ID ) ) return;

		// bail if not our post type
		if ( $post->post_type != $this->post_type_name ) return;

		// check for revision
		if ( $post->post_type == 'revision' ) {

			// get parent
			if ( $post->post_parent != 0 ) {
				$post_obj = get_post( $post->post_parent );
			} else {
				$post_obj = $post;
			}

		} else {
			$post_obj = $post;
		}

		// store our metadata
		$this->save_metadata( $post_obj );

	}



	/**
	 * When a post is saved, this also saves the metadata.
	 *
	 * @since 2.0
	 *
	 * @param WP_Post $post The object for the post.
	 */
	private function save_metadata( $post ) {

		// authenticate
		$nonce = isset( $_POST['wordpress_meetings_meeting_info_nonce'] ) ? $_POST['wordpress_meetings_meeting_info_nonce'] : '';
		if ( ! wp_verify_nonce( $nonce, 'wordpress_meetings_meeting_info_box' ) ) return;

		// define key
		$db_key = '_' . $this->date_meta_key;

		// get date value (yyyy-mm-dd)
		$date = isset( $_POST[$this->date_meta_key] ) ? trim( $_POST[$this->date_meta_key] ) : 0;

		// save if valid
		if ( $this->is_valid_date( $date ) ) {
			$this->save_meta( $post, $db_key, $date );
		}

		// define key
		$db_key = '_' . $this->start_time_meta_key;

		// get time value (yyyy-mm-dd)
		$start_time = isset( $_POST[$this->start_time_meta_key] ) ? trim( $_POST[$this->start_time_meta_key] ) : 0;

		// save if valid
		if ( $this->is_valid_time( $start_time ) ) {
			$this->save_meta( $post, $db_key, $start_time );
		}

		// define key
		$db_key = '_' . $this->end_time_meta_key;

		// get time value (yyyy-mm-dd)
		$end_time = isset( $_POST[$this->end_time_meta_key] ) ? trim( $_POST[$this->end_time_meta_key] ) : 0;

		// save if valid
		if ( $this->is_valid_time( $end_time ) ) {
			$this->save_meta( $post, $db_key, $end_time );
		}

		// build array to pass to action
		$metadata = array(
			'date' => $date,
			'start_time' => $start_time,
			'end_time' => $end_time,
		);

		// remove our hook
		remove_action( 'save_post', array( $this, 'save_post' ), 1 );

		/**
		 * Broadcast that the metadata for a Meeting has been saved.
		 *
		 * @since 2.0.1
		 *
		 * @param WP_Post $post The meeting post object.
		 * @param array $metadata The meeting metadata.
		 */
		do_action( 'wordpress_meetings_cpt_' . $this->post_type_name . '_meta_saved', $post, $metadata );

		// rehook
		add_action( 'save_post', array( $this, 'save_post' ), 1, 2 );

	}



	/**
	 * Utility to automate metadata saving.
	 *
	 * @since 2.0
	 *
	 * @param WP_Post $post_obj The WordPress post object.
	 * @param string $key The meta key.
	 * @param mixed $data The data to be saved.
	 * @return mixed $data The data that was saved.
	 */
	private function save_meta( $post, $key, $data = '' ) {

		// add first, but update if there is existing metadata
		if ( ! add_post_meta( $post->ID, $key, $data, true ) ) {
			update_post_meta( $post->ID, $key, $data );
		}

		// --<
		return $data;

	}



	/**
	 * Utility to check the format of a date.
	 *
	 * @since 2.0
	 *
	 * @param str $date The date to test in yyyy-mm-dd format.
	 * @return bool $is_valid True if the date is valid, false otherwise.
	 */
	private function is_valid_date( $date ) {

		// assume invalid
		$is_valid = false;

		// get parts
		$parts = explode( '-', $date );

		// bail if not yyyy-mm-dd
		if ( count( $parts ) !== 3 ) return $is_valid;

		// check parts
		if ( wp_checkdate( $parts[1], $parts[2], $parts[0], $date ) ) {
			$is_valid = true;
		}

		// --<
		return $is_valid;

	}



	/**
	 * Utility to check the format of a time.
	 *
	 * @since 2.0.1
	 *
	 * @param str $date The time to test in hh:mm format.
	 * @return bool $is_valid True if the time is valid, false otherwise.
	 */
	private function is_valid_time( $time ) {

		// assume invalid
		$is_valid = false;

		// get parts
		$parts = explode( ':', $time );

		// bail if not hh:mm
		if ( count( $parts ) !== 2 ) return $is_valid;

		// check parts
		if ( absint( $parts[0] ) > 23 ) return $is_valid;
		if ( absint( $parts[1] ) > 59 ) return $is_valid;

		// we're good
		$is_valid = true;

		// --<
		return $is_valid;

	}



	/**
	 * Get the time rounded to the nearest 15 min.
	 *
	 * @since 2.0.2
	 *
	 * @return str $time The rounded time.
	 */
	private function rounded_time() {

		// init datetime object
		$datetime = new DateTime( date( 'Y-m-d H:i:s' ) );

		// round up if seconds are past the nearest mark
		$second = $datetime->format( 's' );
		if ( $second > 0 ) {
			$datetime->add( new DateInterval( 'PT' . ( 60 - $second ) . 'S' ) );
		}

		// round up if minutes are past the nearest mark
		$minute = $datetime->format( 'i' );
		$minute = $minute % 15;
		if ( $minute != 0 ) {
			$diff = 15 - $minute;
			$datetime->add( new DateInterval( 'PT' . $diff . 'M' ) );
		}

		// --<
		return $datetime;

	}



	// #########################################################################



	/**
	 * Filter the title.
	 *
	 * @since 2.0
	 *
	 * @param str $title The existing title.
	 * @param int $id The numeric ID of the WordPress post.
	 * @return str $title The modifed title.
	 */
	public function title_filter( $title, $id = null ) {

		// bail when not required
		if ( is_admin() || ! in_the_loop() || ! is_main_query() ) {
			return $title;
		}

		// bail if not one of our CPT pages
		if ( ! is_singular( $this->post_type_name ) AND ! is_post_type_archive( $this->post_type_name ) ) {
			return $title;
		}

		// use common function
		$title = wp_meetings_meeting_title();

		// --<
		return $title;

	}



	// #########################################################################



	/**
	 * Get the Meeting object connected to another post type.
	 *
	 * @since 2.0.2
	 *
	 * @param int $post_id The numeric ID of the post.
	 * @return object|bool $queried_obj The meeting object if found, false otherwise.
	 */
	public function meeting_get( $post_id = null ) {

		// default to false
		$queried_obj = false;

		// get post ID
		if ( is_null( $post_id ) ) {
			$post_id = get_the_ID();
		}

		// get post
		$post = get_post( $post_id );

		// get post type
		$post_type = get_post_type( $post_id );

		// get meeting object if not meeting CPT
		if ( 'meeting' != $post_type ) {

			$connected_meetings = get_posts( array(
				'connected_type' => 'meeting_to_' . $post_type,
				'connected_items' => $post,
				'nopaging' => true,
				'suppress_filters' => false,
			) );

			// there should only ever be one
			if ( isset( $connected_meetings[0] ) ) {
				$queried_obj = $connected_meetings[0];
			}

		} else {

			// meeting object is the post
			$queried_obj = $post;

		}

		// --<
		return $queried_obj;

	}



} // class ends



/**
 * For a given post, get the Meeting object connected to it.
 *
 * @since 2.0.2
 *
 * @param int $post_id The numeric ID of the post.
 * @return object The meeting object if found, queried object if not.
 */
function wp_meetings_meeting_get_object( $post_id ) {
	return wordpress_meetings()->cpts['meeting']->meeting_get( $post_id );
}


