<?php
/**
 * The template for displaying the post list grid item (expanded or not)
 *
 * In WP loop
 *
 * @package Angilla
 * @since Angilla 3.5.0
 */
?>
<article <?php angi_fn_echo( 'article_selectors' ) ?> <?php angi_fn_echo('element_attributes') ?>>
  <section class="grid__item">
    <div class="tc-grid-figure entry-media__holder <?php angi_fn_echo( 'figure_class' ) ?>">
      <div class="entry-media__wrapper angi__r-i">
        <a class="bg-link" href="<?php the_permalink() ?>" title="<?php esc_attr( strip_tags( get_the_title() ) ) ?>"></a>
        <?php angi_fn_echo( 'thumb_img' ) ?>
      </div>
      <div class="tc-grid-caption">
          <div class="entry-summary <?php angi_fn_echo('entry_summary_class') ?>">
            <?php

            /* The expanded grid item has the title inside the caption */
            if( angi_fn_get_property( 'has_title_in_caption' ) && !angi_fn_get_property( 'title_in_caption_below' ) ):

            ?>
            <h2 class="entry-title over-thumb">
              <a class="angi-title" href="<?php the_permalink() ?>" title="<?php _e( 'Permalink to' , 'angilla' ) ?> <?php echo esc_attr( strip_tags( get_the_title() ) ) ?>" rel="bookmark"><?php angi_fn_echo( 'title' ) ?></a>
            </h2>
            <?php

            /* end expanded title */
            endif

            ?>
            <div class="tc-g-cont <?php angi_fn_echo('gcont_class') ?>"><?php angi_fn_echo( 'text' ) ?></div>
            <?php

            /* The expanded grid item has the title inside the caption */
            if( angi_fn_get_property( 'has_title_in_caption' ) && angi_fn_get_property( 'title_in_caption_below' ) ):

            ?>
            <h2 class="entry-title over-thumb">
              <a class="angi-title" href="<?php the_permalink() ?>" title="<?php _e( 'Permalink to' , 'angilla' ) ?> <?php echo esc_attr( strip_tags( get_the_title() ) ) ?>" rel="bookmark"><?php angi_fn_echo( 'title' ) ?></a>
            </h2>
            <?php

            /* end expanded title */
            endif

            ?>
          </div>
          <?php

          /* additional effect for not expanded grid items */
        if( angi_fn_get_property( 'has_fade_expt' ) ):

          ?>
          <span class="tc-grid-fade_expt"></span>
          <?php

        endif

          ?>
      </div>

      <?php

      /* Edit link above the thumb for the expanded item */
        if ( angi_fn_get_property( 'has_edit_above_thumb' ) ) {
          if ( (bool) $edit_post_link = get_edit_post_link() ) {
            angi_fn_edit_button( array( 'class' => 'inverse', 'link' => $edit_post_link ) );
          }
        }
      ?>
    </div>
  <?php

    /* Header in the bottom for not expanded */
    if( ! angi_fn_get_property( 'has_title_in_caption' ) ) :

  ?>
    <div class="tc-content">
      <?php angi_fn_render_template( 'content/post-lists/item-parts/headings/post_list_item_header',
        array(
          'model_args' => array(
            'the_title' => angi_fn_get_property('title')
          )
        )
      )?>
      <?php angi_fn_render_template( 'content/post-lists/item-parts/footers/post_list_item_footer' ) ?>
    </div>
  <?php

    endif

  ?>
  </section>
</article>