<?php

// Associated Content
$queried_obj = get_queried_object();

$connected_agenda = get_posts( array(
	'connected_type' => 'meeting_to_agenda',
	'connected_items' => $queried_obj,
	'nopaging' => true,
	'suppress_filters' => false
) );

$connected_summary = get_posts( array(
	'connected_type' => 'meeting_to_summary',
	'connected_items' => $queried_obj,
	'nopaging' => true,
	'suppress_filters' => false
) );

$connected_proposal = get_posts( array(
	'connected_type' => 'meeting_to_proposal',
	'connected_items' => $queried_obj,
	'nopaging' => true,
	'suppress_filters' => false
) );

$connected_event = get_posts( array(
	'connected_type' => 'meeting_to_event',
	'connected_items' => $queried_obj,
	'nopaging' => true,
	'suppress_filters' => false
) );

if ( ! empty( $connected_agenda ) || ! empty( $connected_summary ) || ! empty( $connected_proposal ) || ! empty( $connected_event ) ) : ?>

	<nav class="connected-content-nav" role="navigation">

		<ul class="connected-content">

			<?php if ( ! empty( $connected_event ) ) : ?>
				<?php foreach( $connected_event as $event ) : ?>
					<?php $post_type_obj = get_post_type_object( get_post_type( $event->ID ) ); ?>
					<?php $post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : ''; ?>
					<?php $post_class = $post_type_obj->name; ?>
					<li class="<?php echo ( $post_class ) ? $post_class : '' ?>-link">
						<a href="<?php echo get_post_permalink( $event->ID ); ?>" title="View <?php echo ( $post_type_obj ) ? $post_type_obj->labels->singular_name : ''; ?>" rel="bookmark"><span class="link-text"><?php echo ( $post_type_obj ) ? $post_type_obj->labels->singular_name : ''; ?></span></a>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if ( ! empty( $connected_agenda ) ) : ?>
				<?php foreach( $connected_agenda as $agenda ) : ?>
					<?php $post_type_obj = get_post_type_object( get_post_type( $agenda->ID ) ); ?>
					<?php $post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : ''; ?>
					<?php $post_class = $post_type_obj->name; ?>
					<li class="<?php echo ( $post_class ) ? $post_class : '' ?>-link">
						<a href="<?php echo get_post_permalink( $agenda->ID ); ?>" rel="bookmark" title="View <?php echo ( $post_type_obj ) ? $post_type_obj->labels->singular_name : ''; ?>"><span class="link-text"><?php echo ( $post_type_name ) ? $post_type_name : $agenda->post_title; ?></span></a>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if ( ! empty( $connected_summary ) ) : ?>
				<?php foreach( $connected_summary as $summary ) : ?>
					<?php $post_type_obj = get_post_type_object( get_post_type( $summary->ID ) ); ?>
					<?php $post_type_name = ( $post_type_obj ) ? $post_type_obj->labels->singular_name : ''; ?>
					<?php $post_class = $post_type_obj->name; ?>
					<li class="<?php echo ( $post_class ) ? $post_class : '' ?>-link">
						<a href="<?php echo get_post_permalink( $summary->ID ); ?>" title="View <?php echo ( $post_type_obj ) ? $post_type_obj->labels->singular_name : ''; ?>" rel="bookmark"><span class="link-text"><?php echo ( $post_type_obj ) ? $post_type_obj->labels->singular_name : ''; ?></span></a>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if ( 'proposal' == get_post_type() && ! empty( $connected_proposal ) ) : ?>
				<?php foreach( $connected_proposal as $proposal ) : ?>
					<?php $post_type_obj = get_post_type_object( get_post_type( $proposal->ID ) ); ?>
					<?php $post_class = $post_type_obj->name; ?>
					<li class="<?php echo ( $post_class ) ? $post_class : '' ?>-link">
						<a href="<?php echo get_post_permalink( $proposal->ID ); ?>" title="View <?php echo ( $post_type_obj ) ? $post_type_obj->labels->singular_name : ''; ?>" rel="bookmark"><span class="link-text"><?php echo ( $post_type_obj ) ? $post_type_obj->labels->singular_name : ''; ?></span></a>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>

		</ul>

	</nav>

<?php endif; ?>
