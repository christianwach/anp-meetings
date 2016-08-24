<?php

/**
 * ANP Meetings Agenda Post Type
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
if ( ! function_exists( 'anp_agenda_post_type' ) ) {

    // Register Custom Post Type
    function anp_agenda_post_type() {

        $slug = apply_filters( 'anp_agenda_post_type', 'agenda' );

        $labels = array(
            'name'                => _x( 'Agendas', 'Post Type General Name', 'meeting' ),
            'singular_name'       => _x( 'Agenda', 'Post Type Singular Name', 'meeting' ),
            'menu_name'           => __( 'Agendas', 'meeting' ),
            'name_admin_bar'      => __( 'Agenda', 'meeting' ),
            'parent_item_colon'   => __( 'Parent Agenda:', 'meeting' ),
            'all_items'           => __( 'All Agenda', 'meeting' ),
            'add_new_item'        => __( 'Add New Agenda', 'meeting' ),
            'add_new'             => __( 'Add New Agenda', 'meeting' ),
            'new_item'            => __( 'New Agenda', 'meeting' ),
            'edit_item'           => __( 'Edit Agenda', 'meeting' ),
            'update_item'         => __( 'Update Agenda', 'meeting' ),
            'view_item'           => __( 'View Agenda', 'meeting' ),
            'search_items'        => __( 'Search Agenda', 'meeting' ),
            'not_found'           => __( 'Not found', 'meeting' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'meeting' ),
        );
        $rewrite = array(
            'slug'                => $slug,
            'with_front'          => false,
            'pages'               => true,
            'feeds'               => true,
        );
        $default_config = array(
            'label'               => __( 'Agenda', 'meeting' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'comments', 'custom-fields', 'wpcom-markdown', 'revisions' ),
            'taxonomies'          => array( 'meeting_tag' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'menu_position'       => 5,
            'menu_icon'             => 'dashicons-editor-ol',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => 'agendas',
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
				'publish_posts' => 'publish_agendas',
				'edit_posts' => 'edit_agendas',
				'edit_others_posts' => 'edit_others_agendas',
				'delete_posts' => 'delete_agendas',
				'delete_others_posts' => 'delete_others_agendas',
				'read_private_posts' => 'read_private_agendas',
				'edit_post' => 'edit_agenda',
				'delete_post' => 'delete_agenda',
				'read_post' => 'read_agenda',
			),
        );
        // Allow customization of the default post type configuration via filter.
        $config = apply_filters( 'agenda_post_type_defaults', $default_config, $slug );

        register_post_type( $slug, $config );
    }
    add_action( 'init', 'anp_agenda_post_type', 0 );

}

/**
 * Move Admin Menus
 * Display admin as submenu under Meetings
 *
 * @uses `add_submenu_page` with $cap set to `edit_agendas`
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'anp_agenda_add_to_menu' ) ) {

    function anp_agenda_add_to_menu() {

        add_submenu_page(
            'edit.php?post_type=meeting',
            __('All Agendas', 'meeting'),
            __('All Agendas', 'meeting'),
            'edit_meetings',
            'edit.php?post_type=agenda'
        );

        add_submenu_page(
            'edit.php?post_type=meeting',
            __('New Agenda', 'meeting'),
            __('New Agenda', 'meeting'),
            'edit_meetings',
            'post-new.php?post_type=agenda'
        );

    }

    add_action('admin_menu', 'anp_agenda_add_to_menu');

}

?>
