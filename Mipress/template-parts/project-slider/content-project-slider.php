<?php
/**
 * The template for displaying featured posts on the front page
 *
 * @package Mipress
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="clients-section-thumbnail post-thumbnail">
        <a href="<?php the_permalink(); ?>">
            <?php
			// Output the featured image.
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'mipress-project-slider' );
			} else {
				echo '<img src="' .  trailingslashit( esc_url( get_template_directory_uri() ) ) . 'assets/images/no-thumb-100x100.jpg"/>';
			}
			?>
        </a>
    </div>
</article>

