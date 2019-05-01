<?php
/**
 * The template for displaying featured content
 *
 * @package WordPress
 * @subpackage Lyove
 * @since Lyove 1.0
 */
?>

<div id="featured-content" class="featured-content">
	<div class="featured-content-inner">
	<?php
		/**
		 * Fires before the Lyove featured content.
		 *
		 * @since Lyove 1.0
		 */
		do_action( 'lyove_featured_posts_before' );

		$featured_posts = lyove_get_featured_posts();
        foreach ( (array) $featured_posts as $order => $post ) :
            setup_postdata( $post );?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <figure class="post-thumbnail effect-jazz">
                    <a href="<?php the_permalink(); ?>">
                    <?php
                        // Output the featured image.
                    if ( has_post_thumbnail() ) :
                        if ( 'grid' == get_theme_mod( 'featured_layout' ) ) {
                            the_post_thumbnail();
                        } else {
                            the_post_thumbnail( 'lyove-full-width' );
                        }
                    else: ?>
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/blank_img.png"/>
                    <?php endif; ?>
                    </a>
                </figure>

                <header class="entry-header">
                    <?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && lyove_categorized_blog() ) : ?>
                    <div class="entry-meta">
                        <span class="cat-links"><?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'lyove' ) ); ?></span>
                    </div><!-- .entry-meta -->
                    <?php endif; ?>

                    <?php the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' ); ?>
                </header><!-- .entry-header -->
            </article><!-- #post-## -->

		<?php
        endforeach;

		/**
		 * Fires after the Lyove featured content.
		 *
		 * @since Lyove 1.0
		 */
		do_action( 'lyove_featured_posts_after' );

		wp_reset_postdata();
	?>
	</div><!-- .featured-content-inner -->
</div><!-- #featured-content .featured-content -->