<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Lyove
 */

?>
	</div><!-- #content -->
	<?php lyove_footer_widgets(); ?>
	<footer class="site-footer" id="colophon">
		<div class="site-info">
			<?php lyove_footer_info(); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
