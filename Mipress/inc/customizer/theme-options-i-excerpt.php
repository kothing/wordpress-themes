<?php
/**
 * Excerpt options
 *
 * @package Mipress
 */

/**
 * Add excerpt options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mipress_excerpt_options( $wp_customize ) {
    
	/* Excerpt Options */
	$wp_customize->add_section( 'mipress_i_excerpt', array(
		'panel'     => 'mipress_theme_options',
		'title'     => esc_html__( 'Excerpt Options', 'mipress' ),
	) );

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_excerpt_length',
			'default'           => '20',
			'sanitize_callback' => 'absint',
			'description' => esc_html__( 'Excerpt length. Default is 30 words', 'mipress' ),
			'input_attrs' => array(
				'min'   => 10,
				'max'   => 200,
				'step'  => 5,
				'style' => 'width: 60px;',
			),
			'label'    => esc_html__( 'Excerpt Length (words)', 'mipress' ),
			'section'  => 'mipress_i_excerpt',
			'type'     => 'number',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_excerpt_more_text',
			'default'           => esc_html__( 'Continue reading...', 'mipress' ),
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Read More Text', 'mipress' ),
			'section'           => 'mipress_i_excerpt',
			'type'              => 'text',
		)
	);
}
add_action( 'customize_register', 'mipress_excerpt_options', 10 );
