<?php
/**
 * Customizer functionality
 *
 * @package Mipress
 */

/**
 * Sets up the WordPress core custom header and custom background features.
 *
 * @since Mipress 0.1
 *
 * @see mipress_header_style()
 */
function mipress_custom_header_and_background() {
	/**
	 * Filter the arguments used when adding 'custom-background' support in Persona.
	 *
	 * @since Mipress 0.1
	 *
	 * @param array $args {
	 *     An array of custom-background support arguments.
	 *
	 *     @type string $default-color Default color of the background.
	 * }
	 */
	add_theme_support( 'custom-background', apply_filters( 'mipress_custom_background_args', array(
		'default-color' => '#f2f2f2',
	) ) );

	/**
	 * Filter the arguments used when adding 'custom-header' support in Persona.
	 *
	 * @since Mipress 0.1
	 *
	 * @param array $args {
	 *     An array of custom-header support arguments.
	 *
	 *     @type string $default-text-color Default color of the header text.
	 *     @type int      $width            Width in pixels of the custom header image. Default 1200.
	 *     @type int      $height           Height in pixels of the custom header image. Default 280.
	 *     @type bool     $flex-height      Whether to allow flexible-height header images. Default true.
	 *     @type callable $wp-head-callback Callback function used to style the header image and text
	 *                                      displayed on the blog.
	 * }
	 */
	add_theme_support( 'custom-header', apply_filters( 'mipress_custom_header_args', array(
		'default-image'      	 => get_parent_theme_file_uri( '/assets/images/header-image.jpg' ),
		'default-text-color'     => '#ffffff',
		'width'                  => 1920,
		'height'                 => 954,
		'flex-height'            => true,
		'flex-height'            => true,
		'wp-head-callback'       => 'mipress_header_style',
		'video'                  => true,
	) ) );

	register_default_headers( array(
		'default-image' => array(
			'url'           => '%s/assets/images/header-image.jpg',
			'thumbnail_url' => '%s/assets/images/header-image-275x155.jpg',
			'description'   => esc_html__( 'Default Header Image', 'mipress' ),
		),
		'second-image' => array(
			'url'           => '%s/assets/images/header-image-1.jpg',
			'thumbnail_url' => '%s/assets/images/header-image-1-275x155.jpg',
			'description'   => esc_html__( 'Another Header Image', 'mipress' ),
		),
	) );
}
add_action( 'after_setup_theme', 'mipress_custom_header_and_background' );

if ( ! function_exists( 'mipress_header_style' ) ) :
	/**
	 * Styles the header text displayed on the site.
	 *
	 * Create your own mipress_header_style() function to override in a child theme.
	 *
	 * @since Mipress 0.1
	 *
	 * @see mipress_custom_header_and_background().
	 */
	function mipress_header_style() {
		$header_image = mipress_featured_overall_image();

		if ( $header_image ) : ?>
		<style type="text/css" rel="header-image">
			.custom-header:before {
				background-image: url( <?php echo esc_url( $header_image ); ?>);
				background-position: center top;
				background-repeat: no-repeat;
				background-size: cover;
			}
		</style>
		<?php
		endif;

		$enable = get_theme_mod( 'mipress_header_text', 'homepage' );

		$header_text_color = get_header_textcolor();

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
		<?php
			// Has the text been hidden?
			if ( ! mipress_check_section( $enable ) ) :
		?>
			.site-branding-text {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
			}
		<?php
			// If the user has set a custom color for the text use that.
			else :
		?>
			.site-title a,
			.site-description {
				color: <?php echo esc_attr( $header_text_color ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}
endif;

/**
 * Customize video play/pause button in the custom header.
 *
 * @param array $settings header video settings.
 */
function mipress_video_controls( $settings ) {
	$settings['l10n']['play'] = '<span class="screen-reader-text">' . esc_html__( 'Play background video', 'mipress' ) . '</span>' . mipress_get_svg( array(
		'icon' => 'play',
	) );
	$settings['l10n']['pause'] = '<span class="screen-reader-text">' . esc_html__( 'Pause background video', 'mipress' ) . '</span>' . mipress_get_svg( array(
		'icon' => 'pause',
	) );
	return $settings;
}
add_filter( 'header_video_settings', 'mipress_video_controls' );
