/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	var addInlineCss = function ( id, css ) {
		if ( $( 'style#' + id ).length ) {
			$( 'style#' + id ).html( css );
		}
		else {
			$( 'head' ).append( '<style id="' + id + '">' + css + '</style>' );
			setTimeout( function () {
				$( 'style#' + id ).not( ':last' ).remove();
			}, 100 );
		}
	}

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );

	// Sticky main menu.
	wp.customize( 'lyove_sticky_main_menu', function ( value ) {
		value.bind( function ( to ) {
			$( 'body' ).removeClass( 'fixed-nav' );
			if ( to ) {
				$( 'body' ).addClass( 'fixed-nav' );
			}
		} );
	} );

	// Color Scheme
	wp.customize( 'lyove_theme_color', function ( value ) {
		value.bind( function ( to ) {
			if ( !to ) {
				to = '#004d80';
			}
			var css = 'button:hover,input:hover[type="button"],input:hover[type="reset"],input:hover[type="submit"],button:focus,input:focus[type="button"],input:focus[type="reset"],input:focus[type="submit"] {background-color: ' + to + ';border-color: ' + to + '}';
			css += 'input:focus,textarea:focus {border-color: ' + to + '}';
			css += 'a,.nav-menu a:hover,.nav-menu a:focus,.nav-next a:hover,.nav-previous a:hover,.nav-next a:focus,.nav-previous a:focus {color: ' + to + '}';
			css += '.widget-title > span {border-bottom: 2px solid ' + to + '}';
			addInlineCss( 'lyove_theme_color', css );
		} );
	} );

	// Content sidebar layout
	wp.customize( 'lyove_layout', function ( value ) {
		value.bind( function( to ) {
			if ( ! $( '#secondary' ).length ) {
				wp.customize.preview.send( 'refresh' );
			} else {
				$( 'body' ).removeClass( 'content-sidebar sidebar-content only-content' );
				$( '#secondary' ).show();
				if ( to ) {
					$( 'body' ).addClass( to );
					if ( 'only-content' === to ) {
						$( '#secondary' ).hide();
					}
				}
			}
		} );
	} );
    /*
	wp.customize.previewer.bind( 'refresh', function() {
		wp.customize.previewer.refresh();
	} );
    */
} )( jQuery );
