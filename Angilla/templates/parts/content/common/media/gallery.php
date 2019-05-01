<?php
/**
 * The template for displaying a gallery
 *
 *
 * @package Angilla
 */
?>
<?php
      $gallery_items = angi_fn_get_property( 'gallery_items' );
?>
<div class="angi-gallery angi-carousel" <?php angi_fn_echo( 'element_attributes' ) ?>>
<?php
        if ( count( $gallery_items ) > 1 ) :
            angi_fn_carousel_nav();
        endif;

?>
  <div class="carousel carousel-inner" <?php angi_fn_echo( 'carousel_inner_attributes' ) ?>>
<?php
        foreach ( $gallery_items as $gallery_item ) :
?>
    <div class="carousel-cell">
        <?php echo $gallery_item ?>
    </div>
<?php
        endforeach;
?>
    </div>
<?php
    if ( angi_fn_get_property( 'has_lightbox' ) ) :
        angi_fn_post_action( $link = '#', $class = 'expand-img-gallery' );
    endif;
?>
</div>
