<?php
/**
 * Template Name: Documentation Page
 * Description: Template für Dokumentations-Seiten mit Sidebar-Navigation
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
			<div style="display: grid; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start;">
				
				<!-- Sidebar Navigation -->
				<aside class="doc-sidebar">
					<nav class="doc-nav">
						<h3 style="margin: 0 0 1rem; font-size: 1rem; font-weight: 600;">Dokumentation</h3>
						
						<?php
						// Get documentation pages
						$docs_pages = get_pages( array(
							'parent' => get_page_by_path( 'documentation' )->ID ?? 0,
							'sort_column' => 'menu_order',
							'sort_order' => 'ASC'
						) );
						
						if ( $docs_pages ) :
						?>
							<ul>
								<?php foreach ( $docs_pages as $doc_page ) : ?>
									<li>
										<a href="<?php echo get_permalink( $doc_page->ID ); ?>" 
										   class="<?php echo ( get_the_ID() === $doc_page->ID ) ? 'active' : ''; ?>">
											<?php echo esc_html( $doc_page->post_title ); ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php else : ?>
							<ul>
								<li><a href="<?php echo home_url( '/documentation/installation/' ); ?>">Installation</a></li>
								<li><a href="<?php echo home_url( '/documentation/configuration/' ); ?>">Konfiguration</a></li>
								<li><a href="<?php echo home_url( '/documentation/shortcodes/' ); ?>">Shortcodes</a></li>
								<li><a href="<?php echo home_url( '/documentation/templates/' ); ?>">Templates</a></li>
								<li><a href="<?php echo home_url( '/documentation/customization/' ); ?>">Anpassungen</a></li>
								<li><a href="<?php echo home_url( '/documentation/troubleshooting/' ); ?>">Troubleshooting</a></li>
							</ul>
						<?php endif; ?>
						
						<hr style="margin: 1.5rem 0; border: none; border-top: 1px solid var(--border-color);">
						
						<h3 style="margin: 0 0 1rem; font-size: 1rem; font-weight: 600;">Weitere Links</h3>
						<ul>
							<li><a href="<?php echo home_url( '/demos/' ); ?>">Live Demos</a></li>
							<li><a href="https://github.com/FEGAschaffenburg/churchtools-suite" target="_blank">GitHub Repository</a></li>
							<li><a href="https://github.com/FEGAschaffenburg/churchtools-suite/issues" target="_blank">Issues melden</a></li>
							<li><a href="<?php echo home_url( '/download/' ); ?>">Download</a></li>
						</ul>
					</nav>
				</aside>
				
				<!-- Main Content -->
				<article class="doc-content">
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
					
					<!-- Page Navigation -->
					<?php
					$prev_page = null;
					$next_page = null;
					
					if ( $docs_pages ) {
						$current_index = array_search( get_the_ID(), array_column( $docs_pages, 'ID' ) );
						
						if ( $current_index !== false ) {
							if ( isset( $docs_pages[ $current_index - 1 ] ) ) {
								$prev_page = $docs_pages[ $current_index - 1 ];
							}
							if ( isset( $docs_pages[ $current_index + 1 ] ) ) {
								$next_page = $docs_pages[ $current_index + 1 ];
							}
						}
					}
					
					if ( $prev_page || $next_page ) :
					?>
						<nav style="display: flex; justify-content: space-between; margin-top: 3rem; padding-top: 2rem; border-top: 2px solid var(--border-color);">
							<?php if ( $prev_page ) : ?>
								<a href="<?php echo get_permalink( $prev_page->ID ); ?>" style="display: flex; flex-direction: column; text-decoration: none;">
									<span style="color: var(--text-light); font-size: 0.875rem;">← Vorherige Seite</span>
									<span style="color: var(--primary-color); font-weight: 600; margin-top: 0.25rem;">
										<?php echo esc_html( $prev_page->post_title ); ?>
									</span>
								</a>
							<?php else : ?>
								<div></div>
							<?php endif; ?>
							
							<?php if ( $next_page ) : ?>
								<a href="<?php echo get_permalink( $next_page->ID ); ?>" style="display: flex; flex-direction: column; text-align: right; text-decoration: none;">
									<span style="color: var(--text-light); font-size: 0.875rem;">Nächste Seite →</span>
									<span style="color: var(--primary-color); font-weight: 600; margin-top: 0.25rem;">
										<?php echo esc_html( $next_page->post_title ); ?>
									</span>
								</a>
							<?php endif; ?>
						</nav>
					<?php endif; ?>
				</article>
				
			</div>
		</div>
	</div>

<?php endwhile; ?>

<?php get_footer(); ?>
