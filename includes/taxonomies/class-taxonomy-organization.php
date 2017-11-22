<?php

/**
 * WordPress Meetings Custom Taxonomy Class.
 *
 * A class that encapsulates a Custom Taxonomy for WordPress Meetings.
 *
 * @package WordPress_Meetings
 */
class WordPress_Meetings_Taxonomy_Organization extends WordPress_Meetings_Taxonomy_Base {

	/**
	 * Taxonomy name.
	 *
	 * @since 2.0
	 * @access public
	 * @var str $taxonomy_name The name of the Custom Taxonomy.
	 */
	public $taxonomy_name = 'organization';

	/**
	 * Custom Post Types.
	 *
	 * @since 2.0
	 * @access public
	 * @var array $post_types The Post Types to which this Taxonomy applies.
	 */
	public $post_types = array( 'meeting', 'agenda', 'summary', 'proposal' );



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

	}



	// #########################################################################



	/**
	 * Create our Custom Taxonomy.
	 *
	 * @since 2.0
	 */
	public function taxonomy_create() {

		$labels = array(
			'name'                       => _x( 'Organizational Group', 'Taxonomy General Name', 'wordpress-meetings' ),
			'singular_name'              => _x( 'Organizational Group', 'Taxonomy Singular Name', 'wordpress-meetings' ),
			'menu_name'                  => __( 'Organizations', 'wordpress-meetings' ),
			'all_items'                  => __( 'All Organizational Groups', 'wordpress-meetings' ),
			'parent_item'                => __( 'Parent Organizational Group', 'wordpress-meetings' ),
			'parent_item_colon'          => __( 'Parent Organizational Group:', 'wordpress-meetings' ),
			'new_item_name'              => __( 'New Organizational Group Name', 'wordpress-meetings' ),
			'add_new_item'               => __( 'Add New Organizational Group', 'wordpress-meetings' ),
			'edit_item'                  => __( 'Edit Organizational Group', 'wordpress-meetings' ),
			'update_item'                => __( 'Update Organizational Group', 'wordpress-meetings' ),
			'view_item'                  => __( 'View Organizational Group', 'wordpress-meetings' ),
			'separate_items_with_commas' => __( 'Separate organizational groups with commas', 'wordpress-meetings' ),
			'add_or_remove_items'        => __( 'Add or remove organizational groups', 'wordpress-meetings' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'wordpress-meetings' ),
			'popular_items'              => __( 'Popular Organizational Groups', 'wordpress-meetings' ),
			'search_items'               => __( 'Search Organizational Groups', 'wordpress-meetings' ),
			'not_found'                  => __( 'Not Found', 'wordpress-meetings' ),
		);

		$capabilities = array(
			'manage_terms'               => 'manage_categories',
			'edit_terms'                 => 'manage_categories',
			'delete_terms'               => 'manage_categories',
			'assign_terms'               => 'edit_meetings',
		);

		/**
		 * Allow customization of the default capabilities.
		 *
		 * @since 2.0
		 *
		 * @param array $capabilities The default capabilities.
		 * @return array $capabilities The modified capabilities.
		 */
		$capabilities = apply_filters( 'wordpress_meetings_tax_' . $this->taxonomy_name . '_caps', $capabilities );

		/**
		 * Allow customization of the default taxonomy slug.
		 *
		 * @since 2.0
		 *
		 * @param str $slug The default slug.
		 * @return str $slug The modified slug.
		 */
		$slug = apply_filters( 'wordpress_meetings_tax_' . $this->taxonomy_name . '_slug', $this->taxonomy_name );

		$rewrite = array(
			'slug'                       => $slug,
			'with_front'                 => true,
		);

		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'query_var'                  => $slug,
			'show_in_rest'       		 => true,
			'rest_base'          		 => $slug,
			'rest_controller_class' 	 => 'WP_REST_Terms_Controller',
			'rewrite'                    => $rewrite,
			'capabilities'          	 => $capabilities,
		);

		register_taxonomy(
			$this->taxonomy_name,
			$this->post_types,
			$args
		);

	}



} // class ends



