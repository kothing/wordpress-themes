<?php
/**
 * Mipress functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Mipress
 */

if ( ! function_exists( 'mipress_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function mipress_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Mipress, use a find and replace
		 * to change 'mipress' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'mipress', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		set_post_thumbnail_size( 640, 480, true ); // Ratio 3:2

		// Used in portfolio
		add_image_size( 'mipress-portfolio', 640, 480 ); // Ratio 3:2

		// Used in hero content
		add_image_size( 'mipress-hero', 592, 592, true ); // Ratio 1:1

		// Used in featured content
		add_image_size( 'mipress-featured', 640, 480, true ); // Ratio 3:2

		// Used in featured slider
		add_image_size( 'mipress-slider', 1920, 954, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1'      => esc_html__( 'Primary', 'mipress' ),
			'social-menu' => esc_html__( 'Social Menu', 'mipress' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 225,
			'width'       => 225,
		) );

		add_editor_style( array( 'assets/css/editor-style.css', mipress_fonts_url() ) );
	}
endif;
add_action( 'after_setup_theme', 'mipress_setup' );

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 *
 */
function mipress_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-2' ) ) {
		$count++;
	}

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		$count++;
	}

	if ( is_active_sidebar( 'sidebar-4' ) ) {
		$count++;
	}

	if ( is_active_sidebar( 'sidebar-5' ) ) {
		$count++;
	}

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
		case '4':
			$class = 'four';
			break;
	}

	if ( $class ) {
		echo 'class="widget-area footer-widget-area ' . esc_attr( $class ) . '"';
	}
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mipress_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mipress_content_width', 920 );
}
add_action( 'after_setup_theme', 'mipress_content_width', 0 );

if ( ! function_exists( 'mipress_template_redirect' ) ) :
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet for different value other than the default one
	 *
	 * @global int $content_width
	 */
	function mipress_template_redirect() {
		$layout = mipress_get_theme_layout();

		if ( 'no-sidebar-full-width' === $layout ) {
			$GLOBALS['content_width'] = 1640;
		}
	}
endif;
add_action( 'template_redirect', 'mipress_template_redirect' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mipress_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'mipress' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'mipress' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 1', 'mipress' ),
		'id'            => 'sidebar-2',
		'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'mipress' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 2', 'mipress' ),
		'id'            => 'sidebar-3',
		'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'mipress' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 3', 'mipress' ),
		'id'            => 'sidebar-4',
		'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'mipress' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'mipress_widgets_init' );

if ( ! function_exists( 'mipress_fonts_url' ) ) :
	/**
	 * Register Google fonts for Mipress Pro
	 *
	 * Create your own mipress_fonts_url() function to override in a child theme.
	 *
	 * @since Mipress 0.1
	 *
	 * @return string Google fonts URL for the theme.
	 */
	function mipress_fonts_url() {
		$fonts_url = '';

		/* Translators: If there are characters in your language that are not
		* supported by Montserrat, translate this to 'off'. Do not translate
		* into your own language.
		*/
		$open_sans = _x( 'on', 'Open Sans: on or off', 'mipress' );

		/* Translators: If there are characters in your language that are not
		* supported by Playfair Display, translate this to 'off'. Do not translate
		* into your own language.
		*/
		$source_sans = _x( 'on', 'Source Sans Pro font: on or off', 'mipress' );

		if ( 'off' !== $open_sans || 'off' !== $source_sans ) {
			$font_families = array();

			if ( 'off' !== $open_sans ) {
			$font_families[] = 'Open Sans:300,400,600,700,900,300italic,400italic,600italic,700italic,900italic';
			}

			if ( 'off' !== $source_sans ) {
			$font_families[] = 'Source Sans Pro:300,400,600,700,900,300italic,400italic,600italic,700italic,900italic';
			}

			$query_args = array(
				'family' => urlencode( implode( '|', $font_families ) ),
				'subset' => urlencode( 'latin,latin-ext' ),
			);

			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
		}

		return esc_url_raw( $fonts_url );
	}
endif;

if ( ! function_exists( 'mipress_excerpt_length' ) ) :
	/**
	 * Sets the post excerpt length to n words.
	 *
	 * function tied to the excerpt_length filter hook.
	 * @uses filter excerpt_length
	 *
	 * @since Simple Persona Pro 1.0
	 */
	function mipress_excerpt_length( $length ) {
		if ( is_admin() ) {
			return $length;
		}

		// Getting data from Customizer Options
		$length	= get_theme_mod( 'mipress_excerpt_length', 30 );

		return absint( $length );
	}
endif; //simple_persona_excerpt_length
add_filter( 'excerpt_length', 'mipress_excerpt_length', 999 );

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Mipress 0.1
 */
function mipress_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'mipress_javascript_detection', 0 );

/**
 * Enqueue scripts and styles.
 */
function mipress_scripts() {
	wp_enqueue_style( 'mipress-fonts', mipress_fonts_url(), array(), null );

	wp_enqueue_style( 'mipress-style', get_stylesheet_uri() );

	wp_enqueue_script( 'mipress-skip-link-focus-fix', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/skip-link-focus-fix.min.js', array(), '20180111', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script( 'mipress-script', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/functions.min.js', array( 'jquery' ), '20180111', true );

	wp_localize_script( 'mipress-script', 'mipressScreenReaderText', array(
		'expand'   => esc_html__( 'expand child menu', 'mipress' ),
		'collapse' => esc_html__( 'collapse child menu', 'mipress' ),
		'icon'     => mipress_get_svg( array( 'icon' => 'caret-down', 'fallback' => true ) ),
	) );

	//Slider Scripts
	$enable_slider      = get_theme_mod( 'mipress_slider_option', 'disabled' );
	$enable_logo        = get_theme_mod( 'mipress_logo_option', 'homepage' );

	if ( mipress_check_section( $enable_slider ) || mipress_check_section( $enable_testimonial ) || mipress_check_section( $enable_logo ) ) {
		wp_enqueue_script( 'jquery-cycle2', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/jquery.cycle/jquery.cycle2.min.js', array( 'jquery' ), '2.1.5', true );

		$transition_effects = array(
			get_theme_mod( 'mipress_slider_transition_effects', 'fade' ),
		);

		/**
		 * Condition checks for additional slider transition plugins
		 */
		// Scroll Vertical transition plugin addition.
		if ( in_array( 'scrollVert', $transition_effects, true ) ) {
			wp_enqueue_script( 'jquery-cycle2-scrollVert', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/jquery.cycle/jquery.cycle2.scrollVert.min.js', array( 'jquery-cycle2' ), '2.1.5', true );
		}

		// Flip transition plugin addition.
		if ( in_array( 'flipHorz', $transition_effects, true ) || in_array( 'flipVert', $transition_effects, true ) ) {
			wp_enqueue_script( 'jquery-cycle2-flip', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/jquery.cycle/jquery.cycle2.flip.min.js', array( 'jquery-cycle2' ), '2.1.5', true );
		}

		// Shuffle transition plugin addition.
		if ( in_array( 'tileSlide', $transition_effects, true ) || in_array( 'tileBlind', $transition_effects, true ) ) {
			wp_enqueue_script( 'jquery-cycle2-tile', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/jquery.cycle/jquery.cycle2.tile.min.js', array( 'jquery-cycle2' ), '2.1.5', true );
		}

		// Shuffle transition plugin addition.
		if ( in_array( 'shuffle', $transition_effects, true ) ) {
			wp_enqueue_script( 'jquery-cycle2-shuffle', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/jquery.cycle/jquery.cycle2.shuffle.min.js', array( 'jquery-cycle2' ), '2.1.5', true );
		}

		// Carousel transition plugin addition.
		if ( mipress_check_section( $enable_logo ) ) {
			wp_enqueue_script( 'jquery-cycle2-carousel', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/jquery.cycle/jquery.cycle2.carousel.min.js', array( 'jquery-cycle2' ), '2.1.5', true );
		}
	}

	// Enqueue fitvid if JetPack is not installed.
	if ( ! class_exists( 'Jetpack' ) ) {
		wp_enqueue_script( 'jquery-fitvids', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/fitvids.min.js', array( 'jquery' ), '1.1', true );
	}
}
add_action( 'wp_enqueue_scripts', 'mipress_scripts' );

/**
 * SVG icons functions and filters.
 */
require get_parent_theme_file_path( '/inc/icon-functions.php' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Color Scheme additions
 */
require get_template_directory() . '/inc/header-background-color.php';

/**
 * Include Slider
 */
require get_template_directory() . '/inc/featured-slider.php';

/**
 * Include Breadcrumbs
 */
require get_template_directory() . '/inc/breadcrumb.php';

/**
 * Load Metabox
 */
require get_template_directory() . '/inc/metabox/metabox.php';

/**
 * Load Social Widgets
 */
require get_template_directory() . '/inc/widget-social-icons.php';


/**
 * Remove unnecessary functions.
 */
require get_template_directory() . '/inc/remove-needless.php';
