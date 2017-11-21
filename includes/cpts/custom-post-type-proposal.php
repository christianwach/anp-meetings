<?php

/**
 * WordPress Meetings Proposals Post Type
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
if ( ! function_exists( 'wordpress_proposals_post_type' ) ) {

    // Register Custom Post Type
    function wordpress_proposals_post_type() {

        $slug = apply_filters( 'wordpress_proposal_post_type', 'proposal' );

        $labels = array(
            'name'                => _x( 'Proposals', 'Post Type General Name', 'wordpress-meetings' ),
            'singular_name'       => _x( 'Proposal', 'Post Type Singular Name', 'wordpress-meetings' ),
            'menu_name'           => __( 'Proposals', 'wordpress-meetings' ),
            'name_admin_bar'      => __( 'Proposals', 'wordpress-meetings' ),
            'parent_item_colon'   => __( 'Parent Proposal:', 'wordpress-meetings' ),
            'all_items'           => __( 'All Proposals', 'wordpress-meetings' ),
            'add_new_item'        => __( 'Add New Proposal', 'wordpress-meetings' ),
            'add_new'             => __( 'Add Proposal', 'wordpress-meetings' ),
            'new_item'            => __( 'New Proposal', 'wordpress-meetings' ),
            'edit_item'           => __( 'Edit Proposal', 'wordpress-meetings' ),
            'update_item'         => __( 'Update Proposal', 'wordpress-meetings' ),
            'view_item'           => __( 'View Proposal', 'wordpress-meetings' ),
            'search_items'        => __( 'Search Proposal', 'wordpress-meetings' ),
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
            'label'               => __( 'Proposal', 'wordpress-meetings' ),
            'description'         => __( '', 'wordpress-meetings' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'comments', 'custom-fields', 'wpcom-markdown', 'revisions' ),
            'taxonomies'          => array(
                'organization',
                'meeting_tag',
                'proposal_status',
            ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'menu_position'       => 30,
            'menu_icon'             => 'dashicons-lightbulb',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => 'proposals',
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'query_var'           => $slug,
            'rewrite'             => $rewrite,
            'show_in_rest'        => true,
	  		'rest_base'           => $slug,
	  		'rest_controller_class' => 'WP_REST_Posts_Controller',
            'capability_type'	  => 'meeting',
			'capabilities'		  => apply_filters( 'wordpress_meetings_proposal_capabilities', $capabilities ),
			'map_meta_cap' 		  => true
        );
        // Allow customization of the default post type configuration via filter.
        $config = apply_filters( 'proposal_post_type_defaults', $default_config, $slug );

        register_post_type( $slug, $config );

    }
    add_action( 'init', 'wordpress_proposals_post_type', 0 );

}



/**
 * Add Custom Taxonomy.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wordpress_proposals_status_taxonomy' ) ) {

    // Register Custom Taxonomy
    function wordpress_proposals_status_taxonomy() {

        $slug = apply_filters( 'wordpress_proposal_status_taxonomy', 'proposal_status' );

        $labels = array(
            'name'                       => _x( 'Proposal Statuses', 'Taxonomy General Name', 'wordpress-meetings' ),
            'singular_name'              => _x( 'Proposal Status', 'Taxonomy Singular Name', 'wordpress-meetings' ),
            'menu_name'                  => __( 'Statuses', 'wordpress-meetings' ),
            'all_items'                  => __( 'All Proposal Statuses', 'wordpress-meetings' ),
            'parent_item'                => __( 'Parent Proposal Status', 'wordpress-meetings' ),
            'parent_item_colon'          => __( 'Parent Proposal Status:', 'wordpress-meetings' ),
            'new_item_name'              => __( 'New Proposal Status Name', 'wordpress-meetings' ),
            'add_new_item'               => __( 'Add New Proposal Status', 'wordpress-meetings' ),
            'edit_item'                  => __( 'Edit Proposal Status', 'wordpress-meetings' ),
            'update_item'                => __( 'Update Proposal Status', 'wordpress-meetings' ),
            'view_item'                  => __( 'View Proposal Status', 'wordpress-meetings' ),
            'separate_items_with_commas' => __( 'Separate proposal status with commas', 'wordpress-meetings' ),
            'add_or_remove_items'        => __( 'Add or remove proposal status', 'wordpress-meetings' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'wordpress-meetings' ),
            'popular_items'              => __( 'Popular Proposal Statuses', 'wordpress-meetings' ),
            'search_items'               => __( 'Search Proposal Status', 'wordpress-meetings' ),
            'not_found'                  => __( 'Not Found', 'wordpress-meetings' ),
        );

        $rewrite = array(
            'slug'                       => 'proposal-status',
            'with_front'                 => true,
        );

        $capabilities = array(
			'manage_terms'               => 'manage_categories',
			'edit_terms'                 => 'manage_categories',
			'delete_terms'               => 'manage_categories',
			'assign_terms'               => 'edit_meetings',
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
            'capabilities'          	 => apply_filters( 'meetings_proposal_status_tax_capabilities', $capabilities ),
        );
        register_taxonomy( 'proposal_status', array( 'proposal' ), $args );

    }
    add_action( 'init', 'wordpress_proposals_status_taxonomy', 0 );

}



/**
 * Move Admin Menus.
 *
 * Display admin as submenu under Meetings.
 *
 * @uses `add_submenu_page` with $cap set to `edit_proposals`
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wordpress_proposals_add_to_menu' ) ) {

    function wordpress_proposals_add_to_menu() {

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

        add_submenu_page(
            'edit.php?post_type=meeting',
            __( 'Proposal Statuses', 'wordpress-meetings' ),
            __( 'Proposal Statuses', 'wordpress-meetings' ),
            apply_filters( 'wordpress_meetings_proposal_status_menu_capability', 'manage_categories' ),
            'edit-tags.php?taxonomy=proposal_status&post_type=proposal'
        );

    }
    add_action( 'admin_menu', 'wordpress_proposals_add_to_menu' );

}



function wordpress_remove_status_meta() {
    remove_meta_box( 'proposal_statusdiv' , 'proposal' , 'side' );
}
add_action( 'admin_menu', 'wordpress_remove_status_meta' );


