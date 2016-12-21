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
            'name'                => _x( 'Agendas', 'Post Type General Name', 'meetings' ),
            'singular_name'       => _x( 'Agenda', 'Post Type Singular Name', 'meetings' ),
            'menu_name'           => __( 'Agendas', 'meetings' ),
            'name_admin_bar'      => __( 'Agenda', 'meetings' ),
            'parent_item_colon'   => __( 'Parent Agenda:', 'meetings' ),
            'all_items'           => __( 'All Agenda', 'meetings' ),
            'add_new_item'        => __( 'Add New Agenda', 'meetings' ),
            'add_new'             => __( 'Add New Agenda', 'meetings' ),
            'new_item'            => __( 'New Agenda', 'meetings' ),
            'edit_item'           => __( 'Edit Agenda', 'meetings' ),
            'update_item'         => __( 'Update Agenda', 'meetings' ),
            'view_item'           => __( 'View Agenda', 'meetings' ),
            'search_items'        => __( 'Search Agenda', 'meetings' ),
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
            'label'               => __( 'Agenda', 'meetings' ),
            'labels'              => $labels,
            'supports'            => array(
                'title',
                'editor',
                'comments',
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
            'capability_type'     => array( 'post', 'event' ),
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
            'edit.php?post_type=event',
            __('Agendas', 'meetings'),
            __('Agendas', 'meetings'),
            'edit_events',
            'edit.php?post_type=agenda'
        );

        add_submenu_page(
            'edit.php?post_type=event',
            __('New Agenda', 'meetings'),
            __('Add Agenda', 'meetings'),
            'edit_events',
            'post-new.php?post_type=agenda'
        );

    }

    add_action('admin_menu', 'anp_agenda_add_to_menu');

}

/**
 * Register `event-tag` Taxonomy
 * Add event-tag to meeting post types
 *
 * @since 1.0.9
 *
 * @uses register_taxonomy_for_object_type()
 *
 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy_for_object_type
 */
function anp_meeting_add_taxonomy_event_tag() {
    register_taxonomy_for_object_type( 'event-tag', 'agenda' );
    register_taxonomy_for_object_type( 'event-tag', 'summary' );
    register_taxonomy_for_object_type( 'event-tag', 'proposal' );
}
add_action( 'init', 'anp_meeting_add_taxonomy_event_tag' );

?>
