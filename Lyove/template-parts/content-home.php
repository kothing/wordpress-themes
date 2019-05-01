<?php
/**
 * Template part for displaying home page.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Lyove
 */

?>

<?php
    $cats = get_categories();
    foreach ( $cats as $cat ) {
        query_posts( array(
            'cat'       => $cat->cat_ID,
            'showposts' => 5,
            'orderby'   => 'date',
            'order'     => 'DESC',
        ) );
        ?>
        <div class="section-box cats-box object-non-visible" data-animation-effect="fadeInUpSmall" data-effect-delay="200">
            <header class="section-title">
                <h3><a href="<?php echo get_category_link($cat) ?>"><?php echo $cat->cat_name; ?></a></h3>
            </header>
            <?php while ( have_posts() ) {
                the_post();?>
                <article class="section-content">
                    <?php
                        if (!get_theme_mod( 'lyove_no_thumbnail_onpost', true ) ) {
                            if('' !== get_the_post_thumbnail()) { ?>
                                <?php echo lyove_post_thumbnail();?>
                            <?php } else {?>
                                <figure class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>" aria-hidden="true">
                                        <img class="aligncenter thumbnails" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/blank_img.png"/>
                                    </a>
                                </figure>
                            <?php }
                        }
                    ?>
                    <div class="float-box">
                        <div class="entry-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </div>
                        <div class="entry-meta">
                            <?php
                                printf( '<span class="byline"><i class="genericon genericon-user"></i><a class="author-url" href="%s">%s</a></span>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), esc_html( get_the_author() ));     
                                if ( comments_open() ) {
                                    printf( '<span class="comments-num"><i class="genericon genericon-comment"></i><a href="%s#comments" rel="nofollow" title="%s">%s</a></span>', get_the_permalink(), the_title_attribute('echo=0'), get_comments_number() );
                                }
                                $tags_list = get_the_tag_list( '', ' ', '' );
                                if ( $tags_list ) {
                                    printf( '<span class="tags"><i class="genericon genericon-tag"></i>' . __( '%1$s', 'lyove' ) . '</span>', $tags_list );
                                }
                            ?>
                        </div>
                    </div>
                </article>
            <?php
            }
                wp_reset_query();
            ?>
        </div>
<?php } ?>