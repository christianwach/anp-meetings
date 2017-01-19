<?php

/**
 * ANP Meetings Meeting Post Type
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_Meetings
 */

/**
 * Add Custom Post Type
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'anp_meetings_post_type' ) ) {

	// Register Custom Post Type - Meeting
	function anp_meetings_post_type() {

		$slug = apply_filters( 'anp_meeting_post_type', 'meeting' );

		$labels = array(
			'name'                => _x( 'Meetings', 'Post Type General Name', 'meetings' ),
			'singular_name'       => _x( 'Meeting', 'Post Type Singular Name', 'meetings' ),
			'menu_name'           => __( 'Meetings', 'meetings' ),
			'name_admin_bar'      => __( 'Meetings', 'meetings' ),
			'parent_item_colon'   => __( 'Parent Meeting:', 'meetings' ),
			'all_items'           => __( 'All Meetings', 'meetings' ),
			'add_new_item'        => __( 'Add New Meeting', 'meetings' ),
			'add_new'             => __( 'New Meeting', 'meetings' ),
			'new_item'            => __( 'New Meeting', 'meetings' ),
			'edit_item'           => __( 'Edit Meeting', 'meetings' ),
			'update_item'         => __( 'Update Meeting', 'meetings' ),
			'view_item'           => __( 'View Meeting', 'meetings' ),
			'search_items'        => __( 'Search Meeting', 'meetings' ),
			'not_found'           => __( 'Not found', 'meetings' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'meetings' ),
		);

		$capabilities = anp_meetings_capabilities();

		$rewrite = array(
			'slug'                => $slug,
			'with_front'          => false,
			'pages'               => true,
			'feeds'               => true,
		);

		$default_config = array(
			'label'               => __( 'Meetings', 'meetings' ),
			'description'         => __( 'Custom post type for meeting agendas and notes', 'meetings' ),
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
			'capability_type'	  => array( 'meeting' ),
			'capabilities'		  => apply_filters( 'meetings_meeting_capabilities', $capabilities ),
			'map_meta_cap' 		  => true
		);

		// Allow customization of the default post type configuration via filter.
		$config = apply_filters( 'meeting_post_type_defaults', $default_config, $slug );

		register_post_type( $slug, $config );

	}

	// Hook into the 'init' action
	add_action( 'init', 'anp_meetings_post_type', 0 );

}

/**
 * Add Custom Taxonomy for Organization
 *
 * @since 1.0.8
 */
if ( ! function_exists( 'anp_organization_taxonomy' ) ) {

	// Register Custom Taxonomy
	function anp_organization_taxonomy() {

		$slug = apply_filters( 'anp_organization_taxonomy', 'organization' );

		$labels = array(
			'name'                       => _x( 'Organizational Group', 'Taxonomy General Name', 'meetings' ),
			'singular_name'              => _x( 'Organizational Group', 'Taxonomy Singular Name', 'meetings' ),
			'menu_name'                  => __( 'Organizations', 'meetings' ),
			'all_items'                  => __( 'All Organizational Groups', 'meetings' ),
			'parent_item'                => __( 'Parent Organizational Group', 'meetings' ),
			'parent_item_colon'          => __( 'Parent Organizational Group:', 'meetings' ),
			'new_item_name'              => __( 'New Organizational Group Name', 'meetings' ),
			'add_new_item'               => __( 'Add New Organizational Group', 'meetings' ),
			'edit_item'                  => __( 'Edit Organizational Group', 'meetings' ),
			'update_item'                => __( 'Update Organizational Group', 'meetings' ),
			'view_item'                  => __( 'View Organizational Group', 'meetings' ),
			'separate_items_with_commas' => __( 'Separate organizational groups with commas', 'meetings' ),
			'add_or_remove_items'        => __( 'Add or remove organizational groups', 'meetings' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'meetings' ),
			'popular_items'              => __( 'Popular Organizational Groups', 'meetings' ),
			'search_items'               => __( 'Search Organizational Groups', 'meetings' ),
			'not_found'                  => __( 'Not Found', 'meetings' ),
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
	add_action( 'init', 'anp_organization_taxonomy', 0 );

}

/**
 * Add Custom Taxonomy
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'anp_meetings_type' ) ) {

	// Register Custom Taxonomy
	function anp_meetings_type() {

		$slug = apply_filters( 'anp_meeting_type_taxonomy', 'meeting_type' );

		$labels = array(
			'name'                       => _x( 'Meeting Type', 'Taxonomy General Name', 'meetings' ),
			'singular_name'              => _x( 'Meeting Type', 'Taxonomy Singular Name', 'meetings' ),
			'menu_name'                  => __( 'Types', 'meetings' ),
			'all_items'                  => __( 'All Meeting Types', 'meetings' ),
			'parent_item'                => __( 'Parent Meeting Type', 'meetings' ),
			'parent_item_colon'          => __( 'Parent Meeting Type:', 'meetings' ),
			'new_item_name'              => __( 'New Meeting Type Name', 'meetings' ),
			'add_new_item'               => __( 'Add New Meeting Type', 'meetings' ),
			'edit_item'                  => __( 'Edit Meeting Type', 'meetings' ),
			'update_item'                => __( 'Update Meeting Type', 'meetings' ),
			'view_item'                  => __( 'View Meeting Type', 'meetings' ),
			'separate_items_with_commas' => __( 'Separate meeting types with commas', 'meetings' ),
			'add_or_remove_items'        => __( 'Add or remove meeting types', 'meetings' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'meetings' ),
			'popular_items'              => __( 'Popular Meeting Types', 'meetings' ),
			'search_items'               => __( 'Search Meeting Types', 'meetings' ),
			'not_found'                  => __( 'Not Found', 'meetings' ),
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
	add_action( 'init', 'anp_meetings_type', 0 );

}

/**
 * Add Custom Taxonomy
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'anp_meetings_tag' ) ) {

	// Register Custom Taxonomy
	function anp_meetings_tag() {

		$slug = apply_filters( 'anp_meetings_tag_taxonomy', 'meeting_tag' );

		$labels = array(
			'name'                       => _x( 'Meeting Tags', 'Taxonomy General Name', 'meetings' ),
			'singular_name'              => _x( 'Meeting Tag', 'Taxonomy Singular Name', 'meetings' ),
			'menu_name'                  => __( 'Tags', 'meetings' ),
			'all_items'                  => __( 'All Tags', 'meetings' ),
			'parent_item'                => __( 'Parent Tag', 'meetings' ),
			'parent_item_colon'          => __( 'Parent Tag:', 'meetings' ),
			'new_item_name'              => __( 'New Tag Name', 'meetings' ),
			'add_new_item'               => __( 'Add New Tag', 'meetings' ),
			'edit_item'                  => __( 'Edit Tag', 'meetings' ),
			'update_item'                => __( 'Update Tag', 'meetings' ),
			'view_item'                  => __( 'View Tag', 'meetings' ),
			'separate_items_with_commas' => __( 'Separate tags with commas', 'meetings' ),
			'add_or_remove_items'        => __( 'Add or remove tags', 'meetings' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'meetings' ),
			'popular_items'              => __( 'Popular Tags', 'meetings' ),
			'search_items'               => __( 'Search Tags', 'meetings' ),
			'not_found'                  => __( 'Not Found', 'meetings' ),
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
	add_action( 'init', 'anp_meetings_tag', 0 );

}


/**
 * Add Custom Admin Columns
 *
 * @since 1.0.0
 */
if(! function_exists( 'anp_meetings_columns' ) ) {

	function anp_meetings_columns( $columns ) {
	    $columns['meeting_date'] = 'Date';
	    unset( $columns['comments'] );
	    unset( $columns['glocal_post_thumb'] );
	    unset( $columns['date'] );
	    unset( $columns['author'] );
	    return $columns;
	}

	add_filter( 'manage_edit-meeting_columns', 'anp_meetings_columns' ) ;

}

if(! function_exists( '' ) ) {
	function anp_meetings_populate_columns( $column ) {
	    if ( 'meeting_date' == $column ) {
	        $meeting_date = esc_html( get_post_meta( get_the_ID(), 'meeting_date', true ) );
	        echo $meeting_date;
	    }
	}

	add_action( 'manage_posts_custom_column', 'anp_meetings_populate_columns' );

}

/**
 * Hide custom fields metabox
 *
 * @since 1.0.3
 *
 * @link https://codex.wordpress.org/Function_Reference/remove_meta_box
 */
function meetings_remove_custom_fields_metabox() {
	remove_meta_box( 'postcustom', 'meeting', 'side' );
	remove_meta_box( 'postcustom', 'agenda', 'side' );
	remove_meta_box( 'postcustom', 'summary', 'side' );
	remove_meta_box( 'postcustom', 'proposal', 'side' );
}
add_action( 'admin_menu', 'meetings_remove_custom_fields_metabox' );
