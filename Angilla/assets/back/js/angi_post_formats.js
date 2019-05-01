/* ===================================================
 * angi-post-formats.js v1.0.1
 * ===================================================
 * (c) 2015 Nicolas Guillaume, Nice, France
 *
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * =================================================== */
( function($) {
   "use strict";

   $( function() {

      if ( ! ANGIPostFormatsParams )
         return;
      if ( ! ANGIPostFormatsParams.postFormatSections )
         return;

      // temporary workaround to make sure gutenberg elements have been rendered
      setTimeout( function() {
          var _wpPostFormatsInputSelectorClassical    = '#post-formats-select input[name="post_format"]',
              _wpPostFormatsInputSelectorGutenberg    = '.editor-post-format select',
              _isClassical                            = $(_wpPostFormatsInputSelectorClassical).length > 0,
              _isGutenberg                            = $(_wpPostFormatsInputSelectorGutenberg).length > 0;

          if ( !( _isClassical || _isGutenberg ) ) {
             return;
          }

          var _currentPostFormatSelector              = _isClassical ? _wpPostFormatsInputSelectorClassical + ':checked' : _wpPostFormatsInputSelectorGutenberg,
              _onChangePostFromatSelector             = _isClassical ? _wpPostFormatsInputSelectorClassical + ':radio'   : _wpPostFormatsInputSelectorGutenberg,
              _postFormatsMap                         = _.object( _.chain( ANGIPostFormatsParams.postFormatSections )
                                                             .map( function( _section ) {
                                                                var _post_format       = _section.replace( '_section', '' ),
                                                                    _mbsectionSelector = '#' + _section + 'id';
                                                                //create a pair [ audio , #audio_sectionid ]
                                                                return [ _post_format, _mbsectionSelector ];
                                                             })
                                                             //remove duplicates
                                                             .compact()
                                                             //values the chain
                                                             .value()
                                                       );//transform the list in an object like { audio: #audio_sectionid, video: #video_sectionid }
          if ( _postFormatsMap ) {
             init();
          }


          function init() {
             //initial Visibility
             setVisibilities( $(_currentPostFormatSelector).val() );
             //bind change
             // Hide/show post format meta box when option changed
             $(_onChangePostFromatSelector).on( 'change', function(evt) {
                setVisibilities( $(this).val() );
             });
          }

          function setVisibilities( _val ) {
             //hide all
             $( _.values( _postFormatsMap ).join() ).hide();
             //show selected
             $( _.pluck( [_postFormatsMap], _val ).join() ).show();
          }
      }, 300 );
   });
})( jQuery );