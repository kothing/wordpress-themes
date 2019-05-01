<?php
/**
 * The template for displaying the single featured page
 */

if ( angi_fn_get_property( 'is_first_of_row' ) ) : ?>
<div class="row fp-widget-area" role="complementary" <?php angi_fn_echo('element_attributes') ?>>
<?php endif ?>
  <div class="featured-page col-12 col-md-<?php angi_fn_echo( 'fp_col' ) ?> fp-<?php angi_fn_echo( 'fp_id' ) ?>">
    <div class="widget-front angi-link-mask-p round" <?php angi_fn_echo('element_attributes') ?>>
      <?php if ( angi_fn_get_property( 'fp_img' ) ) : /* FP IMAGE */?>
      <div class="tc-thumbnail angi-thumb-wrapper angi__r-wTCT <?php angi_fn_echo( 'thumb_wrapper_class' ) ?>">
        <a class="angi-link-mask" href="<?php angi_fn_echo( 'featured_page_link' ) /* escaped in the model */?>" title="<?php echo esc_attr( strip_tags( angi_fn_get_property( 'featured_page_title' ) ) ) ?>"></a>
          <?php angi_fn_echo( 'fp_img' ) ?>
      </div>
      <?php endif /* END FP IMAGE*/ ?>
      <?php /* FP TITLE */ ?>
        <h4 class="fp-title"><?php echo strip_tags( angi_fn_get_property( 'featured_page_title' ) ) ?></h4>
      <?php /* END FP TITLE */ ?>
      <?php
      /* FP EDIT BUTTON */
      if ( angi_fn_get_property( 'edit_enabled' ) ) {
        angi_fn_edit_button( array( 'link' => get_edit_post_link( angi_fn_get_property( 'featured_page_id' ) ) ) );
      }
      /* END FP EDIT BUTTON */

      ?>
      <?php /* FP TEXT */ ?>
        <p class="fp-text-<?php angi_fn_echo( 'fp_id' ) ?>"><?php angi_fn_echo( 'text' ) ?></p>
      <?php /* END FP TEXT*/ ?>
      <?php if ( angi_fn_get_property( 'fp_button_text' ) ) {/* FP BUTTON TEXT */
        angi_fn_readmore_button( array(
            'class' => 'fp-button'. angi_fn_get_property( 'fp-button-class' ),
            'link' => angi_fn_get_property( 'featured_page_link' ),
            'esc_url' => false, //already escaped in the model
            'title' => strip_tags( angi_fn_get_property( 'featured_page_title' ) ),
            'text' => angi_fn_get_property( 'fp_button_text' ),
            'echo' => true
        ) );
      }/* END FP BUTTON TEXT*/ ?>
    </div><!--/.widget-front-->
  </div><!--/.fp-->
<?php if ( angi_fn_get_property( 'is_last_of_row' ) ) : ?>
</div>
<?php endif;