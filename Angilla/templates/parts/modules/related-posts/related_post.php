<?php
/*
* Related posts item template
*/
?>
<article <?php angi_fn_echo('article_selectors') ?> <?php angi_fn_echo('element_attributes') ?>>
  <div class="grid__item flex-wrap flex-lg-nowrap flex-row">
    <?php
      angi_fn_render_template(
        'content/common/media',
        array(
          'model_args' => array(
              'element_class'         => angi_fn_get_property('media_cols'),
              'media_type'            => 'angi-thumb',
              'thumb_size'            => 'tc-sq-thumb',
              'use_thumb_placeholder' => true
          )
        )
      );
      /* Content */
    ?>
      <section class="tc-content entry-content__holder <?php angi_fn_echo('content_cols') ?>">
        <div class="entry-content__wrapper">
        <?php
          /* header */
          angi_fn_render_template(
            'content/post-lists/item-parts/headings/post_list_item_header_date',
            array(
              'model_class' => 'content/post-lists/item-parts/headings/post_list_item_header'
            )
          );
          /* content inner */
          angi_fn_render_template( 'content/post-lists/item-parts/contents/post_list_item_content_inner' );
        ?>
        </div>
      </section>
  </div>
</article>