var angiapp = angiapp || {};
(function($, angiapp) {
      angiapp._printLog = function( log ) {
            var _render = function() {
                  return $.Deferred( function() {
                        var dfd = this;
                        $.when( $('#footer').before( $('<div/>', { id : "bulklog" }) ) ).done( function() {
                              $('#bulklog').css({
                                    position: 'fixed',
                                    'z-index': '99999',
                                    'font-size': '0.8em',
                                    color: '#000',
                                    padding: '5%',
                                    width: '90%',
                                    height: '20%',
                                    overflow: 'hidden',
                                    bottom: '0',
                                    left: '0',
                                    background: 'yellow'
                              });

                              dfd.resolve();
                        });
                  }).promise();
                },
                _print = function() {
                      $('#bulklog').prepend('<p>' + angiapp._prettyfy( { consoleArguments : [ log ], prettyfy : false } ) + '</p>');
                };

            if ( 1 != $('#bulk-log').length ) {
                _render().done( _print );
            } else {
                _print();
            }
      };


      angiapp._truncate = function( string , length ){
            length = length || 150;
            if ( ! _.isString( string ) )
              return '';
            return string.length > length ? string.substr( 0, length - 1 ) : string;
      };
      var _prettyPrintLog = function( args ) {
            var _defaults = {
                  bgCol : '#5ed1f5',
                  textCol : '#000',
                  consoleArguments : []
            };
            args = _.extend( _defaults, args );

            var _toArr = Array.from( args.consoleArguments ),
                _truncate = function( string ){
                      if ( ! _.isString( string ) )
                        return '';
                      return string.length > 300 ? string.substr( 0, 299 ) + '...' : string;
                };
            if ( ! _.isEmpty( _.filter( _toArr, function( it ) { return ! _.isString( it ); } ) ) ) {
                  _toArr =  JSON.stringify( _toArr.join(' ') );
            } else {
                  _toArr = _toArr.join(' ');
            }
            return [
                  '%c ' + _truncate( _toArr ),
                  [ 'background:' + args.bgCol, 'color:' + args.textCol, 'display: block;' ].join(';')
            ];
      };

      var _wrapLogInsideTags = function( title, msg, bgColor ) {
            if ( ( _.isUndefined( console ) && typeof window.console.log != 'function' ) )
              return;
            if ( angiapp.localized.isDevMode ) {
                  if ( _.isUndefined( msg ) ) {
                        console.log.apply( console, _prettyPrintLog( { bgCol : bgColor, textCol : '#000', consoleArguments : [ '<' + title + '>' ] } ) );
                  } else {
                        console.log.apply( console, _prettyPrintLog( { bgCol : bgColor, textCol : '#000', consoleArguments : [ '<' + title + '>' ] } ) );
                        console.log( msg );
                        console.log.apply( console, _prettyPrintLog( { bgCol : bgColor, textCol : '#000', consoleArguments : [ '</' + title + '>' ] } ) );
                  }
            } else {
                  console.log.apply( console, _prettyPrintLog( { bgCol : bgColor, textCol : '#000', consoleArguments : [ title ] } ) );
            }
      };
      angiapp.consoleLog = function() {
            if ( ! angiapp.localized.isDevMode )
              return;
            if ( ( _.isUndefined( console ) && typeof window.console.log != 'function' ) )
              return;
            console.log.apply( console, _prettyPrintLog( { consoleArguments : arguments } ) );
            console.log( 'Unstyled console message : ', arguments );
      };

      angiapp.errorLog = function() {
            if ( ( _.isUndefined( console ) && typeof window.console.log != 'function' ) )
              return;

            console.log.apply( console, _prettyPrintLog( { bgCol : '#ffd5a0', textCol : '#000', consoleArguments : arguments } ) );
      };


      angiapp.errare = function( title, msg ) { _wrapLogInsideTags( title, msg, '#ffd5a0' ); };
      angiapp.infoLog = function( title, msg ) { _wrapLogInsideTags( title, msg, '#5ed1f5' ); };
      angiapp.doAjax = function( queryParams ) {
            queryParams = queryParams || ( _.isObject( queryParams ) ? queryParams : {} );

            var ajaxUrl = queryParams.ajaxUrl || angiapp.localized.ajaxUrl,//the ajaxUrl can be specified when invoking doAjax
                nonce = angiapp.localized.frontNonce,//{ 'id' => 'HuFrontNonce', 'handle' => wp_create_nonce( 'hu-front-nonce' ) },
                dfd = $.Deferred(),
                _query_ = _.extend( {
                            action : '',
                            withNonce : false
                      },
                      queryParams
                );
            if ( "https:" == document.location.protocol ) {
                  ajaxUrl = ajaxUrl.replace( "http://", "https://" );
            }
            if ( _.isEmpty( _query_.action ) || ! _.isString( _query_.action ) ) {
                  angiapp.errorLog( 'angiapp.doAjax : unproper action provided' );
                  return dfd.resolve().promise();
            }
            _query_[ nonce.id ] = nonce.handle;
            if ( ! _.isObject( nonce ) || _.isUndefined( nonce.id ) || _.isUndefined( nonce.handle ) ) {
                  angiapp.errorLog( 'angiapp.doAjax : unproper nonce' );
                  return dfd.resolve().promise();
            }

            $.post( ajaxUrl, _query_ )
                  .done( function( _r ) {
                        if ( '0' === _r ||  '-1' === _r || false === _r.success ) {
                              angiapp.errare( 'angiapp.doAjax : done ajax error for action : ' + _query_.action , _r );
                              dfd.reject( _r );
                        }
                        dfd.resolve( _r );
                  })
                  .fail( function( _r ) {
                        angiapp.errare( 'angiapp.doAjax : failed ajax error for : ' + _query_.action, _r );
                        dfd.reject( _r );
                  });
            return dfd.promise();
      };
})(jQuery, angiapp);
(function($, angiapp) {
      angiapp.isKeydownButNotEnterEvent = function ( event ) {
        return ( 'keydown' === event.type && 13 !== event.which );
      };
      angiapp.setupDOMListeners = function( event_map , args, instance ) {
              var _defaultArgs = {
                        model : {},
                        dom_el : {}
                  };

              if ( _.isUndefined( instance ) || ! _.isObject( instance ) ) {
                    angiapp.errorLog( 'setupDomListeners : instance should be an object', args );
                    return;
              }
              if ( ! _.isArray( event_map ) ) {
                    angiapp.errorLog( 'setupDomListeners : event_map should be an array', args );
                    return;
              }
              if ( ! _.isObject( args ) ) {
                    angiapp.errorLog( 'setupDomListeners : args should be an object', event_map );
                    return;
              }

              args = _.extend( _defaultArgs, args );
              if ( ! ( args.dom_el instanceof jQuery ) || 1 != args.dom_el.length ) {
                    angiapp.errorLog( 'setupDomListeners : dom element should be an existing dom element', args );
                    return;
              }
              _.map( event_map , function( _event ) {
                    if ( ! _.isString( _event.selector ) || _.isEmpty( _event.selector ) ) {
                          angiapp.errorLog( 'setupDOMListeners : selector must be a string not empty. Aborting setup of action(s) : ' + _event.actions.join(',') );
                          return;
                    }
                    if ( ! _.isString( _event.selector ) || _.isEmpty( _event.selector ) ) {
                          angiapp.errorLog( 'setupDOMListeners : selector must be a string not empty. Aborting setup of action(s) : ' + _event.actions.join(',') );
                          return;
                    }
                    var once = _event.once ? _event.once : false;
                    args.dom_el[ once ? 'one' : 'on' ]( _event.trigger , _event.selector, function( e, event_params ) {
                          e.stopPropagation();
                          if ( angiapp.isKeydownButNotEnterEvent( e ) ) {
                            return;
                          }
                          e.preventDefault(); // Keep this AFTER the key filter above
                          var actionsParams = $.extend( true, {}, args );
                          if ( _.has( actionsParams, 'model') && _.has( actionsParams.model, 'id') ) {
                                if ( _.has( instance, 'get' ) )
                                  actionsParams.model = instance();
                                else
                                  actionsParams.model = instance.getModel( actionsParams.model.id );
                          }
                          $.extend( actionsParams, { event : _event, dom_event : e } );
                          $.extend( actionsParams, event_params );
                          if ( ! _.has( actionsParams, 'event' ) || ! _.has( actionsParams.event, 'actions' ) ) {
                                angiapp.errorLog( 'executeEventActionChain : missing obj.event or obj.event.actions' );
                                return;
                          }
                          try { angiapp.executeEventActionChain( actionsParams, instance ); } catch( er ) {
                                angiapp.errorLog( 'In setupDOMListeners : problem when trying to fire actions : ' + actionsParams.event.actions );
                                angiapp.errorLog( 'Error : ' + er );
                          }
                    });//.on()
              });//_.map()
      };//setupDomListeners
      angiapp.executeEventActionChain = function( args, instance ) {
              if ( 'function' === typeof( args.event.actions ) )
                return args.event.actions.call( instance, args );
              if ( ! _.isArray( args.event.actions ) )
                args.event.actions = [ args.event.actions ];
              var _break = false;
              _.map( args.event.actions, function( _cb ) {
                    if ( _break )
                      return;

                    if ( 'function' != typeof( instance[ _cb ] ) ) {
                          throw new Error( 'executeEventActionChain : the action : ' + _cb + ' has not been found when firing event : ' + args.event.selector );
                    }
                    var $_dom_el = ( _.has(args, 'dom_el') && -1 != args.dom_el.length ) ? args.dom_el : false;
                    if ( ! $_dom_el ) {
                          angiapp.errorLog( 'missing dom element');
                          return;
                    }
                    $_dom_el.trigger( 'before_' + _cb, _.omit( args, 'event' ) );
                    var _cb_return = instance[ _cb ].call( instance, args );
                    if ( false === _cb_return )
                      _break = true;
                    $_dom_el.trigger( 'after_' + _cb, _.omit( args, 'event' ) );
              });//_.map
      };
})(jQuery, angiapp);var angiapp = angiapp || {};
angiapp.methods = {};

(function( $ ){
      var ctor, inherits, slice = Array.prototype.slice;
      ctor = function() {};
      inherits = function( parent, protoProps, staticProps ) {
        var child;
        if ( protoProps && protoProps.hasOwnProperty( 'constructor' ) ) {
          child = protoProps.constructor;
        } else {
          child = function() {
            var result = parent.apply( this, arguments );
            return result;
          };
        }
        $.extend( child, parent );
        ctor.prototype  = parent.prototype;
        child.prototype = new ctor();
        if ( protoProps )
          $.extend( child.prototype, protoProps );
        if ( staticProps )
          $.extend( child, staticProps );
        child.prototype.constructor = child;
        child.__super__ = parent.prototype;

        return child;
      };
      angiapp.Class = function( applicator, argsArray, options ) {
        var magic, args = arguments;

        if ( applicator && argsArray && angiapp.Class.applicator === applicator ) {
          args = argsArray;
          $.extend( this, options || {} );
        }

        magic = this;
        if ( this.instance ) {
          magic = function() {
            return magic.instance.apply( magic, arguments );
          };

          $.extend( magic, this );
        }

        magic.initialize.apply( magic, args );
        return magic;
      };
      angiapp.Class.extend = function( protoProps, classProps ) {
        var child = inherits( this, protoProps, classProps );
        child.extend = this.extend;
        return child;
      };

      angiapp.Class.applicator = {};
      angiapp.Class.prototype.initialize = function() {};
      angiapp.Class.prototype.extended = function( constructor ) {
        var proto = this;

        while ( typeof proto.constructor !== 'undefined' ) {
          if ( proto.constructor === constructor )
            return true;
          if ( typeof proto.constructor.__super__ === 'undefined' )
            return false;
          proto = proto.constructor.__super__;
        }
        return false;
      };
      angiapp.Events = {
        trigger: function( id ) {
          if ( this.topics && this.topics[ id ] )
            this.topics[ id ].fireWith( this, slice.call( arguments, 1 ) );
          return this;
        },

        bind: function( id ) {
          this.topics = this.topics || {};
          this.topics[ id ] = this.topics[ id ] || $.Callbacks();
          this.topics[ id ].add.apply( this.topics[ id ], slice.call( arguments, 1 ) );
          return this;
        },

        unbind: function( id ) {
          if ( this.topics && this.topics[ id ] )
            this.topics[ id ].remove.apply( this.topics[ id ], slice.call( arguments, 1 ) );
          return this;
        }
      };
      angiapp.Value = angiapp.Class.extend({
        initialize: function( initial, options ) {
          this._value = initial; // @todo: potentially change this to a this.set() call.
          this.callbacks = $.Callbacks();
          this._dirty = false;

          $.extend( this, options || {} );

          this.set = $.proxy( this.set, this );
        },
        instance: function() {
          return arguments.length ? this.set.apply( this, arguments ) : this.get();
        },
        get: function() {
          return this._value;
        },
        set: function( to, o ) {
              var from = this._value, dfd = $.Deferred(), self = this, _promises = [];

              to = this._setter.apply( this, arguments );
              to = this.validate( to );
              var args = _.extend( { silent : false }, _.isObject( o ) ? o : {} );
              if ( null === to || _.isEqual( from, to ) ) {
                    return dfd.resolveWith( self, [ to, from, o ] ).promise();
              }

              this._value = to;
              this._dirty = true;
              if ( true === args.silent ) {
                    return dfd.resolveWith( self, [ to, from, o ] ).promise();
              }

              if ( this._deferreds ) {
                    _.each( self._deferreds, function( _prom ) {
                          _promises.push( _prom.apply( null, [ to, from, o ] ) );
                    });

                    $.when.apply( null, _promises )
                          .fail( function() { angiapp.errorLog( 'A deferred callback failed in api.Value::set()'); })
                          .then( function() {
                                self.callbacks.fireWith( self, [ to, from, o ] );
                                dfd.resolveWith( self, [ to, from, o ] );
                          });
              } else {
                    this.callbacks.fireWith( this, [ to, from, o ] );
                    return dfd.resolveWith( self, [ to, from, o ] ).promise( self );
              }
              return dfd.promise( self );
        },
        silent_set : function( to, dirtyness ) {
              var from = this._value;

              to = this._setter.apply( this, arguments );
              to = this.validate( to );
              if ( null === to || _.isEqual( from, to ) ) {
                return this;
              }

              this._value = to;
              this._dirty = ( _.isUndefined( dirtyness ) || ! _.isBoolean( dirtyness ) ) ? this._dirty : dirtyness;

              this.callbacks.fireWith( this, [ to, from, { silent : true } ] );

              return this;
        },

        _setter: function( to ) {
          return to;
        },

        setter: function( callback ) {
          var from = this.get();
          this._setter = callback;
          this._value = null;
          this.set( from );
          return this;
        },

        resetSetter: function() {
          this._setter = this.constructor.prototype._setter;
          this.set( this.get() );
          return this;
        },

        validate: function( value ) {
          return value;
        },
        bind: function() {
            var self = this,
                _isDeferred = false,
                _cbs = [];

            $.each( arguments, function( _key, _arg ) {
                  if ( ! _isDeferred )
                    _isDeferred = _.isObject( _arg  ) && _arg.deferred;
                  if ( _.isFunction( _arg ) )
                    _cbs.push( _arg );
            });

            if ( _isDeferred ) {
                  self._deferreds = self._deferreds || [];
                  _.each( _cbs, function( _cb ) {
                        if ( ! _.contains( _cb, self._deferreds ) )
                          self._deferreds.push( _cb );
                  });
            } else {
                  self.callbacks.add.apply( self.callbacks, arguments );
            }
            return this;
        },
        unbind: function() {
          this.callbacks.remove.apply( this.callbacks, arguments );
          return this;
        },
      });
      angiapp.Values = angiapp.Class.extend({
        defaultConstructor: angiapp.Value,

        initialize: function( options ) {
          $.extend( this, options || {} );

          this._value = {};
          this._deferreds = {};
        },
        instance: function( id ) {
          if ( arguments.length === 1 )
            return this.value( id );

          return this.when.apply( this, arguments );
        },
        value: function( id ) {
          return this._value[ id ];
        },
        has: function( id ) {
          return typeof this._value[ id ] !== 'undefined';
        },
        add: function( id, value ) {
          if ( this.has( id ) )
            return this.value( id );

          this._value[ id ] = value;
          value.parent = this;
          if ( value.extended( angiapp.Value ) )
            value.bind( this._change );

          this.trigger( 'add', value );
          if ( this._deferreds[ id ] )
            this._deferreds[ id ].resolve();

          return this._value[ id ];
        },
        create: function( id ) {
          return this.add( id, new this.defaultConstructor( angiapp.Class.applicator, slice.call( arguments, 1 ) ) );
        },
        each: function( callback, context ) {
          context = typeof context === 'undefined' ? this : context;

          $.each( this._value, function( key, obj ) {
            callback.call( context, obj, key );
          });
        },
        remove: function( id ) {
          var value;

          if ( this.has( id ) ) {
            value = this.value( id );
            this.trigger( 'remove', value );
            if ( value.extended( angiapp.Value ) )
              value.unbind( this._change );
            delete value.parent;
          }

          delete this._value[ id ];
          delete this._deferreds[ id ];
        },
        when: function() {
          var self = this,
            ids  = slice.call( arguments ),
            dfd  = $.Deferred();
          if ( $.isFunction( ids[ ids.length - 1 ] ) )
            dfd.done( ids.pop() );
          $.when.apply( $, $.map( ids, function( id ) {
            if ( self.has( id ) )
              return;
            return self._deferreds[ id ] || $.Deferred();
          })).done( function() {
            var values = $.map( ids, function( id ) {
                return self( id );
              });
            if ( values.length !== ids.length ) {
              self.when.apply( self, ids ).done( function() {
                dfd.resolveWith( self, values );
              });
              return;
            }

            dfd.resolveWith( self, values );
          });

          return dfd.promise();
        },
        _change: function() {
          this.parent.trigger( 'change', this );
        }
      });
      $.extend( angiapp.Values.prototype, angiapp.Events );

})( jQuery );//@global TCParams
var angiapp = angiapp || {};
(function($, angiapp) {
      var _methods = {
            cacheProp : function() {
                  var self = this;
                  $.extend( angiapp, {
                        $_window         : $(window),
                        $_html           : $('html'),
                        $_body           : $('body'),
                        $_wpadminbar     : $('#wpadminbar'),
                        $_header       : $('.tc-header'),
                        localized        : "undefined" != typeof(TCParams) && TCParams ? TCParams : { _disabled: [] },
                        is_responsive    : self.isResponsive(),//store the initial responsive state of the window
                        current_device   : self.getDevice()//store the initial device
                  });
            },
            isResponsive : function() {
                  return this.matchMedia(979);
            },
            getDevice : function() {
                  var _devices = {
                        desktop : 979,
                        tablet : 767,
                        smartphone : 480
                      },
                      _current_device = 'desktop',
                      that = this;


                  _.map( _devices, function( max_width, _dev ){
                        if ( that.matchMedia( max_width ) )
                          _current_device = _dev;
                  } );

                  return _current_device;
            },

            matchMedia : function( _maxWidth ) {
                  if ( window.matchMedia )
                    return ( window.matchMedia("(max-width: "+_maxWidth+"px)").matches );
                  var $_window = angiapp.$_window || $(window);
                  return $_window.width() <= ( _maxWidth - 15 );
            },
            emitCustomEvents : function() {

                  var that = this;
                  angiapp.$_window.resize( function() {
                        var //$_windowWidth     = angiapp.$_window.width(),
                            _current          = angiapp.current_device,//<= stored on last resize event or on load
                            _to               = that.getDevice();
                        angiapp.is_responsive  = that.isResponsive();
                        angiapp.current_device = _to;
                        angiapp.$_body.trigger( 'tc-resize', { current : _current, to : _to} );
                  } );//resize();
                  if ( 'undefined' !== typeof wp && 'undefined' !== typeof wp.customize && 'undefined' !== typeof wp.customize.selectiveRefresh ) {
                        wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function( placement ) {
                              angiapp.$_header = $('.tc-header');
                              angiapp.$_body.trigger( 'partialRefresh.angi', placement );
                        });
                  }

            },

            emit : function( cbs, args ) {
                  cbs = _.isArray(cbs) ? cbs : [cbs];
                  var self = this;
                  _.map( cbs, function(cb) {
                        if ( 'function' == typeof(self[cb]) ) {
                              args = 'undefined' == typeof( args ) ? [] : args ;
                              self[cb].apply(self, args );
                              angiapp.trigger( cb, _.object( _.keys(args), args ) );
                        }
                  });//_.map
            },

            triggerSimpleLoad : function( $_imgs ) {
                  if ( 0 === $_imgs.length )
                    return;

                  $_imgs.map( function( _ind, _img ) {
                    $(_img).load( function () {
                      $(_img).trigger('simple_load');
                    });//end load
                    if ( $(_img)[0] && $(_img)[0].complete )
                      $(_img).load();
                  } );//end map
            },//end of fn

            isUserLogged     : function() {
                  return angiapp.$_body.hasClass('logged-in') || 0 !== angiapp.$_wpadminbar.length;
            },

            isSelectorAllowed : function( $_el, skip_selectors, requested_sel_type ) {
                  var sel_type = 'ids' == requested_sel_type ? 'id' : 'class',
                  _selsToSkip   = skip_selectors[requested_sel_type];
                  if ( 'object' != typeof(skip_selectors) || ! skip_selectors[requested_sel_type] || ! $.isArray( skip_selectors[requested_sel_type] ) || 0 === skip_selectors[requested_sel_type].length )
                    return true;
                  if ( $_el.parents( _selsToSkip.map( function( _sel ){ return 'id' == sel_type ? '#' + _sel : '.' + _sel; } ).join(',') ).length > 0 )
                    return false;
                  if ( ! $_el.attr( sel_type ) )
                    return true;

                  var _elSels       = $_el.attr( sel_type ).split(' '),
                      _filtered     = _elSels.filter( function(classe) { return -1 != $.inArray( classe , _selsToSkip ) ;});
                  return 0 === _filtered.length;
            },
            _isMobile : function() {
                  return ( _.isFunction( window.matchMedia ) && matchMedia( 'only screen and (max-width: 720px)' ).matches ) || ( this._isCustomizing() && 'desktop' != this.previewDevice() );
            },
            _isCustomizing : function() {
                  return angiapp.$_body.hasClass('is-customizing') || ( 'undefined' !== typeof wp && 'undefined' !== typeof wp.customize );
            },
            _has_iframe : function ( $_elements ) {
                  var //that = this,
                      to_return = [];
                  _.each( $_elements, function( $_el, container ){
                        if ( $_el.length > 0 && $_el.find('IFRAME').length > 0 )
                          to_return.push(container);
                  });
                  return to_return;
            },
      };//_methods{}

      angiapp.methods.Base = angiapp.methods.Base || {};
      $.extend( angiapp.methods.Base , _methods );//$.extend

})(jQuery, angiapp);/***************************
* ADD BROWSER DETECT METHODS
****************************/
(function($, angiapp) {
  var _methods =  {
    addBrowserClassToBody : function() {
          if ( $.browser.chrome )
              angiapp.$_body.addClass("chrome");
          else if ( $.browser.webkit )
              angiapp.$_body.addClass("safari");
          if ( $.browser.mozilla )
              angiapp.$_body.addClass("mozilla");
          else if ( $.browser.msie || '8.0' === $.browser.version || '9.0' === $.browser.version || '10.0' === $.browser.version || '11.0' === $.browser.version )
              angiapp.$_body.addClass("ie").addClass("ie" + $.browser.version.replace(/[.0]/g, ''));
          if ( angiapp.$_body.hasClass("ie") )
              angiapp.$_body.addClass($.browser.version);
    }
  };//_methods{}
  angiapp.methods.BrowserDetect = angiapp.methods.BrowserDetect || {};
  $.extend( angiapp.methods.BrowserDetect , _methods );

})(jQuery, angiapp);
var angiapp = angiapp || {};
(function($, angiapp) {
      var _methods = {
            centerImagesWithDelay : function( delay ) {
              var self = this;
              setTimeout( function(){ self.emit('centerImages'); }, delay || 300 );
            },

            centerInfinity : function() {
                  var centerInfiniteImagesClassicStyle = function( collection, _container ) {
                        var   $_container = $(_container);

                        if ( 'object' !== typeof collection || 1 > $_container.length) {
                              return;
                        }
                        _.each( collection, function( elementSelector ) {
                              var $_img = $(  elementSelector + ' .thumb-wrapper', $_container ).centerImages( {
                                    enableCentering : 1 == angiapp.localized.centerAllImg,
                                    enableGoldenRatio : false,
                                    disableGRUnder : 0,//<= don't disable golden ratio when responsive
                                    oncustom : [ 'simple_load']
                              }).find( 'img' );

                              if ( $_img.length < 1 ) {
                                    $_img = $( elementSelector + ' .tc-rectangular-thumb',  $_container ).centerImages( {
                                          enableCentering : 1 == angiapp.localized.centerAllImg,
                                          enableGoldenRatio : true,
                                          goldenRatioVal : angiapp.localized.goldenRatio || 1.618,
                                          disableGRUnder : 0,//<= don't disable golden ratio when responsive
                                          oncustom : [ 'simple_load']
                                    }).find( 'img' );
                              }
                              if ( $_img.length < 1 ) {
                                    $_img = $( elementSelector + ' .tc-grid-figure', $_container ).centerImages( {
                                          enableCentering : 1 == angiapp.localized.centerAllImg,
                                          oncustom : [ 'simple_load'],
                                          enableGoldenRatio : true,
                                          goldenRatioVal : angiapp.localized.goldenRatio || 1.618,
                                          goldenRatioLimitHeightTo : angiapp.localized.gridGoldenRatioLimit || 350
                                    }).find( 'img' );
                              }
                              angiapp.methods.Base.triggerSimpleLoad( $_img );
                        });
                  };//end centerInfiniteImagesClassicStyle
                  angiapp.$_body.on( 'post-load', function( e, response ) {
                        if ( ( 'undefined' !== typeof response ) && 'success' == response.type && response.collection && response.container ) {
                              centerInfiniteImagesClassicStyle(
                                  response.collection,
                                  '#'+response.container //_container
                              );
                        }
                  } );
            },
            imgSmartLoad : function() {
              var smartLoadEnabled = 1 == TCParams.imgSmartLoadEnabled,
                  _where           = TCParams.imgSmartLoadOpts.parentSelectors.join();
              if (  smartLoadEnabled )
                $( _where ).imgSmartLoad(
                  _.size( TCParams.imgSmartLoadOpts.opts ) > 0 ? TCParams.imgSmartLoadOpts.opts : {}
                );
              if ( 1 == TCParams.centerAllImg ) {
                var self                   = this,
                    $_to_center            = smartLoadEnabled ?
                       $( _.filter( $( _where ).find('img'), function( img ) {
                          return $(img).is(TCParams.imgSmartLoadOpts.opts.excludeImg.join());
                        }) ): //filter
                        $( _where ).find('img');
                    var $_to_center_with_delay = $( _.filter( $_to_center, function( img ) {
                        return $(img).hasClass('tc-holder-img');
                    }) );
                setTimeout( function(){
                  self.triggerSimpleLoad( $_to_center_with_delay );
                }, 300 );
                self.triggerSimpleLoad( $_to_center );
              }
            },
            dropCaps : function() {
              if ( ! TCParams.dropcapEnabled || ! _.isObject( TCParams.dropcapWhere ) )
                return;

              $.each( TCParams.dropcapWhere , function( ind, val ) {
                if ( 1 == val ) {
                  $( '.entry-content' , 'body.' + ( 'page' == ind ? 'page' : 'single-post' ) ).children().first().addDropCap( {
                    minwords : TCParams.dropcapMinWords,//@todo check if number
                    skipSelectors : _.isObject(TCParams.dropcapSkipSelectors) ? TCParams.dropcapSkipSelectors : {}
                  });
                }
              });//each
            },
            extLinks : function() {
              if ( ! TCParams.extLinksStyle && ! TCParams.extLinksTargetExt )
                return;
              $('a' , '.entry-content').extLinks({
                addIcon : TCParams.extLinksStyle,
                newTab : TCParams.extLinksTargetExt,
                skipSelectors : _.isObject(TCParams.extLinksSkipSelectors) ? TCParams.extLinksSkipSelectors : {}
              });
            },
            fancyBox : function() {
              if ( 1 != TCParams.FancyBoxState || 'function' != typeof($.fn.fancybox) )
                return;

              $("a.grouped_elements").fancybox({
                transitionOut: "elastic",
                transitionIn: "elastic",
                speedIn: 200,
                speedOut: 200,
                overlayShow: !1,
                autoScale: 1 == TCParams.FancyBoxAutoscale ? "true" : "false",
                changeFade: "fast",
                enableEscapeButton: !0
              });
              $('a[rel*=tc-fancybox-group]').each( function() {
                var title = $(this).find('img').prop('title');
                var alt = $(this).find('img').prop('alt');
                if (typeof title !== 'undefined' && 0 !== title.length)
                  $(this).attr('title',title);
                else if (typeof alt !== 'undefined' &&  0 !== alt.length)
                  $(this).attr('title',alt);
              });
            },
            centerImages : function() {
              setTimeout( function() {
                $.each( $( '.carousel .carousel-inner') , function() {
                  $( this ).centerImages( {
                    enableCentering : 1 == TCParams.centerSliderImg,
                    imgSel : '.angi-item .carousel-image img',
                    oncustom : ['angilla.slid', 'simple_load', 'smartload'],
                    defaultCSSVal : { width : '100%' , height : 'auto' },
                    useImgAttr : true
                  });
                  var self = this;
                  setTimeout( function() {
                      $( self ).prevAll('.tc-slider-loader-wrapper').fadeOut();
                  }, 500 );
                });
              } , 50);
              $('.widget-front .thumb-wrapper').centerImages( {
                enableCentering : 1 == TCParams.centerAllImg,
                enableGoldenRatio : false,
                disableGRUnder : 0,//<= don't disable golden ratio when responsive
                zeroTopAdjust : 1,
                leftAdjust : 2.5,
                oncustom : ['smartload', 'simple_load']
              });
              $('.thumb-wrapper', '.hentry' ).centerImages( {
                enableCentering : 1 == TCParams.centerAllImg,
                enableGoldenRatio : false,
                disableGRUnder : 0,//<= don't disable golden ratio when responsive
                oncustom : ['smartload', 'simple_load']
              });
              $('.tc-rectangular-thumb', '.tc-post-list-context' ).centerImages( {
                enableCentering : 1 == TCParams.centerAllImg,
                enableGoldenRatio : true,
                goldenRatioVal : TCParams.goldenRatio || 1.618,
                disableGRUnder : 0,//<= don't disable golden ratio when responsive
                oncustom : ['smartload', 'refresh-height', 'simple_load'] //bind 'refresh-height' event (triggered to the the customizer preview frame)
              });
              $('.tc-rectangular-thumb' , '.tc-singular-thumbnail-wrapper').centerImages( {
                enableCentering : 1 == TCParams.centerAllImg,
                enableGoldenRatio : false,
                disableGRUnder : 0,//<= don't disable golden ratio when responsive
                oncustom : ['smartload', 'refresh-height', 'simple_load'], //bind 'refresh-height' event (triggered to the the customizer preview frame)
                setOpacityWhenCentered : true,//will set the opacity to 1
                opacity : 1
              });
              $('.tc-grid-figure').centerImages( {
                enableCentering : 1 == TCParams.centerAllImg,
                oncustom : ['smartload', 'simple_load'],
                enableGoldenRatio : true,
                goldenRatioVal : TCParams.goldenRatio || 1.618,
                goldenRatioLimitHeightTo : TCParams.gridGoldenRatioLimit || 350
              } );
            },//center_images
            parallax : function() {
              $( '.parallax-item' ).angiParallax(
              {
                parallaxRatio : 0.55
              }
              );
            }
      };//_methods{}

      angiapp.methods.JQPlugins = angiapp.methods.JQPlugins || {};
      $.extend( angiapp.methods.JQPlugins = {} , _methods );

})(jQuery, angiapp);var angiapp = angiapp || {};
(function($, angiapp) {
      var _methods = {
            initOnDomReady : function() {
                  var self = this;
                  this.$_sliders = $( 'div[id*="angilla-slider"]' );
                  angiapp.$_window.resize( function(){
                    self.centerSliderArrows();
                  });
            },



            fireSliders : function(name, delay, hover) {
              var self = this,
                  _name   = name || TCParams.SliderName,
                  _delay  = delay || TCParams.SliderDelay,
                  _hover  = hover || TCParams.SliderHover,
                  _cellSelector = '.angi-item',
                  _cssLoaderClass = 'tc-css-loader',
                  _css_loader = '<div class="' + _cssLoaderClass + ' tc-mr-loader" style="display:none"><div></div><div></div><div></div></div>';

              if ( 0 === _name.length || 1 > self.$_sliders.length )
                return;
              if ( angiapp.localized.imgSmartLoadsForSliders ) {
                    self.$_sliders.addClass('disable-transitions-for-smartload');
                    self.$_sliders.find( _cellSelector + '.active').imgSmartLoad().data( 'angi_smartLoaded', true );

                    var _maybeRemoveLoader = function( $_cell ) {
                          $_cell.find('.angi-css-loader').fadeOut( {
                                duration: 'fast',
                                done : function() { $(this).remove();}
                          } );
                    };


                    var _smartLoadCellImg = function( _event_ ) {
                          _event_ = _event_ || 'angi-smartloaded';
                          var $_cell = this;
                          if ( 1 > $_cell.find('img[data-src], img[data-smartload]').length )
                            return;
                          if ( ! $_cell.data( 'angi_smartLoaded' ) ) {
                                if ( 1 > $_cell.find('.angi-css-loader').length ) {
                                      $_cell.append( _css_loader ).find('.angi-css-loader').fadeIn( 'slow' );
                                }
                                $_cell.imgSmartLoad().data( 'angi_smartLoaded', true ).addClass( _event_ );
                                $_cell.data( 'angi_loader_timer' , $.Deferred( function() {
                                      var self = this;
                                      _.delay( function() {
                                            self.resolve();
                                      }, 2000 );
                                      return this.promise();
                                }) );
                                $_cell.data( 'angi_loader_timer' ).done( function() {
                                      _maybeRemoveLoader( $_cell );
                                });
                          }
                    };
                    self.$_sliders.data( 'angi_smartload_scheduled', $.Deferred().done( function() {
                          self.$_sliders.addClass('angi-smartload-scheduled');
                    }) );
                    var _isSliderDataSetup = function() {
                          return 1 <= self.$_sliders.length && ! _.isUndefined( self.$_sliders.data( 'angi_smartload_scheduled' ) );
                    };
                    self.$_sliders.data( 'angi_schedule_select',
                          $.Deferred( function() {
                                var dfd = this;
                                self.$_sliders.parent().one( 'angilla.slide click' , function() {
                                      dfd.resolve();
                                } );
                          }).done( function() {
                                if ( ! _isSliderDataSetup() || 'resolved' == self.$_sliders.data( 'angi_smartload_scheduled' ).state() )
                                    return;

                                self.$_sliders.find( _cellSelector ).each( function() {
                                      _smartLoadCellImg.call( $(this), 'angi-smartloaded-on-select' );
                                });
                                self.$_sliders.data( 'angi_smartload_scheduled').resolve();
                          })
                    );//data( 'angi_schedule_select' )
                    self.$_sliders.data( 'angi_schedule_scroll_resize',
                          $.Deferred( function() {
                                var dfd = this;
                                angiapp.$_window.one( 'scroll resize', function() {
                                      _.delay( function() { dfd.resolve(); }, 5000 );
                                });
                          }).done( function() {
                                if ( ! _isSliderDataSetup() || 'resolved' == self.$_sliders.data( 'angi_smartload_scheduled' ).state() )
                                    return;

                                self.$_sliders.find( _cellSelector ).each( function() {
                                      _smartLoadCellImg.call( $(this), 'angi-smartloaded-on-scroll' );
                                });
                                self.$_sliders.data( 'angi_smartload_scheduled').resolve();
                          })
                    );//data( 'angi_schedule_scroll_resize' )
                    self.$_sliders.data( 'angi_schedule_autoload',
                          $.Deferred( function() {
                                var dfd = this;
                                _.delay( function() { dfd.resolve(); }, 10000 );
                          }).done( function() {
                                if ( ! _isSliderDataSetup() || 'resolved' == self.$_sliders.data( 'angi_smartload_scheduled' ).state() )
                                    return;

                                self.$_sliders.find( _cellSelector ).each( function() {
                                      _smartLoadCellImg.call( $(this), 'angi-auto-smartloaded' );
                                });
                                self.$_sliders.data( 'angi_smartload_scheduled').resolve();
                          })
                    );
                    self.$_sliders.on( 'smartload', _cellSelector , function() {
                          _maybeRemoveLoader( $(this) );
                    });
              }//if angiapp.localized.imgSmartLoadsForSliders

              if ( 0 !== _delay.length && ! _hover ) {
                this.$_sliders.angiCarousel({
                    interval: _delay,
                    pause: "false"
                });
              } else if ( 0 !== _delay.length ) {
                this.$_sliders.angiCarousel({
                    interval: _delay
                });
              } else {
                this.$_sliders.angiCarousel();
              }
            },

            parallaxSliders : function() {
              if ( 'function' == typeof $.fn.angiParallax ) {
                $( '.angi-parallax-slider' ).angiParallax();
              }
            },

            manageHoverClass : function() {
              this.$_sliders.hover( function() {
                  $(this).addClass('tc-slid-hover');
                },
                function() {
                  $(this).removeClass('tc-slid-hover');
                }
              );
            },
            centerSliderArrows : function() {
              if ( 0 === this.$_sliders.length )
                  return;
              this.$_sliders.each( function() {
                  var _slider_height = $( '.carousel-inner' , $(this) ).height();
                  $('.tc-slider-controls', $(this) ).css("line-height", _slider_height +'px').css("max-height", _slider_height +'px');
              });
            },
            addSwipeSupport : function() {
              if ( 'function' != typeof($.fn.hammer) || 0 === this.$_sliders.length )
                return;
              this.$_sliders.on('touchstart touchmove', 'input, button, textarea, select, a:not(".tc-slide-link")', function(ev) {
                  ev.stopPropagation();
              });

              var _is_rtl = angiapp.$_body.hasClass('rtl');
              this.$_sliders.each( function() {
                  $(this).hammer().on('swipeleft', function() {
                      $(this).angiCarousel( ! _is_rtl ? 'next' : 'prev' );
                  });
                  $(this).hammer().on('swiperight', function(){
                      $(this).angiCarousel( ! _is_rtl ? 'prev' : 'next' );
                  });
              });
            },
            sliderTriggerSimpleLoad : function() {
              this.triggerSimpleLoad( this.$_sliders.find('.carousel-inner img') );
            }
      };//methods {}

      angiapp.methods.Slider = {};
      $.extend( angiapp.methods.Slider , _methods );

})(jQuery, angiapp);var angiapp = angiapp || {};

(function($, angiapp) {
      var _methods =  {
            initOnDomReady : function() {
                this.timer = 0;
                this.increment = 1;//used to wait a little bit after the first user scroll actions to trigger the timer
            },//init
            eventListener : function() {
                  var self = this;

                  angiapp.$_window.scroll( _.throttle( function() {
                        self.eventHandler( 'scroll' );
                  }, 50 ) );
            },//eventListener
            eventHandler : function ( evt ) {
              var self = this;

              switch ( evt ) {
                case 'scroll' :
                  if ( 0 === $('.tc-btt-wrapper').length )
                    return;
                  if ( this.timer) {
                    this.increment++;
                    clearTimeout(self.timer);
                  }
                  if ( 1 == TCParams.timerOnScrollAllBrowsers ) {
                    this.timer = setTimeout( function() {
                      self.bttArrowVisibility();
                    }, self.increment > 5 ? 50 : 0 );
                  } else if ( angiapp.$_body.hasClass('ie') ) {
                    this.timer = setTimeout( function() {
                      self.bttArrowVisibility();
                    }, self.increment > 5 ? 50 : 0 );
                  }
                break;
              }
            },//eventHandler
            outline: function() {
              if ( angiapp.$_body.hasClass( 'mozilla' ) && 'function' == typeof( tcOutline ) )
                  tcOutline();
            },
            smoothScroll: function() {
              if ( TCParams.SmoothScroll && TCParams.SmoothScroll.Enabled )
                smoothScroll( TCParams.SmoothScroll.Options );
            },
            anchorSmoothScroll : function() {
              if ( ! TCParams.anchorSmoothScroll || 'easeOutExpo' != TCParams.anchorSmoothScroll )
                    return;

              var _excl_sels = ( TCParams.anchorSmoothScrollExclude && _.isArray( TCParams.anchorSmoothScrollExclude.simple ) ) ? TCParams.anchorSmoothScrollExclude.simple.join(',') : '',
                  self = this,
                  $_links = $('#tc-page-wrap a[href^="#"],#tc-sn a[href^="#"]').not(_excl_sels);
              var _links, _deep_excl = _.isObject( TCParams.anchorSmoothScrollExclude.deep ) ? TCParams.anchorSmoothScrollExclude.deep : null ;
              if ( _deep_excl )
                _links = _.toArray($_links).filter( function ( _el ) {
                  return ( 2 == ( ['ids', 'classes'].filter(
                                function( sel_type) {
                                    return self.isSelectorAllowed( $(_el), _deep_excl, sel_type);
                                } ) ).length
                        );
                });
              $(_links).click( function () {
                var anchor_id = $(this).attr("href");
                if ( ! $(anchor_id).length )
                  return;

                if ('#' != anchor_id) {
                    $('html, body').animate({
                        scrollTop: $(anchor_id).offset().top
                    }, 700, TCParams.anchorSmoothScroll);
                }
                return false;
              });//click
            },
            bttArrowVisibility : function () {
              if ( angiapp.$_window.scrollTop() > 100 )
                $('.tc-btt-wrapper').addClass('show');
              else
                $('.tc-btt-wrapper').removeClass('show');
            },//bttArrowVisibility
            backToTop : function() {
              var $_html = $("html, body"),
                  _backToTop = function( evt ) {
                    return ( evt.which > 0 || "mousedown" === evt.type || "mousewheel" === evt.type) && $_html.stop().off( "scroll mousedown DOMMouseScroll mousewheel keyup", _backToTop );
                  };

              $(".back-to-top, .tc-btt-wrapper, .btt-arrow").on("click touchstart touchend", function ( evt ) {
                evt.preventDefault();
                evt.stopPropagation();
                $_html.on( "scroll mousedown DOMMouseScroll mousewheel keyup", _backToTop );
                $_html.animate({
                    scrollTop: 0
                }, 1e3, function () {
                    $_html.stop().off( "scroll mousedown DOMMouseScroll mousewheel keyup", _backToTop );
                });
              });
            },
            widgetsHoverActions : function() {
              angiapp.$_body.on( 'mouseenter mouseleave', '.widget-front, article', _toggleThisHoverClass );

              angiapp.$_body.on( 'mouseenter mouseleave', '.widget li', _toggleThisOnClass );

              function _toggleThisHoverClass( evt ) {
                    _toggleElementClassOnHover( $(this), 'hover', evt );
              }

              function _toggleThisOnClass( evt ) {
                    _toggleElementClassOnHover( $(this), 'on', evt );
              }

              function _toggleElementClassOnHover( $_el, _class, _evt ) {
                    if ( 'mouseenter' == _evt.type )
                       $_el.addClass( _class );
                    else if ( 'mouseleave' == _evt.type )
                       $_el.removeClass( _class );
              }

            },
            attachmentsFadeEffect : function() {
              $("article.attachment img").delay(500).animate({
                    opacity: 1
                }, 700, function () {}
              );
            },
            clickableCommentButton : function() {
              if ( ! TCParams.HasComments )
                return;
              $('cite p.edit-link').each(function() {
                $(this).removeClass('btn btn-success btn-mini');
              });
              $('cite p.edit-link > a').each(function() {
                $(this).addClass('btn btn-success btn-mini');
              });
              $('.comment .reply').each(function() {
                $(this).removeClass('btn btn-small');
              });
              $('.comment .reply .comment-reply-link').each(function() {
                $(this).addClass('btn btn-small');
              });
            },
            dynSidebarReorder : function() {
              if ( 1 != TCParams.ReorderBlocks )
                return;
              if ( 'desktop' != this.getDevice() )
                this._reorderSidebars( 'responsive' );
              var self = this;
              angiapp.$_body.on( 'tc-resize' , function(e, param) {
                param = _.isObject(param) ? param : {};
                var _to = 'desktop' != param.to ? 'responsive' : 'normal',
                    _current = 'desktop' != param.current ? 'responsive' : 'normal';

                if ( _current != _to )
                  self._reorderSidebars( _to );
              } );
            },
            _reorderSidebars : function( _sidebarLayout ) {
              _sidebarLayout = _sidebarLayout || 'normal';
              var that = this,
                  LeftSidebarClass    = TCParams.LeftSidebarClass || '.span3.left.tc-sidebar',
                  RightSidebarClass   = TCParams.RightSidebarClass || '.span3.right.tc-sidebar';
              that.$_content      = that.$_content || $("#main-wrapper .container .article-container");
              that.$_left         = that.$_left || $("#main-wrapper .container " + LeftSidebarClass);
              that.$_right        = that.$_right || $("#main-wrapper .container " + RightSidebarClass);
              var iframeContainers = that._has_iframe( { 'content' : this.$_content, 'left' : this.$_left } ) ;

              var leftIframe    = $.inArray('left', iframeContainers) > -1,
                  contentIframe = $.inArray('content', iframeContainers) > -1;
              if ( leftIframe && contentIframe )
                return;

              if ( that.$_left.length ) {
                if ( leftIframe )
                  that.$_content[ _sidebarLayout === 'normal' ?  'insertAfter' : 'insertBefore']( that.$_left );
                else
                  that.$_left[ _sidebarLayout === 'normal' ?  'insertBefore' : 'insertAfter']( that.$_content );
              }
            },
            dropdownMenuEventsHandler : function() {
              var $dropdown_ahrefs    = $('.tc-open-on-click .menu-item.menu-item-has-children > a[href!="#"]'),
                  $dropdown_submenus  = $('.tc-open-on-click .dropdown .dropdown-submenu');
              $dropdown_ahrefs.on('click', function() {
                if ( ( $(this).next('.dropdown-menu').css('visibility') != 'hidden' &&
                        $(this).next('.dropdown-menu').is(':visible')  &&
                        ! $(this).parent().hasClass('dropdown-submenu') ) ||
                     ( $(this).next('.dropdown-menu').is(':visible') &&
                        $(this).parent().hasClass('dropdown-submenu') ) )
                    window.location = $(this).attr('href');
              });//.on()
              $dropdown_submenus.each(function(){
                var $parent = $(this),
                    $children = $parent.children('[data-toggle="dropdown"]');
                $children.on('click', function(){
                    var //submenu   = $(this).next('.dropdown-menu'),
                        openthis  = false;
                    if ( ! $parent.hasClass('open') ) {
                      openthis = true;
                    }
                    $($parent.parent()).children('.dropdown-submenu').each(function(){
                        $(this).removeClass('open');
                    });
                    if ( openthis )
                        $parent.addClass('open');

                    return false;
                });//.on()
              });//.each()
            },
            menuButtonHover : function() {
              var $_menu_btns = $('.btn-toggle-nav');
              $_menu_btns.hover(
                function() {
                  $(this).addClass('hover');
                },
                function() {
                  $(this).removeClass('hover');
                }
              );
            },
            secondMenuRespActions : function() {
              if ( ! TCParams.isSecondMenuEnabled )
                return;
              var userOption = TCParams.secondMenuRespSet || false,
                  that = this;
              if ( ! userOption || -1 == userOption.indexOf('in-sn') )
                return;
              var _cacheElements = function() {
                    that.$_sec_menu_els  = $('.nav > li', '.tc-header .nav-collapse');
                    that.$_sn_wrap       = $('.sn-nav', '.sn-nav-wrapper');
                    that.$_sec_menu_wrap = $('.nav', '.tc-header .nav-collapse');
                  },
                  _maybeClean = function() {
                    var $_sep = $( '.secondary-menu-separator' );

                    if ( $_sep.length ) {

                      switch(userOption) {
                          case 'in-sn-before' :
                            $_sep.prevAll('.menu-item').remove();
                          break;
                          case 'in-sn-after' :
                            $_sep.nextAll('.menu-item').remove();
                          break;
                      }
                      $_sep.remove();
                    }
                  };
              _cacheElements();
              var _locationOnDomReady = 'desktop' == this.getDevice() ? 'navbar' : 'side_nav';

              if ( 'desktop' != this.getDevice() )
                this._manageMenuSeparator( _locationOnDomReady , userOption)._moveSecondMenu( _locationOnDomReady , userOption );
              angiapp.$_body.on( 'tc-resize partialRefresh.angi', function( ev, param ) {
                    var _force = false;

                    if ( 'partialRefresh' == ev.type && 'angi' === ev.namespace && param.container && param.container.hasClass('tc-header')  ) {
                          _maybeClean();
                          _cacheElements();
                          param   = { to: angiapp.current_device, current: angiapp.current_device };
                          _force  = true;
                    }

                    param = _.isObject(param) ? param : {};
                    var _to = 'desktop' != param.to ? 'side_nav' : 'navbar',
                        _current = 'desktop' != param.current ? 'side_nav' : 'navbar';

                    if ( _current == _to && !_force )
                      return;

                    that._manageMenuSeparator( _to, userOption)._moveSecondMenu( _to, userOption );
              } );//.on()

            },

            _manageMenuSeparator : function( _to, userOption ) {
              var that = this;
              if ( 'navbar' == _to ) {
                $( '.secondary-menu-separator', that.$_sn_wrap).remove();
              }
              else {
                var $_sep = $( '<li class="menu-item secondary-menu-separator"><hr class="featurette-divider"></hr></li>' );

                switch(userOption) {
                  case 'in-sn-before' :
                    this.$_sn_wrap.prepend($_sep);
                  break;

                  case 'in-sn-after' :
                    this.$_sn_wrap.append($_sep);
                  break;
                }
              }
              return this;
            },
            _moveSecondMenu : function( _where, userOption ) {
              _where = _where || 'side_nav';
              var that = this;
              switch( _where ) {
                  case 'navbar' :
                    that.$_sec_menu_wrap.append(that.$_sec_menu_els);
                  break;

                  case 'side_nav' :
                    if ( 'in-sn-before' == userOption )
                      that.$_sn_wrap.prepend(that.$_sec_menu_els);
                    else
                      that.$_sn_wrap.append(that.$_sec_menu_els);
                  break;
                }
            },
            _has_iframe : function ( $_elements ) {
              var to_return = [];
              _.map( $_elements, function( $_el, container ){
                if ( $_el.length > 0 && $_el.find('IFRAME').length > 0 )
                  to_return.push(container);
              });
              return to_return;
            }

      };//_methods{}


      angiapp.methods.UserXP = {};
      $.extend( angiapp.methods.UserXP , _methods );
})(jQuery, angiapp);
var angiapp = angiapp || {};
(function($, angiapp) {
  var _methods =  {
        mayBePrintFrontNote : function() {
              if ( angiapp.localized && _.isUndefined( angiapp.localized.frontNotifications ) )
                return;
              if ( _.isEmpty( angiapp.localized.frontNotifications ) || ! _.isObject( angiapp.localized.frontNotifications ) )
                return;

              var self = this;
              angiapp.frontNotificationVisible = new angiapp.Value( false );
              angiapp.frontNotificationRendered = false;
              _.each( angiapp.localized.frontNotifications, function( _notification ) {
                    if ( ! _.isUndefined( angiapp.frontNotification ) )
                      return;

                    if ( ! _.isObject( _notification ) )
                      return;
                    _notification = _.extend( {
                          enabled : false,
                          content : '',
                          dismissAction : '',
                          ajaxUrl : angiapp.localized.ajaxUrl
                    }, _notification );
                    if ( _notification.enabled ) {
                          angiapp.frontNotification = new angiapp.Value( _notification );
                    }

              });
              angiapp.frontNotificationVisible.bind( function( visible ) {
                      return self._toggleNotification( visible );//returns a promise()
              }, { deferred : true } );

              angiapp.frontNotificationVisible( true );
        },//mayBePrintFrontNote()


        _toggleNotification : function( visible ) {
              var self = this,
                  dfd = $.Deferred();

              if ( angiapp.frontNotificationRendered && angiapp.frontNotificationVisible() )
                return dfd.resolve().promise();

              var _hideAndDestroy = function() {
                    return $.Deferred( function() {
                          var _dfd_ = this,
                              $notifWrap = $('#bottom-front-notification', '#footer');
                          if ( 1 == $notifWrap.length ) {
                                $notifWrap.css( { bottom : '-100%' } );
                                _.delay( function() {
                                      $notifWrap.remove();
                                      angiapp.$_body.find('#tc-footer-btt-wrapper').fadeIn('slow');
                                      angiapp.frontNotificationRendered = false;
                                      _dfd_.resolve();
                                }, 450 );// consistent with css transition: all 0.45s ease-in-out;
                          } else {
                              _dfd_.resolve();
                          }
                    });
              };

              var _renderAndSetup = function() {
                    var _dfd_ = $.Deferred(),
                        $footer = $('#footer', '#tc-page-wrap');
                    if ( _.isUndefined( angiapp.frontNotification ) || ! _.isFunction( angiapp.frontNotification ) || ! _.isObject( angiapp.frontNotification() ) )
                        return _dfd_.resolve().promise();
                    $.Deferred( function() {
                          var dfd = this,
                              _notifHtml = angiapp.frontNotification().content,
                              _wrapHtml = [
                                    '<div id="bottom-front-notification">',
                                      '<div class="note-content">',
                                        '<span class="fas fa-times close-note" title="' + angiapp.localized.i18n['Permanently dismiss'] + '"></span>',
                                      '</div>',
                                    '</div>'
                              ].join('');

                          if ( 1 == $footer.length && ! _.isEmpty( _notifHtml ) ) {
                                $.when( $footer.append( _wrapHtml ) ).done( function() {
                                    $(this).find( '.note-content').prepend( _notifHtml );
                                    angiapp.$_body.find('#tc-footer-btt-wrapper').fadeOut('slow');
                                    angiapp.frontNotificationRendered = true;
                                });

                                _.delay( function() {
                                      $('#bottom-front-notification', '#footer').css( { bottom : 0 } );
                                      dfd.resolve();
                                }, 500 );
                          } else {
                                dfd.resolve();
                          }
                    }).done( function() {
                          angiapp.setupDOMListeners(
                                [
                                      {
                                            trigger   : 'click keydown',
                                            selector  : '.close-note',
                                            actions   : function() {
                                                  angiapp.frontNotificationVisible( false ).done( function() {
                                                        angiapp.doAjax( {
                                                              action: angiapp.frontNotification().dismissAction,
                                                              withNonce : true,
                                                              ajaxUrl : angiapp.frontNotification().ajaxUrl
                                                        });
                                                  });
                                            }
                                      }
                                ],//actions to execute
                                { dom_el: $footer },//dom scope
                                self //instance where to look for the cb methods
                          );
                          _dfd_.resolve();
                    });
                    return _dfd_.promise();
              };//renderAndSetup

              if ( visible ) {
                    _.delay( function() {
                          _renderAndSetup().always( function() {
                                dfd.resolve();
                          });
                    }, 3000 );
              } else {
                    _hideAndDestroy().done( function() {
                          angiapp.frontNotificationVisible( false );//should be already false
                          dfd.resolve();
                    });
              }
              _.delay( function() {
                          angiapp.frontNotificationVisible( false );
                    },
                    45000
              );
              return dfd.promise();
        }//_toggleNotification
  };//_methods{}

  angiapp.methods.UserXP = angiapp.methods.UserXP || {};
  $.extend( angiapp.methods.UserXP , _methods );

})(jQuery, angiapp);var angiapp = angiapp || {};
(function($, angiapp) {
      var _methods =  {
          initOnDomReady : function() {
                var self = this;
                this.stickyHeaderCacheElements();
                this.elToHide         = []; //[ '.social-block' , '.site-description' ],
                this.customOffset     = TCParams.stickyCustomOffset || {};// defaults : { _initial : 0, _scrolling : 0 }

                this.triggerHeight    = 20; //0.5 * windowHeight;

                this.scrollingDelay   = 1 != TCParams.timerOnScrollAllBrowsers && angiapp.$_body.hasClass('ie') ? 50 : 5;
                this.isHeaderSticky   = new angiapp.Value( false );
                this.isHeaderSticky.bind( function( isSticky ) { self._isHeaderStickyReact( isSticky ); } );
                this.stickyHeaderEventListener();
                this.triggerStickyHeaderLoad();
          },//init()
          stickyHeaderCacheElements : function() {
                this.$_resetMarginTop = $('#tc-reset-margin-top');
                this.$_sticky_logo    = $('img.sticky', '.site-logo');
                this.logo             = 0 === this.$_sticky_logo.length ? { _logo: $('img:not(".sticky")', '.site-logo') , _ratio: '' }: false;
          },
          stickyHeaderEventListener : function() {
                var self = this;
                angiapp.$_body.on( 'sticky-enabled-on-load' , function() {
                      self.stickyHeaderEventHandler( 'on-load' );
                });//.on()
                angiapp.$_window.on( 'tc-resize', function() {
                      self.stickyHeaderEventHandler( 'resize' );
                });
                angiapp.$_body.on( 'partialRefresh.angi', function( e, placement ) {
                      if ( placement.container && placement.container.hasClass( 'tc-header' )  ) {
                            self.stickyHeaderCacheElements();
                            self.stickyHeaderEventHandler( 'resize' );
                      }
                });
                angiapp.$_window.scroll( _.throttle( function() {
                      self.stickyHeaderEventHandler( 'scroll' );
                }, ! ( angiapp.$_body.hasClass('tc-smoothscroll') && ! self.isHeaderSticky() ) ? self.scrollingDelay : 15 ) );
                angiapp.$_body.on( angiapp.$_body.hasClass('tc-is-mobile') ? 'touchstart' : 'click' , '.sn-toggle', function() {
                      self.stickyHeaderEventHandler( 'sidenav-toggle' );
                });
          },
          triggerStickyHeaderLoad : function() {
                if ( ! this._is_sticky_enabled() )
                  return;
                angiapp.$_body.trigger( 'sticky-enabled-on-load' , { on : 'load' } );
          },
          stickyHeaderEventHandler : function( evt ) {
                if ( ! this._is_sticky_enabled() )
                  return;

                var self = this;

                switch ( evt ) {
                      case 'on-load' :
                            self._prepare_logo_transition();
                            setTimeout( function() {
                                  self._sticky_refresh();
                                  self._set_header_top_offset();
                                  self.isHeaderSticky( angiapp.$_window.scrollTop() > self.triggerHeight );
                            } , 20 );//setTimeout()
                      break;

                      case 'scroll' :
                            self._set_header_top_offset();
                            self.isHeaderSticky( angiapp.$_window.scrollTop() > self.triggerHeight );
                      break;

                      case 'resize' :
                      case 'sidenav-toggle' :
                            self._set_sticky_offsets();
                            self._set_header_top_offset();
                            self._set_logo_height();
                      break;
                }
          },
          _isHeaderStickyReact : function( isSticky ) {
                var self = this;
                if ( isSticky ) {
                      angiapp.$_body
                            .addClass("sticky-enabled")
                            .removeClass("sticky-disabled")
                            .trigger('tc-sticky-enabled');
                      if ( ! angiapp.$_header.hasClass('tc-shrink-on') ) {
                            self._set_logo_height();
                      }
                } else {
                      angiapp.$_body
                            .removeClass("sticky-enabled")
                            .addClass("sticky-disabled")
                            .trigger('tc-sticky-disabled');
                      setTimeout( function() { self._sticky_refresh(); } ,
                            self._isCustomizing ? 100 : 20
                      );
                      setTimeout( function() { self._sticky_refresh(); } , 200 );
                }
          },
          _is_sticky_enabled : function() {
                return angiapp.$_body.hasClass('tc-sticky-header');
          },
          _get_top_offset : function() {
                var initialOffset   = 0,
                    that            = this,
                    customOffset    = +this._get_custom_offset( that.isHeaderSticky() ? '_scrolling' : '_initial' );

                if ( 1 == this.isUserLogged() && ! this._isCustomizing() ) {
                      if ( 580 < angiapp.$_window.width() )
                        initialOffset = angiapp.$_wpadminbar.height();
                      else
                        initialOffset = ! this.isHeaderSticky() ? angiapp.$_wpadminbar.height() : 0;
                }
                return initialOffset + customOffset ;
          },
          _get_custom_offset : function( _context ) {
                if ( _.isEmpty( this.customOffset ) )
                  return 0;
                if ( ! this.customOffset[_context] )
                  return 0;
                if ( ! this.customOffset.options )
                  return this.customOffset[_context];
                if ( '_scrolling' == _context )
                  return +this.customOffset[_context] || 0;
                if ( this.customOffset.options._static )
                  return +this.customOffset[_context] || 0;

                var that = this,
                    $_el = $(that.customOffset.options._element);
                if ( ! $_el.length )
                  return 0;
                else {
                  return $_el.outerHeight() || +this.customOffset[_context] || 0;
                }
          },
          _set_sticky_offsets : function() {
                angiapp.$_header.css('top' , '');
                angiapp.$_header.css('height' , 'auto' );
                this.$_resetMarginTop.css('margin-top' , '' ).show();
                var headerHeight    = angiapp.$_header.outerHeight(true); /* include borders and eventual margins (true param)*/
                this.$_resetMarginTop.css('margin-top' , + headerHeight  + 'px');
          },
          _set_header_top_offset : function() {
                var self = this;
                angiapp.$_header.css('top' , self._get_top_offset() );
          },
          _prepare_logo_transition : function(){
                if ( ! ( angiapp.$_html.hasClass('csstransitions') && ( this.logo && 0 !== this.logo._logo.length ) ) )
                  return;

                var logoW = this.logo._logo.originalWidth(),
                    logoH = this.logo._logo.originalHeight();
                if ( 2 != _.size( _.filter( [ logoW, logoH ], function(num){ return _.isNumber( parseInt(num, 10) ) && 0 !== num; } ) ) )
                  return;

                this.logo._ratio = logoW / logoH;
                this.logo._logo.css('width' , logoW );
          },
          _set_logo_height : function(){
                if ( this.logo && 0 === this.logo._logo.length || ! this.logo._ratio )
                  return;
                var self = this;
                this.logo._logo.css('height' , self.logo._logo.width() / self.logo._ratio );

                setTimeout( function() {
                      self._set_sticky_offsets();
                      self._set_header_top_offset();
                } , 200 );
          },

          _sticky_refresh : function() {
                var self = this;
                setTimeout( function() {
                      self._set_sticky_offsets();
                      self._set_header_top_offset();
                } , 20 );
                angiapp.$_window.trigger('resize');
          }
    };//_methods{}

    angiapp.methods.StickyHeader = {};
    $.extend( angiapp.methods.StickyHeader , _methods );

})(jQuery, angiapp);var angiapp = angiapp || {};
(function($, angiapp) {
      var _methods =  {
            initOnDomReady : function() {
              this.$_push   = $('#tc-push-footer');
              this._class   = 'sticky-footer-enabled';
              this.$_page   = $('#tc-page-wrap');

              if ( 1 != TCParams.stickyHeader ) {//sticky header fires a resize
                var self = this;
                setTimeout( function() {
                        self._apply_sticky_footer(); }, 50
                );
              }
            },
            stickyFooterEventListener : function() {
              var self = this;
              angiapp.$_window.on( 'tc-resize', function() {
                self.stickyFooterEventHandler('resize');
              });
              angiapp.$_window.on( 'golden-ratio-applied', function() {
                self.stickyFooterEventHandler('refresh');
              });
              angiapp.$_body.on( 'refresh-sticky-footer', function() {
                self.stickyFooterEventHandler('refresh');
              });

            },

            stickyFooterEventHandler : function( evt ) {
              var self = this;

              if ( ! this._is_sticky_footer_enabled() )
                return;

              switch ( evt ) {
                case 'resize':
                  var func = function() { return self._apply_sticky_footer() ;};
                  for ( var i = 0; i<5; i++ ) /* I've seen something like that in twentyfifteen */
                    setTimeout( func, 50 * i);
                break;
                case 'refresh':
                  this._apply_sticky_footer();
                break;
              }
            },
            _apply_sticky_footer : function() {

              var  _f_height     = this._get_full_height(),
                   _w_height     = angiapp.$_window.height(),
                   _push_height  = _w_height - _f_height,
                   _event        = false;

              if ( _push_height > 0 ) {
                this.$_push.css('height', _push_height).addClass(this._class);
                _event = 'sticky-footer-on';
              }else if ( this.$_push.hasClass(this._class) ) {
                this.$_push.removeClass(this._class);
                _event = 'sticky-footer-off';
              }
              if ( _event )
                angiapp.$_body.trigger(_event);
            },
            _is_sticky_footer_enabled : function() {
              return angiapp.$_body.hasClass('tc-sticky-footer');
            },
            _get_full_height : function() {
              if ( this.$_page.length < 1 )
                return $(window).outerHeight(true);
              var _full_height = this.$_page.outerHeight(true) + this.$_page.offset().top,
                  _push_height = 'block' == this.$_push.css('display') ? this.$_push.outerHeight() : 0;

              return _full_height - _push_height;
            }
      };//_methods{}

      angiapp.methods.StickyFooter = {};
      $.extend( angiapp.methods.StickyFooter , _methods );

})(jQuery, angiapp);
var angiapp = angiapp || {};
(function($, angiapp) {
      var _methods =  {
            initOnDomReady : function() {
              this.$_sidenav                = $( '#tc-sn' );

              if ( ! this._is_sn_on() )
                return;
              this.$_page_wrapper           = $('#tc-page-wrap');
              this.$_page_wrapper_node      = this.$_page_wrapper.get(0);
              this.$_page_wrapper_btn       = $('.btn-toggle-nav', '#tc-page-wrap');

              this.$_sidenav_inner          = $( '.tc-sn-inner', this.$_sidenav);

              this._toggle_event            = 'click';// before c4, was angiapp.$_body.hasClass('tc-is-mobile') ? 'touchstart' : 'click';

              this._browser_can_translate3d = ! angiapp.$_html.hasClass('no-csstransforms3d');
              this.transitionEnd            = 'transitionend webkitTransitionEnd otransitionend oTransitionEnd MSTransitionEnd';
              this.sideNavEventListener();

              this._set_offset_height();

            },//init()
            sideNavEventListener : function() {
              var self = this;
              angiapp.$_body.on( this._toggle_event, '.sn-toggle', function( evt ) {
                self.sideNavEventHandler( evt, 'toggle' );
              });
              this.$_page_wrapper.on( this.transitionEnd, function( evt ) {
                self.sideNavEventHandler( evt, 'transitionend' );
              });
              angiapp.$_window.on('tc-resize', function( evt ) {
                self.sideNavEventHandler( evt, 'resize');
              });

              angiapp.$_window.scroll( function( evt ) {
                self.sideNavEventHandler( evt, 'scroll');
              });
            },


            sideNavEventHandler : function( evt, evt_name ) {
              var self = this;

              switch ( evt_name ) {
                case 'toggle':
                  if ( ! this._is_translating() )
                    this._toggle_callback( evt );
                break;

                case 'transitionend' :
                   if ( this._is_translating() && evt.target == this.$_page_wrapper_node )
                     this._transition_end_callback();
                break;

                case 'scroll' :
                case 'resize' :
                  setTimeout( function(){
                      self._set_offset_height();
                  }, 200);
                break;
              }
            },


            _toggle_callback : function ( evt ){
              evt.preventDefault();

              if ( angiapp.$_body.hasClass( 'tc-sn-visible' ) )
                this._anim_type = 'sn-close';
              else
                this._anim_type = 'sn-open';
              if ( this._browser_can_translate3d ){
                angiapp.$_body.addClass( 'animating ' + this._anim_type )
                             .trigger( this._anim_type + '_start' );
                if ( this._is_sticky_header() ){
                  if ( angiapp.$_body.hasClass('sticky-disabled') )
                    angiapp.$_body.removeClass('tc-sticky-header');
                }
              } else {
                angiapp.$_body.toggleClass('tc-sn-visible')
                             .trigger( this._anim_type );
              }
              var _event = evt || event,
                  $_clicked_btn = $( _event.target ),
                  _is_opening   = $('#tc-page-wrap').has( $_clicked_btn).length > 0;

              this.$_page_wrapper_btn.each( function(){
                $(this).fadeTo( 500 , _is_opening ? 0 : 1 , function() {
                  $(this).css( "visibility", _is_opening ? "hidden" : "visible");
                }); //.fadeTo() duration, opacity, callback
              } );
              return false;
           },

           _transition_end_callback : function() {
             angiapp.$_body.removeClass( 'animating ' +  this._anim_type)
                          .toggleClass( 'tc-sn-visible' )
                          .trigger( this._anim_type + '_end' )
                          .trigger( this._anim_type );
             if ( this._is_sticky_header() ){
               if ( angiapp.$_body.hasClass('sticky-disabled') )
                 angiapp.$_body.addClass('tc-sticky-header');
              }
            },
            _is_sn_on : function() {
              return this.$_sidenav.length > 0;
            },
            _get_initial_offset : function() {
              var _initial_offset = angiapp.$_wpadminbar.length > 0 ? angiapp.$_wpadminbar.height() : 0;
              _initial_offset = _initial_offset && angiapp.$_window.scrollTop() && 'absolute' == angiapp.$_wpadminbar.css('position') ? 0 : _initial_offset;

              return _initial_offset; /* add a custom offset ?*/
            },
            _set_offset_height : function() {
              var _offset = this._get_initial_offset();

              this.$_sidenav.css('top', _offset );
              this.$_sidenav_inner.css('max-height', this.$_sidenav.outerHeight() - _offset);
            },
            _is_translating : function() {
              return angiapp.$_body.hasClass('animating');
            },
            _is_sticky_header : function() {
              this.__is_sticky_header = this.__is_sticky_header || angiapp.$_body.hasClass('tc-sticky-header');
              return this.__is_sticky_header;
            }

      };//_methods{}

      angiapp.methods.SideNav = {};
      $.extend( angiapp.methods.SideNav , _methods );

})(jQuery, angiapp);
var angiapp = angiapp || {};
(function($, angiapp) {
      var _methods =  {
            fireDropDown : function() {
              this.$_sidenav                = $( '#tc-sn' );
              this._dd_first_selector       = '.menu-item-has-children.dropdown > .dropdown-menu' ;
              this.$_nav_collapse           = angiapp.$_header.length > 0 ? angiapp.$_header.find( '.navbar-wrapper .nav-collapse' ) : [];
              this.$_nav                    = this.$_nav_collapse.length ? this.$_nav_collapse.find( '.nav' ) : [];

              if ( ! this._has_dd_to_move() )
                return;
              this.$_navbar_wrapper         = this.$_nav_collapse.closest( '.navbar-wrapper' );
              this.$_nav                    = this.$_nav_collapse.find( '.nav' );
              this.$_head                   = $( 'head' );
              this._dyn_style_id            = 'tc-dropdown-dyn-style';
              this._prop                    = angiapp.$_body.hasClass('rtl') ? 'right' : 'left';
              this.dropdownPlaceEventListener();
              this._place_dropdowns();
            },//init()


            dropdownPlaceCacheElements : function() {
              this.$_nav_collapse           = angiapp.$_header.length > 0 ? angiapp.$_header.find( '.navbar-wrapper .nav-collapse' ) : [];
              this.$_nav                    = this.$_nav_collapse.length ? this.$_nav_collapse.find( '.nav' ) : [];
              this.$_navbar_wrapper         = this.$_nav_collapse.length ? this.$_nav_collapse.closest( '.navbar-wrapper' ) : [];
            },
            dropdownPlaceEventListener : function() {
                  var self    = this,
                      _events = 'tc-resize sn-open sn-close tc-sticky-enabled tc-place-dropdowns partialRefresh.angi';
                  angiapp.$_body.on( _events, function( evt, data ) {
                        if ( 'partialRefresh' === evt.type && 'angi' === evt.namespace && data.container && data.container.hasClass( 'tc-header' )  ) {
                              self.dropdownPlaceCacheElements();
                        }
                        self.dropdownPlaceEventHandler( evt, 'resize' );
                  });
            },


            dropdownPlaceEventHandler : function( evt, evt_name ) {
              var self = this;

              switch ( evt_name ) {
                case 'resize' :
                  setTimeout( function(){
                    self._place_dropdowns();
                  }, 250);
                break;
              }
            },


            _place_dropdowns : function () {
              var _dd = this._get_dd_to_move();
              if ( ! _dd.length )
                return;

              this._staging();
              this._move_dropdown( _dd );
              this._write_dyn_style();
              this._unstaging();
            },
            _has_dd_to_move : function() {
              if ( this.$_nav_collapse.length < 1 )
                return false;
              if ( this.$_nav.length && this.$_nav.find( this._dd_first_selector ) < 1 )
                return false;

              return true;
            },
            _get_dd_to_move : function() {
              if ( 'absolute' == this.$_nav_collapse.css('position') )
                return {};
              if ( ! this.$_nav.is(':visible') )
                return {};
              return this.$_nav.find( this._dd_first_selector );
            },
            _staging : function() {
              this._window_width = angiapp.$_window.width();
              if ( this.$_navbar_wrapper.hasClass('tc-submenu-fade') )
                this.$_navbar_wrapper.removeClass('tc-submenu-fade').addClass('tc-submenu-fade-susp');
              var _max_width            = this._window_width - 40,
                  _dyn_style_css_prefix = '.tc-header .nav-collapse .dropdown-menu';
              this._dyn_style  = _dyn_style_css_prefix + ' {max-width: ' + _max_width + 'px;}';
              this._dyn_style += _dyn_style_css_prefix + ' > li > a { word-wrap: break-word; white-space: pre; }';
              this._write_dyn_style();
            },
            _unstaging : function() {
              if ( this.$_navbar_wrapper.hasClass('tc-submenu-fade-susp') )
                this.$_navbar_wrapper.removeClass('tc-submenu-fade-susp').addClass('tc-submenu-fade');
            },
            _write_dyn_style : function() {
              var $_dyn_style_el = this.$_head.find('#' + this._dyn_style_id);
              if ( $_dyn_style_el.length > 0 )
                $_dyn_style_el.remove();
              if ( this._dyn_style )
                $("<style type='text/css' id='" + this._dyn_style_id +"'>" + this._dyn_style + "</style>")
                  .appendTo( this.$_head );
            },
            _move_dropdown : function( $dropdown_menu ) {
              if ( $dropdown_menu && $dropdown_menu.length ) {
                if ( $dropdown_menu.length > 1 ) {
                  var self = this;
                  $.each( $dropdown_menu, function(){
                    self._move_dropdown( $(this) );
                  });
                  return;
                }//end array of dropdown case
              }else //no dropdown
                return;
              var _is_dropdown_visible = $dropdown_menu.is(':visible');
              if ( ! _is_dropdown_visible )
                $dropdown_menu.css('display', 'block').css('visibility', 'hidden');
              this._set_dropdown_offset( $dropdown_menu, '' );
              var _overflow     = this._get_dropdown_overflow( $dropdown_menu );

              if ( _overflow )
                this._set_dropdown_offset( $dropdown_menu, _overflow );
              var $_children_dropdowns = $dropdown_menu.children('li.dropdown-submenu');
                if ( $_children_dropdowns.length )
                  this._move_dropdown( $_children_dropdowns.children('ul.dropdown-menu') );
              if ( ! _is_dropdown_visible )
                $dropdown_menu.css('display', '').css('visibility', '');
            },
            _set_dropdown_offset : function( $dropdown_menu, _dropdown_overflow ) {
              var _offset = '';

              if ( _dropdown_overflow ) {
                var $_parent_dropdown  = $dropdown_menu.parent('.menu-item-has-children'),
                    _is_dropdown_submenu = $_parent_dropdown.hasClass('dropdown-submenu');
                if ( _is_dropdown_submenu ) {
                  _offset = parseFloat( $dropdown_menu.css( this._prop ) ) - _dropdown_overflow - 5;
                  if ( $_parent_dropdown.next('.menu-item').length ) {
                    var _submenu_overflows_parent = this._get_element_overflow( $dropdown_menu, _offset, $_parent_dropdown );
                    if ( _offset < 30  || _submenu_overflows_parent < 30 )
                      _offset = _offset - _submenu_overflows_parent - 30;
                  }
                } else {
                  _offset = -20 - _dropdown_overflow; //add some space (20px) on the right(rtl-> left)
                  var _menu_id = $_parent_dropdown.attr('class').match(/(menu|page)-item-\d+/);
                  _menu_id = _menu_id ? _menu_id[0] : null;
                  if ( _menu_id )
                    this._set_dropdown_arrow_style( _menu_id, _offset );
                }
              }
              $dropdown_menu.css( this._prop, _offset );
            },
            _get_dropdown_overflow : function ( $dropdown_menu ) {
              var overflow = null,
                  _t_overflow;
               if ( 'left' == this._prop ) {
                 _t_overflow = this._get_element_overflow( $dropdown_menu, $dropdown_menu.offset().left, {}, this._window_width );
                overflow = _t_overflow > -5 ? _t_overflow : overflow ;
              }else { // rtl
                _t_overflow = $dropdown_menu.offset().left;
                overflow  = _t_overflow < 5 ? -1 * _t_overflow : overflow;
              }
                return overflow;
            },
            _get_element_overflow : function ( $_el, _offset, $_parent, _parent_width ) {
              _parent_width = $_parent.length ? $_parent.width() : _parent_width;
              return $_el.width() + _offset - _parent_width;
            },
            _set_dropdown_arrow_style : function( _menu_id, _offset ) {
              var _arrow_before_offset    = +9 - _offset,
                  _arrow_after_offset     = _arrow_before_offset + 1,
                  _arrow_css_rule_prefix  = '.tc-header .navbar .nav > .' + _menu_id + ' > .dropdown-menu',

                 _arrow_before_css_rule  = _arrow_css_rule_prefix + ":before { " + this._prop + ": " + _arrow_before_offset + "px;}",
                 _arrow_after_css_rule   = _arrow_css_rule_prefix + ":after { " + this._prop + ": " + _arrow_after_offset + "px;}";

              this._dyn_style += "\n" + _arrow_before_css_rule + "\n" + _arrow_after_css_rule;
            }
      };//_methods{}

      angiapp.methods.Dropdowns = {};
      $.extend( angiapp.methods.Dropdowns , _methods );

})(jQuery, angiapp);var angiapp = angiapp || {};

( function ( angiapp, $, _ ) {
      $.extend( angiapp, angiapp.Events );
      angiapp.Root           = angiapp.Class.extend( {
            initialize : function( options ) {
                  $.extend( this, options || {} );
                  this.isReady = $.Deferred();
            },
            ready : function() {
                  var self = this;
                  if ( self.dom_ready && _.isArray( self.dom_ready ) ) {
                        angiapp.status = angiapp.status || [];
                        _.each( self.dom_ready , function( _m_ ) {
                              if ( ! _.isFunction( _m_ ) && ! _.isFunction( self[_m_]) ) {
                                    angiapp.status.push( 'Method ' + _m_ + ' was not found and could not be fired on DOM ready.');
                                    return;
                              }
                              try { ( _.isFunction( _m_ ) ? _m_ : self[_m_] ).call( self ); } catch( er ){
                                    angiapp.status.push( [ 'NOK', self.id + '::' + _m_, _.isString( er ) ? angiapp._truncate( er ) : er ].join( ' => ') );
                                    return;
                              }
                        });
                  }
                  this.isReady.resolve();
            }
      });

      angiapp.Base           = angiapp.Root.extend( angiapp.methods.Base );
      angiapp.ready          = $.Deferred();
      angiapp.bind( 'angiapp-ready', function() {
            angiapp.ready.resolve();
      });
      var _instantianteAndFireOnDomReady = function( newMap, previousMap, isInitial ) {
            if ( ! _.isObject( newMap ) )
              return;
            _.each( newMap, function( params, name ) {
                  if ( angiapp[ name ] || ! _.isObject( params ) )
                    return;

                  params = _.extend(
                        {
                              ctor : {},//should extend angiapp.Base with custom methods
                              ready : [],//a list of method to execute on dom ready,
                              options : {}//can be used to pass a set of initial params to set to the constructors
                        },
                        params
                  );
                  var ctorOptions = _.extend(
                      {
                          id : name,
                          dom_ready : params.ready || []
                      },
                      params.options
                  );

                  try { angiapp[ name ] = new params.ctor( ctorOptions ); }
                  catch( er ) {
                        angiapp.errorLog( 'Error when loading ' + name + ' | ' + er );
                  }
            });
            $(function () {
                  _.each( newMap, function( params, name ) {
                        if ( angiapp[ name ] && angiapp[ name ].isReady && 'resolved' == angiapp[ name ].isReady.state() )
                          return;
                        if ( _.isObject( angiapp[ name ] ) && _.isFunction( angiapp[ name ].ready ) ) {
                              angiapp[ name ].ready();
                        }
                  });
                  angiapp.status = angiapp.status || 'OK';
                  if ( _.isArray( angiapp.status ) ) {
                        _.each( angiapp.status, function( error ) {
                              angiapp.errorLog( error );
                        });
                  }
                  angiapp.trigger( isInitial ? 'angiapp-ready' : 'angiapp-updated' );
            });
      };//_instantianteAndFireOnDomReady()
      angiapp.appMap = new angiapp.Value( {} );
      angiapp.appMap.bind( _instantianteAndFireOnDomReady );//<=THE MAP IS LISTENED TO HERE
      angiapp.customMap = new angiapp.Value( {} );
      angiapp.customMap.bind( _instantianteAndFireOnDomReady );//<=THE CUSTOM MAP IS LISTENED TO HERE


})( angiapp, jQuery, _ );/****************************************************************
* FORMER HARD CODED SCRIPTS MADE ENQUEUABLE WITH LOCALIZED PARAMS
*****************************************************************/
(function($, angiapp, _ ) {
    angiapp.ready.then( function() {
          var pluginCompatParams = ( angiapp.localized && angiapp.localized.pluginCompats ) ? angiapp.localized.pluginCompats : {},
              frontHelpNoticeParams = ( angiapp.localized && angiapp.localized.frontHelpNoticeParams ) ? angiapp.localized.frontHelpNoticeParams : {};
          $( function( $ ) {
                if ( angiapp.localized.isParallaxOn ) {
                      $( '.angi-parallax-slider' ).angiParallax( { parallaxRatio : angiapp.localized.parallaxRatio || 0.55 } );
                }
          });
          if ( pluginCompatParams.optimizepress_compat && pluginCompatParams.optimizepress_compat.remove_fancybox_loading ) {
                  var opjq = opjq || 'undefined';
                  if ( ! _.isUndefined( opjq ) ) {
                      opjq(document).ready( function() {
                          opjq('#fancybox-loading').remove();
                      } );
                }
          }
    });
})(jQuery, angiapp, _ );var angiapp = angiapp || {};
( function ( angiapp ) {
      angiapp.localized = TCParams || {};
      var appMap = {
                base : {
                      ctor : angiapp.Base,
                      ready : [
                            'cacheProp',
                            'emitCustomEvents'
                      ]
                },
                browserDetect : {
                      ctor : angiapp.Base.extend( angiapp.methods.BrowserDetect ),
                      ready : [ 'addBrowserClassToBody' ]
                },
                jqPlugins : {
                      ctor : angiapp.Base.extend( angiapp.methods.JQPlugins ),
                      ready : [
                            'centerImagesWithDelay',
                            'centerInfinity',
                            'imgSmartLoad',
                            'dropCaps',
                            'extLinks',
                            'fancyBox',
                            'parallax'
                      ]
                },
                slider : {
                      ctor : angiapp.Base.extend( angiapp.methods.Slider ),
                      ready : [
                            'initOnDomReady',
                            'fireSliders',
                            'parallaxSliders',
                            'manageHoverClass',
                            'centerSliderArrows',
                            'addSwipeSupport',
                            'sliderTriggerSimpleLoad'
                      ]
                },
                dropdowns : {
                      ctor : angiapp.Base.extend( angiapp.methods.Dropdowns ),
                      ready : [ 'fireDropDown' ]
                },

                userXP : {
                      ctor : angiapp.Base.extend( angiapp.methods.UserXP ),
                      ready : [
                            'initOnDomReady',
                            'eventListener',
                            'outline',
                            'smoothScroll',
                            'anchorSmoothScroll',
                            'backToTop',
                            'widgetsHoverActions',
                            'attachmentsFadeEffect',
                            'clickableCommentButton',
                            'dynSidebarReorder',
                            'dropdownMenuEventsHandler',
                            'menuButtonHover',
                            'secondMenuRespActions',

                            'mayBePrintFrontNote'
                      ]
                },
                stickyHeader : {
                      ctor : angiapp.Base.extend( angiapp.methods.StickyHeader ),
                      ready : [
                            'initOnDomReady',
                      ]
                },
                stickyFooter : {
                      ctor : angiapp.Base.extend( angiapp.methods.StickyFooter ),
                      ready : [
                            'initOnDomReady',
                            'stickyFooterEventListener'
                      ]
                },
                sideNav : {
                      ctor : angiapp.Base.extend( angiapp.methods.SideNav ),
                      ready : [
                            'initOnDomReady'
                      ]
                }
      };//map
      angiapp.appMap( appMap , true );//true for isInitial map

})( angiapp );