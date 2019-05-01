<?php
class ANGI_related_posts_model_class extends ANGI_model {

  /**
  * @override
  * fired before the model properties are parsed
  *
  * return model preset array()
  */
  function angi_fn_get_preset_model() {
    $_preset = array(
      'excerpt_length'        => 22,
      'media_cols'            => 'col-12 col-lg-6 angi__r-w1by1' ,
      'content_cols'          => 'col-12 col-lg-6 angi__r-w1by1',
      'element_width'         => array( 'col-6' ),
    );

    return $_preset;
  }
  /*

  * Fired just before the view is rendered
  * @hook: pre_rendering_view_{$this -> id}, 9999
  */
  function angi_fn_setup_late_properties() {
    $this -> angi_fn_setup_query();

    //do nothing if no custom query has been created, e.g. display related posts by tag and current post with no tags
    if ( ! $this->query ) {
      return;
    }

    $this -> angi_fn_setup_text_hooks();
    //we don't display author metas hence we force the hentry class removal for this model
    //this filter is documented in core/init-base.php
    add_filter( 'angi_post_class_remove_hentry_class', '__return_true', 999 );
  }

  /*
  * Fired just before the view is rendered
  * @hook: post_rendering_view_{$this -> id}, 9999
  */
  function angi_fn_reset_late_properties() {
    //do nothing if no custom query has been created, e.g. display related posts by tag and current post with no tags
    if ( ! $this->query ) {
      return;
    }

    //all post lists do this
    $this -> angi_fn_reset_text_hooks();
    $this -> angi_fn_reset_query();

    remove_filter( 'angi_post_class_remove_hentry_class', '__return_true', 999 );
  }


  /**
  * hook : __masonry_loop_start
  * @package Angilla
  * @since Angilla 4.0
  */
  function angi_fn_setup_text_hooks() {
    //filter the excerpt length
    add_filter( 'excerpt_length'        , array( $this , 'angi_fn_set_excerpt_length') , 999 );
  }


  /**
  * hook : __masonry_loop_end
  * @package Angilla
  * @since Angilla 4.0
  */
  function angi_fn_reset_text_hooks() {
    remove_filter( 'excerpt_length'     , array( $this , 'angi_fn_set_excerpt_length') , 999 );
  }

  /**
  * hook : excerpt_length hook
  * @return string
  * @package Angilla
  * @since Angilla 3.2.0
  */
  function angi_fn_set_excerpt_length( $length ) {
    $_custom = $this -> excerpt_length;
    return ( false === $_custom || !is_numeric($_custom) ) ? $length : $_custom;
  }

  function angi_fn_get_article_selectors() {
    $_width  = is_array( $this -> element_width ) ? $this -> element_width : array();

    return angi_fn_get_the_post_list_article_selectors( array_merge( $_width, array( 'grid-item', 'angi-related-post' ) ), "_{$this -> id}" );
  }


  function angi_fn_setup_query() {
    /* Taken from hueman */
    global $wp_query;

    /* Query setup */
    $post_id = get_the_ID();

    // Define shared post arguments
    $args = array(
      'post_type'				        => get_post_type(),
      'no_found_rows'           => true,
      'update_post_meta_cache'  => false,
      'update_post_term_cache'  => false,
      'ignore_sticky_posts'     => 1,
      'orderby'                 => 'rand',
      'post__not_in'            => array($post_id),
      'posts_per_page'          => 4
    );

    // Related by categories
    if ( 'categories' == angi_fn_opt('tc_related_posts') ) {
      $cats = get_post_meta($post_id, 'related-cat', true);
      if ( !$cats ) {
        $cats = wp_get_post_categories($post_id, array('fields'=>'ids'));
        $args['category__in'] = $cats;
      } else {
        $args['cat'] = $cats;
      }
    }

    // Related by tags
    else if ( 'tags' == angi_fn_opt('tc_related_posts') ) {
      $tags = get_post_meta($post_id, 'related-tag', true);
      if ( !$tags ) {
        $tags = wp_get_post_tags($post_id, array('fields'=>'ids'));
        $args['tag__in'] = $tags;
      } else {
        $args['tag_slug__in'] = explode(',', $tags);
      }
      if ( !$tags ) { $break = true; }
    }

    if ( isset($break) ){
      //if there are no tags set this visibility to false and skip new query generation
      $this->visibility = false;
      return;
    }

    $wp_query = new WP_Query( $args );

    $this -> angi_fn_update( array('query' => $wp_query ) );
  }


  function angi_fn_reset_query() {
    if ( ! $this -> query )
      return;

    wp_reset_query();
    wp_reset_postdata();
  }

}//end class