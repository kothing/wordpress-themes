<?php
/**
 * Lyove functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Lyove
 */

/**
 * Lyove only works in WordPress 4.5 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.5', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

if ( ! function_exists( 'lyove_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function lyove_setup() {
		/*
		 * Lyove主题本地化翻译加载
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Lyove, use a find and replace
		 * to change 'lyove' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'lyove', get_template_directory() . '/languages' );

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
		set_post_thumbnail_size( 720, 405 );
        add_image_size( 'lyove-full-width', 1100, 618, true );
        add_image_size( 'lyove-small-width', 160, 90, true );

		// Set the default content width.
		$GLOBALS['content_width'] = 720;

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => __( 'Primary', 'lyove' ),
			'social'  => __( 'Social', 'lyove' ),
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
        
        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support(
            'post-formats', array(
                'aside',
                'image',
                'video',
                'quote',
                'link',
                'gallery',
                'audio',
            )
        );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'lyove_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Set up the WordPress core custom logo feature.
		add_theme_support( 'custom-logo', apply_filters( 'lyove_custom_logo_args', array(
			'flex-width'  => true,
			'flex-height' => true,
			'width'       => 100,
			'height'      => 100,
		) ) );
        
        // Add support for featured content.
		add_theme_support(
			'featured-content', array(
				'featured_content_filter' => 'lyove_get_featured_posts',
				'max_posts'               => 6,
			)
		);

		add_editor_style( array( 'assets/css/editor-style.css' ) );
	}
endif;
add_action( 'after_setup_theme', 'lyove_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @since 1.0.0
 *
 * @global int $content_width
 */
function lyove_content_width() {
	$content_width = $GLOBALS['content_width'];
	$GLOBALS['content_width'] = apply_filters( 'lyove_content_width', $content_width );
}
add_action( 'template_redirect' , 'lyove_content_width', 0 );


/**
 * Getter function for Featured Content Plugin.
 *
 * @since Lyove 1.0
 *
 * @return array An array of WP_Post objects.
 */
function lyove_get_featured_posts() {
	/**
	 * Filter the featured posts to return in Lyove.
	 *
	 * @since Lyove 1.0
	 *
	 * @param array|bool $posts Array of featured posts, otherwise false.
	 */
	return apply_filters( 'lyove_get_featured_posts', array() );
}

/**
 * A helper conditional function that returns a boolean value.
 *
 * @since Lyove 1.0
 *
 * @return bool Whether there are featured posts.
 */
function lyove_has_featured_posts() {
	return ! is_paged() && (bool) lyove_get_featured_posts();
}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function lyove_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'lyove' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here.', 'lyove' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s object-non-visible" data-animation-effect="fadeInUpSmall" data-effect-delay="200">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'footer-widget-1', 'lyove' ),
		'id'            => 'footer-1',
		'description'   => __( 'Add footer widgets here.', 'lyove' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s object-non-visible" data-animation-effect="fadeInUpSmall" data-effect-delay="200">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'footer-widget-2', 'lyove' ),
		'id'            => 'footer-2',
		'description'   => __( 'Add footer widgets here.', 'lyove' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s object-non-visible" data-animation-effect="fadeInUpSmall" data-effect-delay="200">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'footer-widget-3', 'lyove' ),
		'id'            => 'footer-3',
		'description'   => __( 'Add footer widgets here.', 'lyove' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s object-non-visible" data-animation-effect="fadeInUpSmall" data-effect-delay="200">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title"><span>',
		'after_title'   => '</span></h2>',
	) );
}
add_action( 'widgets_init', 'lyove_widgets_init' );


/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * This function incorporates code from Twenty Seventeen WordPress Theme,
 * Copyright 2016-2017 WordPress.org. Twenty Seventeen is distributed
 * under the terms of the GNU GPL.
 *
 * @since 1.0.0
 */
function lyove_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'lyove_javascript_detection', 0 );

/**
 * Enqueue scripts and styles.
 */
function lyove_scripts() {

	wp_enqueue_style( 'lyove-style', get_stylesheet_uri() );
    
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/assets/genericons/genericons.css', array(), '3.0.3' );
    
    wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css', array(), '4.7.0' );

	wp_enqueue_script( 'lyove-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '1.0.0', true );

	wp_enqueue_script( 'lyove-sidebar-toggle', get_template_directory_uri() . '/assets/js/sidebar-toggle.js', array( 'jquery' ), '1.0.0', true );

	if ( has_nav_menu( 'primary' ) ) {
		$lyove_l10n = array(
			'expand'   => __( 'Expand child menu', 'lyove' ),
			'collapse' => __( 'Collapse child menu', 'lyove' ),
			'icon'     => lyove_get_svg( array( 'icon' => 'angle-down', 'fallback' => true ) ),
		);
		wp_enqueue_script( 'lyove-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array( 'jquery' ), '1.0.0', true );
		wp_localize_script( 'lyove-navigation', 'lyoveScreenReaderText', $lyove_l10n );
	}
    
    if ( is_front_page() && 'slider' == get_theme_mod( 'featured_layout' ) ) {
		wp_enqueue_script( 'lyove-slider', get_template_directory_uri() . '/assets/js/slider.js', array( 'jquery' ), '1.0.0', true );
		wp_localize_script(
			'lyove-slider', 'featuredSliderDefaults', array(
				'prevText' => __( 'Previous', 'lyove' ),
				'nextText' => __( 'Next', 'lyove' ),
			)
		);
	}
    
    wp_enqueue_script( 'mipress-appear', get_theme_file_uri( '/assets/js/jquery.appear.js' ), array( 'jquery' ), '1.0。0', true );
    
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
    
    wp_enqueue_script( 'smoothScroll', get_theme_file_uri() . '/assets/js/smoothScroll.js', array( 'jquery' ), '1.0.0', true );
    
    wp_enqueue_script( 'lyove-function', get_template_directory_uri() . '/assets/js/functions.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'lyove_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Remove unnecessary functions.
 */
require get_template_directory() . '/inc/remove-needless.php';

/**
 * Add a custom field to the article.
 */
//require get_template_directory() . '/inc/post-fields.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add Featured Content functionality.
 */
if ( ! class_exists( 'Featured_Content' ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {
	require get_template_directory() . '/inc/featured.php';
}

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load font icons functions file.
 */
require get_template_directory() . '/inc/icons.php';
