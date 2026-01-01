<?php
/**
 * Main Index Template
 * 
 * @package CTS_Demo_Theme
 * @since 1.0.0
 */

get_header();
?>

<?php if ( is_home() || is_front_page() ) : ?>
	
	<!-- Hero Section -->
	<div style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; padding: 4rem 0;">
		<div class="container" style="text-align: center;">
			<h1 style="font-size: 3rem; margin: 0 0 1rem; color: white;">ChurchTools Suite</h1>
			<p style="font-size: 1.25rem; margin: 0 0 2rem; opacity: 0.9;">WordPress Integration für ChurchTools – Termine, Kalender und Services nahtlos präsentieren</p>
			
			<div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
				<a href="<?php echo home_url( '/demos/' ); ?>" class="button button-large" style="background: white; color: var(--primary-color); padding: 1rem 2rem; text-decoration: none; border-radius: 4px; font-weight: 600;">
					Live Demos ansehen
				</a>
				<a href="<?php echo home_url( '/download/' ); ?>" class="button button-large button-outline" style="background: transparent; color: white; border: 2px solid white; padding: 1rem 2rem; text-decoration: none; border-radius: 4px; font-weight: 600;">
					Plugin herunterladen
				</a>
			</div>
		</div>
	</div>
	
	<!-- Features Section -->
	<div style="padding: 4rem 0; background: #f9fafb;">
		<div class="container">
			<h2 style="text-align: center; margin: 0 0 3rem; font-size: 2rem;">Features</h2>
			
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
				
				<!-- Feature 1 -->
				<div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
					<div style="font-size: 3rem; margin-bottom: 1rem;">📅</div>
					<h3 style="margin: 0 0 1rem;">Kalender-Integration</h3>
					<p style="color: var(--text-light); line-height: 1.6; margin: 0;">
						Synchronisiere ChurchTools Kalender mit deiner WordPress-Website. Mehrere Ansichten verfügbar: Monatlich, Wöchentlich, Täglich.
					</p>
				</div>
				
				<!-- Feature 2 -->
				<div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
					<div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
					<h3 style="margin: 0 0 1rem;">Event-Listen</h3>
					<p style="color: var(--text-light); line-height: 1.6; margin: 0;">
						Zeige Events als Listen oder Grids. Classic, Modern, Fluent, Liquid – viele Designs zur Auswahl.
					</p>
				</div>
				
				<!-- Feature 3 -->
				<div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
					<div style="font-size: 3rem; margin-bottom: 1rem;">🎨</div>
					<h3 style="margin: 0 0 1rem;">Template-System</h3>
					<p style="color: var(--text-light); line-height: 1.6; margin: 0;">
						Überschreibe Templates in deinem Theme. Volle Kontrolle über Design und Layout.
					</p>
				</div>
				
				<!-- Feature 4 -->
				<div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
					<div style="font-size: 3rem; margin-bottom: 1rem;">🔄</div>
					<h3 style="margin: 0 0 1rem;">Auto-Sync</h3>
					<p style="color: var(--text-light); line-height: 1.6; margin: 0;">
						Automatische Synchronisation mit ChurchTools. Inkrementelle Syncs für optimale Performance.
					</p>
				</div>
				
				<!-- Feature 5 -->
				<div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
					<div style="font-size: 3rem; margin-bottom: 1rem;">🧩</div>
					<h3 style="margin: 0 0 1rem;">Gutenberg & Elementor</h3>
					<p style="color: var(--text-light); line-height: 1.6; margin: 0;">
						Nahtlose Integration in Gutenberg Block Editor und Elementor Page Builder.
					</p>
				</div>
				
				<!-- Feature 6 -->
				<div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
					<div style="font-size: 3rem; margin-bottom: 1rem;">⚡</div>
					<h3 style="margin: 0 0 1rem;">Performance</h3>
					<p style="color: var(--text-light); line-height: 1.6; margin: 0;">
						Repository-Pattern, Prepared Statements, Caching – optimiert für große Event-Mengen.
					</p>
				</div>
				
			</div>
		</div>
	</div>
	
	<!-- Quick Start -->
	<div style="padding: 4rem 0;">
		<div class="container">
			<h2 style="text-align: center; margin: 0 0 3rem; font-size: 2rem;">Quick Start</h2>
			
			<div style="max-width: 600px; margin: 0 auto;">
				<ol style="font-size: 1.125rem; line-height: 2;">
					<li><strong>Plugin herunterladen</strong> von <a href="<?php echo home_url( '/download/' ); ?>">Download-Seite</a></li>
					<li><strong>In WordPress installieren</strong> (Plugins → Installieren → Hochladen)</li>
					<li><strong>ChurchTools API konfigurieren</strong> (Einstellungen → ChurchTools Suite)</li>
					<li><strong>Kalender synchronisieren</strong> (Kalender-Tab → Sync)</li>
					<li><strong>Shortcode einfügen</strong> z.B. <code>[cts_calendar view="monthly-modern"]</code></li>
				</ol>
				
				<div style="text-align: center; margin-top: 2rem;">
					<a href="<?php echo home_url( '/documentation/installation/' ); ?>" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">
						→ Ausführliche Installationsanleitung
					</a>
				</div>
			</div>
		</div>
	</div>
	
<?php else : ?>
	
	<!-- Blog/Archive View -->
	<div class="container">
		<div class="page-content">
			
			<?php if ( have_posts() ) : ?>
				
				<?php while ( have_posts() ) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post' ); ?>>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						
						<div class="post-meta" style="color: var(--text-light); font-size: 0.875rem; margin-bottom: 1rem;">
							<?php echo get_the_date(); ?> | <?php the_author(); ?>
						</div>
						
						<div class="post-excerpt">
							<?php the_excerpt(); ?>
						</div>
						
						<a href="<?php the_permalink(); ?>" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">
							Weiterlesen →
						</a>
					</article>
					
				<?php endwhile; ?>
				
				<div class="pagination" style="margin-top: 3rem;">
					<?php
					the_posts_pagination( array(
						'mid_size' => 2,
						'prev_text' => __( '← Vorherige', 'cts-demo-theme' ),
						'next_text' => __( 'Nächste →', 'cts-demo-theme' ),
					) );
					?>
				</div>
				
			<?php else : ?>
				
				<p><?php _e( 'Keine Beiträge gefunden.', 'cts-demo-theme' ); ?></p>
				
			<?php endif; ?>
			
		</div>
	</div>
	
<?php endif; ?>

<?php get_footer(); ?>
