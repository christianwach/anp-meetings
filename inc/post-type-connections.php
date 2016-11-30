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
                'from' => __( 'Agenda', 'meeting' ),
                'to' => __( 'Meeting', 'meeting' )
            ),
            'from_labels' => array(
                'singular_name' => __( 'Meeting Event', 'meeting' ),
                'search_items' => __( 'Search meeting events', 'meeting' ),
                'not_found' => __( 'No meetings found.', 'meeting' ),
                'create' => __( 'Attach a Meeting Event', 'meeting' ),
            ),
            'to_labels' => array(
                'singular_name' => __( 'Agenda', 'meeting' ),
                'search_items' => __( 'Search agendas', 'meeting' ),
                'not_found' => __( 'No agendas found.', 'meeting' ),
                'create' => __( 'Attach an Agenda', 'meeting' ),
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
                'from' => __( 'Notes', 'meeting' ),
                'to' => __( 'Meeting', 'meeting' )
            ),
            'from_labels' => array(
                'singular_name' => __( 'Meeting Event', 'meeting' ),
                'search_items' => __( 'Search meeting events', 'meeting' ),
                'not_found' => __( 'No meetings found.', 'meeting' ),
                'create' => __( 'Attach a Meeting Event', 'meeting' ),
            ),
            'to_labels' => array(
                'singular_name' => __( 'Notes', 'meeting' ),
                'search_items' => __( 'Search notes', 'meeting' ),
                'not_found' => __( 'No notes found.', 'meeting' ),
                'create' => __( 'Attach Notes', 'meeting' ),
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
                'from' => __( 'Proposals', 'meeting' ),
                'to' => __( 'Event', 'meeting' )
            ),
            'from_labels' => array(
                'singular_name' => __( 'Meeting Event', 'meeting' ),
                'search_items' => __( 'Search meeting events', 'meeting' ),
                'not_found' => __( 'No meetings found.', 'meeting' ),
                'create' => __( 'Attach a Meeting Event', 'meeting' ),
            ),
            'to_labels' => array(
                'singular_name' => __( 'Proposals', 'meeting' ),
                'search_items' => __( 'Search proposals', 'meeting' ),
                'not_found' => __( 'No proposals found.', 'meeting' ),
                'create' => __( 'Attach a Proposal', 'meeting' ),
            ),
        ) );

    }
    add_action( 'p2p_init', 'anp_meetings_connection_types' );

}
