<?php
/**
 * Template for displaying search forms in mipress
 *
* @package mipress
 */
?>

<?php
$unique_id   = uniqid( 'search-form-' );
$search_text = get_theme_mod( 'mipress_search_text', esc_html__( 'Search', 'mipress' ) );
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $unique_id ); ?>">
		<span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'mipress' ); ?></span>
		<input type="search" id="<?php echo esc_attr( $unique_id ); ?>" class="search-field" placeholder="<?php echo esc_attr( $search_text ); ?>" value="<?php the_search_query(); ?>" name="s" />
	</label>
	<button type="submit" class="search-submit"><?php echo mipress_get_svg( array( 'icon' => 'search' ) ); ?><span class="screen-reader-text"><?php echo _x( 'Search', 'submit button', 'mipress' ); ?></span></button>
</form>
