<?php
/**
 * ANP Meetings Admin Functions
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.9
 * @package   ANP_Meetings
 */

 /**
  * Admin Post List Order
  * Order posts alphabetically in the P2P connections box
  *
  * @param array $args
  * @param string $ctype Content type
  * @param int $post_id
  *
  * @return array $args
  *
  */
 if(! function_exists( 'anp_connection_box_order' ) ) {

     function anp_connection_box_order( $args, $ctype, $post_id ) {
         if ( ( 'event_to_agenda' == $ctype->name || 'event_to_summary' == $ctype->name || 'event_to_proposal' == $ctype->name ) ) {
             $args['orderby'] = 'title';
             $args['order'] = 'asc';
         }

         return $args;
     }
     add_filter( 'p2p_connectable_args', 'anp_connection_box_order', 10, 3 );

 }

 /*
  * Add markdown support for custom post types
  */
 if(! function_exists( 'meeting_markdown_support' )  ) {

     function meeting_markdown_support() {
         add_post_type_support( 'event', 'wpcom-markdown' );
         add_post_type_support( 'proposal', 'wpcom-markdown' );
         add_post_type_support( 'summary', 'wpcom-markdown' );
         add_post_type_support( 'agenda', 'wpcom-markdown' );
     }
     add_action( 'init', 'meeting_markdown_support' );

 }
