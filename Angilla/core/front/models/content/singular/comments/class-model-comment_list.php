<?php
class ANGI_comment_list_model_class extends ANGI_Model {
  public $comment_args = array();
  public $comment_depth;

  /*
  * @override
  */
  function angi_fn_maybe_render_this_model_view() {
    return $this -> visibility && have_comments();
  }

  /**
  * @override
  * fired before the model properties are parsed
  *
  * return model params array()
  */
  function angi_fn_extend_params( $model = array() ) {
    // set wp list comments callback.
    $model[ 'comment_args' ]['callback']    = array( $this , 'angi_fn_comments_callback' );

    return $model;
  }

  /**
   * Template for comments and pingbacks.
   *
   *
   * Used as a callback by wp_list_comments() for displaying the comments.
   *  Inspired from Twenty Twelve 1.0
   * @package Angilla
   * @since Angilla 1.0
  */
  function angi_fn_comments_callback( $comment, $args, $depth ) {
    $this -> angi_fn_update( array(
      'comment_args'  => array_merge( $args, $this -> comment_args ),
      'comment_depth' => $depth
    ) );
    if ( angi_fn_is_registered_or_possible( 'comment' ) && isset( $args['type'] ) && 'comment' == $args['type'] ) {
      angi_fn_render_template( 'content/singular/comments/comment' );
    }
    elseif ( angi_fn_is_registered_or_possible( 'trackpingback' ) && isset( $args['type'] ) && 'pings' == $args['type'] ) {
      angi_fn_render_template( 'content/singular/comments/trackpingback' );
    }
  }
}