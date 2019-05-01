<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Lyove
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">

	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'lyove' ); ?></a>

	<header class="site-header" id="masthead">
		<div class="header-items">
			<div class="site-branding">
				<?php lyove_the_custom_logo();?>

				<div class="title-area">
					<?php if ( is_front_page() || is_home() ) : ?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php else : ?>
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<?php endif;

					$description = get_bloginfo( 'description', 'display' );?>
					<?php if ( $description || is_customize_preview() ) : ?>
						<p class="site-description"><?php echo $description; ?></p>
					<?php endif; ?>
				</div><!-- .title-area -->
			</div><!-- .site-branding -->

		</div><!-- .header-items -->

		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<nav id="main-navigation" class="main-navigation" aria-label="Primary Menu">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<?php
					lyove_svg( array( 'icon' => 'bars' ) );
					_e( 'Menu', 'lyove' );
					?>
				</button>
				<?php wp_nav_menu(
					array(
						'theme_location'  => 'primary',
						'menu_id'         => 'primary-menu',
						'menu_class'      => 'nav-menu',
						'container_class' => 'wrap',
					)
				);?>
			</nav><!-- #site-navigation -->
		<?php endif; ?>
	</header><!-- #masthead -->

	<?php lyove_the_custom_header();?>
    
	<div id="content" class="site-content">
