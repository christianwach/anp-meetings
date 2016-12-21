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
		);
		register_taxonomy( 'organization', array( 'event', 'agenda', 'summary', 'proposal' ), $args );

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
			'menu_name'                  => __( 'Meeting Types', 'meetings' ),
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
		);
		register_taxonomy( 'meeting_type', array( 'event' ), $args );

	}

	// Hook into the 'init' action
	add_action( 'init', 'anp_meetings_type', 0 );

}

/**
 * Hide custom fields metabox
 *
 * @since 1.0.3
 *
 * @link https://codex.wordpress.org/Function_Reference/remove_meta_box
 */
function meetings_remove_custom_fields_metabox() {
	remove_meta_box( 'postcustom', 'agenda', 'side' );
	remove_meta_box( 'postcustom', 'summary', 'side' );
	remove_meta_box( 'postcustom', 'proposal', 'side' );
}

add_action( 'admin_menu', 'meetings_remove_custom_fields_metabox' );

/**
 * Change `event-tag` Args
 * Change display name of `event-tag` to "Tags"
 *
 * @since 1.0.9
 *
 * @uses eventorganiser_register_taxonomy_event-tag filter
 *
 * @param  array $event_tag_args
 * @return array $event_tag_args modified
 */
function anp_meetings_change_event_tag_args( $event_tag_args ) {
	return $event_tag_args['labels']['name'] = __( 'Tags', 'meetings' );
}
add_filter( 'eventorganiser_register_taxonomy_event-tag', 'anp_meetings_change_event_tag_args');
