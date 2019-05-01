<?php
/**
 * The template for displaying the branding wrapper
 * July 2017 : no specific model for this template. The 'inner_branding_class' property => is added to the default model when invoking the angi_fn_render_template
 */
?>
<div class="branding__container <?php angi_fn_echo('element_class') ?>" <?php angi_fn_echo('element_attributes') ?>>
  <div class="branding align-items-center flex-column <?php angi_fn_echo( 'inner_branding_class' ); ?>">
    <div class="branding-row d-flex align-self-start flex-row align-items-center">
      <?php
        if ( angi_fn_is_registered_or_possible('logo_wrapper') ) {
          angi_fn_render_template( 'header/parts/logo_wrapper' );
        } else if ( angi_fn_is_registered_or_possible('title_alone') ) {
          angi_fn_render_template( 'header/parts/title' );
        }
        if ( angi_fn_is_registered_or_possible('title_next_logo') || angi_fn_is_registered_or_possible( 'branding_tagline_aside' ) ) { ?>
          <div class="branding-aside col-auto flex-column d-flex">
          <?php
            if ( angi_fn_is_registered_or_possible('title_next_logo') ) {
              angi_fn_render_template( 'header/parts/title' );
            }
            if ( angi_fn_is_registered_or_possible( 'branding_tagline_aside' ) ) {
              angi_fn_render_template( 'header/parts/tagline' );
            }
          ?>
          </div>
          <?php
        }
        ?>
      </div>
    <?php
    if ( angi_fn_is_registered_or_possible( 'branding_tagline_below' ) ) {
      angi_fn_render_template( 'header/parts/tagline' );
    }
  ?>
  </div>
</div>
