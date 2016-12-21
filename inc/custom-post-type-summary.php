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
			'name'                => _x( 'Summaries', 'Post Type General Name', 'meetings' ),
			'singular_name'       => _x( 'Summary', 'Post Type Singular Name', 'meetings' ),
			'menu_name'           => __( 'Summaries', 'meetings' ),
			'name_admin_bar'      => __( 'Summaries', 'meetings' ),
			'parent_item_colon'   => __( 'Parent Summary:', 'meetings' ),
			'all_items'           => __( 'All Summaries', 'meetings' ),
			'add_new_item'        => __( 'Add New Summary', 'meetings' ),
			'add_new'             => __( 'Add New Summary', 'meetings' ),
			'new_item'            => __( 'New Summary', 'meetings' ),
			'edit_item'           => __( 'Edit Summary', 'meetings' ),
			'update_item'         => __( 'Update Summary', 'meetings' ),
			'view_item'           => __( 'View Summary', 'meetings' ),
			'search_items'        => __( 'Search Summary', 'meetings' ),
			'not_found'           => __( 'Not found', 'meetings' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'meetings' ),
		);
		$rewrite = array(
			'slug'                => $slug,
			'with_front'          => false,
			'pages'               => true,
			'feeds'               => true,
		);
		$default_config = array(
			'label'               => __( 'Summary', 'meetings' ),
			'labels'              => $labels,
			'supports'            => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'custom-fields',
				'wpcom-markdown',
				'revisions',
				'attributes'
			 ),
			'taxonomies'          => array(
				'organization',
				'meeting_type',
				'event-tag',
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
			'capability_type'     => array( 'post', 'event' ),
			'map_meta_cap'		  => true,
			'capabilities' => array(
				'publish_posts' => 'publish_summaries',
				'edit_posts' => 'edit_summaries',
				'edit_others_posts' => 'edit_others_summaries',
				'delete_posts' => 'delete_summaries',
				'delete_others_posts' => 'delete_others_summaries',
				'read_private_posts' => 'read_private_summaries',
				'edit_post' => 'edit_summary',
				'delete_post' => 'delete_summary',
				'read_post' => 'read_summary',
			),
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
            'edit.php?post_type=event',
            __('Summaries', 'meetings'),
            __('Summaries', 'meetings'),
            'edit_events',
            'edit.php?post_type=summary'
        );

        add_submenu_page(
            'edit.php?post_type=event',
            __('New Summary', 'meetings'),
            __('New Summary', 'meetings'),
            'edit_events',
            'post-new.php?post_type=summary'
        );

    }

    add_action( 'admin_menu', 'anp_summary_add_to_menu' );

}

?>
