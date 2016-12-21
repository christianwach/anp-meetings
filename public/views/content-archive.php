<?php

/*
 * Content Variables - DO NOT REMOVE
 * Variables that can be used in the template
 */

global $wp_query;

$post_type = get_post_type( get_the_ID() );

// Meeting Meta
$meeting_date = ( get_post_meta( get_the_ID(), 'meeting_date', true ) ) ? date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( get_the_ID(), 'meeting_date', true ) ) ) : '' ;
$organization = get_the_term_list( get_the_ID(), 'organization', '<span class="organization tag">', ', ', '</span>' );
$meeting_type = get_the_term_list( get_the_ID(), 'meeting_type', '<span class="meeting-type tag">', ', ', '</span>' );
$meeting_tags = get_the_term_list( get_the_ID(), 'meeting_tag', '<span class="meeting-tag tag">', ', ', '</span>' );

// Proposal Meta
$approval_date = $meeting_date;
$effective_date = get_post_meta( get_the_ID(), 'proposal_date_effective', true );
$proposal_status = get_the_term_list( get_the_ID(), 'proposal_status', '<span class="proposal-status tag">', ', ', '</span>' ); ?>

<div class="meta meeting-meta"><?php echo ( !empty( $meeting_date ) ) ? '<span class="meta-label">' . __( 'Date:','anp-meetings'  ) . '</span> ' . $meeting_date : ''; ?></div>
<div class="meta meeting-meta"><?php echo ( !empty( $organization ) ) ? '<span class="meta-label">' . __( 'Organization:', 'anp-meetings' ) . '</span> ' . $organization : ''; ?></div>
<div class="meta meeting-meta"><?php echo ( !empty( $meeting_tags ) ) ? '<span class="meta-label">' . __( 'Tags:', 'anp-meetings'  ) . '</span> ' . $meeting_tags : '' ; ?></div>

<?php
if( 'meetings' == $post_type ) { ?>

    <div class="meta meeting-meta"><?php echo ( !empty( $meeting_type ) ) ? __( 'Type: ', 'anp-meetings'  ) . $meeting_type : '' ; ?></div>

    <?php
    $agendas = ( function_exists( 'meeting_get_agenda' ) ) ? meeting_get_agenda( get_the_ID() ) : '';

    $summaries = ( function_exists( 'meeting_get_summary' ) ) ? meeting_get_summary( get_the_ID() ) : '';

    $proposals = ( function_exists( 'meeting_get_proposal' ) ) ? meeting_get_proposal( get_the_ID() ) : '';

    if( $agendas || $summaries || $proposals ) { ?>

        <ul class="connected-content">

        <?php echo ( $agendas ) ? $agendas : ''; ?>

        <?php echo ( $summaries ) ? $summaries : ''; ?>

        <?php echo ( $proposals ) ? $proposals : ''; ?>

        </ul>

    <?php
    }
}

if( 'agenda' == $post_type ) {

    $agendas = ( function_exists( 'meeting_get_agenda' ) ) ? meeting_get_agenda( get_the_ID() ) : '';

    if( $agendas ) { ?>

        <ul class="connected-content meeting">

        <?php ( $agendas ) ? $agendas : ''; ?>

        </ul>'

    <?php
    }

}

if( 'summary' == $post_type ) {

    $summaries = ( function_exists( 'meeting_get_summary' ) ) ? meeting_get_summary( get_the_ID() ) : '';

    if( $summaries ) { ?>

        <ul class="connected-content meeting">

        <?php echo ( $summaries ) ? $summaries : ''; ?>

        </ul>

    <?php
    }
}


?>
