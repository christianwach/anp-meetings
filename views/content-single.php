<?php

/*
 * Content Variables - DO NOT REMOVE
 * Variables that can be used in the template
 */

$post_id = get_the_ID();
$post_type = get_post_type( $post_id );

// Meeting Meta

$meeting_date = get_post_meta( $post_id, 'meeting_date', true );
$organization = get_the_term_list( get_the_ID(), 'organization', '<span class="organization tag">', ', ', '</span>' );
$meeting_type = get_the_term_list( $post_id, 'meeting_type', '<span class="meeting-type tag">', ', ', '</span>' );
$meeting_tags = get_the_term_list( $post_id, 'meeting_tag', '<span class="meeting-tag tag">', ', ', '</span>' );

// Proposal Meta
$approval_date = $meeting_date;
$effective_date = get_post_meta( $post_id, 'proposal_date_effective', true );
$proposal_status = get_the_term_list( $post_id, 'proposal_status', '<span class="proposal-status tag">', ', ', '</span>' );

// Associated Content
$connected_agenda = get_posts( array(
  'connected_type' => 'meeting_to_agenda',
  'connected_items' => get_queried_object(),
  'nopaging' => true,
  'suppress_filters' => false
) );

$connected_summary = get_posts( array(
  'connected_type' => 'meeting_to_summary',
  'connected_items' => get_queried_object(),
  'nopaging' => true,
  'suppress_filters' => false
) );

$connected_proposal = get_posts( array(
  'connected_type' => 'meeting_to_proposal',
  'connected_items' => get_queried_object(),
  'nopaging' => true,
  'suppress_filters' => false
) ); ?>

<?php if( 'meeting' == $post_type ) : ?>

    <?php if( $meeting_date ) : ?>
        <p class="meta meeting-meta"><span class="meta-label"><?php _e( 'Date:', 'anp-meeting' ); ?></span> <?php echo $meeting_date; ?></p>
    <?php endif; ?>
    <?php if( $meeting_type ) : ?>
        <p class="meta meeting-meta"><span class="meta-label"><?php _e( 'Type:', 'anp-meeting' ); ?></span> <?php echo $meeting_type; ?></p>
    <?php endif; ?>

<?php endif; ?>

<?php if( $organization ) : ?>
    <p class="meta meeting-meta"><span class="meta-label"><?php _e( 'Organization:', 'anp-meeting' ); ?></span> <?php echo $organization; ?></p>
<?php endif; ?>

<?php if( $meeting_tags ) : ?>
    <p class="meta meeting-meta"><span class="meta-label"><?php _e( 'Tags:', 'anp-meeting' ); ?></span> <?php echo $meeting_tags; ?></p>
<?php endif; ?>

<?php if( 'proposal' == $post_type ) : ?>

    <?php if( $proposal_status ) : ?>
        <p class="meta meeting-meta"><span class="meta-label"><?php _e( 'Status:', 'anp-meeting' ); ?></span> <?php echo $proposal_status; ?></p>
    <?php endif; ?>

    <?php if( $meeting_date ) : ?>
        <p class="meta meeting-meta"><span class="meta-label"><?php _e( 'Date Appoved:', 'anp-meeting' ); ?></span> <?php echo $meeting_date; ?></p>
    <?php endif; ?>

    <?php if( $effective_date) : ?>
        <p class="meta meeting-meta"><span class="meta-label"><?php _e( 'Date Effective:', 'anp-meeting' ); ?></span> <?php echo $effective_date; ?></p>
    <?php endif; ?>

<?php endif; ?>

<?php if( !empty( $connected_agenda ) || !empty( $connected_summary ) || !empty( $connected_proposal ) ) : ?>

    <div class="meta meeting-meta">

        <ul class="connected-content">

        <?php foreach( $connected_agenda as $agenda ) : ?>
            <?php $post_type_obj = get_post_type_object( get_post_type( $agenda->ID ) ); ?>
            <?php $post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : ''; ?>

            <li class="agenda-link">
                <a href="<?php echo get_post_permalink( $agenda->ID ); ?>"> <?php echo ( $post_type_name ) ? $post_type_name : $agenda->post_title; ?></a>
            </li>
        <?php endforeach; ?>

        <?php foreach( $connected_summary as $summary ) : ?>
            <?php $post_type_obj = get_post_type_object( get_post_type( $summary->ID ) ); ?>
            <?php $post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : ''; ?>
            <li class="summary-link"><a href="<?php echo get_post_permalink( $summary->ID ); ?>">
            <?php echo ( $post_type_obj ) ? $post_type_obj->labels->singular_name : ''; ?>
            </a></li>
        <?php endforeach; ?>

        <?php if( 'proposal' == get_post_type() ) : ?>

          <?php foreach( $connected_proposal as $proposal ) : ?>
              <?php $post_type_obj = get_post_type_object( get_post_type( $proposal->ID ) ); ?>
              <li class="meeting-link">
                  <a href="<?php echo get_post_permalink( $proposal->ID ); ?>"> <?php _e( 'Meeting', 'anp-meeting' ); ?></a>
              </li>
          <?php endforeach; ?>

        <?php endif; ?>

        <?php if( 'meeting' == get_post_type() && count( $connected_proposal ) > 0 ) : ?>

            <li class="proposal-link"><a href="#proposals"><?php _e( 'Proposal(s)', 'anp-meeting' ); ?></a></li>

        <?php endif; ?>

        </ul>
    </div>

<?php endif; ?>
