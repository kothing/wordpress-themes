<?php
 /**
 * The template for displaying the footer.
 *
 *
 * @package Angilla
 * @since Angilla 3.0
 */
if ( apply_filters( 'angi_ms', false ) ) {
  do_action( 'angi_ms_tmpl', 'footer' );
  return;
}

  	do_action( '__before_footer' ); ?>
  		<!-- FOOTER -->
  		<footer id="footer" class="<?php echo angi_fn__f('tc_footer_classes', '') ?>">
  		 	<?php do_action( '__footer' ); // hook of footer widget and colophon?>
  		</footer>
    </div><!-- //#tc-page-wrapper -->
		<?php
    do_action( '__after_page_wrap' );
		wp_footer(); //do not remove, used by the theme and many plugins
	  do_action( '__after_footer' ); ?>
	</body>
	<?php do_action( '__after_body' ); ?>
</html>