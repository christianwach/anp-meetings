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
Version: 1.0.9.1
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
if( !class_exists( 'CMB2' ) ) {
  include_once( ANP_MEETINGS_PLUGIN_DIR . 'libs/cmb2/init.php' );
}

if( !function_exists( '_p2p_load' ) ) {
  include_once( ANP_MEETINGS_PLUGIN_DIR . 'libs/posts-to-posts/posts-to-posts.php' );
}

include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-meeting.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-agenda.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-summary.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-proposal.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-fields.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/post-type-connections.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-content-filters.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-pre-get-filters.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/render-functions.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-search-filters.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-rewrite.php' );

/**
 * Plugin Capabilities
 *
 * @since 1.0.9
 */
function anp_meetings_capabilities() {
    $capabilities = array(
        'publish_posts'         => 'publish_meetings',
        'edit_posts'            => 'edit_meetings',
        'edit_others_posts'     => 'edit_others_meetings',
        'delete_posts'          => 'delete_meetings',
        'delete_others_posts'   => 'delete_others_meetings',
        'read_private_posts'    => 'read_private_meetings',
        'edit_post'             => 'edit_meeting',
        'delete_post'           => 'delete_meeting',
        'read_post'             => 'read_meeting',
    );
    return apply_filters( 'meetings_globabl_capabilities', $capabilities );
}

/**
 * Add Custom Capabilities
 *
 * @since 0.1.9
 *
 * @uses get_role()
 * @uses has_cap()
 * @uses add_cap()
 *
 * @return void
 */
function anp_meetings_add_capabilities() {
    global $wp_roles;
    $roles = $wp_roles->roles;

    $capabilities = array(
        'edit_meeting',
        'read_meeting',
        'delete_meeting',
        'edit_meetings',
        'edit_others_meetings',
        'publish_meetings',
        'read_private_meetings',
    );

    $capabilities = apply_filters( 'anp_meetings_add_capabilities', $capabilities );

    foreach( $roles as $role_name => $display_name ) {
      $role = $wp_roles->get_role( $role_name );
      if ( $role->has_cap( 'publish_posts' ) ) {

          foreach( $capabilities as $capability ) {
              $role->add_cap( $capability );
          }

      }
    }
}
add_action( 'anp_meetings_activate', 'anp_meetings_add_capabilities' );

/**
 * Add Activation Hook
 *
 * @since 1.0.9
 *
 * @link https://codex.wordpress.org/Function_Reference/register_activation_hook#Process_Flow
 */
function anp_meetings_activate() {
    anp_organization_taxonomy();
    anp_meetings_type();
    anp_meetings_tag();
    anp_proposals_status_taxonomy();
    anp_meetings_post_type();
    anp_agenda_post_type();
    anp_proposals_post_type();
    anp_summary_post_type();
    anp_meetings_add_capabilities();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'anp_meetings_activate' );

/**
 * Add De-activation Hook
 *
 * @since 1.0.9
 *
 * @link https://codex.wordpress.org/Function_Reference/register_deactivation_hook
 */
function anp_meetings_deactivate() {
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'anp_meetings_deactivate' );
