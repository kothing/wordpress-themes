<?php
/**
 * Contact options
 *
 * @package Mipress
 */

/**
 * Add contact options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mipress_contact_options( $wp_customize ) {
    
    /* Contact */
    $wp_customize->add_section( 'mipress_f_contact', array(
			'title' => esc_html__( 'Contact', 'mipress' ),
			'panel' => 'mipress_theme_options',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_contact_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'mipress_sanitize_select',
			'choices'           => mipress_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'mipress' ),
			'section'           => 'mipress_f_contact',
			'type'              => 'select',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_contact_title',
			'default'           => esc_html__( 'Contact', 'mipress' ),
			'sanitize_callback' => 'wp_kses_data',
			'active_callback'   => 'mipress_is_contact_active',
			'label'             => esc_html__( 'Title', 'mipress' ),
			'section'           => 'mipress_f_contact',
			'type'              => 'text',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_contact_description',
			'default'           => wp_kses_data( __( 'For further details about my services, availability and inquiry, please fell free to contact me with the information below', 'mipress' ) ),
			'sanitize_callback' => 'wp_kses_data',
			'active_callback'   => 'mipress_is_contact_active',
			'label'             => esc_html__( 'Description', 'mipress' ),
			'section'           => 'mipress_f_contact',
			'type'              => 'textarea',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_contact_phone_title',
			'default'           => esc_html__( 'Phone', 'mipress' ),
			'sanitize_callback' => 'sanitize_text_field',
			'active_callback'   => 'mipress_is_contact_active',
			'label'             => esc_html__( 'Phone Title', 'mipress' ),
			'section'           => 'mipress_f_contact',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_contact_phone',
			'default'           => '123-456-7890',
			'sanitize_callback' => 'sanitize_text_field',
			'active_callback'   => 'mipress_is_contact_active',
			'label'             => esc_html__( 'Phone', 'mipress' ),
			'section'           => 'mipress_f_contact',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_contact_email_title',
			'default'           => esc_html__( 'Email', 'mipress' ),
			'sanitize_callback' => 'sanitize_text_field',
			'active_callback'   => 'mipress_is_contact_active',
			'label'             => esc_html__( 'Email Title', 'mipress' ),
			'section'           => 'mipress_f_contact',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_contact_email',
			'default'           => 'someone@somewhere.com',
			'sanitize_callback' => 'sanitize_email',
			'active_callback'   => 'mipress_is_contact_active',
			'label'             => esc_html__( 'Email', 'mipress' ),
			'section'           => 'mipress_f_contact',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_contact_address_title',
			'default'           => esc_html__( 'Address', 'mipress' ),
			'sanitize_callback' => 'sanitize_text_field',
			'active_callback'   => 'mipress_is_contact_active',
			'label'             => esc_html__( 'Address Title', 'mipress' ),
			'section'           => 'mipress_f_contact',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_contact_address',
			'default'           => '1 Changan Road, Beijing',
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'mipress_is_contact_active',
			'label'             => esc_html__( 'Address', 'mipress' ),
			'section'           => 'mipress_f_contact',
			'type'              => 'textarea',
		)
	);

	mipress_register_option( $wp_customize, array(
			'name'              => 'mipress_contact_page',
			'sanitize_callback' => 'mipress_sanitize_post',
			'active_callback'   => 'mipress_is_contact_active',
			'label'             => esc_html__( 'Page', 'mipress' ),
			'section'           => 'mipress_f_contact',
			'type'              => 'dropdown-pages',
		)
	);
}
add_action( 'customize_register', 'mipress_contact_options', 10 );

/** Active Callback Functions **/
if ( ! function_exists( 'mipress_is_contact_active' ) ) :
	/**
	* Return true if contact is active
	*
	* @since Mipress 0.1
	*/
	function mipress_is_contact_active( $control ) {
		$enable = $control->manager->get_setting( 'mipress_contact_option' )->value();

		//return true only if previewed page on customizer matches the type of content option selected
		return ( mipress_check_section( $enable ) );
	}
endif;
