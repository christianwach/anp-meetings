<?php
/**
 * ANP Meetings Event Meta
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.9
 * @package   ANP_Meetings
 */
?>

<?php if( !empty( $connected_agenda ) ) : ?>

    <?php foreach( $connected_agenda as $post ) : ?>
        <?php setup_postdata( $post ); ?>
        <li class="agenda"><a href="<?php the_permalink( $post->ID ); ?>" title="<?php echo esc_attr( $post->post_title ); ?>"><?php _e( 'Agenda', 'meetings' ); ?></a></li>
    <?php endforeach; ?>
    <?php wp_reset_postdata(); ?>

<?php endif; ?>

<?php if( !empty( $connected_summary ) ) : ?>

    <?php foreach( $connected_summary as $post ) : ?>
        <?php setup_postdata( $post ); ?>
        <li class="summary"><a href="<?php the_permalink( $post->ID ); ?>" title="<?php echo esc_attr( $post->post_title ); ?>"><?php _e( 'Summary', 'meetings' ); ?></a></li>
    <?php endforeach; ?>
    <?php wp_reset_postdata(); ?>

<?php endif; ?>

<?php if( !empty( $connected_proposal ) ) : ?>

    <li>
        <h5><?php _e( 'Proposals', 'meetings' ); ?></h5>
        <ul class="proposals">
            <?php foreach( $connected_proposal as $post ) : ?>
                <?php setup_postdata( $post ); ?>
                <li class="proposal"><a href="<?php the_permalink( $post->ID ); ?>" title="<?php echo esc_attr( $post->post_title ); ?>"> <?php echo esc_attr( $post->post_title ); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <?php wp_reset_postdata(); ?>

<?php endif; ?>
