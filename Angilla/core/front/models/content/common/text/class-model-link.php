<?php
class ANGI_link_model_class extends ANGI_Model {

      private static $meta_key      = 'angi_link_meta';

      private static $meta_fields   = array(
                                          'title'       => 'link_title',
                                          'url'         => 'link_url'
                                    );

      protected $content;
      protected $link_item;


      public $defaults              = array(
                                          'content'         => null,
                                          'link_item'       => '',

                                          'post_id'         => null,

                                          'visibility'      => true,
                                    );






      /* Public api */


      public function angi_fn_get_link_url() {

            return array_key_exists( 'link_url', $this->link_item ) ? esc_url( $this->link_item[ 'link_url' ] ) : false;

      }




      public function angi_fn_get_link_title() {

            return array_key_exists( 'link_title', $this->link_item ) ? esc_html( $this->link_item[ 'link_title' ] ) : false;

      }




      public function angi_fn_setup( $args = array() ) {

            $defaults = array (

                  'post_id'         => null,

            );

            $args = wp_parse_args( $args, $defaults );

            $args[ 'post_id' ]     = $args[ 'post_id' ] ? $args[ 'post_id' ] : get_the_ID();

            /* This will update the model object properties, merging the $model -> defaults too */
            $this -> angi_fn_update( $args );

            /* Set the media property */
            $this -> angi_fn__set_raw_content();

            /* Toggle visibility */
            $this -> angi_fn_set_property( 'visibility',  (bool) $this->angi_fn_get_raw_content() );

      }




      public function angi_fn_get_raw_content() {

            return $this->content;

      }




      /*
      * Fired just before the view is rendered
      * @hook: pre_rendering_view_{$this -> id}, 9999
      */
      /*
      * Each time this model view is rendered setup the current quote
      */
      protected function angi_fn_setup_late_properties() {

            if ( is_null( $this->content ) ) {
                  $this -> angi_fn_setup( array(
                        'post_id'         => $this->post_id
                  ) );
            }


            $this->angi_fn__setup_the_link_item();

      }



      protected function angi_fn__set_raw_content() {

            $this -> angi_fn_set_property( 'content', $this->angi_fn__get_post_meta() );

      }



      protected function angi_fn__setup_the_link_item() {

            $this->angi_fn_set_property( 'link_item', $this->angi_fn__get_the_quote() );
      }




      protected function angi_fn__get_the_quote() {

            $content      = $this->content;


            if ( empty( $content ) )
                  return array();


            $_url        = $this->angi_fn__get_link_url();

            $_title      = $this->angi_fn__get_link_title();


            return array(
                  'link_url'       => $_url,
                  'link_title'     => $_title,
            );

      }




      protected function angi_fn__get_link_url() {

            $_content         = $this->content;

            if ( ! isset( $_content[ 'url' ] ) )
                  return false;


            return $_content[ 'url' ];

      }




      protected function angi_fn__get_link_title() {

            $_content         = $this->content;

            if ( ! isset( $_content[ 'title' ] ) )
                  return angi_fn__get_link_url();

            return $_content[ 'title' ];

      }




      protected function angi_fn__get_post_meta() {

            $post_id  = $this->post_id ? $this->post_id : get_the_ID();
            $meta     = get_post_meta( $post_id, self::$meta_key, true );

            return $this -> angi_fn__validate_media_from_meta( $meta );

      }




      protected function angi_fn__validate_media_from_meta( $meta ) {


            if ( ! ( is_array( $meta ) && array_key_exists( self::$meta_fields[ 'url' ], $meta ) && !empty( $meta[ self::$meta_fields[ 'url' ] ] ) ) )
                  return false;

            $content = array();

            //build content array
            foreach ( self::$meta_fields as $key => $meta_field ) {
                  if ( array_key_exists( $meta_field, $meta ) && !empty( $meta[ $meta_field ] ) ) {
                        $content[ $key ] = $meta[ $meta_field ];
                  }
            }

            return empty( $content ) ? false : $content;
      }

}