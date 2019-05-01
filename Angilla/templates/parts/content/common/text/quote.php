<?php
/**
 * The template for displaying a blockquote
 *
 *
 * @package Angilla
 */
?>
<blockquote class="blockquote entry-quote <?php angi_fn_echo( 'element_class' ) ?>" <?php angi_fn_echo( 'element_attributes' ) ?>>
  <p>
<?php angi_fn_echo( 'quote_text' ); ?>
  </p>
<?php if ( angi_fn_get_property( 'quote_source' )  ): ?>
    <footer><cite><?php angi_fn_echo( 'quote_source' ) ?></cite></footer>
<?php
    endif //angi_fn_get_property( 'quote_source' )
?>
</blockquote>

