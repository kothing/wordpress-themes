<?php
/**
 * Breadcrumb options
 *
 * @package Mipress
 */

/**
 * Add breadcrumb options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mipress_breadcrumb_options( $wp_customize ) {

	// Breadcrumb Option.
	$wp_customize->add_section( 'mipress_h_breadcrumb', array(
            'description' => esc_html__( 'Breadcrumbs are a great way of letting your visitors find out where they are on your site with just a glance.', 'mipress' ),
            'panel'       => 'mipress_theme_options',
            'title'       => esc_html__( 'Breadcrumb', 'mipress' ),
        )
    );

    mipress_register_option( $wp_customize, array(
            'name'              => 'mipress_breadcrumb_option',
            'default'           => 1,
            'sanitize_callback' => 'mipress_sanitize_checkbox',
            'label'             => esc_html__( 'Check to enable Breadcrumb', 'mipress' ),
            'section'           => 'mipress_h_breadcrumb',
            'type'              => 'checkbox',
        )
    );

    mipress_register_option( $wp_customize, array(
            'name'              => 'mipress_breadcrumb_on_homepage',
            'sanitize_callback' => 'mipress_sanitize_checkbox',
            'label'             => esc_html__( 'Check to enable Breadcrumb on Homepage', 'mipress' ),
            'section'           => 'mipress_h_breadcrumb',
            'type'              => 'checkbox',
        )
    );

    mipress_register_option( $wp_customize, array(
            'name'              => 'mipress_breadcrumb_seperator',
            'default'           => '/',
            'sanitize_callback' => 'wp_kses_data',
            'input_attrs'       => array(
                'style' => 'width: 100px;'
            ),
            'label'             => esc_html__( 'Separator between Breadcrumbs', 'mipress' ),
            'section'           => 'mipress_h_breadcrumb',
            'type'              => 'text'
        )
    );
}
add_action( 'customize_register', 'mipress_breadcrumb_options', 10 );