<?php
/**
 * WordPress Meetings Custom Fields.
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   WordPress_Meetings
 */



/**
 * Meeting Metabox.
 */
function wordpress_meetings_add_meeting_metabox() {

    $cmb = new_cmb2_box( array(
        'id'           => 'meeting_info',
        'title'        => __( 'Meeting Information', 'wordpress-meetings' ),
        'object_types' => array( 'meeting', 'agenda', 'summary' ),
        'context'      => 'side',
        'priority'     => 'high',
    ) );

    $cmb->add_field( array(
        'name' => __( 'Meeting Date*', 'wordpress-meetings' ),
        'id' => 'meeting_date',
        'type' => 'text_date',
        'attributes' => array(
            'required' => 'required'
        )
    ) );

}



/**
 * Proposal Metabox.
 */
function wordpress_meetings_add_proposal_metabox() {

    $cmb = new_cmb2_box( array(
        'id'           => 'proposal-detail-page',
        'title'        => __( 'Proposal Detail Page', 'wordpress-meetings' ),
        'object_types' => array( 'proposal' ),
        'context'      => 'side',
        'priority'     => 'high',
    ) );

    $cmb->add_field( array(
        'name' => __( 'Status', 'wordpress-meetings' ),
        'id' => 'proposal_status',
        'type' => 'taxonomy_select',
        'taxonomy' => 'proposal_status',
        'attributes' => array(
            'required' => 'required'
        )
    ) );

    $cmb->add_field( array(
        'name' => __( 'Date Accepted', 'wordpress-meetings' ),
        'id' => 'meeting_date',
        'type' => 'text_date',
    ) );

    $cmb->add_field( array(
        'name' => __( 'Date Effective', 'wordpress-meetings' ),
        'id' => 'proposal_date_effective',
        'type' => 'text_date',
    ) );
}
add_action( 'cmb2_init', 'wordpress_meetings_add_meeting_metabox' );
add_action( 'cmb2_init', 'wordpress_meetings_add_proposal_metabox' );


