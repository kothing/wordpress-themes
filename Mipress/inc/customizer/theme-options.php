<?php
/**
 * Theme Options
 *
 * @package Mipress
 */

/**
 * Add theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mipress_theme_options( $wp_customize ) {
    
    /* Theme Options */
	$wp_customize->add_panel( 'mipress_theme_options', array(
		'title'    => esc_html__( 'Theme Options', 'mipress' ),
		'priority' => 130,
	) );

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_reset_typography',
			'sanitize_callback' => 'mipress_sanitize_checkbox',
			'transport'         => 'postMessage',
			'label'             => esc_html__( 'Check to reset fonts', 'mipress' ),
			'section'           => 'mipress_font_family',
			'type'              => 'checkbox',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_latest_posts_title',
			'default'           => esc_html__( 'News', 'mipress' ),
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Latest Posts Title', 'mipress' ),
			'section'           => 'mipress_theme_options',
		)
	);

}
add_action( 'customize_register', 'mipress_theme_options' );

if( ! function_exists( 'mipress_is_recent_posts_on_static_page_enabled' ) ) :
	/**
	* Return true if recent posts on static page enabled
	*
	* @since Mipress Pro 1.0
	*/
	function mipress_is_recent_posts_on_static_page_enabled( $control ) {
		$is_home_and_blog = is_home() && is_front_page();
		return ( $is_home_and_blog );
	}
endif;



/**
 * Include Featured Slider
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options-a-featured-slider.php' );

/**
 * Include Hero Content
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options-b-hero-content.php' );

/**
 * Include Project Slider
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options-c-project-slider.php' );

/**
 * Include Front blog functions
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options-d-front-blog.php' );

/**
 * Include Services
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options-e-services.php' );

/**
 * Include Contact
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options-f-contact.php' );



/**
 * Include Layout functions
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options-g-layout.php' );

/**
 * Include Breadcrumb
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options-h-breadcrumb.php' );

/**
 * Include Excerpt functions
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options-i-excerpt.php' );

/**
 * Include Search functions
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options-j-search.php' );

/**
 * Include Pagination functions
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options-k-pagination.php' );

/**
 * Include Scrollup functions
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options-l-scrollup.php' );

