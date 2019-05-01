<?php
/**
 * Project Slider Options
 *
 * @package Mipress
 */

/**
 * Add Project Slider options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mipress_project_slider_options( $wp_customize ) {
    
    /* Project Slider */
	$wp_customize->add_section( 'mipress_c_project_slider', array(
			'title' => esc_html__( 'Project Slider', 'mipress' ),
			'panel' => 'mipress_theme_options',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_project_slider_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'mipress_sanitize_select',
			'choices'           => mipress_section_visibility_options(),
			'label'             => esc_html__( 'Enable Project Slider on', 'mipress' ),
			'section'           => 'mipress_c_project_slider',
			'type'              => 'select',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_project_slider_bg_image',
			'default'           => trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/images/clients-section-bg.jpg',
			'sanitize_callback' => 'esc_url_raw',
			'active_callback'   => 'mipress_is_project_slider_active',
			'custom_control'    => 'WP_Customize_Image_Control',
			'label'             => esc_html__( 'Background Image', 'mipress' ),
			'section'           => 'mipress_c_project_slider',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_project_slider_title',
			'default'           => esc_html__( 'Project Slider', 'mipress' ),
			'sanitize_callback' => 'sanitize_text_field',
			'active_callback'   => 'mipress_is_project_slider_active',
			'label'             => esc_html__( 'Title', 'mipress' ),
			'section'           => 'mipress_c_project_slider',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_project_slider_sub_title',
			'sanitize_callback' => 'sanitize_text_field',
			'active_callback'   => 'mipress_is_project_slider_active',
			'label'             => esc_html__( 'Sub Title', 'mipress' ),
			'section'           => 'mipress_c_project_slider',
			'type'              => 'textarea',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_project_slider_number',
			'default'           => 4,
			'sanitize_callback' => 'mipress_sanitize_number_range',
			'active_callback'   => 'mipress_is_project_slider_active',
			'description'       => esc_html__( 'Save and refresh the page if No. of Items is changed', 'mipress' ),
			'input_attrs'       => array(
				'style' => 'width: 45px;',
				'min'   => 0,
			),
			'label'             => esc_html__( 'No of Items', 'mipress' ),
			'section'           => 'mipress_c_project_slider',
			'type'              => 'number',
		)
	);


	//loop for featured post sliders
	for ( $i=1; $i <= get_theme_mod( 'mipress_project_slider_number', 4 ); $i++ ) {

		//page content
		mipress_register_option( $wp_customize, array(
				'name'              => 'mipress_project_slider_page_'. $i,
				'sanitize_callback' => 'mipress_sanitize_post',
				'active_callback'   => 'mipress_is_project_slider_active',
				'label'             => esc_html__( 'Page', 'mipress' ) . ' ' . $i ,
				'section'           => 'mipress_c_project_slider',
				'type'              => 'dropdown-pages',
			)
		);
	}
}
add_action( 'customize_register', 'mipress_project_slider_options', 10 );

/** Active Callback Functions **/
if ( ! function_exists( 'mipress_is_project_slider_active' ) ) :
	/**
	* Return true if project_slider is active
	*
	* @since Mipress 1.0
	*/
	function mipress_is_project_slider_active( $control ) {
		$enable = $control->manager->get_setting( 'mipress_project_slider_option' )->value();

		return ( mipress_check_section( $enable ) );
	}
endif;
