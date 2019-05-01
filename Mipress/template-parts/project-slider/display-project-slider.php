<?php
/**
 * The template for displaying featured content
 *
 * @package Mipress
 */
?>

<?php
$enable_content = get_theme_mod( 'mipress_project_slider_option', 'disabled' );

if ( ! mipress_check_section( $enable_content ) ) {
	// Bail if featured content is disabled.
	return;
}

$type      = 'page';
$title     = get_theme_mod( 'mipress_project_slider_title', esc_html__( 'Project Slider', 'mipress' ) );
$sub_title = get_theme_mod( 'mipress_project_slider_sub_title' );

$classes[] = $type;
$classes[] = 'section';

$project_slider_bg = get_theme_mod( 'mipress_project_slider_bg_image', trailingslashit( esc_url( get_template_directory_uri() ) ) . 'assets/images/clients-section-bg.jpg' );

if ( $project_slider_bg ) {
	$classes[] = 'background-image';
} else {
	$classes[] = 'no-background-image';
}
?>

<div id="clients-section" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<div class="wrapper">
		<?php if ( '' !== $title || $sub_title ) : ?>
			<div class="section-heading-wrapper clients-section-headline">
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

		<div class="section-content-wrapper clients-content-wrapper">
			<!-- prev/next for SVG links -->
			<button id="project-slider-prev" class="cycle-prev" aria-label="<?php esc_attr_e( 'Previous', 'mipress' ); ?>"><?php echo mipress_get_svg( array( 'icon' => 'angle-down' ) ); ?></button>
			<button id="project-slider-next" class="cycle-next" aria-label="<?php esc_attr_e( 'Next', 'mipress' ); ?>"><?php echo mipress_get_svg( array( 'icon' => 'angle-down' ) ); ?></button>

			<!-- empty element for pager links -->
			<div id="project-slider-pager" class="cycle-pager"></div>
			<div class="cycle-slideshow"
			data-cycle-log="false"
			data-cycle-pause-on-hover="true"
			data-cycle-swipe="true"
			data-cycle-auto-height=container
			data-cycle-fx=carousel
			data-cycle-speed="1000"
			data-cycle-timeout="4000"
			data-cycle-loader=false
			data-cycle-slides="> article"
			data-cycle-carousel-fluid="true"
			data-cycle-prev= .cycle-prev
			data-cycle-next= .cycle-next
			data-cycle-pager="#project-slider-pager"
			data-cycle-prev="#project-slider-prev"
			data-cycle-next="#project-slider-next"
			data-cycle-slides="> .post-slide"
			data-cycle-carousel-visible="4"
			>

				<?php
						get_template_part( 'template-parts/project-slider/post-types', 'project-slider' );
				?>
			</div><!-- .cycle-slideshow -->
		</div><!-- .section-content-wrap -->
	</div><!-- .wrapper -->
</div><!-- #clients-section -->
