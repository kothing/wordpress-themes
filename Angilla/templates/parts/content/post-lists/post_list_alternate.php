<?php
/**
 * The template for displaying the alternate article wrapper
 *
 * In WP loop
 *
 * @package Angilla
 */
if ( angi_fn_get_property( 'print_start_wrapper' ) ) : ?>
<div class="grid-container grid-container__alternate <?php angi_fn_echo('element_class') ?>" <?php angi_fn_echo('element_attributes') ?>>
  <div class="alternate__wrapper row">
<?php endif ?>
    <article <?php angi_fn_echo( 'article_selectors' ) ?> >
      <div class="grid__item <?php angi_fn_echo('grid_item_class') ?>">
        <div class="sections-wrapper <?php angi_fn_echo( 'sections_wrapper_class' ) ?>">
        <?php
            if ( angi_fn_get_property( 'has_post_media' ) ) {
              /* Media */
              angi_fn_render_template(
                'content/common/media',
                array(
                  'model_id'   => 'media',
                  'reset_to_defaults' => false,

                  'model_args' =>  array(
                    'element_class'            => angi_fn_get_property( 'media_class' ),
                    'inner_wrapper_class'      => angi_fn_get_property( 'media_inner_class' ),
                    'link_class'               => angi_fn_get_property( 'media_link_class' ),
                    'image_centering'          => angi_fn_get_property( 'image_centering' ),
                  )
                )
              );
            }
             /* Content */
            ?>
            <section class="tc-content entry-content__holder <?php angi_fn_echo('content_class') ?>">
              <div class="entry-content__wrapper">
              <?php
                /* header */
                angi_fn_render_template( 'content/post-lists/item-parts/headings/post_list_item_header');
                /* content inner */
                angi_fn_render_template(
                    'content/post-lists/item-parts/contents/post_list_item_content_inner',
                    array(
                      'model_id'   => 'post_list_item_content_inner',
                      'reset_to_defaults' => false,
                    )

                );
                /* footer */
                angi_fn_render_template( 'content/post-lists/item-parts/footers/post_list_item_footer' );
              ?>
              </div>
            </section>
            <?php

        ?>
        </div>
      </div>
    </article>
<?php if ( angi_fn_get_property( 'print_end_wrapper' ) ) : ?>
  </div>
</div>
<?php endif;