/* global lyoveScreenReaderText */
/**
 * Adds toggle icon for mobile navigation and dropdown animations for widescreen navigation
 */
( function ( $ ) {

	var scrnRdrSpn = $('<span />', {'class': 'screen-reader-text', text: lyoveScreenReaderText.expand}),
		dropdownToggle = $('<button />', {'class': 'sub-menu-toggle', 'aria-expanded': false}).append(lyoveScreenReaderText.icon).append(scrnRdrSpn);

	$.fn.responsiveMenu = function() {

		var touchStartFn, i,
			$this = $(this),
			parentLink = $this.find('.menu-item-has-children > a, .page_item_has_children > a'),
			menuToggle = $this.find('.menu-toggle');

		if ( !menuToggle.length ) {
			return;
		}

		menuToggle.on('click.lyove', function () {
			$this.toggleClass('toggled-on');
			$(this).attr('aria-expanded', $this.hasClass('toggled-on'));
		} );

		parentLink.after(dropdownToggle);
		$this.find('.sub-menu-toggle').click( function (e) {
			var screenReaderSpan = $(this).find('.screen-reader-text');
			e.preventDefault();
			$(this).toggleClass('toggled-on');
			$(this).next('.children, .sub-menu').toggleClass('toggled-on');
			$(this).attr('aria-expanded', $(this).hasClass('toggled-on'));
			screenReaderSpan.text( $(this).hasClass('toggled-on') ? lyoveScreenReaderText.collapse : lyoveScreenReaderText.expand );
		} );

		if ('ontouchstart' in window) {
			$(window).on('resize.lyove', toggleFocusClassTouchScreen);
			toggleFocusClassTouchScreen();
		}

		$this.find('a').on('focus.lyove blur.lyove', function () {
			$(this).parents('.menu-item, .page_item').toggleClass('focus');
		} );

		function toggleFocusClassTouchScreen() {
			if ('none' === menuToggle.css('display')) {
				$(document.body).on( 'touchstart.lyove', function (e) {
					if (!$(e.target).closest('.nav-menu li').length) {
						$('.nav-menu li').removeClass('focus');
					}
				} );
				parentLink.on('touchstart.lyove', function (e) {
					var el = $(this).parent( 'li' );
					if (!el.hasClass('focus')) {
						e.preventDefault();
						el.toggleClass('focus');
						el.siblings('.focus').removeClass('focus');
					}
				} );
			}
			else {
				parentLink.unbind('touchstart.lyove');
			}
		}
	}

	if ($('.main-navigation').length) {
		$('.main-navigation').responsiveMenu();
	}

	/*
	 * Test if inline SVGs are supported.
	 * @link https://github.com/Modernizr/Modernizr/
	 */
	function supportsInlineSVG() {
		var div = document.createElement( 'div' );
		div.innerHTML = '<svg/>';
		return 'http://www.w3.org/2000/svg' === ( 'undefined' !== typeof SVGRect && div.firstChild && div.firstChild.namespaceURI );
	}
	// Change HTML class based on SVG support.
	$( document ).ready( function () {
		if ( true === supportsInlineSVG() ) {
			document.documentElement.className = document.documentElement.className.replace( /(\s*)no-svg(\s*)/, '$1svg$2' );
		}
	} );

	$(document).ready(function () {

		var navOffset,
			scrollPos = 0,
			scrollTimer = null,
			$placeholder = $("<div>", {'class': 'nav-placeholder'}),
			$menuToggle = $("#main-navigation").find('.menu-toggle');

		$placeholder.insertBefore("#main-navigation");

		function resetCss() {
			$(".nav-placeholder").css({
				"height" :"0",
				"width"  :"0",
				"margin" :"0",
				"padding":"0",
				"visibility":"hidden"
			});
		}

		function stickyUtility() {
			if (!$("#main-navigation").hasClass("site-navigation-fixed")) {
				navOffset = $("#main-navigation").offset().top;
				if ($('#wpadminbar').length) {
					navOffset = navOffset - $('#wpadminbar').outerHeight();
				}
			}
			if ('none' !== $menuToggle.css('display')) {
				resetCss();
			}
		}

		resetCss();
		stickyUtility();

		$(window).resize(function () {
			clearTimeout(scrollTimer);
			scrollTimer = setTimeout(stickyUtility, 100);
		});

		$(window).scroll(function () {

			if ( 'none' !== $menuToggle.css('display') ) {
				return;
			}

			var scrollPos = $(window).scrollTop();
			if ($("#main-navigation").hasClass("site-navigation-fixed")) {
				if (scrollPos < navOffset) {
					$("#main-navigation").removeClass("site-navigation-fixed");
					resetCss();
				}
			} else {
				if (scrollPos >= navOffset && $("body").hasClass("fixed-nav") ) {
					$("#main-navigation").addClass("site-navigation-fixed");
					$(".nav-placeholder").css({
						"width"  :"100%",
						"visibility":"visibile"
					});
					$(".nav-placeholder").height($("#main-navigation").outerHeight());
				}
			}
		} );
	} );

} )( jQuery );
