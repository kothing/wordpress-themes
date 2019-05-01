/* Functions JS */

( function ( $ ) {

	var body    = $( 'body' ),
		_window = $( window );

    // Initialize Featured Content slider.
    if ( body.is( '.slider' ) ) {
        $( '.featured-content' ).featuredslider( {
            selector: '.featured-content-inner > article',
            controlsContainer: '.featured-content'
        } );
    }
    
    // Animations
    if ($("[data-animation-effect]").length>0) {
        $("[data-animation-effect]").each(function() {
            var $this = $(this),
            animationEffect = $this.attr("data-animation-effect");
            if ($this.appear) {
                $this.appear(function() {
                    var delay = ($this.attr("data-effect-delay") ? $this.attr("data-effect-delay") : 1);
                    if(delay > 1) $this.css("effect-delay", delay + "ms");
                    setTimeout(function() {
                        $this.removeClass('object-non-visible');
                        $this.addClass('animated object-visible ' + animationEffect);
                    }, delay);
                }, {accX: 0, accY: -130});
            } else {
                $this.removeClass('object-non-visible');
                $this.addClass('object-visible');
            }
        });
    };

} )( jQuery );
