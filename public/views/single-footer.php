<footer class="entry-footer">

<?php if( !empty( $meeting_tags ) ) : ?>

    <p class="tags meta"><span class="meta-label"><?php _e( 'Tags:', 'meetings' ) ?></span> <?php echo $meeting_tags; ?>
    </p>

<?php endif; ?>

<?php if( !empty( $connected_proposal ) ) : ?>

    <h3 id="proposals"><?php _e( 'Proposals', 'meetings' ); ?></h3>

    <ul class="proposal-links">

    <?php foreach( $connected_proposal as $proposal ) : ?>

        <?php $statuses = wp_get_post_terms( $proposal->ID, 'proposal_status', array( 'fields' => 'names' ) ); ?>

        <?php $status = ( !empty( $statuses ) ) ? $statuses[0] : ''; ?>

        <li class="proposal-link">
            <a href="<?php echo get_post_permalink( $proposal->ID ); ?>"><?php echo $proposal->post_title; ?></a>

            <?php if( $status ) : ?>
                <span class="proposal-status">
                    <label for="proposal-status"><?php _e( 'Status', 'meetings' ); ?></label>
                    <?php echo $status; ?>
                </span>
            <?php endif; ?>
            
        </li>

    <?php endforeach; ?>

    </ul>

<?php endif; ?>

</footer>
