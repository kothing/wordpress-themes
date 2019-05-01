<?php
/**
 * The Header for Angilla.
 *
 * Displays all of the <head> section and everything up till <div id="main-wrapper">
 *
 * @package Angilla
 * @since Angilla 1.0
 */
if ( apply_filters( 'angi_ms', false ) ) {
    //in core init => add_action( 'angi_ms_tmpl', array( $this , 'angi_fn_load_modern_template_with_no_model' ), 10 , 1 );
    //function angi_fn_load_modern_template_with_no_model( $template = null ) {
    //     $template = $template ? $template : 'index';
    //     $this -> angi_fn_require_once( ANGI_MAIN_TEMPLATES_PATH . $template . '.php' );
    // }
    do_action( 'angi_ms_tmpl', 'header' );
    return;
}
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7 no-js" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8 no-js" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html class="no-js" <?php language_attributes(); ?>>
<!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=EDGE" />
        <?php if ( ! function_exists( '_wp_render_title_tag' ) ) :?>
			<title><?php wp_title( '|' , true, 'right' ); ?></title>
        <?php endif; ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="profile"  href="https://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<!-- html5shiv for IE8 and less  -->
		<!--[if lt IE 9]>
			<script src="<?php echo ANGI_FRONT_ASSETS_URL ?>js/libs/html5.js"></script>
		<![endif]-->
		<?php wp_head(); ?>
	</head>
	<?php
		do_action( '__before_body' );
	?>

	<body <?php body_class(); ?> <?php echo apply_filters('tc_body_attributes' , '') ?>>

    <?php do_action( '__before_page_wrapper' ); ?>

    <div id="tc-page-wrap" class="<?php echo implode( " ", apply_filters('tc_page_wrap_class', array() ) ) ?>">

  		<?php do_action( '__before_header' ); ?>

  	   	<header class="<?php echo implode( " ", apply_filters('tc_header_classes', array('tc-header' ,'clearfix', 'row-fluid') ) ) ?>" role="banner">
  			<?php
  				// The '__header' hook is used with the following callback functions (ordered by priorities) :
  				//ANGI_header_main::$instance->tc_logo_title_display(), ANGI_header_main::$instance->angi_fn_tagline_display(), ANGI_header_main::$instance->angi_fn_navbar_display()
  				do_action( '__header' );
  			?>
  		</header>
  		<?php
  		 	//This hook is used for the slider : ANGI_slider::$instance->angi_fn_slider_display()
  			do_action ( '__after_header' )
  		?>