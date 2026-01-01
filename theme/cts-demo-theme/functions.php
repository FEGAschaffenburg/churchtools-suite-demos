<?php
/**
 * ChurchTools Suite Demo Theme Functions
 *
 * @package CTS_Demo_Theme
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme Setup
 */
function cts_demo_theme_setup() {
	// Add theme support
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script'
	) );
	
	// Register navigation menus
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'cts-demo' ),
		'footer'  => __( 'Footer Menu', 'cts-demo' ),
	) );
}
add_action( 'after_setup_theme', 'cts_demo_theme_setup' );

/**
 * Enqueue Scripts and Styles
 */
function cts_demo_theme_scripts() {
	// Theme stylesheet
	wp_enqueue_style( 'cts-demo-style', get_stylesheet_uri(), array(), '1.0.0' );
	
	// Prism.js for syntax highlighting
	wp_enqueue_style( 'prism-css', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css', array(), '1.29.0' );
	wp_enqueue_script( 'prism-js', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js', array(), '1.29.0', true );
	
	// Copy to clipboard functionality
	wp_enqueue_script( 'cts-demo-clipboard', get_template_directory_uri() . '/assets/js/clipboard.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'cts_demo_theme_scripts' );

/**
 * Breadcrumbs
 */
function cts_demo_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}
	
	echo '<nav class="breadcrumbs" aria-label="Breadcrumb">';
	echo '<div class="container">';
	echo '<ul>';
	echo '<li><a href="' . home_url() . '">Home</a></li>';
	
	if ( is_page() ) {
		// Parent pages
		global $post;
		if ( $post->post_parent ) {
			$parent_id = $post->post_parent;
			$breadcrumbs = array();
			
			while ( $parent_id ) {
				$page = get_post( $parent_id );
				$breadcrumbs[] = '<li><a href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a></li>';
				$parent_id = $page->post_parent;
			}
			
			$breadcrumbs = array_reverse( $breadcrumbs );
			foreach ( $breadcrumbs as $crumb ) {
				echo $crumb;
			}
		}
		
		// Current page
		echo '<li>' . get_the_title() . '</li>';
	}
	
	echo '</ul>';
	echo '</div>';
	echo '</nav>';
}

/**
 * Custom Page Template Detection
 */
function cts_is_demo_page() {
	return is_page_template( 'page-templates/demo-page.php' );
}

function cts_is_docs_page() {
	return is_page_template( 'page-templates/documentation.php' );
}

/**
 * Get Demo Category
 */
function cts_get_demo_category() {
	global $post;
	
	// Get from custom field or slug
	$category = get_post_meta( $post->ID, 'demo_category', true );
	
	if ( ! $category ) {
		// Try to extract from slug
		$slug = $post->post_name;
		if ( strpos( $slug, 'calendar' ) !== false ) {
			$category = 'calendar';
		} elseif ( strpos( $slug, 'list' ) !== false ) {
			$category = 'list';
		} elseif ( strpos( $slug, 'grid' ) !== false ) {
			$category = 'grid';
		} elseif ( strpos( $slug, 'slider' ) !== false ) {
			$category = 'slider';
		} elseif ( strpos( $slug, 'single' ) !== false ) {
			$category = 'single';
		}
	}
	
	return $category;
}

/**
 * Get Related Demos
 */
function cts_get_related_demos( $category = '', $limit = 3 ) {
	global $post;
	
	if ( ! $category ) {
		$category = cts_get_demo_category();
	}
	
	$args = array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'post__not_in'   => array( $post->ID ),
		'meta_query'     => array(
			array(
				'key'     => 'demo_category',
				'value'   => $category,
				'compare' => '='
			)
		)
	);
	
	return new WP_Query( $args );
}

/**
 * Demo Code Block
 */
function cts_demo_code_block( $code, $language = 'php', $title = 'Shortcode' ) {
	ob_start();
	?>
	<div class="demo-code">
		<div class="demo-code-header">
			<span><?php echo esc_html( $title ); ?></span>
			<button class="copy-button" data-clipboard-text="<?php echo esc_attr( $code ); ?>">
				Kopieren
			</button>
		</div>
		<pre><code class="language-<?php echo esc_attr( $language ); ?>"><?php echo esc_html( $code ); ?></code></pre>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Demo Preview Block
 */
function cts_demo_preview_block( $shortcode ) {
	ob_start();
	?>
	<div class="demo-preview">
		<?php echo do_shortcode( $shortcode ); ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Add Custom Meta Boxes
 */
function cts_demo_add_meta_boxes() {
	add_meta_box(
		'cts_demo_meta',
		__( 'Demo Settings', 'cts-demo' ),
		'cts_demo_meta_box_callback',
		'page',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'cts_demo_add_meta_boxes' );

/**
 * Meta Box Callback
 */
function cts_demo_meta_box_callback( $post ) {
	wp_nonce_field( 'cts_demo_meta_box', 'cts_demo_meta_box_nonce' );
	
	$category = get_post_meta( $post->ID, 'demo_category', true );
	$shortcode = get_post_meta( $post->ID, 'demo_shortcode', true );
	$difficulty = get_post_meta( $post->ID, 'demo_difficulty', true );
	?>
	
	<p>
		<label for="demo_category"><strong><?php _e( 'Kategorie:', 'cts-demo' ); ?></strong></label><br>
		<select name="demo_category" id="demo_category" style="width: 100%;">
			<option value="">-- Auswählen --</option>
			<option value="calendar" <?php selected( $category, 'calendar' ); ?>>Calendar</option>
			<option value="list" <?php selected( $category, 'list' ); ?>>List</option>
			<option value="grid" <?php selected( $category, 'grid' ); ?>>Grid</option>
			<option value="slider" <?php selected( $category, 'slider' ); ?>>Slider</option>
			<option value="single" <?php selected( $category, 'single' ); ?>>Single Event</option>
			<option value="other" <?php selected( $category, 'other' ); ?>>Sonstiges</option>
		</select>
	</p>
	
	<p>
		<label for="demo_shortcode"><strong><?php _e( 'Shortcode:', 'cts-demo' ); ?></strong></label><br>
		<input type="text" name="demo_shortcode" id="demo_shortcode" value="<?php echo esc_attr( $shortcode ); ?>" style="width: 100%;" placeholder='[cts_list view="classic"]'>
	</p>
	
	<p>
		<label for="demo_difficulty"><strong><?php _e( 'Schwierigkeit:', 'cts-demo' ); ?></strong></label><br>
		<select name="demo_difficulty" id="demo_difficulty" style="width: 100%;">
			<option value="beginner" <?php selected( $difficulty, 'beginner' ); ?>>Anfänger</option>
			<option value="intermediate" <?php selected( $difficulty, 'intermediate' ); ?>>Fortgeschritten</option>
			<option value="advanced" <?php selected( $difficulty, 'advanced' ); ?>>Experte</option>
		</select>
	</p>
	<?php
}

/**
 * Save Meta Box Data
 */
function cts_demo_save_meta_box( $post_id ) {
	// Check nonce
	if ( ! isset( $_POST['cts_demo_meta_box_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['cts_demo_meta_box_nonce'], 'cts_demo_meta_box' ) ) {
		return;
	}
	
	// Check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	
	// Check permissions
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	
	// Save fields
	if ( isset( $_POST['demo_category'] ) ) {
		update_post_meta( $post_id, 'demo_category', sanitize_text_field( $_POST['demo_category'] ) );
	}
	
	if ( isset( $_POST['demo_shortcode'] ) ) {
		update_post_meta( $post_id, 'demo_shortcode', sanitize_text_field( $_POST['demo_shortcode'] ) );
	}
	
	if ( isset( $_POST['demo_difficulty'] ) ) {
		update_post_meta( $post_id, 'demo_difficulty', sanitize_text_field( $_POST['demo_difficulty'] ) );
	}
}
add_action( 'save_post', 'cts_demo_save_meta_box' );
