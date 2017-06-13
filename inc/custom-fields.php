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
 * Meeting Metabox
 */
function anp_meetings_add_meeting_metabox() {

    $cmb = new_cmb2_box( array(
        'id'           => 'meeting_info',
        'title'        => __( 'Meeting Information', 'meeting' ),
        'object_types' => array( 'meeting', 'agenda', 'summary' ),
        'context'      => 'side',
        'priority'     => 'high',
    ) );

    $cmb->add_field( array(
        'name' => __( 'Meeting Date*', 'meeting' ),
        'id' => 'meeting_date',
        'type' => 'text_date',
        'attributes' => array(
            'required' => 'required'
        )
    ) );

}

/**
 * Proposal Metabox
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
add_action( 'cmb2_init', 'anp_meetings_add_meeting_metabox' );
add_action( 'cmb2_init', 'anp_meetings_add_proposal_metabox' );
