<?php
/**
 * Header Media Options
 *
 * @package Mipress
 */

/**
 * Add Header Media options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mipress_header_media_options( $wp_customize ) {
	$wp_customize->get_section( 'header_image' )->description = esc_html__( 'If you add video, it will only show up on Homepage/FrontPage. Other Pages will use Header/Post/Page Image depending on your selection of option. Header Image will be used as a fallback while the video loads ', 'mipress' );

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_header_media_option',
			'default'           => 'entire-site-page-post',
			'sanitize_callback' => 'mipress_sanitize_select',
			'choices'           => array(
				'homepage'               => esc_html__( 'Homepage / Frontpage', 'mipress' ),
				'exclude-home'           => esc_html__( 'Excluding Homepage', 'mipress' ),
				'exclude-home-page-post' => esc_html__( 'Excluding Homepage, Page/Post Featured Image', 'mipress' ),
				'entire-site'            => esc_html__( 'Entire Site', 'mipress' ),
				'entire-site-page-post'  => esc_html__( 'Entire Site, Page/Post Featured Image', 'mipress' ),
				'pages-posts'            => esc_html__( 'Pages and Posts', 'mipress' ),
				'disable'                => esc_html__( 'Disabled', 'mipress' ),
			),
			'label'             => esc_html__( 'Enable on', 'mipress' ),
			'section'           => 'header_image',
			'type'              => 'select',
			'priority'          => 1,
		)
	);
}
add_action( 'customize_register', 'mipress_header_media_options' );
