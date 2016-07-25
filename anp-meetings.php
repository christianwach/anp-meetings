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
Version: 1.0.5
License: GPLv3
Text Domain: meeting
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
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-meeting.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-agenda.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-summary.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-proposal.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-fields.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/post-type-connections.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-content-filters.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-pre-get-filters.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'anp-meetings-render.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-search-filters.php' );


?>