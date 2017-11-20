<footer class="entry-footer">

<?php if ( ! empty( $connected_proposal ) ) : ?>

    <h3 id="proposals"><?php _e( 'Proposals', 'wordpress-meetings' ); ?></h3>

    <ul class="proposal-links">

    <?php foreach( $connected_proposal as $proposal ) : ?>

        <?php $statuses = wp_get_post_terms( $proposal->ID, 'proposal_status', array( 'fields' => 'names' ) ); ?>

        <?php $status = ( ! empty( $statuses ) ) ? $statuses[0] : ''; ?>

        <li class="proposal-link">
            <a href="<?php echo get_post_permalink( $proposal->ID ); ?>"><?php echo $proposal->post_title; ?></a>

            <?php if ( $status ) : ?>
                <span class="proposal-status">
                    <span class="meta-label"><?php _e( 'Status', 'wordpress-meetings' ); ?></span>
                    <?php echo $status; ?>
                </span>
            <?php endif; ?>

        </li>

    <?php endforeach; ?>

    </ul>

<?php endif; ?>

</footer>
