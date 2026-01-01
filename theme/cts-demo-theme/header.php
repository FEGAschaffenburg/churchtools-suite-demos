<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site-wrapper">
	<header class="site-header">
		<div class="header-container">
			<div class="site-branding">
				<span class="site-logo">📅</span>
				<h1 class="site-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php bloginfo( 'name' ); ?>
					</a>
				</h1>
			</div>
			
			<nav class="main-navigation" role="navigation" aria-label="Primary Navigation">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => '',
					'fallback_cb'    => false,
				) );
				?>
			</nav>
		</div>
	</header>
	
	<main class="site-content">
