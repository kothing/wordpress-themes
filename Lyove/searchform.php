<?php
/**
 * Display search form
 *
 * @link https://developer.wordpress.org/reference/functions/get_search_form
 *
 * @package Lyove
 * @since 1.1
 */

?>

<form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ) ?>">
	<label class="label-search">
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'lyove' ) ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'lyove' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'lyove' ) ?>" />
	</label>
	<button type="submit" class="search-submit"><?php lyove_svg( array( 'icon' => 'search' ) ); ?><span class="screen-reader-text"><?php echo _x( 'Search', 'submit button', 'lyove' ); ?></span></button>
</form>
