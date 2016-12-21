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
            'name' => 'event_to_agenda',
            'from' => 'event',
            'to' => 'agenda',
            'reciprocal' => true,
            'cardinality' => 'one-to-one',
            'admin_column' => true,
            'admin_dropdown' => 'to',
            'sortable' => 'any',
            'title' => array(
                'from' => __( 'Agenda', 'meetings' ),
                'to' => __( 'Meeting', 'meetings' )
            ),
            'from_labels' => array(
                'singular_name' => __( 'Meeting Event', 'meetings' ),
                'search_items' => __( 'Search meeting events', 'meetings' ),
                'not_found' => __( 'No meetings found.', 'meetings' ),
                'create' => __( 'Attach a Meeting Event', 'meetings' ),
            ),
            'to_labels' => array(
                'singular_name' => __( 'Agenda', 'meetings' ),
                'search_items' => __( 'Search agendas', 'meetings' ),
                'not_found' => __( 'No agendas found.', 'meetings' ),
                'create' => __( 'Attach an Agenda', 'meetings' ),
            ),
        ) );

        p2p_register_connection_type( array(
            'name' => 'event_to_summary',
            'from' => 'event',
            'to' => 'summary',
            'reciprocal' => true,
            'cardinality' => 'one-to-one',
            'admin_column' => true,
            'admin_dropdown' => 'to',
            'sortable' => 'any',
            'title' => array(
                'from' => __( 'Notes', 'meetings' ),
                'to' => __( 'Meeting', 'meetings' )
            ),
            'from_labels' => array(
                'singular_name' => __( 'Meeting Event', 'meetings' ),
                'search_items' => __( 'Search meeting events', 'meetings' ),
                'not_found' => __( 'No meetings found.', 'meetings' ),
                'create' => __( 'Attach a Meeting Event', 'meetings' ),
            ),
            'to_labels' => array(
                'singular_name' => __( 'Notes', 'meetings' ),
                'search_items' => __( 'Search notes', 'meetings' ),
                'not_found' => __( 'No notes found.', 'meetings' ),
                'create' => __( 'Attach Notes', 'meetings' ),
            ),
        ) );

        p2p_register_connection_type( array(
            'name' => 'event_to_proposal',
            'from' => 'event',
            'to' => 'proposal',
            'reciprocal' => true,
            'cardinality' => 'one-to-many',
            'admin_column' => true,
            'admin_dropdown' => 'any',
            'sortable' => 'any',
            'title' => array(
                'from' => __( 'Proposals', 'meetings' ),
                'to' => __( 'Event', 'meetings' )
            ),
            'from_labels' => array(
                'singular_name' => __( 'Meeting Event', 'meetings' ),
                'search_items' => __( 'Search meeting events', 'meetings' ),
                'not_found' => __( 'No meetings found.', 'meetings' ),
                'create' => __( 'Attach a Meeting Event', 'meetings' ),
            ),
            'to_labels' => array(
                'singular_name' => __( 'Proposals', 'meetings' ),
                'search_items' => __( 'Search proposals', 'meetings' ),
                'not_found' => __( 'No proposals found.', 'meetings' ),
                'create' => __( 'Attach a Proposal', 'meetings' ),
            ),
        ) );

    }
    add_action( 'p2p_init', 'anp_meetings_connection_types' );

}
