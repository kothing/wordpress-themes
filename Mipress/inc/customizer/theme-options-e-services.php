<?php
/**
 * Services options
 *
 * @package Mipress
 */

/**
 * Add services options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mipress_services_options( $wp_customize ) {
	$wp_customize->add_section( 'mipress_e_services', array(
			'title' => esc_html__( 'Services', 'mipress' ),
			'panel' => 'mipress_theme_options',
		)
	);

	// Add color scheme setting and control.
	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_services_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'mipress_sanitize_select',
			'choices'           => mipress_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'mipress' ),
			'section'           => 'mipress_e_services',
			'type'              => 'select',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_services_bg_image',
			'default'           => trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/images/services-section-bg.jpg',
			'sanitize_callback' => 'esc_url_raw',
			'active_callback'   => 'mipress_is_services_active',
			'custom_control'    => 'WP_Customize_Image_Control',
			'label'             => esc_html__( 'Background Image', 'mipress' ),
			'section'           => 'mipress_e_services',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_services_title',
			'default'           => esc_html__( 'Services', 'mipress' ),
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'mipress_is_services_active',
			'label'             => esc_html__( 'Title', 'mipress' ),
			'section'           => 'mipress_e_services',
			'type'              => 'text',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_services_sub_title',
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'mipress_is_services_active',
			'label'             => esc_html__( 'Sub Title', 'mipress' ),
			'section'           => 'mipress_e_services',
			'type'              => 'textarea',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_services_number',
			'default'           => 4,
			'sanitize_callback' => 'mipress_sanitize_number_range',
			'active_callback'   => 'mipress_is_services_active',
			'description'       => esc_html__( 'Save and refresh the page if No. of items is changed', 'mipress' ),
			'input_attrs'       => array(
				'style' => 'width: 100px;',
				'min'   => 0,
			),
			'label'             => esc_html__( 'No of Items', 'mipress' ),
			'section'           => 'mipress_e_services',
			'type'              => 'number',
			'transport'         => 'postMessage',
		)
	);

	$number = get_theme_mod( 'mipress_services_number', 4 );

	for ( $i = 1; $i <= $number ; $i++ ) {
	
		mipress_register_option( $wp_customize, array(
				'name'              => 'mipress_services_page_' . $i,
				'sanitize_callback' => 'mipress_sanitize_post',
				'active_callback'   => 'mipress_is_services_active',
				'label'             => esc_html__( 'Page', 'mipress' ) . ' ' . $i ,
				'section'           => 'mipress_e_services',
				'type'              => 'dropdown-pages',
			)
		);
	} // End for().
}
add_action( 'customize_register', 'mipress_services_options', 10 );

/** Active Callback Functions **/
if( ! function_exists( 'mipress_is_services_active' ) ) :
	/**
	* Return true if stat is active
	*
	* @since Mipress 0.1
	*/
	function mipress_is_services_active( $control ) {
		$enable = $control->manager->get_setting( 'mipress_services_option' )->value();

		return ( mipress_check_section( $enable ) );
	}
endif;
