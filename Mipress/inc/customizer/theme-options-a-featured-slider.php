<?php
/**
 * Featured Slider Options
 *
 * @package Mipress
 */

/**
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mipress_slider_options( $wp_customize ) {
    
    /* Featured Slider */
	$wp_customize->add_section( 'mipress_a_featured_slider', array(
			'panel' => 'mipress_theme_options',
			'title' => esc_html__( 'Featured Slider', 'mipress' ),
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_slider_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'mipress_sanitize_select',
			'choices'           => mipress_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'mipress' ),
			'section'           => 'mipress_a_featured_slider',
			'type'              => 'select',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_slider_transition_effect',
			'default'           => 'fade',
			'sanitize_callback' => 'mipress_sanitize_select',
			'active_callback'   => 'mipress_is_slider_active',
			'choices'           => mipress_slider_transition_effects(),
			'label'             => esc_html__( 'Transition Effect', 'mipress' ),
			'section'           => 'mipress_a_featured_slider',
			'type'              => 'select',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_slider_transition_delay',
			'default'           => '4',
			'sanitize_callback' => 'absint',
			'active_callback'   => 'mipress_is_slider_active',
			'description'       => esc_html__( 'seconds(s)', 'mipress' ),
			'input_attrs'       => array(
				'style' => 'width: 40px;',
			),
			'label'             => esc_html__( 'Transition Delay', 'mipress' ),
			'section'           => 'mipress_a_featured_slider',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_slider_transition_length',
			'default'           => '1',
			'sanitize_callback' => 'absint',

			'active_callback'   => 'mipress_is_slider_active',
			'description'       => esc_html__( 'seconds(s)', 'mipress' ),
			'input_attrs'       => array(
				'style' => 'width: 100px;',
			),
			'label'             => esc_html__( 'Transition Length', 'mipress' ),
			'section'           => 'mipress_a_featured_slider',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_slider_image_loader',
			'default'           => 'false',
			'sanitize_callback' => 'mipress_sanitize_select',
			'active_callback'   => 'mipress_is_slider_active',
			'choices'           => mipress_slider_image_loader(),
			'label'             => esc_html__( 'Image Loader', 'mipress' ),
			'section'           => 'mipress_a_featured_slider',
			'type'              => 'select',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_slider_number',
			'default'           => '4',
			'sanitize_callback' => 'mipress_sanitize_number_range',

			'active_callback'   => 'mipress_is_slider_active',
			'description'       => esc_html__( 'Save and refresh the page if No. of items is changed', 'mipress' ),
			'input_attrs'       => array(
				'style' => 'width: 45px;',
				'min'   => 0,
				'step'  => 1,
			),
			'label'             => esc_html__( 'No of Items', 'mipress' ),
			'section'           => 'mipress_a_featured_slider',
			'type'              => 'number',
			'transport'         => 'postMessage',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_slider_content_show',
			'default'           => 'hide-content',
			'sanitize_callback' => 'mipress_sanitize_select',
			'active_callback'   => 'mipress_is_slider_active',
			'choices'           => mipress_content_show(),
			'label'             => esc_html__( 'Display Content', 'mipress' ),
			'section'           => 'mipress_a_featured_slider',
			'type'              => 'select',
		)
	);

	$slider_number = get_theme_mod( 'mipress_slider_number', 4 );

	for ( $i = 1; $i <= $slider_number ; $i++ ) {
		// Page Sliders
		mipress_register_option( $wp_customize, array(
				'name'              =>'mipress_slider_page_' . $i,
				'sanitize_callback' => 'mipress_sanitize_post',
				'active_callback'   => 'mipress_is_slider_active',
				'label'             => esc_html__( 'Page', 'mipress' ) . ' # ' . $i,
				'section'           => 'mipress_a_featured_slider',
				'type'              => 'dropdown-pages',
			)
		);
	} // End for().
}
add_action( 'customize_register', 'mipress_slider_options' );


/**
 * Returns an array of feature slider transition effects
 *
 * @since Mipress 0.1
 */
function mipress_slider_transition_effects() {
	$options = array(
		'fade'       => esc_html__( 'Fade', 'mipress' ),
		'fadeout'    => esc_html__( 'Fade Out', 'mipress' ),
		'none'       => esc_html__( 'None', 'mipress' ),
		'scrollHorz' => esc_html__( 'Scroll Horizontal', 'mipress' ),
		'scrollVert' => esc_html__( 'Scroll Vertical', 'mipress' ),
		'flipHorz'   => esc_html__( 'Flip Horizontal', 'mipress' ),
		'flipVert'   => esc_html__( 'Flip Vertical', 'mipress' ),
		'tileSlide'  => esc_html__( 'Tile Slide', 'mipress' ),
		'tileBlind'  => esc_html__( 'Tile Blind', 'mipress' ),
		'shuffle'    => esc_html__( 'Shuffle', 'mipress' ),
	);

	return apply_filters( 'mipress_slider_transition_effects', $options );
}


/**
 * Returns an array of featured slider image loader options
 *
 * @since Mipress 0.1
 */
function mipress_slider_image_loader() {
	$options = array(
		'true'  => esc_html__( 'True', 'mipress' ),
		'wait'  => esc_html__( 'Wait', 'mipress' ),
		'false' => esc_html__( 'False', 'mipress' ),
	);

	return apply_filters( 'mipress_slider_image_loader', $options );
}


/**
 * Returns an array of featured content show registered
 *
 * @since Mipress 0.1
 */
function mipress_content_show() {
	$options = array(
		'excerpt'      => esc_html__( 'Show Excerpt', 'mipress' ),
		'full-content' => esc_html__( 'Full Content', 'mipress' ),
		'hide-content' => esc_html__( 'Hide Content', 'mipress' ),
	);
	return apply_filters( 'mipress_content_show', $options );
}

/** Active Callback Functions */

if( ! function_exists( 'mipress_is_slider_active' ) ) :
	/**
	* Return true if slider is active
	*
	* @since Mipress 0.1
	*/
	function mipress_is_slider_active( $control ) {
		$enable = $control->manager->get_setting( 'mipress_slider_option' )->value();

		return ( mipress_check_section( $enable ) );
	}
endif;