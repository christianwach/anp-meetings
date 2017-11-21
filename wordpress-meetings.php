<?php /*
--------------------------------------------------------------------------------
Plugin Name: WordPress Meetings
Plugin URI: https://github.com/christianwach/wordpress-meetings
Description: Creates custom post types for Meetings with custom fields and custom taxonomies that can be used to store and display meeting notes/minutes, agendas, proposals and summaries.
Author: Pea, Glocal, needle
Author URI: http://glocal.coop
Version: 2.0
License: GPLv3
Text Domain: wordpress-meetings
Domain Path: /languages
--------------------------------------------------------------------------------
*/




// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}



/* ---------------------------------- *
 * Constants
 * ---------------------------------- */
define( 'WORDPRESS_MEETINGS_VERSION', '2.0' );

if ( ! defined( 'WORDPRESS_MEETINGS_PLUGIN_DIR' ) ) {
    define( 'WORDPRESS_MEETINGS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'WORDPRESS_MEETINGS_PLUGIN_URL' ) ) {
    define( 'WORDPRESS_MEETINGS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}



/* ---------------------------------- *
 * Required Files
 * ---------------------------------- */

include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/required-plugins.php' );

// NB: this will load the class if spl_autoload is defined :(
if ( ! class_exists( 'Gamajo_Template_Loader' ) ) {
  include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'vendor/gamajo/template-loader/class-gamajo-template-loader.php' );
}

include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'admin/class-options.php' );

include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-meeting.php' );
include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-agenda.php' );
include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-summary.php' );
include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-proposal.php' );
include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-fields.php' );
include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/post-type-connections.php' );
include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-content-filters.php' );
include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-pre-get-filters.php' );
include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/enqueue.php' );
include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/render-functions.php' );
include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-search-filters.php' );
include_once( WORDPRESS_MEETINGS_PLUGIN_DIR . 'inc/custom-rewrite.php' );



/**
 * Plugin Capabilities.
 *
 * @since 1.0.9
 */
function wordpress_meetings_capabilities() {
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
    return apply_filters( 'meetings_global_capabilities', $capabilities );
}



/**
 * Add Custom Capabilities.
 *
 * @since 0.1.9
 *
 * @uses get_role()
 * @uses has_cap()
 * @uses add_cap()
 */
function wordpress_meetings_add_capabilities() {
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

    $capabilities = apply_filters( 'wordpress_meetings_add_capabilities', $capabilities );

    foreach( $roles as $role_name => $display_name ) {
      $role = $wp_roles->get_role( $role_name );
      if ( $role->has_cap( 'publish_posts' ) ) {

          foreach( $capabilities as $capability ) {
              $role->add_cap( $capability );
          }

      }
    }
}
add_action( 'wordpress_meetings_activate', 'wordpress_meetings_add_capabilities' );



/**
 * Actions After Plugins are Loaded.
 *
 * Make sure the plugins we're looking for are loaded before checking for the functions/classes.
 *
 * @since 1.2.0
 *
 * @uses plugins_loaded hook
 * @link https://developer.wordpress.org/reference/hooks/plugins_loaded/
 */
function wordpress_meetings_init() {
  if ( function_exists( 'p2p_register_connection_type' ) ) {
    add_action( 'p2p_init', 'wordpress_meetings_connection_types' );
  }
}
add_action( 'plugins_loaded', 'wordpress_meetings_init' );



/**
 * Add Activation Hook.
 *
 * @since 1.0.9
 *
 * @link https://codex.wordpress.org/Function_Reference/register_activation_hook#Process_Flow
 */
function wordpress_meetings_activate() {
    wordpress_organization_taxonomy();
    wordpress_meetings_type();
    wordpress_meetings_tag();
    wordpress_proposals_status_taxonomy();
    wordpress_meetings_post_type();
    wordpress_meetings_agenda_post_type();
    wordpress_proposals_post_type();
    wordpress_summary_post_type();
    wordpress_meetings_add_capabilities();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'wordpress_meetings_activate' );



/**
 * Add De-activation Hook.
 *
 * @since 1.0.9
 *
 * @link https://codex.wordpress.org/Function_Reference/register_deactivation_hook
 */
function wordpress_meetings_deactivate() {
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'wordpress_meetings_deactivate' );


