<?php
/**
 * The template for displaying featured content
 *
 * @package Mipress
 */
?>

<?php
$enable_content = get_theme_mod( 'mipress_services_option', 'disabled' );

if ( ! mipress_check_section( $enable_content ) ) {
	// Bail if featured content is disabled.
	return;
}

$type      = 'page';
$title     = get_theme_mod( 'mipress_services_title', esc_html__( 'Services', 'mipress' ) );
$sub_title = get_theme_mod( 'mipress_services_sub_title' );
$layout    = 'layout-four';

$classes[] = esc_attr( $layout );
$classes[] = esc_attr( $type );
$classes[] = 'section';

$services_bg_image = get_theme_mod( 'mipress_services_bg_image', trailingslashit( esc_url( get_template_directory_uri() ) ) . 'assets/images/services-section-bg.jpg' );

if ( $services_bg_image ) {
	$classes[] = 'background-image';
} else {
	$classes[] = 'no-background-image';
}
?>

<div id="numbers-section" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<div class="wrapper">
		<?php if ( '' !== $title || $sub_title ) : ?>
			<div class="section-heading-wrapper numbers-section-headline">
				<?php if ( '' !== $title ) : ?>
					<div class="section-title-wrapper">
						<h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>
					</div><!-- .section-title-wrapper -->
				<?php endif; ?>

				<?php if ( $sub_title ) : ?>
					<div class="taxonomy-description-wrapper">
						<p class="section-subtitle">
							<?php echo wp_kses_post( $sub_title ); ?>
						</p>
					</div><!-- .taxonomy-description-wrapper -->
				<?php endif; ?>
			</div><!-- .section-heading-wrapper -->
		<?php endif; ?>

		<div class="section-content-wrapper numbers-content-wrapper <?php echo esc_attr( $layout ); ?>">

			<?php
					get_template_part( 'template-parts/services/post-types', 'services' );
			?>
		</div><!-- .section-content-wrap -->
	</div><!-- .wrapper -->
</div><!-- #numbers-section -->
