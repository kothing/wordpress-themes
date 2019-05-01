<?php
/**
 * The template for displaying the grid item wrapper in lists of posts
 *
 * In WP loop
 *
 * @package Angilla
 * @since Angilla 3.5.0
 */

/* Are we at the start of the loop? in this case print a section wrapper element */
if ( angi_fn_get_property( 'print_start_wrapper' ) ) : ?>
<div id="<?php angi_fn_echo('element_id') ?>" class="grid-container grid-container__classic <?php angi_fn_echo('element_class') ?>" <?php angi_fn_echo('element_attributes') ?>>
  <div class="grid__wrapper grid">
<?php
endif;
  /* Is the item we're about to display the first of a grid section (the expanded sticky is both a first and last of a grid section)*/
  if ( angi_fn_get_property( 'is_first_of_grid' ) ) :
  ?>
  <section class="row <?php angi_fn_echo('grid_section_class') ?>">
  <?php
  endif;
    angi_fn_render_template(
      'modules/grid/grid_item',
      array( 'model_args' => angi_fn_get_property( 'grid_item' ) )
    );

  /* Is the item we're about to display the last of a grid section (the expanded sticky is both a first and last of a grid section)*/
  if ( angi_fn_get_property( 'is_last_of_grid' ) ) :
  ?>
  </section>
  <?php
  endif;
/* Close se section at the end of the loop */
if ( angi_fn_get_property( 'print_end_wrapper' ) ) : ?>
  </div>
</div>
<?php endif;