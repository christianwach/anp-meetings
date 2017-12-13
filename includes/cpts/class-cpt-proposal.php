<?php

/**
 * WordPress Meetings Custom Post Type Class.
 *
 * A class that encapsulates a Custom Post Type for WordPress Meetings.
 *
 * @package WordPress_Meetings
 */
class WordPress_Meetings_CPT_Proposal extends WordPress_Meetings_CPT_Common {

	/**
	 * Custom Post Type name.
	 *
	 * @since 2.0
	 * @access public
	 * @var str $post_type_name The name of the Custom Post Type.
	 */
	public $post_type_name = 'proposal';

	/**
	 * Status form key.
	 *
	 * @since 2.0
	 * @access public
	 * @var str $status_meta_key The form key for the Proposal Status.
	 */
	public $status_meta_key = 'wordpress_meetings_proposal_status';

	/**
	 * Date Accepted meta key.
	 *
	 * @since 2.0
	 * @access public
	 * @var str $date_accepted_meta_key The meta key for Date Accepted.
	 */
	public $date_accepted_meta_key = 'wordpress_meetings_proposal_date_accepted';

	/**
	 * Date Effective meta key.
	 *
	 * @since 2.0
	 * @access public
	 * @var str $date_effective_meta_key The meta key for Date Effective.
	 */
	public $date_effective_meta_key = 'wordpress_meetings_proposal_date_effective';



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

		// add menu item
		add_action( 'admin_menu', array( $this, 'add_to_menu' ) );

		// remove unwanted metaboxes
		add_action( 'admin_menu', array( $this, 'metaboxes_remove' ) );

		// add meta boxes
		add_action( 'add_meta_boxes', array( $this, 'metaboxes_add' ) );

		// intercept save
		add_action( 'save_post', array( $this, 'save_post' ), 1, 2 );

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
			'name'                => _x( 'Proposals', 'Post Type General Name', 'wordpress-meetings' ),
			'singular_name'       => _x( 'Proposal', 'Post Type Singular Name', 'wordpress-meetings' ),
			'menu_name'           => __( 'Proposals', 'wordpress-meetings' ),
			'name_admin_bar'      => __( 'Proposal', 'wordpress-meetings' ),
			'parent_item_colon'   => __( 'Parent Proposal:', 'wordpress-meetings' ),
			'all_items'           => __( 'All Proposals', 'wordpress-meetings' ),
			'add_new_item'        => __( 'Add New Proposal', 'wordpress-meetings' ),
			'add_new'             => __( 'Add New Proposal', 'wordpress-meetings' ),
			'new_item'            => __( 'New Proposal', 'wordpress-meetings' ),
			'edit_item'           => __( 'Edit Proposal', 'wordpress-meetings' ),
			'update_item'         => __( 'Update Proposal', 'wordpress-meetings' ),
			'view_item'           => __( 'View Proposal', 'wordpress-meetings' ),
			'search_items'        => __( 'Search Proposals', 'wordpress-meetings' ),
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
			'meeting_tag',
			'proposal_status',
		);

		$config = array(
			'label' => __( 'Proposal', 'wordpress-meetings' ),
			'description' => __( 'Custom post type for proposals', 'wordpress-meetings' ),
			'labels' => $labels,
			'supports' => $supports,
			'taxonomies' => $taxonomies,
			'hierarchical' => false,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => false,
			'menu_position' => 30,
			'menu_icon' => 'dashicons-lightbulb',
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'can_export' => true,
			'has_archive' => 'proposals',
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
				__( 'Proposal updated. <a href="%s">View proposal</a>', 'wordpress-meetings' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// custom fields
			2 => __( 'Custom field updated.', 'wordpress-meetings' ),
			3 => __( 'Custom field deleted.', 'wordpress-meetings' ),
			4 => __( 'Proposal updated.', 'wordpress-meetings' ),

			// item restored to a revision
			5 => isset( $_GET['revision'] ) ?

					// revision text
					sprintf(
						// translators: %s: date and time of the revision
						__( 'Proposal restored to revision from %s', 'wordpress-meetings' ),
						wp_post_revision_title( (int) $_GET['revision'], false )
					) :

					// no revision
					false,

			// item published
			6 => sprintf(
				__( 'Proposal published. <a href="%s">View proposal</a>', 'wordpress-meetings' ),
				esc_url( get_permalink( $post_ID ) )
			),

			// item saved
			7 => __( 'Proposal saved.', 'wordpress-meetings' ),

			// item submitted
			8 => sprintf(
				__( 'Proposal submitted. <a target="_blank" href="%s">Preview proposal</a>', 'wordpress-meetings' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			),

			// item scheduled
			9 => sprintf(
				__( 'Proposal scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview proposal</a>', 'wordpress-meetings' ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i' ),
				strtotime( $post->post_date ) ),
				esc_url( get_permalink( $post_ID ) )
			),

			// draft updated
			10 => sprintf(
				__( 'Proposal draft updated. <a target="_blank" href="%s">Preview proposal</a>', 'wordpress-meetings' ),
				esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) )
			)

		);

		// --<
		return $messages;

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

		// remove proposal status
		remove_meta_box( 'proposal_statusdiv' , $this->post_type_name , 'side' );

	}



	/**
	 * Adds metaboxes to admin screens.
	 *
	 * @since 2.0
	 */
	public function metaboxes_add() {

		// add Proposal Info meta box
		add_meta_box(
			'wordpress_meetings_proposal_info',
			__( 'Proposal Information', 'wordpress-meetings' ),
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

		// trigger date picker
		add_action( 'admin_footer', array( $this, 'metabox_js' ) );

		// give it some style
		wp_enqueue_style(
			'wordpress_meetings_datepicker',
			WORDPRESS_MEETINGS_URL . 'assets/css/datepicker/jquery-ui.min.css',
			array(), // dependencies
			WORDPRESS_MEETINGS_VERSION, // version
			'all' // media
		);

		// get existing terms (there will be either one or none)
		$terms = wp_get_object_terms(
			$post->ID,
			'proposal_status',
			array( 'fields' => 'all' )
		);

		// set status
		$status = '-1';
		if ( count( $terms ) > 0 ) {
			$status = $terms[0]->term_id;
		}

		// set key
		$key = '_' . $this->date_accepted_meta_key;

		// init date with today
		$date_accepted = date( 'Y-m-d' );

		// if the custom field already has a value, grab it
		if ( get_post_meta( $post->ID, $key, true ) != '' ) {
			$date_accepted = get_post_meta( $post->ID, $key, true );
		}

		// set key
		$key = '_' . $this->date_effective_meta_key;

		// init date with today
		$date_effective = date( 'Y-m-d' );

		// if the custom field already has a value, grab it
		if ( get_post_meta( $post->ID, $key, true ) != '' ) {
			$date_effective = get_post_meta( $post->ID, $key, true );
		}

		// include template file
		include( WORDPRESS_MEETINGS_PATH . 'assets/templates/admin/metabox-proposal-info.php' );

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
				dateFormat : 'yy-mm-dd'
			});
		});
		</script>
		<?php
	}



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
		$nonce = isset( $_POST['wordpress_meetings_proposal_info_nonce'] ) ? $_POST['wordpress_meetings_proposal_info_nonce'] : '';
		if ( ! wp_verify_nonce( $nonce, 'wordpress_meetings_proposal_info_box' ) ) return;

		// get value of select
		$status = isset( $_POST[$this->status_meta_key] ) ? trim( $_POST[$this->status_meta_key] ) : '-1';

		// save if valid, delete otherwise
		if ( $this->is_valid_term( $status ) ) {
			wp_set_object_terms( $post->ID, absint( $status ), 'proposal_status' );
		} else {
			wp_delete_object_term_relationships( $post->ID, 'proposal_status' );
		}

		// define key
		$db_key = '_' . $this->date_accepted_meta_key;

		// get date value (yyyy-mm-dd)
		$date_accepted = isset( $_POST[$this->date_accepted_meta_key] ) ? trim( $_POST[$this->date_accepted_meta_key] ) : 0;

		// save if valid
		if ( $this->is_valid_date( $date_accepted ) ) {
			$this->save_meta( $post, $db_key, $date_accepted );
		}

		// define key
		$db_key = '_' . $this->date_effective_meta_key;

		// get date value (yyyy-mm-dd)
		$date_effective = isset( $_POST[$this->date_effective_meta_key] ) ? trim( $_POST[$this->date_effective_meta_key] ) : 0;

		// save if valid
		if ( $this->is_valid_date( $date_effective ) ) {
			$this->save_meta( $post, $db_key, $date_effective );
		}

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
	 * Utility to check a "status" term.
	 *
	 * @since 2.0.1
	 *
	 * @param str $status The status to test.
	 * @return bool $is_valid True if the status is valid, false otherwise.
	 */
	private function is_valid_term( $status ) {

		// assume invalid
		$is_valid = false;

		// -1 is not a valid status (means none selected)
		if ( $status == '-1' ) {
			return $is_valid;
		}

		// other statuses must be integers
		elseif ( $status == absint( $status ) ) {
			$is_valid = true;
		}

		// --<
		return $is_valid;

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
	 * Move Admin Menus.
	 *
	 * Display admin as submenu under Meetings.
	 *
	 * @since 2.0
	 */
	public function add_to_menu() {

		add_submenu_page(
			'edit.php?post_type=meeting',
			__( 'All Proposals', 'wordpress-meetings' ),
			__( 'All Proposals', 'wordpress-meetings' ),
			'edit_meetings',
			'edit.php?post_type=proposal'
		);

		add_submenu_page(
			'edit.php?post_type=meeting',
			__( 'New Proposal', 'wordpress-meetings' ),
			__( 'New Proposal', 'wordpress-meetings' ),
			'edit_meetings',
			'post-new.php?post_type=proposal'
		);

	}



} // class ends



