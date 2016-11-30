<?php

/**
 * ANP Meetings Custom Fields
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_Meetings
 */

/**
 * Check if Post is an Event
 * @param  obj  $cmb
 * @return boolean
 */
function anp_meeting_is_event_meeting( $cmb ) {
    // Check if other meta value exists
    if ( get_post_meta( $cmb->object_id, 'is_meeting', true ) ) {
        return true;
    }
    return false;
}

/**
 * Add Metaboxes to Meeting Post Type
 *
 * @uses cmb2_init
 */
function anp_meetings_add_meeting_metabox() {

    $cmb = new_cmb2_box( array(
        'id'           => 'event_type',
        'title'        => __( 'Event Type', 'meeting' ),
        'object_types' => array( 'event' ),
        'context'      => 'side',
        'priority'     => 'high',
    ) );

    $cmb->add_field( array(
        'name'          => __( 'Meeting Event', 'meeting' ),
        'description'   => __( 'Is this event a meeting?', 'meeting' ),
        'id'            => 'is_meeting',
        'type'          => 'checkbox',
        'attributes'    => array()
    ) );

    $cmb->add_field( array(
        'name'           => __( 'Organizational Group', 'meeting' ),
        'id'             => 'organizational_group',
        'taxonomy'       => 'organization',
        'type'           => 'taxonomy_multicheck',
        'show_on_cb'     => 'anp_meeting_is_event_meeting',
        'text'           => array(
            'no_terms_text' => __( 'Sorry, no organizational groups could be found.', 'meeting' )
        ),
        'remove_default' => 'true'
    ) );

    $cmb->add_field( array(
        'name'           => __( 'Meeting Type', 'meeting' ),
        'id'             => 'meeting_type',
        'taxonomy'       => 'meeting_type',
        'type'           => 'taxonomy_multicheck',
        'show_on_cb'     => 'anp_meeting_is_event_meeting',
        'text'           => array(
            'no_terms_text' => __( 'Sorry, no meeting types could be found.', 'meeting' )
        ),
        'remove_default' => 'true'
    ) );

}

add_action( 'cmb2_init', 'anp_meetings_add_meeting_metabox' );

/**
 * Add Metaboxes to Proposal Post Type
 */
function anp_meetings_add_proposal_metabox() {

    $cmb = new_cmb2_box( array(
        'id'           => 'proposal-detail-page',
        'title'        => __( 'Proposal Detail Page', 'meeting' ),
        'object_types' => array( 'proposal' ),
        'context'      => 'side',
        'priority'     => 'high',
    ) );

    $cmb->add_field( array(
        'name' => __( 'Status', 'meeting' ),
        'id' => 'proposal_status',
        'type' => 'taxonomy_select',
        'taxonomy' => 'proposal_status',
        'attributes' => array(
            'required' => 'required'
        )
    ) );

    $cmb->add_field( array(
        'name' => __( 'Date Accepted', 'meeting' ),
        'id' => 'meeting_date',
        'type' => 'text_date',
    ) );

    $cmb->add_field( array(
        'name' => __( 'Date Effective', 'meeting' ),
        'id' => 'proposal_date_effective',
        'type' => 'text_date',
    ) );

}

add_action( 'cmb2_init', 'anp_meetings_add_proposal_metabox' );
