<?php
/**
 * The template for displaying the footer of a post in a post list
 * In ANGI loop
 *
 * @package Angilla
 */
?>
<footer class="entry-footer" <?php angi_fn_echo('element_attributes') ?>><?php
  if ( angi_fn_is_registered_or_possible('post_metas') ) :

    $tags    = angi_fn_get_property( 'tag_list', 'post_metas' );
    $author  = angi_fn_get_property( 'author', 'post_metas' );
    $date    = angi_fn_get_property( 'publication_date', 'post_metas', array( 'permalink' => true ) );
    $up_date = angi_fn_get_property( 'update_date', 'post_metas', array( 'permalink' => !$date ) );

    if ( $tags || $date || $up_date || $author) :
      if ( $tags) :
  ?>
      <div class="post-tags entry-meta">
        <ul class="tags">
          <?php echo $tags; ?>
        </ul>
      </div>
    <?php endif; //tags
      if ( $author || $date || $up_date ): ?>
        <div class="post-info clearfix entry-meta">

          <div class="row flex-row">
            <?php
            if ( $author ) {
              echo '<div class="col col-auto">' . $author . '</div>';
            }

            if ( $date || $up_date ) :
            ?>
              <div class="col col-auto">
                <div class="row">
                  <?php
                    if ( $date ) {
                      echo '<div class="col col-auto">' . $date . '</div>';
                    }

                    if ( $up_date ) {
                      echo '<div class="col col-auto">' . $up_date . '</div>';
                    }
                  ?>
                </div>
              </div>
            <?php endif; // $date || $up_date ?>
          </div>
        </div>
      <?php endif; // $author || $date || $up_date ?>
    <?php endif; // $tags || $date || $up_date || $author ?>
  <?php endif; //post_metas possibile ?>
</footer>