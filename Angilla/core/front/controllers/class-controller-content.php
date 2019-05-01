<?php
if ( ! class_exists( 'ANGI_controller_content' ) ) :
  class ANGI_controller_content extends ANGI_controllers {
    static $instance;

    function __construct( $_args = array()) {
      self::$instance =& $this;
    }

    function angi_fn_display_view_right_sidebar() {
      return $this -> angi_fn_display_view_sidebar( 'right' );
    }

    function angi_fn_display_view_left_sidebar() {
      return $this -> angi_fn_display_view_sidebar( 'left' );
    }

    private function angi_fn_display_view_sidebar( $position ) {
      if ( angi_fn_is_home_empty() )
        return false;

      static $sidebar_map = array(
        //id => allowed layout (- b both )
        'right'  => 'r',
        'left'   => 'l'
      );

      $screen_layout        = angi_fn_get_layout( angi_fn_get_id() , 'sidebar'  );
      if ( ! in_array( $screen_layout, array( $sidebar_map[$position], 'b' ) ) )
        return false;
      return true;
    }

    function angi_fn_display_view_regular_page_heading() {
      $page_heading = apply_filters( 'angi_display_page_heading', $this -> angi_fn_display_view_page() && ! is_front_page() );
      return apply_filters( 'regular_heading',  $page_heading );
    }

    function angi_fn_display_view_regular_post_heading() {
      $post_heading = apply_filters( 'angi_display_post_heading', $this -> angi_fn_display_view_post() );
      return apply_filters( 'regular_heading',  $post_heading );
    }

    function angi_fn_display_view_regular_attachment_image_heading() {
      $attachment_image_heading = apply_filters( 'angi_display_attachment_image_heading', $this -> angi_fn_display_view_attachment_image() );
      return apply_filters( 'regular_heading',  $attachment_image_heading );
    }


    function angi_fn_display_view_singular_headings() {
      return $this -> angi_fn_display_view_post() || $this -> angi_fn_display_view_attachment() || ( $this -> angi_fn_display_view_page() && ! is_front_page() );
    }



    function angi_fn_display_view_archive_heading() {
      return angi_fn_is_list_of_posts() && ! is_search();
    }

    function angi_fn_display_view_search_heading() {
      return is_search();
    }

    function angi_fn_display_view_post_heading() {
      return is_single();
    }


    function angi_fn_display_view_post_list() {
      return apply_filters( 'angi_display_view_post_list', angi_fn_is_list_of_posts() && 'alternate' == esc_attr( angi_fn_opt( 'tc_post_list_grid') ) );
    }

    function angi_fn_display_view_post_list_masonry() {
      return apply_filters( 'angi_display_view_post_list_masonry', angi_fn_is_list_of_posts() && 'masonry' == esc_attr( angi_fn_opt( 'tc_post_list_grid') ) );
    }

    function angi_fn_display_view_post_list_plain() {
      return apply_filters( 'angi_display_view_post_list_plain', angi_fn_is_list_of_posts() && 'plain' == esc_attr( angi_fn_opt( 'tc_post_list_grid') ) );
    }

    function angi_fn_display_view_post_list_plain_excerpt() {
      return apply_filters( 'angi_display_view_post_list_plain', angi_fn_is_list_of_posts() && 'plain_excerpt' == esc_attr( angi_fn_opt( 'tc_post_list_grid') ) );
    }


    function angi_fn_display_view_posts_list_title() {
      return $this -> angi_fn_display_view_posts_list_headings() && ! is_search();
    }

    function angi_fn_display_view_posts_list_search_title() {
      return $this -> angi_fn_display_view_posts_list_headings() && is_search();
    }

    function angi_fn_display_view_posts_list_description() {
      return ! is_author() && ! is_search();
    }

    function angi_fn_display_view_author_description() {
      return apply_filters ( 'angi_show_author_meta', get_the_author_meta('description') );
    }

    function angi_fn_display_view_page() {
      return apply_filters( 'angi_show_single_page_content', angi_fn_is_single_page() );
    }

    function angi_fn_display_view_post() {
      //check conditional tags : we want to show single post or single custom post types
      return apply_filters( 'angi_show_single_post_content', angi_fn_is_single_post() );
    }



    function angi_fn_display_view_single_author_info() {
      if ( ! apply_filters( 'angi_show_author_metas_in_post', esc_attr( angi_fn_opt( 'tc_show_author_info' ) ) ) )
        return;

      if ( !$this -> angi_fn_display_view_post() )
        return;

      $author_id = false;

      if ( ! in_the_loop() ) {
        global $post;
        $author_id = $post->post_author;
      }

      $authors_id      = apply_filters( 'tc_post_author_id', array( $author_id ) );
      $authors_id      = is_array( $authors_id ) ? $authors_id : array( $author_id );
      //author candidates must have a bio to be displayed
      $authors_id      = array_filter( $authors_id, 'angi_fn_get_author_meta_description_by_id' );

      if ( empty( $authors_id ) )
        return false;

      return true;
    }



    function angi_fn_display_view_attachment_image() {
      return apply_filters( 'angi_show_attachment_content', angi_fn_is_single_attachment_image() );
    }


    function angi_fn_display_view_singular_article() {
      return $this -> angi_fn_display_view_post() || $this -> angi_fn_display_view_page() || $this -> angi_fn_display_view_attachment_image() ;
    }

    function angi_fn_display_view_post_list_title() {
      return apply_filters('angi_display_angilla_headings', $this -> angi_fn_display_view_posts_list_headings() || is_front_page() );
    }

    function angi_fn_display_view_singular_title() {
      $display_singular_title = apply_filters( 'angi_display_singular_title', is_singular() && ! ( is_front_page() && 'page' == get_option( 'show_on_front' ) ));
      return apply_filters('angi_display_angilla_headings', $display_singular_title )  && ! is_feed();
    }


    function angi_fn_display_view_post_metas() {

      //As of 17/07/2017 post metas customizer control transport is 'refresh'
      //post metas are always insantiated in customizing context
      //if ( angi_fn_is_customizing() )
      //  $post_metas = true;

      //elseif ( 0 == esc_attr( angi_fn_opt( 'tc_show_post_metas' ) ) )
      if ( 0 == esc_attr( angi_fn_opt( 'tc_show_post_metas' ) ) )
        $post_metas = false;


      elseif ( is_singular() && ! is_page() && ! angi_fn_is_real_home() )
        $post_metas = ( 0 != esc_attr( angi_fn_opt( 'tc_show_post_metas_single_post' ) ) );

      elseif ( ! is_singular() && ! angi_fn_is_real_home() && ! is_page() )
        $post_metas = ( 0 != esc_attr( angi_fn_opt( 'tc_show_post_metas_post_lists' ) ) );

      elseif ( angi_fn_is_real_home() )
        $post_metas = ( 0 != esc_attr( angi_fn_opt( 'tc_show_post_metas_home' ) ) );
      else
        $post_metas = false;

      return apply_filters( 'angi_show_post_metas', $post_metas );
    }


    //when to display attachment post metas?
    //a) in single attachment page
    //b) eventually, in the search list when attachments are allowed
    function angi_fn_display_view_post_metas_attachment() {
      return is_attachment() ||
        ( is_search() && apply_filters( 'angi_include_attachments_in_search_results' , false ) );
    }





    function angi_fn_display_view_posts_navigation() {
      global $wp_query;

      $bool  = $wp_query -> post_count > 0;
      $bool  = is_singular() ? $bool && ! is_attachment() : $bool;

      if ( ! $bool )
        return false;

      //always print post navigation html in the angilla preview - the visibility will be handled in the model/template
      /*if ( angi_fn_is_customizing() )
        return true;
      */

      if ( ! $this->angi_fn_is_posts_navigation_enabled() )
        return false;

      $_context = $this -> angi_fn_get_post_navigation_context();

      return $this -> angi_fn_is_posts_navigation_context_enabled( $_context );
    }



    function angi_fn_display_view_404() {
      return is_404();
    }

    function angi_fn_display_view_no_results() {
      return angi_fn_is_no_results();
    }

    function angi_fn_display_view_headings() {
      return true;
    }

    function angi_fn_display_view_comments() {
      return $this -> angi_fn_are_comments_enabled();
    }

    function angi_fn_display_view_comment_list() {
      return apply_filters( 'angi_display_comment_list', (bool) esc_attr( angi_fn_opt( 'tc_show_comment_list' ) ) && $this -> angi_fn_are_comments_enabled() );
    }


    function angi_fn_display_view_lefts_social_block() {
      return angi_fn_has_social_links() && angi_fn_opt( 'tc_social_in_left-sidebar' );
    }

    function angi_fn_display_view_rights_social_block() {
      return angi_fn_has_social_links() && angi_fn_opt( 'tc_social_in_right-sidebar' );


    }
   /******************************
    VARIOUS HELPERS
    *******************************/


    /**
    * 1) if the page / post is password protected OR if is_home OR ! is_singular() => false
    * 2) if comment_status == 'closed' => false
    * 3) if user defined comment option in customizer == false => false
    *
    * By default, comments are globally disabled in pages and enabled in posts
    *
    * @return  boolean
    *
    * @package Angilla
    */
    public function angi_fn_are_comments_enabled() {

      global $post;
      // 1) By default not displayed on home, for protected posts, and if no comments for page option is checked
      if ( isset( $post ) ) {
        $_bool = post_password_required() ? false : true;

        $_bool = ! in_the_loop() ? $_bool && ! angi_fn_is_real_home() && is_singular() : $_bool;

        //2) if user has enabled comment for this specific post / page => true
        $_bool = ( 'closed' != $post -> comment_status ) ? true : $_bool;

        //3) check global user options for pages and posts
        if ( 'page' == get_post_type() ) {
          $_bool = 1 == esc_attr( angi_fn_opt( 'tc_page_comments' )) && $_bool;
        } else {
          $_bool = 1 == esc_attr( angi_fn_opt( 'tc_post_comments' )) && $_bool;
        }
      } else {
        $_bool = false;
      }

      return apply_filters( 'angi_are_comments_enabled', $_bool );
    }


    /**
    *
    * @return string or bool
    *
    */
    function angi_fn_get_post_navigation_context(){
      if ( is_page() )
        return 'page';
      if ( is_single() && ! is_attachment() )
        return 'single'; // exclude attachments
      if ( is_home() && 'posts' == get_option('show_on_front') )
        return 'home';
      if ( !is_404() && ! angi_fn_is_home_empty() )
        return 'archive';
      return false;
    }

    /*
    * @param (string or bool) the context
    * @return bool
    *
    */
    function angi_fn_is_posts_navigation_context_enabled( $_context ) {
      return $_context && 1 == esc_attr( angi_fn_opt( "tc_show_post_navigation_{$_context}" ) );
    }

    /*
    * @return bool
    */
    function angi_fn_is_posts_navigation_enabled(){
      return apply_filters( 'angi_show_post_navigation', 1 == esc_attr( angi_fn_opt( 'tc_show_post_navigation' ) ) );
    }

  }//end of class
endif;
