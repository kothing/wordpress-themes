<?php
/**
 * Theme Customizer
 *
 * @package Mipress
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mipress_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	$wp_customize->get_setting( 'header_image' )->transport     = 'refresh';


	/**
	 * Here, we are removing the default display_header_text option and adding our won option that will cover this option as well
	 */
	$wp_customize->remove_control( 'display_header_text' );
    
    /* 
     * Title tagline. 
     */
	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_header_text',
			'default'           => 'homepage',
			'description'       => esc_html__( 'When disabled/shown only on homepage, Site Title and Tagline will only be removed only from user view for accessibility purpose.', 'mipress' ),
			'sanitize_callback' => 'mipress_sanitize_select',
			'label'             => esc_html__( 'Enable on', 'mipress' ),
			'section'           => 'title_tagline',
			'type'              => 'select',
			'choices'           => mipress_section_visibility_options(),
			'priority'          => 1,
		)
	);
    
	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_header_content_alignment',
			'default'           => 'align-center',
			'sanitize_callback' => 'mipress_sanitize_select',
			'active_callback'   => 'mipress_header_text_enabled',
			'label'             => esc_html__( 'Content Alignment', 'mipress' ),
			'section'           => 'title_tagline',
			'type'              => 'radio',
			'choices'           => array(
				'align-center' => esc_html__( 'Center Align', 'mipress' ),
				'align-left'   => esc_html__( 'Left Align', 'mipress' ),
				'align-right'  => esc_html__( 'Right Align', 'mipress' ),
			),
			'priority'         => 2,
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.site-title a',
			'container_inclusive' => false,
			'render_callback' => 'mipress_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector' => '.site-description',
			'container_inclusive' => false,
			'render_callback' => 'mipress_customize_partial_blogdescription',
		) );
	}

	/* 
     * Reset all settings to default. 
     */
	$wp_customize->add_section( 'mipress_reset_all', array(
		'description'   => esc_html__( 'Caution: Reset all settings to default. Refresh the page after save to view full effects.', 'mipress' ),
		'title'         => esc_html__( 'Reset all settings', 'mipress' ),
		'priority'      => 998,
	) );

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_reset_all_settings',
			'sanitize_callback' => 'mipress_sanitize_checkbox',
			'label'             => esc_html__( 'Check to reset all settings to default', 'mipress' ),
			'section'           => 'mipress_reset_all',
			'transport'         => 'postMessage',
			'type'              => 'checkbox',
		)
	);
	// Reset all settings to default end.
}
add_action( 'customize_register', 'mipress_customize_register' );

/** Active Callback Functions **/
if ( ! function_exists( 'mipress_header_text_enabled' ) ) :
	/**
	* Return true if header text is enabled
	*
	* @since Mipress 0.1
	*/
	function mipress_header_text_enabled( $control ) {
		$enable = $control->manager->get_setting( 'mipress_header_text' )->value();

		//return true only if previewed page on customizer matches the type of content option selected
		return ( mipress_check_section( $enable ) );
	}
endif;


/**
 * Render the site title for the selective refresh partial.
 *
 * @since Mipress 0.1
 * @see mipress_customize_register()
 *
 * @return void
 */
function mipress_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @since Mipress 0.1
 * @see mipress_customize_register()
 *
 * @return void
 */
function mipress_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function mipress_customize_preview_js() {
	wp_enqueue_script( 'mipress-customize-preview', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/customize-preview.min.js', array( 'customize-preview' ), '20180103', true );
}
add_action( 'customize_preview_init', 'mipress_customize_preview_js' );


/**
 * Include Custom Controls
 */
require get_parent_theme_file_path( 'inc/customizer/custom-controls.php' );

/**
 * Include Header Media Options
 */
require get_parent_theme_file_path( 'inc/customizer/header-media.php' );

/**
 * Include Theme Options
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options.php' );

/**
 * Include Customizer Helper Functions
 */
require get_parent_theme_file_path( 'inc/customizer/helpers.php' );

/**
 * Include Sanitization functions
 */
require get_parent_theme_file_path( 'inc/customizer/sanitize-functions.php' );


