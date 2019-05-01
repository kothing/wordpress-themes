/**
 * Sidebar Toggle
 *
 * Handles toggling the sidebar for small screens.
 */
( function( $ ) {
	if ( ! $( '#secondary' ).length ) {
		return;
	}

	var sidebarToggle = $( '#secondary' ).find('.sidebar-toggle');
	if ( ! sidebarToggle.length ) {
		return;
	}
	
	sidebarToggle.on( 'click.lyove', function() {
		$( '#secondary' ).toggleClass( 'toggled' );
		$( this ).attr( 'aria-expanded', $( '#secondary' ).hasClass( 'toggled' ) );
	});
} )( jQuery );
