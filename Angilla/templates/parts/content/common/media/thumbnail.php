<?php
/**
 * The template for displaying a thumbnail media
 *
 *
 * @package Angilla
 */

/* img */
angi_fn_echo( 'image' );

/* Lightbox Button */
if ( angi_fn_get_property( 'has_lightbox' ) )
    angi_fn_post_action( $link = angi_fn_get_property( 'lightbox_url' ), $class = 'expand-img' );
