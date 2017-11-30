<?php

/*
 * Content Variables - DO NOT REMOVE.
 * Variables that can be used in the template.
 */
$post_id = get_the_ID();
$post_type = get_post_type( $post_id );

// Meeting Meta
$meeting_date = ( get_post_meta( $post_id, 'meeting_date', true ) ) ? date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $post_id, 'meeting_date', true ) ) ) : '' ;
$organization = get_the_term_list( $post_id, 'organization', '<span class="organization term">', ', ', '</span>' );
$meeting_type = get_the_term_list( $post_id, 'meeting_type', '<span class="meeting-type term">', ', ', '</span>' );
$meeting_tags = get_the_term_list( $post_id, 'meeting_tag', '<span class="meeting-tag term">', ', ', '</span>' );

// Proposal Meta
$approval_date = $meeting_date;
$effective_date = get_post_meta( $post_id, 'proposal_date_effective', true );
$proposal_status = get_the_term_list( $post_id, 'proposal_status', '<span class="proposal-status term">', ', ', '</span>' );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>

	<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>

	<div class="meta meeting-meta">
		<?php if ( ! empty( $meeting_date ) ) : ?>
			<span class="meta-label"><?php _e( 'Date:','wordpress-meetings'	); ?></span> <?php echo $meeting_date; ?>
		<?php endif; ?>
	</div>

	<div class="meta meeting-meta">
		<?php if ( ! empty( $organization ) ) : ?>
			<span class="meta-label"><?php _e( 'Organization:', 'wordpress-meetings' ); ?></span> <?php echo $organization; ?>
		<?php endif; ?>
	</div>

	<div class="meta meeting-meta">
		<?php if ( ! empty( $meeting_type ) ) : ?>
			<span class="meta-label"><?php _e( 'Type:', 'wordpress-meetings' ); ?></span> <?php echo $meeting_type; ?>
		<?php endif; ?>
	</div>

	<div class="meta meeting-meta">
		<?php if ( ! empty(	$meeting_tags ) ) : ?>
			<span class="meta-label"><?php _e( 'Tags:', 'wordpress-meetings'	); ?></span> <?php echo $meeting_tags; ?>
		<?php endif; ?>
	</div>

	<?php if ( 'meeting' == $post_type ) : ?>

		<?php
		$agendas = ( function_exists( 'meeting_get_agenda' ) ) ? meeting_get_agenda( $post_id ) : '';
		$summaries = ( function_exists( 'meeting_get_summary' ) ) ? meeting_get_summary( $post_id ) : '';
		$proposals = ( function_exists( 'meeting_get_proposal' ) ) ? meeting_get_proposal( $post_id ) : '';
		$events =	(function_exists( 'meeting_get_event' ) ) ? meeting_get_event( $post_id ) : '';
		?>

		<?php if ( $agendas || $summaries || $proposals || $events ) : ?>
			<ul class="connected-content">
				<?php echo ( $events ) ? $events : ''; ?>
				<?php echo ( $agendas ) ? $agendas : ''; ?>
				<?php echo ( $summaries ) ? $summaries : ''; ?>
				<?php echo ( $proposals ) ? $proposals : ''; ?>
			</ul>
		<?php endif; ?>

	<?php endif; ?>

	<?php if ( 'agenda' == $post_type ) : ?>

		<?php $agendas = ( function_exists( 'meeting_get_agenda' ) ) ? meeting_get_agenda( $post_id ) : ''; ?>

		<?php if ( $agendas ) : ?>
			<nav class="connected-content-nav" role="navigation">
				<ul class="connected-content agendas">
					<?php ( $agendas ) ? $agendas : ''; ?>
				</ul>
			</nav>
		<?php endif; ?>

	<?php endif; ?>

	<?php if ( 'summary' == $post_type ) :	?>

		<?php $summaries = ( function_exists( 'meeting_get_summary' ) ) ? meeting_get_summary( $post_id ) : ''; ?>

		<?php if ( ! empty( $summaries ) ) : ?>
			<ul class="connected-content summaries">
				<?php echo $summaries; ?>
			</ul>
		<?php endif; ?>

	<?php endif; ?>

</article>
