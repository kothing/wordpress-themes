<?php
/**
 * Search options
 *
 * @package Mipress
 */

/**
 * Add search options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mipress_search_options( $wp_customize ) {
    
	/* Search Options */
	$wp_customize->add_section( 'mipress_j_search', array(
		'panel'     => 'mipress_theme_options',
		'title'     => esc_html__( 'Search Options', 'mipress' ),
	) );

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_search_text',
			'default'           => esc_html__( 'Search', 'mipress' ),
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Search Text', 'mipress' ),
			'section'           => 'mipress_j_search',
			'type'              => 'text',
		)
	);
}
add_action( 'customize_register', 'mipress_search_options', 10 );
