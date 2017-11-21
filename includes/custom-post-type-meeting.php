<?php

/**
 * WordPress Meetings Meeting Post Type.
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   WordPress_Meetings
 */



/**
 * Add Custom Post Type.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wordpress_meetings_post_type' ) ) {

	// Register Custom Post Type - Meeting
	function wordpress_meetings_post_type() {

		$slug = apply_filters( 'wordpress_meeting_post_type', 'meeting' );

		$labels = array(
			'name'                => _x( 'Meetings', 'Post Type General Name', 'wordpress-meetings' ),
			'singular_name'       => _x( 'Meeting', 'Post Type Singular Name', 'wordpress-meetings' ),
			'menu_name'           => __( 'Meetings', 'wordpress-meetings' ),
			'name_admin_bar'      => __( 'Meetings', 'wordpress-meetings' ),
			'parent_item_colon'   => __( 'Parent Meeting:', 'wordpress-meetings' ),
			'all_items'           => __( 'All Meetings', 'wordpress-meetings' ),
			'add_new_item'        => __( 'Add New Meeting', 'wordpress-meetings' ),
			'add_new'             => __( 'New Meeting', 'wordpress-meetings' ),
			'new_item'            => __( 'New Meeting', 'wordpress-meetings' ),
			'edit_item'           => __( 'Edit Meeting', 'wordpress-meetings' ),
			'update_item'         => __( 'Update Meeting', 'wordpress-meetings' ),
			'view_item'           => __( 'View Meeting', 'wordpress-meetings' ),
			'search_items'        => __( 'Search Meeting', 'wordpress-meetings' ),
			'not_found'           => __( 'Not found', 'wordpress-meetings' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'wordpress-meetings' ),
		);

		$capabilities = wordpress_meetings_capabilities();

		$rewrite = array(
			'slug'                => $slug,
			'with_front'          => false,
			'pages'               => true,
			'feeds'               => true,
		);

		$default_config = array(
			'label'               => __( 'Meetings', 'wordpress-meetings' ),
			'description'         => __( 'Custom post type for meeting agendas and notes', 'wordpress-meetings' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'comments', 'custom-fields', 'wpcom-markdown', 'revisions' ),
			'taxonomies'          => array(
				'organization',
				'meeting_type',
				'meeting_tag',
			),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-clipboard',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => 'meetings',
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'query_var'           => $slug,
			'rewrite'             => $rewrite,
			'show_in_rest'        => true,
	  		'rest_base'           => $slug,
	  		'rest_controller_class' => 'WP_REST_Posts_Controller',
			'capability_type'	  => 'meeting',
			'capabilities'		  => apply_filters( 'wordpress_meetings_meeting_capabilities', $capabilities ),
			'map_meta_cap' 		  => true
		);

		// Allow customization of the default post type configuration via filter.
		$config = apply_filters( 'meeting_post_type_defaults', $default_config, $slug );

		register_post_type( $slug, $config );

	}

	// Hook into the 'init' action
	add_action( 'init', 'wordpress_meetings_post_type', 0 );

}



/**
 * Add Custom Taxonomy for Organization.
 *
 * @since 1.0.8
 */
if ( ! function_exists( 'wordpress_organization_taxonomy' ) ) {

	// Register Custom Taxonomy
	function wordpress_organization_taxonomy() {

		$slug = apply_filters( 'wordpress_organization_taxonomy', 'organization' );

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
			'capabilities'          	 => apply_filters( 'meetings_organizations_tax_capabilities', $capabilities ),
		);
		register_taxonomy( 'organization', array( 'meeting', 'agenda', 'summary', 'proposal' ), $args );

	}

	// Hook into the 'init' action
	add_action( 'init', 'wordpress_organization_taxonomy', 0 );

}



/**
 * Add Custom Taxonomy.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wordpress_meetings_type' ) ) {

	// Register Custom Taxonomy
	function wordpress_meetings_type() {

		$slug = apply_filters( 'wordpress_meeting_type_taxonomy', 'meeting_type' );

		$labels = array(
			'name'                       => _x( 'Meeting Type', 'Taxonomy General Name', 'wordpress-meetings' ),
			'singular_name'              => _x( 'Meeting Type', 'Taxonomy Singular Name', 'wordpress-meetings' ),
			'menu_name'                  => __( 'Types', 'wordpress-meetings' ),
			'all_items'                  => __( 'All Meeting Types', 'wordpress-meetings' ),
			'parent_item'                => __( 'Parent Meeting Type', 'wordpress-meetings' ),
			'parent_item_colon'          => __( 'Parent Meeting Type:', 'wordpress-meetings' ),
			'new_item_name'              => __( 'New Meeting Type Name', 'wordpress-meetings' ),
			'add_new_item'               => __( 'Add New Meeting Type', 'wordpress-meetings' ),
			'edit_item'                  => __( 'Edit Meeting Type', 'wordpress-meetings' ),
			'update_item'                => __( 'Update Meeting Type', 'wordpress-meetings' ),
			'view_item'                  => __( 'View Meeting Type', 'wordpress-meetings' ),
			'separate_items_with_commas' => __( 'Separate meeting types with commas', 'wordpress-meetings' ),
			'add_or_remove_items'        => __( 'Add or remove meeting types', 'wordpress-meetings' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'wordpress-meetings' ),
			'popular_items'              => __( 'Popular Meeting Types', 'wordpress-meetings' ),
			'search_items'               => __( 'Search Meeting Types', 'wordpress-meetings' ),
			'not_found'                  => __( 'Not Found', 'wordpress-meetings' ),
		);

		$capabilities = array(
			'manage_terms'               => 'manage_categories',
			'edit_terms'                 => 'manage_categories',
			'delete_terms'               => 'manage_categories',
			'assign_terms'               => 'edit_meetings',
		);

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
			'capabilities'          	 => apply_filters( 'meetings_meeting_type_tax_capabilities', $capabilities ),
		);
		register_taxonomy( 'meeting_type', array( 'meeting' ), $args );

	}

	// Hook into the 'init' action
	add_action( 'init', 'wordpress_meetings_type', 0 );

}



/**
 * Add Custom Taxonomy.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wordpress_meetings_tag' ) ) {

	// Register Custom Taxonomy
	function wordpress_meetings_tag() {

		$slug = apply_filters( 'wordpress_meetings_tag_taxonomy', 'meeting_tag' );

		$labels = array(
			'name'                       => _x( 'Meeting Tags', 'Taxonomy General Name', 'wordpress-meetings' ),
			'singular_name'              => _x( 'Meeting Tag', 'Taxonomy Singular Name', 'wordpress-meetings' ),
			'menu_name'                  => __( 'Tags', 'wordpress-meetings' ),
			'all_items'                  => __( 'All Tags', 'wordpress-meetings' ),
			'parent_item'                => __( 'Parent Tag', 'wordpress-meetings' ),
			'parent_item_colon'          => __( 'Parent Tag:', 'wordpress-meetings' ),
			'new_item_name'              => __( 'New Tag Name', 'wordpress-meetings' ),
			'add_new_item'               => __( 'Add New Tag', 'wordpress-meetings' ),
			'edit_item'                  => __( 'Edit Tag', 'wordpress-meetings' ),
			'update_item'                => __( 'Update Tag', 'wordpress-meetings' ),
			'view_item'                  => __( 'View Tag', 'wordpress-meetings' ),
			'separate_items_with_commas' => __( 'Separate tags with commas', 'wordpress-meetings' ),
			'add_or_remove_items'        => __( 'Add or remove tags', 'wordpress-meetings' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'wordpress-meetings' ),
			'popular_items'              => __( 'Popular Tags', 'wordpress-meetings' ),
			'search_items'               => __( 'Search Tags', 'wordpress-meetings' ),
			'not_found'                  => __( 'Not Found', 'wordpress-meetings' ),
		);

		$capabilities = array(
			'manage_terms'               => 'manage_categories',
			'edit_terms'                 => 'manage_categories',
			'delete_terms'               => 'manage_categories',
			'assign_terms'               => 'edit_meetings',
		);

		$rewrite = array(
			'slug'                       => $slug,
			'with_front'                 => true,
			'hierarchical'               => false,
		);

		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
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
			'capabilities'          	 => apply_filters( 'meetings_meeting_tag_tax_capabilities', $capabilities )
		);

		register_taxonomy( 'meeting_tag', array( 'meeting', 'agenda', 'summary', 'proposal' ), $args );

	}

	// Hook into the 'init' action
	add_action( 'init', 'wordpress_meetings_tag', 0 );

}



/**
 * Add Custom Admin Columns.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wordpress_meetings_columns' ) ) {

	function wordpress_meetings_columns( $columns ) {
	    $columns['meeting_date'] = 'Date';
	    unset( $columns['comments'] );
	    unset( $columns['glocal_post_thumb'] );
	    unset( $columns['date'] );
	    unset( $columns['author'] );
	    return $columns;
	}

	add_filter( 'manage_edit-meeting_columns', 'wordpress_meetings_columns' ) ;

}



/**
 * Populate Custom Admin Columns.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wordpress_meetings_populate_columns' ) ) {
	function wordpress_meetings_populate_columns( $column ) {
	    if ( 'meeting_date' == $column ) {
	        $meeting_date = esc_html( get_post_meta( get_the_ID(), 'meeting_date', true ) );
	        echo $meeting_date;
	    }
	}

	add_action( 'manage_posts_custom_column', 'wordpress_meetings_populate_columns' );

}



/**
 * Hide custom fields metabox.
 *
 * @since 1.0.3
 *
 * @link https://codex.wordpress.org/Function_Reference/remove_meta_box
 */
function wordpress_meetings_remove_custom_fields_metabox() {
	remove_meta_box( 'postcustom', 'meeting', 'side' );
	remove_meta_box( 'postcustom', 'agenda', 'side' );
	remove_meta_box( 'postcustom', 'summary', 'side' );
	remove_meta_box( 'postcustom', 'proposal', 'side' );
}
add_action( 'admin_menu', 'wordpress_meetings_remove_custom_fields_metabox' );


