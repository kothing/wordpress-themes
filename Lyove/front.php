<?php
/**
 * Template Name: Front
 * 
 * The template for displaying front page.
 * @package Lyove
*/
?>

<?php get_header(); ?>

<main id="main" class="site-main">

    <?php while ( have_posts() ) : the_post();?>
        
        <article id="post-<?php the_ID(); ?>" <?php //post_class('object-non-visible'); ?> class="object-non-visible" data-animation-effect="fadeInUpSmall" data-effect-delay="200">

            <?php
                if ( !get_theme_mod( 'lyove_no_thumbnail_onpost', true ) ) {
                    lyove_post_thumbnail();
                }
            ?>

            <div class="entry-content">
                <?php
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
                ?>
            </div><!-- .entry-content -->
            
        </article><!-- #post-## -->
        
    <?php endwhile;?>

</main><!-- #main -->

<?php get_footer(); ?>
