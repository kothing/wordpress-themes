<?php
/**
 * Lyove Theme Customizer.
 *
 * @package Lyove
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function lyove_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	// Define main panels for customizer.
	$wp_customize->add_section(
		'lyove_theme_section' , array(
			'title'       => __( 'Theme Options', 'lyove' ),
			'description' => __( 'Options to customize theme elements', 'lyove' ),
			'priority'    => 120,
		)
	);
    
    // Site layout ( Sidebar / Main content) display and positioning options.
	$wp_customize->add_setting(
		'lyove_layout', array(
			'default'           => 'content-sidebar',
			'sanitize_callback' => 'lyove_sanitize_select',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'lyove_layout', array(
			'label'     => esc_html__( 'Content layout', 'lyove' ),
			'section'   => 'lyove_theme_section',
			'settings'  => 'lyove_layout',
			'type'      => 'select',
			'choices'   => array(
				'content-sidebar'   => __( 'Content-Sidebar', 'lyove' ),
				'sidebar-content'   => __( 'Sidebar-Content', 'lyove' ),
				'only-content'      => __( 'Only Content (No sidebar)', 'lyove' ),
			),
		)
	);

	$wp_customize->add_setting(
		'lyove_excerpt_option', array(
			'default'           => 'excerpt',
			'sanitize_callback' => 'lyove_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'lyove_excerpt_option', array(
			'label'     => __( 'Excerpt or full content', 'lyove' ),
			'section'   => 'lyove_theme_section',
			'settings'  => 'lyove_excerpt_option',
			'type'      => 'select',
			'choices'   => array(
				'excerpt' => __( 'Excerpt', 'lyove' ),
				'content' => __( 'Full content', 'lyove' ),
			),
		)
	);

	$wp_customize->add_setting(
		'lyove_theme_color', array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, 'lyove_theme_color', array(
				'label'    => __( 'Color Scheme', 'lyove' ),
				'section'  => 'lyove_theme_section',
				'settings' => 'lyove_theme_color',
			)
		)
	);

	$wp_customize->add_setting(
		'lyove_no_thumbnail_onpost', array(
			'default'           => 0,
			'sanitize_callback' => 'lyove_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'lyove_no_thumbnail_onpost', array(
			'label'     => __( 'Do not display thumbnails on Single post', 'lyove' ),
			'section'   => 'lyove_theme_section',
			'settings'  => 'lyove_no_thumbnail_onpost',
			'type'      => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'lyove_sticky_main_menu', array(
			'default'           => 1,
			'sanitize_callback' => 'lyove_sanitize_checkbox',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'lyove_sticky_main_menu', array(
			'label'     => __( 'Make navigation menu sticky on scroll', 'lyove' ),
			'section'   => 'lyove_theme_section',
			'settings'  => 'lyove_sticky_main_menu',
			'type'      => 'checkbox',
		)
	);
    
	// Add the featured content section in case it's not already there.
	$wp_customize->add_section(
		'featured_content', array(
			'title'           => __( 'Featured Content', 'lyove' ),
			'description'     => sprintf(
				__( 'Use a <a href="%1$s">tag</a> to feature your posts. If no posts match the tag, <a href="%2$s">sticky posts</a> will be displayed instead.', 'lyove' ),
				esc_url( add_query_arg( 'tag', _x( 'featured', 'featured content default tag slug', 'lyove' ), admin_url( 'edit.php' ) ) ),
				admin_url( 'edit.php?show_sticky=1' )
			),
			'priority'        => 120,
			'active_callback' => 'is_front_page',
		)
	);

	// Add the featured content layout setting and control.
	$wp_customize->add_setting(
		'featured_layout', array(
			'default'           => 'grid',
			'sanitize_callback' => 'lyove_sanitize_layout',
		)
	);
	$wp_customize->add_control(
		'featured_layout', array(
			'label'   => __( 'Layout', 'lyove' ),
			'section' => 'featured_content',
			'type'    => 'select',
			'choices' => array(
				'grid'   => __( 'Grid', 'lyove' ),
				'slider' => __( 'Slider', 'lyove' ),
			),
		)
	);
}
add_action( 'customize_register', 'lyove_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function lyove_customize_preview_js() {
	wp_enqueue_script( 'lyove_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'lyove_customize_preview_js' );

/**
 * Sanitize select choices.
 *
 * @param str                  $option  Customizer Option selected.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string
 */
function lyove_sanitize_select( $option, $setting ) {
	$choices = $setting->manager->get_control( $setting->id )->choices;
	if ( array_key_exists( $option, $choices ) ) :
		return $option;
	else :
		return $setting->default;
	endif;
}

/**
 * Validate checkbox value to be '1'
 *
 * @param  bool $option checkbox value.
 * @return bool
 */
function lyove_sanitize_checkbox( $option ) {
	if ( 1 == $option ) :
		return 1;
	else :
		return '';
	endif;
}

/**
 * Sanitize the Featured Content layout value.
 *
 * @since Lyove 1.0
 *
 * @param string $layout Layout type.
 * @return string Filtered layout type (grid|slider).
 */
function lyove_sanitize_layout( $layout ) {
	if ( ! in_array( $layout, array( 'grid', 'slider' ) ) ) {
		$layout = 'grid';
	}

	return $layout;
}