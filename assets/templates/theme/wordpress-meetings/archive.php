<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress_Meetings
 * @since 1.3.0
 * @version 0.1.0
 */

get_header(); ?>

<div class="wrap">

	<?php if ( have_posts() ) : ?>
		<header class="page-header">
			<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
			?>
		</header><!-- .page-header -->
	<?php endif; ?>

	<div id="primary" class="content-area site-content">
		<main id="main" class="site-main meetings-archive" role="main">

		<div class="archive-heading">
			<div class="heading-title" role="heading"><?php _e( 'Title', 'wordpress-meetings' ); ?></div>
			<div class="heading-date" role="heading"><?php _e( 'Details', 'wordpress-meetings' ); ?></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
		</div>

		<?php if ( have_posts() ) : ?>
			<?php

			while ( have_posts() ) : the_post();
				$file = 'wordpress-meetings/content-archive.php';
				$template_path = wp_meetings_template_get( $file );
				include( $template_path );
				//get_template_part( 'assets/templates/content', 'archive' );
			endwhile;

			the_posts_pagination( array(
				'prev_text' => '<span class="screen-reader-text">' . __( 'Previous page', 'wordpress-meetings' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'wordpress-meetings' ) . '</span>',
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'wordpress-meetings' ) . ' </span>',
			) );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer();
