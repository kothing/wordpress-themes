<?php
/**
 * Layout options
 *
 * @package Mipress
 */

/**
 * Add layout options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mipress_layout_options( $wp_customize ) {
    
	// Layout Options
	$wp_customize->add_section( 'mipress_g_layout', array(
		'title' => esc_html__( 'Layout Options', 'mipress' ),
		'panel' => 'mipress_theme_options',
		)
	);

	/* Layout Type */
	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_layout_type',
			'default'           => 'fluid',
			'sanitize_callback' => 'mipress_sanitize_select',
			'label'             => esc_html__( 'Site Layout', 'mipress' ),
			'section'           => 'mipress_g_layout',
			'type'              => 'radio',
			'choices'           => array(
				'fluid' => esc_html__( 'Fluid', 'mipress' ),
				'boxed' => esc_html__( 'Boxed', 'mipress' ),
			),
		)
	);

	/* Default Layout */
	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_default_layout',
			'default'           => 'no-sidebar',
			'sanitize_callback' => 'mipress_sanitize_select',
			'label'             => esc_html__( 'Default Layout', 'mipress' ),
			'section'           => 'mipress_g_layout',
			'type'              => 'radio',
			'choices'           => array(
				'right-sidebar'         => esc_html__( 'Right Sidebar ( Content, Primary Sidebar )', 'mipress' ),
				'no-sidebar'            => esc_html__( 'No Sidebar', 'mipress' ),
			),
		)
	);

	/* Homepage/Archive Layout */
	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_homepage_archive_layout',
			'default'           => 'no-sidebar',
			'sanitize_callback' => 'mipress_sanitize_select',
			'label'             => esc_html__( 'Homepage/Archive Layout', 'mipress' ),
			'section'           => 'mipress_g_layout',
			'type'              => 'radio',
			'choices'           => array(
				'right-sidebar'         => esc_html__( 'Right Sidebar ( Content, Primary Sidebar )', 'mipress' ),
				'no-sidebar'            => esc_html__( 'No Sidebar', 'mipress' ),
			),
		)
	);

	/* Single Page/Post Image Layout */
	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_single_layout',
			'default'           => 'disabled',
			'sanitize_callback' => 'mipress_sanitize_select',
			'label'             => esc_html__( 'Single Page/Post Image Layout', 'mipress' ),
			'section'           => 'mipress_g_layout',
			'type'              => 'radio',
			'choices'           => array(
				'disabled'              => esc_html__( 'Disabled', 'mipress' ),
				'post-thumbnail'        => esc_html__( 'Enable', 'mipress' ),

			),
		)
	);
}
add_action( 'customize_register', 'mipress_layout_options', 10 );
