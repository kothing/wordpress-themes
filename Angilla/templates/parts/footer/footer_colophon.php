<?php
/**
 * The template for displaying the standard colophon
 *
 * @package Angilla
 * @since Angilla 3.5.0
 */
?>
<div id="colophon" class="colophon <?php angi_fn_echo('element_class') ?>" <?php angi_fn_echo('element_attributes') ?>>
  <div class="<?php angi_fn_echo('element_inner_class') ?>">
    <div class="colophon__row row flex-row justify-content-between">
      <div class="col-12 col-sm-auto">
        <?php if ( angi_fn_is_registered_or_possible( 'footer_credits' ) ) angi_fn_render_template( 'footer/footer_credits' ) ?>
      </div>
      <?php if ( angi_fn_is_registered_or_possible( 'footer_social_block' ) ) : ?>
      <div class="col-12 col-sm-auto">
        <div class="social-links">
          <?php angi_fn_render_template( 'modules/common/social_block' ) ?>
        </div>
      </div>
      <?php endif ?>
    </div>
  </div>
</div>
