<?php

/**
 * ANP Meetings Proposals Post Type
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
if ( ! function_exists( 'anp_proposals_post_type' ) ) {

    // Register Custom Post Type
    function anp_proposals_post_type() {

        $slug = apply_filters( 'anp_proposal_post_type', 'proposal' );

        $labels = array(
            'name'                => _x( 'Proposals', 'Post Type General Name', 'anp-meeting' ),
            'singular_name'       => _x( 'Proposal', 'Post Type Singular Name', 'anp-meeting' ),
            'menu_name'           => __( 'Proposals', 'anp-meeting' ),
            'name_admin_bar'      => __( 'Proposals', 'anp-meeting' ),
            'parent_item_colon'   => __( 'Parent Proposal:', 'anp-meeting' ),
            'all_items'           => __( 'All Proposals', 'anp-meeting' ),
            'add_new_item'        => __( 'Add New Proposal', 'anp-meeting' ),
            'add_new'             => __( 'Add Proposal', 'anp-meeting' ),
            'new_item'            => __( 'New Proposal', 'anp-meeting' ),
            'edit_item'           => __( 'Edit Proposal', 'anp-meeting' ),
            'update_item'         => __( 'Update Proposal', 'anp-meeting' ),
            'view_item'           => __( 'View Proposal', 'anp-meeting' ),
            'search_items'        => __( 'Search Proposal', 'anp-meeting' ),
            'not_found'           => __( 'Not found', 'anp-meeting' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'anp-meeting' ),
        );
        $rewrite = array(
            'slug'                => $slug,
            'with_front'          => false,
            'pages'               => true,
            'feeds'               => true,
        );
        $default_config = array(
            'label'               => __( 'Proposal', 'anp-meeting' ),
            'description'         => __( '', 'anp-meeting' ),
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
            'capability_type'     => array( 'post', 'meeting' ),
			'map_meta_cap'		  => true,
			'capabilities' => array(
				'publish_posts' => 'publish_proposals',
				'edit_posts' => 'edit_proposals',
				'edit_others_posts' => 'edit_others_proposals',
				'delete_posts' => 'delete_proposals',
				'delete_others_posts' => 'delete_others_proposals',
				'read_private_posts' => 'read_private_proposals',
				'edit_post' => 'edit_proposal',
				'delete_post' => 'delete_proposal',
				'read_post' => 'read_proposal',
			),
        );
        // Allow customization of the default post type configuration via filter.
        $config = apply_filters( 'proposal_post_type_defaults', $default_config, $slug );

        register_post_type( $slug, $config );

    }
    add_action( 'init', 'anp_proposals_post_type', 0 );

}

/**
 * Add Custom Taxonomy
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'anp_proposals_status_taxonomy' ) ) {

    // Register Custom Taxonomy
    function anp_proposals_status_taxonomy() {

        $slug = apply_filters( 'anp_proposal_status_taxonomy', 'proposal_status' );

        $labels = array(
            'name'                       => _x( 'Proposal Statuses', 'Taxonomy General Name', 'anp-meeting' ),
            'singular_name'              => _x( 'Proposal Status', 'Taxonomy Singular Name', 'anp-meeting' ),
            'menu_name'                  => __( 'Statuses', 'anp-meeting' ),
            'all_items'                  => __( 'All Proposal Statuses', 'anp-meeting' ),
            'parent_item'                => __( 'Parent Proposal Status', 'anp-meeting' ),
            'parent_item_colon'          => __( 'Parent Proposal Status:', 'anp-meeting' ),
            'new_item_name'              => __( 'New Proposal Status Name', 'anp-meeting' ),
            'add_new_item'               => __( 'Add New Proposal Status', 'anp-meeting' ),
            'edit_item'                  => __( 'Edit Proposal Status', 'anp-meeting' ),
            'update_item'                => __( 'Update Proposal Status', 'anp-meeting' ),
            'view_item'                  => __( 'View Proposal Status', 'anp-meeting' ),
            'separate_items_with_commas' => __( 'Separate proposal status with commas', 'anp-meeting' ),
            'add_or_remove_items'        => __( 'Add or remove proposal status', 'anp-meeting' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'anp-meeting' ),
            'popular_items'              => __( 'Popular Proposal Statuses', 'anp-meeting' ),
            'search_items'               => __( 'Search Proposal Status', 'anp-meeting' ),
            'not_found'                  => __( 'Not Found', 'anp-meeting' ),
        );
        $rewrite = array(
            'slug'                       => 'proposal-status',
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
        );
        register_taxonomy( 'proposal_status', array( 'proposal' ), $args );

    }
    add_action( 'init', 'anp_proposals_status_taxonomy', 0 );

}

/**
 * Move Admin Menus
 * Display admin as submenu under Meetings
 *
 * @uses `add_submenu_page` with $cap set to `edit_proposals`
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'anp_proposals_add_to_menu' ) ) {

    function anp_proposals_add_to_menu() {

        add_submenu_page(
            'edit.php?post_type=meeting',
            __('All Proposals', 'anp-meeting'),
            __('All Proposals', 'anp-meeting'),
            'edit_meetings',
            'edit.php?post_type=proposal'
        );

        add_submenu_page(
            'edit.php?post_type=meeting',
            __('New Proposal', 'anp-meeting'),
            __('New Proposal', 'anp-meeting'),
            'edit_meetings',
            'post-new.php?post_type=proposal'
        );

        add_submenu_page(
            'edit.php?post_type=meeting',
            __('Proposal Statuses', 'anp-meeting'),
            __('Proposal Statuses', 'anp-meeting'),
            'edit_meetings',
            'edit-tags.php?taxonomy=proposal_status&post_type=proposal'
        );

    }

    add_action('admin_menu', 'anp_proposals_add_to_menu');

}

function anp_remove_status_meta() {
    remove_meta_box( 'proposal_statusdiv' , 'proposal' , 'side' );
}

add_action( 'admin_menu', 'anp_remove_status_meta' );


?>
