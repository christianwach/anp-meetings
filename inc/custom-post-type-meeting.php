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

		$slug = 'meeting';

		$labels = array(
			'name'                => _x( 'Meetings', 'Post Type General Name', 'anp_meeting' ),
			'singular_name'       => _x( 'Meeting', 'Post Type Singular Name', 'anp_meeting' ),
			'menu_name'           => __( 'Meetings', 'anp_meeting' ),
			'name_admin_bar'      => __( 'Meetings', 'anp_meeting' ),
			'parent_item_colon'   => __( 'Parent Meeting:', 'anp_meeting' ),
			'all_items'           => __( 'All Meetings', 'anp_meeting' ),
			'add_new_item'        => __( 'Add New Meeting', 'anp_meeting' ),
			'add_new'             => __( 'Add New Meeting', 'anp_meeting' ),
			'new_item'            => __( 'New Meeting', 'anp_meeting' ),
			'edit_item'           => __( 'Edit Meeting', 'anp_meeting' ),
			'update_item'         => __( 'Update Meeting', 'anp_meeting' ),
			'view_item'           => __( 'View Meeting', 'anp_meeting' ),
			'search_items'        => __( 'Search Meeting', 'anp_meeting' ),
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
			'label'               => __( 'Meetings', 'anp_meeting' ),
			'description'         => __( 'Custom post type for meeting agendas and notes', 'anp_meeting' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'comments', 'custom-fields', 'wpcom-markdown', 'revisions' ),
			'taxonomies'          => array( 'meeting_type', 'meeting_tag' ),
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
			'query_var'           => 'meeting',
			'rewrite'             => $rewrite,
			'capability_type'     => array( 'post', 'meeting' ),
			'map_meta_cap'			=> true,
			'capabilities' => array(
				'publish_posts' => 'publish_meetings',
				'edit_posts' => 'edit_meetings',
				'edit_others_posts' => 'edit_others_meetings',
				'delete_posts' => 'delete_meetings',
				'delete_others_posts' => 'delete_others_meetings',
				'read_private_posts' => 'read_private_meetings',
				'edit_post' => 'edit_meeting',
				'delete_post' => 'delete_meeting',
				'read_post' => 'read_meeting',
			),
		);

		// Allow customization of the default post type configuration via filter.
		$config = apply_filters( 'meeting_post_type_defaults', $default_config, $slug );

		register_post_type( $slug, $config );

	}

	// Hook into the 'init' action
	add_action( 'init', 'anp_meetings_post_type', 0 );

}

/**
 * Add Custom Taxonomy
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'anp_meetings_type' ) ) {

	// Register Custom Taxonomy
	function anp_meetings_type() {

		$labels = array(
			'name'                       => _x( 'Meeting Type', 'Taxonomy General Name', 'anp_meeting' ),
			'singular_name'              => _x( 'Meeting Type', 'Taxonomy Singular Name', 'anp_meeting' ),
			'menu_name'                  => __( 'Meeting Types', 'anp_meeting' ),
			'all_items'                  => __( 'All Meeting Types', 'anp_meeting' ),
			'parent_item'                => __( 'Parent Meeting Type', 'anp_meeting' ),
			'parent_item_colon'          => __( 'Parent Meeting Type:', 'anp_meeting' ),
			'new_item_name'              => __( 'New Meeting Type Name', 'anp_meeting' ),
			'add_new_item'               => __( 'Add New Meeting Type', 'anp_meeting' ),
			'edit_item'                  => __( 'Edit Meeting Type', 'anp_meeting' ),
			'update_item'                => __( 'Update Meeting Type', 'anp_meeting' ),
			'view_item'                  => __( 'View Meeting Type', 'anp_meeting' ),
			'separate_items_with_commas' => __( 'Separate meeting types with commas', 'anp_meeting' ),
			'add_or_remove_items'        => __( 'Add or remove meeting types', 'anp_meeting' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'anp_meeting' ),
			'popular_items'              => __( 'Popular Meeting Types', 'anp_meeting' ),
			'search_items'               => __( 'Search Meeting Types', 'anp_meeting' ),
			'not_found'                  => __( 'Not Found', 'anp_meeting' ),
		);
		$rewrite = array(
			'slug'                       => 'meeting_type',
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
			'query_var'                  => 'meeting_type',
			'rewrite'                    => $rewrite,
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

		$labels = array(
			'name'                       => _x( 'Meeting Tags', 'Taxonomy General Name', 'anp_meeting' ),
			'singular_name'              => _x( 'Meeting Tag', 'Taxonomy Singular Name', 'anp_meeting' ),
			'menu_name'                  => __( 'Meeting Tags', 'anp_meeting' ),
			'all_items'                  => __( 'All Tags', 'anp_meeting' ),
			'parent_item'                => __( 'Parent Tag', 'anp_meeting' ),
			'parent_item_colon'          => __( 'Parent Tag:', 'anp_meeting' ),
			'new_item_name'              => __( 'New Tag Name', 'anp_meeting' ),
			'add_new_item'               => __( 'Add New Tag', 'anp_meeting' ),
			'edit_item'                  => __( 'Edit Tag', 'anp_meeting' ),
			'update_item'                => __( 'Update Tag', 'anp_meeting' ),
			'view_item'                  => __( 'View Tag', 'anp_meeting' ),
			'separate_items_with_commas' => __( 'Separate tags with commas', 'anp_meeting' ),
			'add_or_remove_items'        => __( 'Add or remove tags', 'anp_meeting' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'anp_meeting' ),
			'popular_items'              => __( 'Popular Tags', 'anp_meeting' ),
			'search_items'               => __( 'Search Tags', 'anp_meeting' ),
			'not_found'                  => __( 'Not Found', 'anp_meeting' ),
		);
		$rewrite = array(
			'slug'                       => 'meeting_tag',
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
			'query_var'                  => 'meeting_tag',
			'rewrite'                    => $rewrite,
		);
		register_taxonomy( 'meeting_tag', array( 'meeting' ), $args );

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

?>
