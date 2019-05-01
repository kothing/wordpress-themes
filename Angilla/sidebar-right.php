<?php
/**
 * The right sidebar template
 *
 *
 * @package Angilla
 * @since Angilla 3.1.0
 */
if ( apply_filters( 'angi_ms', false ) ) {
  do_action( 'angi_ms_tmpl', 'sidebar-right' );
  return;
}
dynamic_sidebar( 'right' );