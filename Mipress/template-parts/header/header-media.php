<?php
/**
 * Display Header Media
 *
 * @package Mipress
 */
?>

<div class="custom-header">
	<div class="wrapper">
		<?php
		$header_image = mipress_featured_overall_image();

		if ( $header_image ) : ?>
		<div class="custom-header-media">
			<?php
			if ( is_header_video_active() && has_header_video() ) {
				the_custom_header_markup();
			} elseif ( $header_image ) {
				echo '<img src="' . esc_url( $header_image ) . '"/>';
			}
			?>
		</div>
		<?php endif; ?>

		<?php $class = get_theme_mod( 'mipress_header_content_alignment', 'align-center' ); ?>
		<div class="custom-header-content sections header-media-section <?php echo esc_attr( $class ); ?>">
			<?php get_template_part( 'template-parts/header/site', 'branding' ); ?>
		</div>

		<?php
		$enable_header_text = get_theme_mod( 'mipress_header_text', 'homepage' );

		if( is_front_page() && ( $header_image || has_custom_logo() || mipress_check_section( $enable_header_text ) ) ) : ?>
			<button class="button-scroll" aria-label="Previous"><?php echo mipress_get_svg( array( 'icon' => 'angle-down' ) ); ?></button>
		<?php endif; ?>
	</div><!-- .wrapper -->
</div><!-- .custom-header -->
