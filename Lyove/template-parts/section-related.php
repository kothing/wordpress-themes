<?php 
    global $post;
    $post_tags = wp_get_post_tags($post->ID);
    if ($post_tags) {
?>
    <div class="related-posts">
        <h3 class="related-posts-title"><?php _e('You May Also Like','lyove') ?></h3>
        <div class="related-posts-row">
            <?php

                foreach ($post_tags as $tag) {
                    // 获取标签列表
                    $tag_list[] .= $tag->term_id;
                }

                // 随机获取标签列表中的一个标签
                $post_tag = $tag_list[ mt_rand(0, count($tag_list) - 1) ];

                // 该方法使用 query_posts() 函数来调用相关文章，以下是参数列表
                $args = array(
                    'tag__in' => array($post_tag),
                    'category__not_in' => array(NULL),  // 不包括的分类ID
                    'post__not_in' => array($post->ID),
                    'showposts' => 3,				    // 显示相关文章数量
                    'caller_get_posts' => 1
                );
                query_posts($args);

                if (have_posts()) {
                    while (have_posts()) {
                    the_post(); update_post_caches($posts); ?>
                    <div class="related-posts-col related-posts-col-3">
                        <figure class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                            <?php if ( '' !== get_the_post_thumbnail() && ! is_single() ) : ?>
                                <?php the_post_thumbnail( 'lyove-small-width' ); ?>
                            <?php else: ?>
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/blank_img.png">
                            <?php endif; ?>	
                            </a>
                        </figure>
                        <div class="meta-info">
                            <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                        </div>
                    </div>
                <?php
                    }
                }
                wp_reset_query(); 
            ?>
        </div>
    </div>

<?php } ?>