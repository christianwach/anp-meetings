<?php

/**
 * ANP Meetings Posts 2 Posts Connections
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_Meetings
 */

if(! function_exists( 'anp_meetings_connection_types' ) ) {

    function anp_meetings_connection_types() {
        p2p_register_connection_type( array(
            'name' => 'meeting_to_agenda',
            'from' => 'meeting',
            'to' => 'agenda',
            'reciprocal' => true,
            'cardinality' => 'one-to-one',
            'admin_column' => true,
            'admin_dropdown' => 'to',
            'title' => array( 'from' => __( 'Agenda', 'meetings' ), 'to' => __( 'Meeting', 'meetings' ) ),
        ) );

        p2p_register_connection_type( array(
            'name' => 'meeting_to_summary',
            'from' => 'meeting',
            'to' => 'summary',
            'reciprocal' => true,
            'cardinality' => 'one-to-one',
            'admin_column' => true,
            'admin_dropdown' => 'to',
            'title' => array( 'from' => __( 'Summary', 'meetings' ), 'to' => __( 'Meeting', 'meetings' ) ),
        ) );

        p2p_register_connection_type( array(
            'name' => 'meeting_to_proposal',
            'from' => 'meeting',
            'to' => 'proposal',
            'reciprocal' => true,
            'cardinality' => 'one-to-many',
            'admin_column' => true,
            'admin_dropdown' => 'any',
            'sortable' => 'any',
            'title' => array( 'from' => __( 'Proposals', 'meetings' ), 'to' => __( 'Meeting', 'meetings' ) ),
        ) );

        /**
         * Register Connection Between Meeting and Event Post types
         *
         * @since 0.9.1
         *
         * @uses post_type_exists()
         * @uses p2p_register_connection_type()
         * @link https://github.com/scribu/wp-posts-to-posts/wiki/p2p_register_connection_type
         */
        if( post_type_exists( 'event' ) ) {
          p2p_register_connection_type( array(
              'name' => 'meeting_to_event',
              'from' => 'meeting',
              'to' => 'event',
              'reciprocal' => true,
              'cardinality' => 'one-to-many',
              'admin_column' => true,
              'admin_dropdown' => 'any',
              'sortable' => 'any',
              'title' => array( 'from' => __( 'Event', 'meetings' ), 'to' => __( 'Meeting', 'meetings' ) ),
          ) );
        }

    }
    add_action( 'p2p_init', 'anp_meetings_connection_types' );

}
