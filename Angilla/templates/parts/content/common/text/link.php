<?php
/**
 * The template for displaying a link
 *
 *
 * @package Angilla
 */
?>
<?php

      if ( angi_fn_get_property( 'link_url' ) ) :

?>
<p class="<?php angi_fn_echo( 'element_class' ) ?> entry-link" <?php angi_fn_echo( 'element_attributes') ?>>
  <a class="angi-format-link" target="_blank" href="<?php angi_fn_echo( 'link_url' ) ?>" title="<?php esc_attr( angi_fn_get_property( 'link_title' ) ) ?>"><?php angi_fn_echo( 'link_title' ) ?></a>
</p>
<?php

      endif //angi_fn_get_property( 'link_url' )

?>

