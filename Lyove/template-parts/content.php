<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Lyove
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('object-non-visible'); ?> data-animation-effect="fadeInUpSmall" data-effect-delay="200">
	<?php
	if ( is_sticky() && is_home() ) :
		lyove_svg( array( 'icon' => 'thumb-tack' ) );
	endif;
	?>
	
	<header class="entry-header">
		<?php
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php lyove_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php
		endif; ?>
	</header><!-- .entry-header -->

    <?php
        if ( !get_theme_mod( 'lyove_no_thumbnail_onpost', true ) ) {
            lyove_post_thumbnail();
        }
    ?>

	<div class="entry-content">
		<?php
		if ( ( is_home() || is_category() || is_archive() ) && 'content' !== get_theme_mod( 'lyove_excerpt_option', 'excerpt' )
			&& ! has_post_format( array( 'aside', 'quote', 'status', 'video', 'audio', 'gallery' ) )
			&& ! post_password_required() ) :
			the_excerpt();
		else :
			the_content( sprintf(
				esc_html__( 'Continue reading %s', 'lyove' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			) );

			/*
			 * Displays page-links for paginated posts (i.e. if the <!--nextpage-->
			 * Quicktag has been used for one or more times in a single post).
			 */
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'lyove' ),
				'after'  => '</div>',
			) );
		endif;
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php lyove_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
