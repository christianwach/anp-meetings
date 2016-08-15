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

        $slug = 'proposal';

        $labels = array(
            'name'                => _x( 'Proposals', 'Post Type General Name', 'anp_meeting' ),
            'singular_name'       => _x( 'Proposal', 'Post Type Singular Name', 'anp_meeting' ),
            'menu_name'           => __( 'Proposals', 'anp_meeting' ),
            'name_admin_bar'      => __( 'Proposals', 'anp_meeting' ),
            'parent_item_colon'   => __( 'Parent Proposal:', 'anp_meeting' ),
            'all_items'           => __( 'All Proposals', 'anp_meeting' ),
            'add_new_item'        => __( 'Add New Proposal', 'anp_meeting' ),
            'add_new'             => __( 'Add Proposal', 'anp_meeting' ),
            'new_item'            => __( 'New Proposal', 'anp_meeting' ),
            'edit_item'           => __( 'Edit Proposal', 'anp_meeting' ),
            'update_item'         => __( 'Update Proposal', 'anp_meeting' ),
            'view_item'           => __( 'View Proposal', 'anp_meeting' ),
            'search_items'        => __( 'Search Proposal', 'anp_meeting' ),
            'not_found'           => __( 'Not found', 'anp_meeting' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'anp_meeting' ),
        );
        $rewrite = array(
            'slug'                => $slug,
            'with_front'          => false,
            'pages'               => true,
            'feeds'               => true,
        );
        $default_config = array(
            'label'               => __( 'Proposal', 'anp_meeting' ),
            'description'         => __( '', 'anp_meeting' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'comments', 'custom-fields', 'wpcom-markdown', 'revisions' ),
            'taxonomies'          => array(
                'proposal_status',
                'meeting_tag' ),
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
            'query_var'           => 'proposal',
            'rewrite'             => $rewrite,
            'capability_type'     => array( 'post', 'meeting' ),
			'map_meta_cap'			=> true,
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

        $labels = array(
            'name'                       => _x( 'Proposal Statuses', 'Taxonomy General Name', 'anp_meeting' ),
            'singular_name'              => _x( 'Proposal Status', 'Taxonomy Singular Name', 'anp_meeting' ),
            'menu_name'                  => __( 'Proposal Statuses', 'anp_meeting' ),
            'all_items'                  => __( 'All Proposal Statuses', 'anp_meeting' ),
            'parent_item'                => __( 'Parent Proposal Status', 'anp_meeting' ),
            'parent_item_colon'          => __( 'Parent Proposal Status:', 'anp_meeting' ),
            'new_item_name'              => __( 'New Proposal Status Name', 'anp_meeting' ),
            'add_new_item'               => __( 'Add New Proposal Status', 'anp_meeting' ),
            'edit_item'                  => __( 'Edit Proposal Status', 'anp_meeting' ),
            'update_item'                => __( 'Update Proposal Status', 'anp_meeting' ),
            'view_item'                  => __( 'View Proposal Status', 'anp_meeting' ),
            'separate_items_with_commas' => __( 'Separate proposal status with commas', 'anp_meeting' ),
            'add_or_remove_items'        => __( 'Add or remove proposal status', 'anp_meeting' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'anp_meeting' ),
            'popular_items'              => __( 'Popular Proposal Statuses', 'anp_meeting' ),
            'search_items'               => __( 'Search Proposal Status', 'anp_meeting' ),
            'not_found'                  => __( 'Not Found', 'anp_meeting' ),
        );
        $rewrite = array(
            'slug'                       => 'proposal-status',
            'with_front'                 => true,
            'hierarchical'               => false,
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'query_var'                  => 'proposal_status',
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
            __('All Proposals', 'anp_meeting'),
            __('All Proposals', 'anp_meeting'),
            'edit_meetings',
            'edit.php?post_type=proposal'
        );

        add_submenu_page(
            'edit.php?post_type=meeting',
            __('New Proposal', 'anp_meeting'),
            __('New Proposal', 'anp_meeting'),
            'edit_meetings',
            'post-new.php?post_type=proposal'
        );

        add_submenu_page(
            'edit.php?post_type=meeting',
            __('Proposal Statuses', 'anp_meeting'),
            __('Proposal Statuses', 'anp_meeting'),
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
