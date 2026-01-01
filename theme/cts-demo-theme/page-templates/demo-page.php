<?php
/**
 * Template Name: Demo Page
 * Description: Template für Live-Demo-Seiten mit Code-Vorschau
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
			
			<?php
			$category = cts_get_demo_category();
			$difficulty = get_post_meta( get_the_ID(), 'demo_difficulty', true );
			?>
			
			<div style="display: flex; gap: 1rem; margin-top: 1rem;">
				<?php if ( $category ) : ?>
					<span class="demo-badge" style="background: var(--primary-color);">
						<?php echo esc_html( ucfirst( $category ) ); ?>
					</span>
				<?php endif; ?>
				
				<?php if ( $difficulty ) : ?>
					<span class="demo-badge" style="background: var(--secondary-color);">
						<?php 
						$difficulty_labels = array(
							'beginner' => 'Anfänger',
							'intermediate' => 'Fortgeschritten',
							'advanced' => 'Experte'
						);
						echo esc_html( $difficulty_labels[ $difficulty ] ?? $difficulty );
						?>
					</span>
				<?php endif; ?>
			</div>
			
			<?php if ( has_excerpt() ) : ?>
				<p class="page-description"><?php the_excerpt(); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<div class="container">
		<div class="page-content">
			
			<?php
			// Get shortcode from meta field
			$shortcode = get_post_meta( get_the_ID(), 'demo_shortcode', true );
			?>
			
			<?php if ( $shortcode ) : ?>
				<!-- Live Demo Section -->
				<div class="demo-section">
					<div class="demo-header">
						<h2 class="demo-title">🎬 Live Demo</h2>
					</div>
					
					<div class="demo-preview">
						<?php echo do_shortcode( $shortcode ); ?>
					</div>
				</div>
				
				<!-- Code Section -->
				<div class="demo-section">
					<div class="demo-header">
						<h2 class="demo-title">💻 Shortcode</h2>
					</div>
					
					<?php echo cts_demo_code_block( $shortcode, 'php', 'Shortcode kopieren' ); ?>
					
					<p style="color: var(--text-light); margin-top: 1rem;">
						Kopiere diesen Shortcode und füge ihn in eine WordPress-Seite oder einen Beitrag ein.
					</p>
				</div>
			<?php endif; ?>
			
			<!-- Content Section -->
			<?php if ( get_the_content() ) : ?>
				<div class="demo-section">
					<div class="demo-header">
						<h2 class="demo-title">📖 Beschreibung</h2>
					</div>
					
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</div>
			<?php endif; ?>
			
			<?php if ( $shortcode ) : ?>
				<!-- Parameters Section -->
				<div class="demo-section">
					<div class="demo-header">
						<h2 class="demo-title">⚙️ Parameter</h2>
					</div>
					
					<div class="demo-params">
						<h4>Verfügbare Parameter:</h4>
						<ul>
							<?php
							// Extract shortcode tag
							preg_match( '/\[(\w+)/', $shortcode, $matches );
							$shortcode_tag = $matches[1] ?? '';
							
							// Common parameters based on shortcode type
							$common_params = array(
								'view' => 'Template-Variante (z.B. "classic", "modern", "fluent")',
								'calendar' => 'Kalender-IDs (kommagetrennt, z.B. "1,2,3")',
								'limit' => 'Maximale Anzahl Events (Standard: 20)',
								'from' => 'Start-Datum (Format: YYYY-MM-DD oder "today")',
								'to' => 'End-Datum (Format: YYYY-MM-DD)',
								'show_services' => 'Dienste anzeigen (true/false)',
								'columns' => 'Anzahl Spalten (Grid, Standard: 3)',
							);
							
							// Display relevant parameters
							foreach ( $common_params as $param => $description ) {
								if ( strpos( $shortcode, $param ) !== false || in_array( $param, ['view', 'calendar', 'limit'] ) ) {
									echo '<li><code>' . esc_html( $param ) . '</code> - ' . esc_html( $description ) . '</li>';
								}
							}
							?>
						</ul>
						
						<p style="margin-top: 1rem;">
							<strong>Vollständige Referenz:</strong> 
							<a href="<?php echo home_url( '/documentation/shortcodes/' ); ?>">Shortcode-Dokumentation →</a>
						</p>
					</div>
				</div>
			<?php endif; ?>
			
			<!-- Related Demos -->
			<?php
			$related = cts_get_related_demos( $category, 3 );
			if ( $related->have_posts() ) :
			?>
				<div class="demo-section">
					<div class="demo-header">
						<h2 class="demo-title">🔗 Ähnliche Demos</h2>
					</div>
					
					<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
						<?php while ( $related->have_posts() ) : $related->the_post(); ?>
							<div style="padding: 1.5rem; background: var(--bg-light); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
								<h3 style="margin: 0 0 0.5rem; font-size: 1.125rem;">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>
								
								<?php if ( has_excerpt() ) : ?>
									<p style="color: var(--text-light); font-size: 0.875rem; margin: 0;">
										<?php echo wp_trim_words( get_the_excerpt(), 15 ); ?>
									</p>
								<?php endif; ?>
								
								<a href="<?php the_permalink(); ?>" style="display: inline-block; margin-top: 1rem; color: var(--primary-color); font-weight: 600; text-decoration: none;">
									Demo ansehen →
								</a>
							</div>
						<?php endwhile; ?>
					</div>
				</div>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
			
		</div>
	</div>

<?php endwhile; ?>

<?php get_footer(); ?>
