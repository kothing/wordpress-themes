<?php
/**
 * The left sidebar template
 *
 *
 * @package Angilla
 * @since Angilla 3.1.0
 */
if ( apply_filters( 'angi_ms', false ) ) {
  do_action( 'angi_ms_tmpl', 'sidebar-left' );
  return;
}
dynamic_sidebar( 'left' );