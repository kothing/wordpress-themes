<?php
/**
 * The template for displaying the footer credits
 *
 */
?>
<div id="footer__credits" class="footer__credits" <?php angi_fn_echo('element_attributes') ?>>
  <p class="angi-copyright">
    <span class="angi-copyright-text">&copy;&nbsp;<?php echo esc_attr( date('Y') ) ?>&nbsp;</span><a class="angi-copyright-link" href="<?php echo esc_url( home_url() ) ?>" title="<?php echo esc_attr( get_bloginfo() ) ?>"><?php echo esc_attr( get_bloginfo() ) ?></a><span class="angi-rights-text">&nbsp;&ndash;&nbsp;<?php _e( 'All rights reserved', 'angilla') ?></span>
  </p>
  <p class="angi-credits">
    <span class="angi-designer">
      <span class="angi-wp-powered"><span class="angi-wp-powered-text"><?php _e( 'Powered by', 'angilla') ?>&nbsp;</span><a class="angi-wp-powered-link fab fa-wordpress" title="<?php _e( 'Powered by WordPress', 'angilla' ) ?>" href="<?php echo esc_url( __( 'https://wordpress.org/', 'angilla' ) ); ?>" target="_blank"></a></span><span class="angi-designer-text">&nbsp;&ndash;&nbsp;<?php printf( __('Designed with the %s', 'angilla'), sprintf( '<a class="angi-designer-link" href="%1$s" title="%2$s">%2$s</a>', esc_url( ANGI_WEBSITE ), __('Angilla', 'angilla') ) ); ?></span>
    </span>
  </p>
</div>
