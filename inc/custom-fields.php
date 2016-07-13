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


/************* CUSTOM FIELDS *****************/

function anp_meetings_add_meeting_metabox() {

    $cmb = new_cmb2_box( array(
        'id'           => 'meeting_info',
        'title'        => __( 'Meeting Information', 'meeting' ),
        'object_types' => array( 'meeting', 'agenda' ),
        'context'      => 'side',
        'priority'     => 'high',
    ) );

    $cmb->add_field( array(
        'name' => __( 'Meeting Date', 'meeting' ),
        'id' => 'meeting_date',
        'type' => 'text_date',
        //'date_format' => 'mm/dd/yyyy',
    ) );

}

add_action( 'cmb2_init', 'anp_meetings_add_meeting_metabox' );


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
    ) );

    $cmb->add_field( array(
        'name' => __( 'Meeting Date', 'meeting' ),
        'id' => 'meeting_date',
        'type' => 'text_date',
        //'date_format' => 'mm/dd/yyyy',
    ) );

    $cmb->add_field( array(
        'name' => __( 'Date Effective', 'meeting' ),
        'id' => 'proposal_date_effective',
        'type' => 'text_date',
        //'date_format' => 'mm/dd/yyyy',
    ) );

}

add_action( 'cmb2_init', 'anp_meetings_add_proposal_metabox' );


// if( function_exists( "register_field_group" ) ) {

//     register_field_group( array(
//         'id' => 'acf_meeting-date',
//         'title' => 'Meeting Date',
//         'fields' => array(
//             array(
//                 'key' => 'field_56257bc5c5ea1',
//                 'label' => 'Meeting Date',
//                 'name' => 'meeting_date',
//                 'type' => 'date_picker',
//                 'required' => 1,
//                 'date_format' => 'yymmdd',
//                 'display_format' => 'mm/dd/yy',
//                 'first_day' => 1,
//             ),
//         ),
//         'location' => array(
//             array(
//                 array(
//                     'param' => 'post_type',
//                     'operator' => '==',
//                     'value' => 'meeting',
//                     'order_no' => 0,
//                     'group_no' => 0,
//                 ),
//             ),
//             array(
//                 array(
//                     'param' => 'post_type',
//                     'operator' => '==',
//                     'value' => 'agenda',
//                     'order_no' => 0,
//                     'group_no' => 1,
//                 ),
//             ),
//             array(
//                 array(
//                     'param' => 'post_type',
//                     'operator' => '==',
//                     'value' => 'summary',
//                     'order_no' => 0,
//                     'group_no' => 2,
//                 ),
//             ),
//         ),
//         'options' => array(
//             'position' => 'acf_after_title',
//             'layout' => 'no_box',
//             'hide_on_screen' => array(
//             ),
//         ),
//         'menu_order' => 0,
//     ));



//     register_field_group( array(
//         'id' => 'acf_proposal-detail-page',
//         'title' => 'Proposal Detail Page',
//         'fields' => array(
//             array(
//                 'key' => 'field_562008d369f6b',
//                 'label' => 'Status',
//                 'name' => 'proposal_status',
//                 'type' => 'taxonomy',
//                 'taxonomy' => 'proposal_status',
//                 'field_type' => 'select',
//                 'allow_null' => 1,
//                 'load_save_terms' => 1,
//                 'return_format' => 'id',
//                 'multiple' => 0,
//             ),
//             array(
//                 'key' => 'field_562008263445d',
//                 'label' => 'Date Accepted',
//                 'name' => 'meeting_date',
//                 'type' => 'date_picker',
//                 'date_format' => 'yymmdd',
//                 'display_format' => 'mm/dd/yy',
//                 'first_day' => 1,
//             ),
//             array(
//                 'key' => 'field_5620088b2365a',
//                 'label' => 'Date Effective',
//                 'name' => 'proposal_date_effective',
//                 'type' => 'date_picker',
//                 'date_format' => 'yymmdd',
//                 'display_format' => 'mm/dd/yy',
//                 'first_day' => 1,
//             ),
//         ),
//         'location' => array(
//             array(
//                 array(
//                     'param' => 'post_type',
//                     'operator' => '==',
//                     'value' => 'proposal',
//                     'order_no' => 0,
//                     'group_no' => 0,
//                 ),
//             ),
//         ),
//         'options' => array(
//             'position' => 'acf_after_title',
//             'layout' => 'default',
//             'hide_on_screen' => array(
//                 0 => 'custom_fields',
//                 1 => 'featured_image',
//             ),
//         ),
//         'menu_order' => 0,
//     ));

// }

?>
