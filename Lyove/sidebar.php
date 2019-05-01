<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Lyove
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ||
	'only-content' === get_theme_mod( 'lyove_layout', 'content-sidebar' ) ) :
	return;
endif;
?>

<aside id="secondary" class="sidebar widget-area">
	<button aria-controls="secondary" aria-expanded="false" class="sidebar-toggle"></button>
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->
