<?php
/**
 * Scrollup options
 *
 * @package Mipress
 */

/**
 * Add scrollup options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mipress_scrollup_options( $wp_customize ) {
    
	/* Scrollup Options */
	$wp_customize->add_section( 'mipress_l_scrollup', array(
		'panel'    => 'mipress_theme_options',
		'title'    => esc_html__( 'Scrollup Options', 'mipress' ),
	) );

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_disable_scrollup',
			'sanitize_callback' => 'mipress_sanitize_checkbox',
			'label'             => esc_html__( 'Disable Scroll Up', 'mipress' ),
			'section'           => 'mipress_l_scrollup',
			'type'              => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'mipress_scrollup_options', 10 );
