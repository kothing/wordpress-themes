<?php
/**
 * Pagination options
 *
 * @package Mipress
 */

/**
 * Add pagination options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mipress_pagination_options( $wp_customize ) {
    
	/* Pagination Options */
	$pagination_type = get_theme_mod( 'mipress_pagination_type', 'default' );

	$wp_customize->add_section( 'mipress_k_pagination', array(
		'panel'       => 'mipress_theme_options',
		'title'       => esc_html__( 'Pagination Options', 'mipress' ),
	) );

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_pagination_type',
			'default'           => 'default',
			'sanitize_callback' => 'mipress_sanitize_select',
			'choices'           => mipress_get_pagination_types(),
			'label'             => esc_html__( 'Pagination type', 'mipress' ),
			'section'           => 'mipress_k_pagination',
			'type'              => 'select',
		)
	);
}
add_action( 'customize_register', 'mipress_pagination_options', 10 );
