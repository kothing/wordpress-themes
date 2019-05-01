<?php
/**
 * Hero Content Options
 *
 * @package Mipress
 */

/**
 * Add hero content options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mipress_hero_content_options( $wp_customize ) {
	$wp_customize->add_section( 'mipress_b_hero_content', array(
			'title' => esc_html__( 'Hero Content', 'mipress' ),
			'panel' => 'mipress_theme_options',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_hero_content_visibility',
			'default'           => 'disabled',
			'sanitize_callback' => 'mipress_sanitize_select',
			'choices'           => mipress_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'mipress' ),
			'section'           => 'mipress_b_hero_content',
			'type'              => 'select',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_hero_content',
			'default'           => '0',
			'sanitize_callback' => 'mipress_sanitize_post',
			'active_callback'   => 'mipress_is_hero_content_active',
			'label'             => esc_html__( 'Page', 'mipress' ),
			'section'           => 'mipress_b_hero_content',
			'type'              => 'dropdown-pages',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_disable_hero_content_title',
			'sanitize_callback' => 'mipress_sanitize_checkbox',
			'active_callback'   => 'mipress_is_hero_content_active',
			'label'             => esc_html__( 'Check to disable title', 'mipress' ),
			'section'           => 'mipress_b_hero_content',
			'type'              => 'checkbox',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_hero_content_show',
			'default'           => 'excerpt',
			'sanitize_callback' => 'mipress_sanitize_select',
			'active_callback'   => 'mipress_is_hero_content_active',
			'choices'           => mipress_content_show(),
			'label'             => esc_html__( 'Display Content', 'mipress' ),
			'section'           => 'mipress_b_hero_content',
			'type'              => 'select',
		)
	);
}
add_action( 'customize_register', 'mipress_hero_content_options' );

/** Active Callback Functions **/
if ( ! function_exists( 'mipress_is_hero_content_active' ) ) :
	/**
	* Return true if hero content is active
	*
	* @since Mipress 0.1
	*/
	function mipress_is_hero_content_active( $control ) {
		$enable = $control->manager->get_setting( 'mipress_hero_content_visibility' )->value();

		return ( mipress_check_section( $enable ) );
	}
endif;