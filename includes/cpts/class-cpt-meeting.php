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

		// remove unwanted metaboxes
		add_action( 'admin_menu', array( $this, 'metaboxes_remove' ) );

		// filter the title
		add_filter( 'the_title', array( $this, 'title_filter' ), 10, 2 );

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

	    if ( 'meeting_date' !== $column ) return;

		// get the date
		$meeting_date = esc_html( get_post_meta( get_the_ID(), 'meeting_date', true ) );

		// show it
		echo $meeting_date;

	}



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
		$title = wordpress_meetings_meeting_title();

		// If agenda or summary, display as {post_type name - singular} - {meeting_type} - {meeting_date}
		if ( is_post_type_archive( array( 'agenda', 'summary' ) ) || is_singular( array( 'agenda', 'summary' ) ) ) {

			global $post;
			$meeting_title = [];

			$post_type_object = get_post_type_object( get_post_type( $post->ID ) );

			$post_type_name = $post_type_object->labels->singular_name;

			$org_terms = wp_get_post_terms( $post->ID, 'organization', array(
				'fields' => 'names'
			) );
			$org_terms = ( ! empty( $org_terms ) ) ? $org_terms[0] : '' ;

			$meeting_date = date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $post->ID, 'meeting_date', true ) ) );

			if ( ! empty( $post_type_name ) ) {
				array_push( $meeting_title, '<span class="post-type">' . $post_type_name . '</span>' );
			}
			if ( ! empty( $org_terms ) ) {
				array_push( $meeting_title, '<span class="organization">' . $org_terms . '</span>' );
			}
			if ( ! empty( $type_terms ) ) {
				array_push( $meeting_title, '<span class="type">' . $type_terms . '</span>' );
			}

			// --<
			return implode( ' - ', $meeting_title );

		}

		// --<
		return $title;

    }



} // class ends



