<?php
/**
 * The template for displaying the footer of a post in a post list
 * In ANGI loop
 *
 * @package Angilla
 */
?>
<footer class="entry-footer post-info" <?php angi_fn_echo('element_attributes') ?>><?php
  if ( angi_fn_is_registered_or_possible('post_metas') ) :

    $author  = angi_fn_get_property( 'author', 'post_metas' );
    $date    = angi_fn_get_property( 'publication_date', 'post_metas', array( 'permalink' => true ) );
    $up_date = angi_fn_get_property( 'update_date', 'post_metas', array( 'permalink' => !$date ) );

    if ( $author || $date || $up_date ) :
  ?>
    <div class="entry-meta row flex-row align-items-center">
      <?php if ( $author ) : ?>
        <div class="col-12 col-md-auto">
          <?php angi_fn_render_template( 'content/post-lists/item-parts/authors/author_info_small' ) ?>
        </div>
      <?php endif;


      ?>
      <?php if ( $date || $up_date ) : ?>
        <div class="col-12 col-md-auto">
          <div class="row">
          <?php
            if ( $date )
              echo '<div class="col col-auto">' . $date . '</div>';

            if ( $up_date )
              echo '<div class="col col-auto">' . $up_date . '</div>';

          ?>
          </div>
        </div>
      <?php endif; /* $date || $up_date */ ?>
    </div>
    <?php
    endif;//( $author || $date || $up_date )
  endif; //post_metas are possible
?></footer>
