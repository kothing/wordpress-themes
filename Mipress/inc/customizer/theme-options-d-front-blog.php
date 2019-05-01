<?php
/**
 * Front blog options
 *
 * @package Mipress
 */

/**
 * Add front blog options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mipress_front_blog_options( $wp_customize ) {
    
	// Blog Options.
	$wp_customize->add_section( 'mipress_d_front_blog', array(
		'description' => esc_html__( 'Only posts that belong to the categories selected here will be displayed on the front page', 'mipress' ),
		'panel'       => 'mipress_theme_options',
		'title'       => esc_html__( 'Front Blog Options', 'mipress' ),
	) );

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_front_page_category',
			'sanitize_callback' => 'mipress_sanitize_category_list',
			'custom_control'    => 'Mipress_Multi_Categories_Control',
			'label'             => esc_html__( 'Categories', 'mipress' ),
			'section'           => 'mipress_d_front_blog',
			'type'              => 'dropdown-categories',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_recent_posts_heading',
			'sanitize_callback' => 'sanitize_text_field',
			'active_callback'   => 'mipress_is_recent_posts_on_static_page_enabled',
			'default'           => esc_html__( 'Blog', 'mipress' ),
			'label'             => esc_html__( 'Recent Posts Heading', 'mipress' ),
			'section'           => 'mipress_d_front_blog',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_recent_posts_subheading',
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'mipress_is_recent_posts_on_static_page_enabled',
			'default'           => esc_html__( 'Follow my blogs about intresting stuff.', 'mipress' ),
			'label'             => esc_html__( 'Recent Posts Sub Heading', 'mipress' ),
			'section'           => 'mipress_d_front_blog',
			'type'              => 'textarea'
		)
	);
}
add_action( 'customize_register', 'mipress_front_blog_options', 10 );
