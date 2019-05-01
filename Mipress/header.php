<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package security
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(''); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'mipress' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="wrapper">
			<div id="site-header-main">
				<?php get_template_part( 'template-parts/navigation/navigation', 'primary' ); ?>
			</div><!-- #site-header-main -->
		</div><!-- .wrapper -->
	</header><!-- #masthead -->

	<?php get_template_part( 'template-parts/slider/display', 'slider' ); ?>

	<?php get_template_part( 'template-parts/header/header', 'media' ); ?>

	<?php get_template_part( 'template-parts/hero-content/content', 'hero' ); ?>

	<?php get_template_part( 'template-parts/project-slider/display', 'project-slider' ); ?>

	<?php get_template_part( 'template-parts/header/breadcrumb' ); ?>

	<div id="content" class="site-content">
		<div class="wrapper">
