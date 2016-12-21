<?php

/**
 * ANP Meetings Init
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_Meetings
 */

/*
Plugin Name: Activist Network Meetings
Plugin URI: https://plan.glocal.coop/projects/anp-meetings/
Description: Creates custom post types for Meetings with custom fields and custom taxonomies that can be used to store and display meeting notes/minutes, agendas, proposals and summaries.
Author: Pea, Glocal
Author URI: http://glocal.coop
Version: 1.0.10
License: GPLv3
Text Domain: meetings
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}


/* ---------------------------------- *
 * Constants
 * ---------------------------------- */

if ( !defined( 'ANP_MEETINGS_PLUGIN_DIR' ) ) {
    define( 'ANP_MEETINGS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( !defined( 'ANP_MEETINGS_PLUGIN_URL' ) ) {
    define( 'ANP_MEETINGS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/* ---------------------------------- *
 * Required Files
 * ---------------------------------- */

include_once( ANP_MEETINGS_PLUGIN_DIR . 'libs/cmb2/init.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'libs/posts-to-posts/posts-to-posts.php' );

include_once( ANP_MEETINGS_PLUGIN_DIR . 'admin/enqueue-scripts.php' );

include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-agenda.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-summary.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-proposal.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-fields.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/post-type-connections.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/pre-get-filters.php' );

include_once( ANP_MEETINGS_PLUGIN_DIR . 'public/content-filters.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'public/render-functions.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'public/search-filters.php' );


/**
 * Add Custom Capabilities
 *
 * @since 0.1.11
 *
 * @uses get_role()
 * @uses has_cap()
 * @uses add_cap()
 *
 * @return void
 *
 */
function anp_meetings_add_capabilities() {
    global $wp_roles;
    $roles = $wp_roles->roles;
    $form_role = 'publish_meetings';

    foreach( $roles as $role_name => $display_name ) {
      $role = $wp_roles->get_role( $role_name );
      if ( $role->has_cap( 'publish_posts' ) ) {
        $role->add_cap( $form_role );
      }
    }
}
add_action( 'anp_meetings_activate', 'anp_meetings_add_capabilities' );

/**
 * [anp_meetings_add_category description]
 */
function anp_meetings_add_category() {
	wp_insert_term( __( 'Meeting', 'meetings' ), 'event-category' );
	if( !term_exists( 'meeting', 'event-category' ) ) {
		wp_insert_term(
		  __( 'Meeting', 'meetings' ),
		  'event-category',
		  array(
			'slug'            => 'meeting',
		  )
		);
	}
}
add_action( 'anp_meetings_activate', 'anp_meetings_add_category' );

/**
 * Add Activation Hook
 *
 * @since 0.1.11
 *
 * @link https://codex.wordpress.org/Function_Reference/register_activation_hook#Process_Flow
 */
function anp_meetings_activate() {
    anp_meetings_add_capabilities();
    anp_meetings_add_category();
}
register_activation_hook( __FILE__, 'anp_meetings_activate' );
