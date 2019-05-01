<?php

/*

** Funding principle: **
We want to have a modular theme which allows us to print a template injected with a certain data model.


*** Models ***

Models are the core of the whole framewoks. They assolve different purposes as:
- setup the data needed to feed the template
- act as controllers (in an MVC pattern), as they're responsible of the View instantiation, whose purpose is mostly to just push the model's in the stack
of the current models (to make it accessible through the angi_fn_(get|echo) apis ) and render the model's template (a file, or a pure html code),

Model's nature is flexible enough to allow us to treat it as:
a) a singleton
b) a single instance

a) Singletons
We generally use the singleton's approach for those models which are used pagewide, which do not have to necessarily retain data
(e.g. post metas) or for performance reasons, like items (articles) and their components (image, text, post formats custom post metas...),

b) Single instance
This approach is used mostly for the grids. Each grid has its own life and defines its own 'environment' at instantiation time (constructor).

Nothing prevents the developer to use a model in both ways, so its up to the developer to prepare the stage before using them.

Due to this dual nature of the models, and to the fact that a template can be feed with a generic data model (see Templates section below), we need a way to setup the models
with a set of params both at instantiation time (single instance) and at rendering time (singletons, generally in a loop),
This purpose is accomplished by passing an array of arguments to the model with which we want to feed a template.

This array of args can be ( by default is ) merged to the model's $defaults array or properties, when updating a model.
This approach allows us, to reset to $defaults a singleton model just before its template's rendering (see Angi_Model:angi_fn_update() used in angi_fn_render_template).

Single instance kind of models can override a base class method to define a preset of properties that will be merged to the passed arguments at
instantiation phase. This "preset" is not persistent.
This approach is typically used for the grids.
Each grid defines a preset generally based on the user's options (display the thumbnail, center the images, excerpt length...).
This represents just the initial state of the grid.

By the nature of WordPress most of the actual data models properties need to be setup just before the rendering (e.g in the loop).
For this purpose each model can override a base method called angi_fn_setup_late_properties (action callback fired by the model's View just before rendering).


* Registration *
Each model needs to be registered in the collection of models, each model then can be re-used (see singletons above) by referring to its model id.
When rendering a template on the fly, if its model is not registered yet, a registration on the fly occurs.

Technically we should not need to register a model before its template rendering request, but in most of the cases we have to:
a) when a model has to enqueue assets or append specific css rules to the inline style (angi_user_options_style)
b) when a model X needs to know something about the model Y before both of them are actually rendered.
(e.g. when the alternate grid item needs to know whether or not the current post has a media to show, in order to set the text and media columns)
c) when a model X wants to alter some properties of Y before Y and X rendering.

Model's can be pre-registered (meaning registered before wp action hook) e.g. when they have to alter the main query (pre_get_posts).
We actually never do this atm.

The model's registration (as well as its view instantiation and its template rendering) can be subordered to various checks
e.g. on its integrity (is the model well formed?) and to what we call the model's controller, mostly consisting in a callback
which checks whether or not we are in the right context (post lists, singular, search ...) and/or user options allow it
(e.g. post metas/breadcrumbs/slider... visibility)



*** Templates ***

Templates mostly consist of html, and minimal php code to retrieve data from their own model.
They do not necessarily need to be a file, but in most of the cases they are, as we can instruct a model
to render pure html code (model's property $html )

A template can be:
a) rendered on fly ( core/functions-ccat.php angi_fn_render_template(...) ) directly from another template
b) rendered at a specific hook and priority

A template can access models data through two functions
1) angi_fn_get_property( property, model_id(optional, default is the current model), args(optional) ) - gets a model's property
2) angi_fn_echo( property, model_id(optional, default is the current model), args(optional) ) - echoes a model's property

Each model object can define the way a property is retrieved by defining a method with the following signature
angi_fn_get_{$property}( $args )
This is particularly useful for those properties which need to be computed on the fly (e.g. in a loop)

Even if we render model's templates, templates do not necessarily need a specific pre-associated model class. In case there's no corrispondent model class for a template,
the base Angi_Model object will be registered and instantiated, assigning the model's property "template" as the relative
file path of the template we're about to render.



*** Loading a page ***
In core/init.php at wp action hook we register the main set of models that we'll render afterwards from our main templates (templates/index.php|header.php etc. etc.)
This set of models consists of:
1) the header
2) the content (contains the logic to retrieve which model/template needs to be passed to the loop)
3) the footer

Each model then can register, at instantiation time, a set of models
For instance, in the content model we register the grid wrapper or the slider's model as they need to act on the user options style.

See templates/index.php for rendering flow.

*/
/**
* Fires the theme : constants definition, core classes loading
*
*/
if ( ! class_exists( 'ANGI___' ) ) :

  final class ANGI___ extends ANGI_BASE  {
        public $angi_core;

        public $collection;
        public $views;//object, stores the views
        public $controllers;//object, stores the controllers

        //stack
        public $current_model = array();

        private $existing_files     = array();
        private $not_existing_files = array();

        public $tc_sq_thumb_size;
        public $tc_ws_thumb_size;
        public $tc_ws_small_thumb_size;

        public $tc_slider_small_size;

        public $slider_resp_shrink_ratio;

        private $_hide_update_notification_for_versions;



        function __construct( $_args = array()) {
            //allow modern_style templates
            add_filter( 'angi_ms'             , '__return_true' );
            //define a constant we can use everywhere
            //that will tell us we're in the new Angilla:
            //Will be highly used during the transion between the two styles
            if( ! defined( 'ANGI_IS_MODERN_STYLE' ) ) define( 'ANGI_IS_MODERN_STYLE' , true );


            //call ANGI_BASE constructor
            parent::__construct( $_args );

            //this action callback is the one responsible to load new angi main templates
            //Those templates have no models there are invoked from the WP templates with this kind of syntax : do_action( 'angi_ms_tmpl', 'header' );
            add_action( 'angi_ms_tmpl' , array( $this , 'angi_fn_load_modern_template_with_no_model' ), 10 , 1 );

            //filters to 'the_content', 'wp_title' => in utils
            add_action( 'wp_head' , 'angi_fn_wp_filters' );

            add_action( 'angi_dev_notice', array( $this, 'angi_fn_print_r') );

            //Slider responsive ratio
            $this -> slider_resp_shrink_ratio = apply_filters( 'angi_slider_resp_shrink_ratios',
              array( '1199' => 0.77 , '991' => 0.618, '767' => 0.5, '575' => 0.38, '320' => 0.28 )
            );


            //Default images sizes
            //Thumbs definition
            $this -> tc_sq_thumb_size            = array( 'width' => 510  , 'height' => 510, 'crop' => true ); //size name : tc-sq-thumb

            //The actual bootstrap4 container width is 1110, while it was 1170 in bootstrap2
            $this -> tc_ws_thumb_size            = array( 'width' => 1110 , 'height' => 624, 'crop' => true ); //size name : tc-ws-thumb, replaces also tc_grid_full_size for modern style

            //Small thumbs : (set at a viewport of 575px : upper limit for extra small devices )
            $this -> tc_ws_small_thumb_size      = array( 'width' => 528  , 'height' => 297, 'crop' => true ); //size name : tc-ws-small-thumb, used by wp as responsive image of tc-ws-thumb

            //Slider's small thumb

            //The actual bootstrap4 container width is 1110, while it was 1170 in bootstrap2
            //to keep the same aspect ratio of the slider boxed thumb we set the width to 517px
            //and the height as as 517 * 500 (default height) / 1110 (default width) = (around) 422
            //NOTE: we'll use the same small size both for the full and the boxed slider layout
            $this -> tc_slider_small_size        = array( 'width' => 517  , 'height' => 235, 'crop' => true ); //size name : tc-slider-small

            add_action( 'angi_after_load', array( $this, 'angi_maybe_prevdem') );

            // control the display of the update notification for a list of versions
            // typically useful when several versions are released in a short time interval
            // to avoid hammering the wp admin dashboard with a new admin notice each time
            // Angilla #
            /*
            $this -> _hide_update_notification_for_versions = array('2.1.28');
            if( ! defined( 'DISPLAY_UPDATE_NOTIFICATION' ) ) {
                define( 'DISPLAY_UPDATE_NOTIFICATION' , ! in_array( CUSTOMIZR_VER, $this -> _hide_update_notification_for_versions ) );
            }
            */

        }


        //fired at the bottom of core/init.php
        public static function angi_fn_instance() {
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof ANGI___ ) ) {
              self::$instance = new ANGI___();

              self::$instance -> angi_fn_setup_loading();

              //fire an action hook before loading the theme class groups
              do_action( 'angi_before_load' );

              self::$instance -> angi_fn_load();

              //fire an action hook after loading the theme class groups
              do_action( 'angi_after_load' );

              //FMK
              self::$instance -> collection = new ANGI_Collection();
              self::$instance -> controllers = new ANGI_Controllers();

              //register the model's map in front
              if ( ! is_admin() )
                add_action('wp'         , array(self::$instance, 'angi_fn_register_model_map') );
            }
            return self::$instance;
        }





        /**
        * The purpose of this callback is to load the modern style main templates located at the root of /templates
        * No model is associated with template called by this code.
        * hook : angi_modern_style_tmpl
        * @return  void
        */
        public function angi_fn_load_modern_template_with_no_model( $template = null ) {
            $template = $template ? $template : 'index';
            $this -> angi_fn_require_once( ANGI_MAIN_TEMPLATES_PATH . $template . '-no-model.php' );
        }




        private function angi_fn_setup_loading() {
            //this is the structure of the Angilla code : groups => ('path' , 'class_suffix')
            $this -> angi_core = apply_filters( 'angi_core',
                array(
                    'fire'      =>   array(
                        array('core'       , 'resources_styles'),
                        array('core'       , 'resources_fonts'),
                        array('core'       , 'resources_scripts'),
                        array('core'       , 'widgets'),//widget factory
                        array('core'       , 'placeholders'),//front end placeholders ajax actions for widgets, menus.... Must be fired if is_admin === true to allow ajax actions.
                        array('core/back'  , 'admin_init'),//loads admin style and javascript ressources. Handles various pure admin actions (no customizer actions)
                        array('core/back'  , 'admin_page')//creates the welcome/help panel including changelog and system config
                    ),
                    'admin'     => array(
                        array('core/back' , 'customize'),//loads customizer actions and resources
                        array('core/back' , 'meta_boxes')//loads the meta boxes for pages, posts and attachment : slider and layout settings
                    ),
                    'header'    =>   array(
                        array('core/front/utils', 'nav_walker')
                    ),
                    'content'   =>   array(
                        array('core/front/utils', 'gallery')
                    ),
                    'addons'    => apply_filters( 'tc_addons_classes' , array() )
                )
            );

            //set files to load according to the context : admin / front / customize
            add_filter( 'angi_get_files_to_load' , array( $this , 'angi_fn_set_files_to_load' ) );
        }


        /**
        * Class instantiation using a singleton factory :
        * Can be called to instantiate a specific class or group of classes
        * @param  array(). Ex : array ('admin' => array( array( 'core/back' , 'meta_boxes') ) )
        * @return  instances array()
        *
        * Thanks to Ben Doherty (https://github.com/bendoh) for the great programming approach
        *
        * @since Angilla 1.0
        */
        function angi_fn_load( $_to_load = array(), $_no_filter = false ) {
            require_once( ANGI_BASE . ANGI_CORE_PATH . 'core-settings-map.php' );

            //loads utils
            require_once( ANGI_BASE . ANGI_CORE_PATH . 'functions-ccat.php' );

            do_action( 'angi_load' );

            //loads init
            $this -> angi_fn_require_once( ANGI_CORE_PATH . 'class-fire-init.php' );
            new ANGI_init();

            //Retro Compat has to be fired after class-fire-init.php, according to R. Aliberti. Well probably.
            $this -> angi_fn_require_once( ANGI_CORE_PATH  . 'class-fire-init_retro_compat.php' );

            //loads the plugin compatibility
            $this -> angi_fn_require_once( ANGI_CORE_PATH . 'class-fire-plugins_compat.php' );
            new ANGI_plugins_compat();


            //do we apply a filter ? optional boolean can force no filter
            $_to_load = $_no_filter ? $_to_load : apply_filters( 'angi_get_files_to_load' , $_to_load );
            if ( empty($_to_load) )
              return;

            foreach ( $_to_load as $group => $files ) {
                foreach ($files as $path_suffix ) {
                    $this -> angi_fn_require_once ( $path_suffix[0] . '/class-' . $group . '-' .$path_suffix[1] . '.php');
                    $classname = 'ANGI_' . $path_suffix[1];

                    if ( in_array( $classname, apply_filters( 'angi_dont_instantiate_in_init', array( 'ANGI_nav_walker') ) ) )
                      continue;
                    //instantiates
                    $instances = class_exists($classname)  ? new $classname : '';
                }
            }

            //load the new framework classes
            if ( ANGI_DEV_MODE ) {
                $this -> angi_fn_require_once( ANGI_FRAMEWORK_PATH . 'class-model.php' );
                $this -> angi_fn_require_once( ANGI_FRAMEWORK_PATH . 'class-collection.php' );
                $this -> angi_fn_require_once( ANGI_FRAMEWORK_PATH . 'class-view.php' );
                $this -> angi_fn_require_once( ANGI_FRAMEWORK_PATH . 'class-controllers.php' );
            } else {
                $this -> angi_fn_require_once( ANGI_CORE_PATH . 'fmk-ccat.php' );
            }

            //load front templates tags files
            if ( ! is_admin() )
              $this -> angi_fn_require_once( ANGI_PHP_FRONT_PATH . 'template-tags/template-tags.php' );
        }//czf_fn_load()


        //hook : wp
        function angi_fn_register_model_map( $_map = array() ) {
            $_to_register =  ( empty( $_map ) || ! is_array($_map) ) ? $this -> angi_fn_get_model_map() : $_map;

            foreach ( $_to_register as $model ) {
                ANGI() -> collection -> angi_fn_register( $model);
            }
        }


        //Returns an array of models describing the theme's views
        private function angi_fn_get_model_map() {
            return apply_filters(
                'angi_model_map',
                array(
                    /*********************************************
                    * HEADER
                    *********************************************/
                    array(
                      'model_class'    => 'header',
                      'id'             => 'header'
                    ),

                    /*********************************************
                    * CONTENT
                    *********************************************/
                    array(
                      'id'             => 'main_content',
                      'model_class'    => 'main_content',
                    ),
                    /*********************************************
                    * FOOTER
                    *********************************************/
                    array(
                      'id'           => 'footer',
                      'model_class'  => 'footer',
                    )
                )
            );
        }




        /***************************
        * HELPERS
        ****************************/
        /**
        * Check the context and return the modified array of class files to load and instantiate
        * hook : angi_fn_get_files_to_load
        * @return boolean
        *
        * @since  Angilla 3.3+
        */
        function angi_fn_set_files_to_load( $_to_load ) {
            $_to_load = empty($_to_load) ? $this -> angi_core : $_to_load;
            //Not customizing
            //1) IS NOT CUSTOMIZING : angi_fn_is_customize_left_panel() || angi_fn_is_customize_preview_frame() || angi_fn_doing_customizer_ajax()
            //---1.1) IS ADMIN
            //-------1.1.a) Doing AJAX
            //-------1.1.b) Not Doing AJAX
            //---1.2) IS NOT ADMIN
            //2) IS CUSTOMIZING
            //---2.1) IS LEFT PANEL => customizer controls
            //---2.2) IS RIGHT PANEL => preview
            if ( ! angi_fn_is_customizing() ) {
                if ( is_admin() ) {
                    //load
                    angi_fn_require_once( ANGI_CORE_PATH . 'angi-admin-ccat.php' );

                    //if doing ajax, we must not exclude the placeholders
                    //because ajax actions are fired by admin_ajax.php where is_admin===true.
                    if ( defined( 'DOING_AJAX' ) )
                      $_to_load = $this -> angi_fn_unset_core_classes( $_to_load, array( 'header' , 'content' , 'footer' ), array( 'admin|core/back|customize' ) );
                    else
                      $_to_load = $this -> angi_fn_unset_core_classes( $_to_load, array( 'header' , 'content' , 'footer' ), array( 'admin|core/back|customize', 'fire|core|placeholders' ) );
                }
                else {
                    //Skips all admin classes
                    $_to_load = $this -> angi_fn_unset_core_classes( $_to_load, array( 'admin' ), array( 'fire|core/admin|admin_init', 'fire|core/admin|admin_page') );
                }
            }
            //Customizing
            else
              {
                //load
                angi_fn_require_once( ANGI_CORE_PATH . 'angi-admin-ccat.php' );
                angi_fn_require_once( ANGI_CORE_PATH . 'angi-customize-ccat.php' );
                //new ANGI_customize();

                //left panel => skip all front end classes
                if (angi_fn_is_customize_left_panel() ) {
                  $_to_load = $this -> angi_fn_unset_core_classes(
                    $_to_load,
                    array( 'header' , 'content' , 'footer' ),
                    array( 'fire|core|resources_styles' , 'fire|core', 'fire|core|resources_scripts', 'fire|core/back|admin_page' , 'admin|core/back|meta_boxes' )
                  );
                }
                if ( angi_fn_is_customize_preview_frame() ) {
                  $_to_load = $this -> angi_fn_unset_core_classes(
                    $_to_load,
                    array(),
                    array( 'fire|core/back|admin_init', 'fire|core/back|admin_page' , 'admin|core/back|meta_boxes' )
                  );
                }
              }
            return $_to_load;
        }



        /**
        * Helper
        * Alters the original classes tree
        * @param $_groups array() list the group of classes to unset like header, content, admin
        * @param $_files array() list the single file to unset.
        * Specific syntax for single files: ex in fire|core/back|admin_page
        * => fire is the group, core/back is the path, admin_page is the file suffix.
        * => will unset core/back/class-fire-admin_page.php
        *
        * @return array() describing the files to load
        *
        * @since  Angilla 3.0.11
        */
        public function angi_fn_unset_core_classes( $_tree, $_groups = array(), $_files = array() ) {
            if ( empty($_tree) )
              return array();
            if ( ! empty($_groups) ) {
                foreach ( $_groups as $_group_to_remove ) {
                    unset($_tree[$_group_to_remove]);
                }
            }
            if ( ! empty($_files) ) {
                foreach ( $_files as $_concat ) {
                    //$_concat looks like : fire|core|resources
                    $_exploded = explode( '|', $_concat );
                    //each single file entry must be a string like 'admin|core/back|customize'
                    //=> when exploded by |, the array size must be 3 entries
                    if ( count($_exploded) < 3 )
                      continue;

                    $gname = $_exploded[0];
                    $_file_to_remove = $_exploded[2];
                    if ( ! isset($_tree[$gname] ) )
                      continue;
                    foreach ( $_tree[$gname] as $_key => $path_suffix ) {
                        if ( false !== strpos($path_suffix[1], $_file_to_remove ) )
                          unset($_tree[$gname][$_key]);
                    }//end foreach
                }//end foreach
            }//end if
            return $_tree;
        }//end of fn


        //called when requiring a file - will always give the precedence to the child-theme file if it exists
        //then to the theme root
        function angi_fn_get_theme_file_path( $path_suffix ) {
            $path_prefixes = array_unique( apply_filters( 'angi_include_paths'     , array( '' ) ) );
            $roots         = array_unique( apply_filters( 'angi_include_roots_path', array( ANGI_BASE_CHILD, ANGI_BASE ) ) );

            foreach ( $roots as $root ) {
                foreach ( $path_prefixes as $path_prefix ) {

                    $filename     = $root . $path_prefix . $path_suffix;
                    $_exists      = in_array( $filename, $this->existing_files );
                    $_exists_not  = in_array( $filename, $this->not_existing_files );

                    if ( ! $_exists_not && ( $_exists || file_exists( $filename ) ) ) {
                        //cache file existence
                        if ( ! $_exists ) {
                            $this->existing_files[] = $filename;
                        }
                        return $filename;
                    } else if ( ! $_exists_not ) {
                        //cache file not existence
                        $this->not_existing_files[] = $filename;
                    }

                }
            }

            return false;
        }



        //called when requiring a file url - will always give the precedence to the child-theme file if it exists
        //then to the theme root
        function angi_fn_get_theme_file_url( $url_suffix ) {
            $url_prefixes   = array_unique( apply_filters( 'angi_include_paths'     , array( '' ) ) );
            $roots          = array_unique( apply_filters( 'angi_include_roots_path', array( ANGI_BASE_CHILD, ANGI_BASE ) ) );
            $roots_urls     = array_unique( apply_filters( 'angi_include_roots_url' , array( ANGI_BASE_URL_CHILD, ANGI_BASE_URL ) ) );

            $combined_roots = array_combine( $roots, $roots_urls );

            foreach ( $roots as $root ) {

              foreach ( $url_prefixes as $url_prefix ) {

                $filename     = $root . $url_prefix . $url_suffix;
                $_exists      = in_array( $filename, $this->existing_files );
                $_exists_not  = in_array( $filename, $this->not_existing_files );

                if ( !$_exists_not && ( $_exists || file_exists( $filename ) ) ) {

                  //cache file existence
                  if ( !$_exists ) {
                    $this->existing_files[] = $filename;
                  }

                  return array_key_exists( $root, $combined_roots) ? $combined_roots[ $root ] . $url_prefix . $url_suffix : false;

                }
                else if ( !$_exists_not ) {
                  //cache file not existence
                  $this->not_existing_files[] = $filename;
                }

              }

            }

            return false;
        }


        //requires a file only if exists
        function angi_fn_require_once( $path_suffix ) {
            if ( false !== $filename = $this -> angi_fn_get_theme_file_path( $path_suffix ) )
              require_once( $filename );

            return (bool) $filename;
        }


        /*
        * Stores the current model in the class current_model stack
        * called by the View class before requiring the view template
        * @param $model
        */
        function angi_fn_set_current_model( $model ) {
            $this -> current_model[ $model -> id ] = &$model;
        }


        /*
        * Pops the current model from the current_model stack
        * called by the View class after the view template has been required/rendered
        */
        function angi_fn_reset_current_model() {
            array_pop( $this -> current_model );
        }


         /*
        * An handly function to get a current model property
        * @param $property (string), the property to get
        * @param $args (array) - optional, an ordered list of params to pass to the current model property getter (if defined)
        */
        function angi_fn_get_property( $property, $model_id = null, $args = array() ) {
            $current_model = false;
            if ( ! is_null( $model_id ) ) {
                if ( angi_fn_is_registered( $model_id ) ) {
                    $current_model = angi_fn_get_model_instance( $model_id );
                }
            } else {
                $current_model = end( $this -> current_model );
            }
            return is_object( $current_model ) ? $current_model -> angi_fn_get_property( $property, $args ) : false;
        }


        /*
        * An handly function to get a the full current model list of properties
        */
        function angi_fn_get_current_model() {
            $current_model = end( $this -> current_model );
            return is_object( $current_model ) ? $current_model : false;
        }


        /*
        * An handly function to print a current model property (wrapper for angi_fn_get)
        * @param $property (string), the property to get
        * @param $args (array) - optional, an ordered list of params to pass to the current model property getter (if defined)
        */
        function angi_fn_echo( $property, $model_id = null, $args = array() ) {
            $prop_value = angi_fn_get_property( $property, $model_id, $args );
            /*
            * is_array returns false if an array is empty:
            * in that case we have to transform it in false or ''
            */
            $prop_value = $prop_value && is_array( $prop_value ) ? angi_fn_stringify_array( $prop_value ) : $prop_value;
            echo empty( $prop_value ) ? '' : $prop_value;
        }


        //hook : angi_dev_notice
        function angi_fn_print_r($message) {
            if ( ! is_user_logged_in() || ! current_user_can( 'edit_theme_options' ) || is_feed() )
              return;
            ?>
              <pre><h6 style="color:red"><?php echo $message ?></h6></pre>
            <?php
        }

        //hook : 'angi_after_init'
        function angi_maybe_prevdem() {
            if ( ! angi_fn_isprevdem() )
              return;
            $this -> angi_fn_require_once( ANGI_CORE_PATH . 'class-fire-prevdem.php' );
            if (  class_exists('ANGI_prevdem') )
              new ANGI_prevdem();
        }
  }//end of class
endif;//endif;

//Fire

/**
 * @since 4.0
 * @return object ANGI Instance
 */
if ( ! function_exists( 'ANGI' ) ) {
    function ANGI() {
      return ANGI___::angi_fn_instance();
    }
}
//fire an action hook before init the theme
do_action( 'angi_before_init' );
// Fire Angilla
ANGI();
//fire an action hook after init the theme
do_action( 'angi_after_init' );