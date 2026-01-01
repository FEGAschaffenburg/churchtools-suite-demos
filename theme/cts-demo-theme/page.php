<?php
/**
 * Standard Page Template
 * 
 * @package CTS_Demo_Theme
 * @since 1.0.0
 */

get_header();
?>

<?php cts_demo_breadcrumbs(); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<div class="page-header">
		<div class="container">
			<h1 class="page-title"><?php the_title(); ?></h1>
			
			<?php if ( has_excerpt() ) : ?>
				<p class="page-description"><?php the_excerpt(); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<div class="container">
		<div class="page-content">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content">
					<?php the_content(); ?>
					
					<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Seiten:', 'cts-demo-theme' ),
						'after'  => '</div>',
					) );
					?>
				</div>
			</article>
		</div>
	</div>

<?php endwhile; ?>

<?php get_footer(); ?>
