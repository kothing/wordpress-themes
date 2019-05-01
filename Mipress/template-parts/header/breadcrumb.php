<?php
/**
 * Display Breadcrumb
 *
 * @package Mipress
 */
?>

<?php

if ( ! get_theme_mod( 'mipress_breadcrumb_option', 1 ) ) {
	// Bail if breadcrumb is disabled.
	return;
}
	mipress_breadcrumb();

