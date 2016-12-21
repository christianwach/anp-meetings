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
function anp_meetings_add_meeting_metabox() {}

add_action( 'cmb2_init', 'anp_meetings_add_meeting_metabox' );

/**
 * Add Metaboxes to Proposal Post Type
 */
function anp_meetings_add_proposal_metabox() {

    $cmb = new_cmb2_box( array(
        'id'           => 'proposal-detail-page',
        'title'        => __( 'Proposal Detail Page', 'meetings' ),
        'object_types' => array( 'proposal' ),
        'context'      => 'side',
        'priority'     => 'high',
    ) );

    $cmb->add_field( array(
        'name' => __( 'Status', 'meetings' ),
        'id' => 'proposal_status',
        'type' => 'taxonomy_select',
        'taxonomy' => 'proposal_status',
        'attributes' => array(
            'required' => 'required'
        )
    ) );

    $cmb->add_field( array(
        'name' => __( 'Date Accepted', 'meetings' ),
        'id' => 'date_accepted',
        'type' => 'text_date',
    ) );

    $cmb->add_field( array(
        'name' => __( 'Date Effective', 'meetings' ),
        'id' => 'date_effective',
        'type' => 'text_date',
    ) );

}

add_action( 'cmb2_init', 'anp_meetings_add_proposal_metabox' );
