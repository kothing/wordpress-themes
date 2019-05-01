<?php
/**
 * The template for displaying the single post content
 *
 * In WP loop
 */
global $post;
?>
<article <?php echo angi_fn_get_the_singular_article_selectors() ?> <?php angi_fn_echo( 'element_attributes' ) ?>>
  <?php do_action( '__before_content_inner' ) ?>
  <?php
  /* heading */
  angi_fn_render_template( 'content/singular/headings/regular_attachment_image_heading' );
  /* navigation */
  angi_fn_render_template( 'content/singular/navigation/single_attachment_image_navigation' );
  ?>
  <div class="post-entry tc-content-inner">
    <section class="entry-attachment attachment-content" >
      <div class="attachment-figure-wrapper display-flex flex-wrap" >
        <figure class="attachment-image-figure">
          <div class="entry-media__holder">
            <a href="<?php angi_fn_echo( 'attachment_link_url' ) ?>" class="<?php angi_fn_echo( 'attachment_class' ) ?> bg-link" title="<?php the_title_attribute(); ?>" <?php angi_fn_echo( 'attachment_link_attributes' ) ?>></a>
            <?php echo wp_get_attachment_image( get_the_ID(), angi_fn_get_property( 'attachment_size' ) ) ?>
          </div>
          <?php if ( $caption = angi_fn_get_property( 'attachment_caption' )  ) :?>
            <figcaption class="wp-caption-text entry-caption">
              <?php echo $caption ?>
            </figcaption>
          <?php endif; ?>
        </figure>
      </div>
      <?php /* hidden ligthbox gallery with all the attachments referring to the same post parent */
      angi_fn_echo( 'attachment_gallery' )
      ?>
      <div class="entry-content">
        <div class="angi-wp-the-content">
          <?php the_content(); ?>
        </div>
      </div>
      <footer class="post-footer clearfix">
        <?php
          if ( angi_fn_is_registered_or_possible('social_share') ) :
        ?>
          <div class="post-share col-xs-12 col-sm-auto col-sm">
              <!-- fake need to have social links somewhere -->
              <?php angi_fn_render_template( 'modules/common/social_block', array( 'model_id' => 'social_share' ) ) ?>
          </div>
        <?php
          endif
        ?>
      </footer>
    </section><!-- .entry-content -->
  </div><!-- .post-entry -->
  <?php do_action( '__after_content_inner' ) ?>
</article>