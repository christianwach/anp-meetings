<?php

/**
 * ANP Meetings Summaries Post Type
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
if ( ! function_exists( 'anp_summary_post_type' ) ) {

	// Register Custom Post Type
	function anp_summary_post_type() {

		$slug = apply_filters( 'anp_summary_post_type', 'summary' );

		$labels = array(
			'name'                => _x( 'Summaries', 'Post Type General Name', 'meeting' ),
			'singular_name'       => _x( 'Summary', 'Post Type Singular Name', 'meeting' ),
			'menu_name'           => __( 'Summaries', 'meeting' ),
			'name_admin_bar'      => __( 'Summaries', 'meeting' ),
			'parent_item_colon'   => __( 'Parent Summary:', 'meeting' ),
			'all_items'           => __( 'All Summaries', 'meeting' ),
			'add_new_item'        => __( 'Add New Summary', 'meeting' ),
			'add_new'             => __( 'Add New Summary', 'meeting' ),
			'new_item'            => __( 'New Summary', 'meeting' ),
			'edit_item'           => __( 'Edit Summary', 'meeting' ),
			'update_item'         => __( 'Update Summary', 'meeting' ),
			'view_item'           => __( 'View Summary', 'meeting' ),
			'search_items'        => __( 'Search Summary', 'meeting' ),
			'not_found'           => __( 'Not found', 'meeting' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'meeting' ),
		);

		$capabilities = anp_meetings_capabilities();

		$rewrite = array(
			'slug'                => $slug,
			'with_front'          => false,
			'pages'               => true,
			'feeds'               => true,
		);

		$default_config = array(
			'label'               => __( 'Summary', 'meeting' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'wpcom-markdown', 'revisions' ),
			'taxonomies'          => array(
				'organization',
				'meeting_tag',
			),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 30,
			'menu_icon'			  => 'dashicons-list-view',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => 'summaries',
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'query_var'           => $slug,
			'rewrite'             => $rewrite,
			'show_in_rest'        => true,
	  		'rest_base'           => $slug,
	  		'rest_controller_class' => 'WP_REST_Posts_Controller',
			'capability_type'	  => array( 'meeting' ),
			'capabilities'		  => apply_filters( 'meetings_summary_capabilities', $capabilities ),
			'map_meta_cap' 		  => true
		);
		// Allow customization of the default post type configuration via filter.
        $config = apply_filters( 'summary_post_type_defaults', $default_config, $slug );

        register_post_type( $slug, $config );

	}
	add_action( 'init', 'anp_summary_post_type', 0 );

}

/**
 * Move Admin Menus
 * Display admin as submenu under Meetings
 *
 * @uses `add_submenu_page` with $cap set to `edit_summaries`
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'anp_summary_add_to_menu' ) ) {

    function anp_summary_add_to_menu() {

        add_submenu_page(
            'edit.php?post_type=meeting',
            __('All Summaries', 'meeting'),
            __('All Summaries', 'meeting'),
            'edit_meetings',
            'edit.php?post_type=summary'
        );

        add_submenu_page(
            'edit.php?post_type=meeting',
            __('New Summary', 'meeting'),
            __('New Summary', 'meeting'),
            'edit_meetings',
            'post-new.php?post_type=summary'
        );

    }

    add_action( 'admin_menu', 'anp_summary_add_to_menu' );

}

?>
