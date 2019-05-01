<?php
class ANGI_comment_model_class extends ANGI_Model {
  public $comment_reply_link_args;

  //bools
  public $is_current_post_author;
  public $is_awaiting_moderation;


  /**
   * Prepare template for comments
   *
   * dynamic, props change at each loop cycle
   *
  */
  function angi_fn_setup_late_properties() {
    global $post;
    global $comment;

    $args  = angi_fn_get_property( 'comment_args' );
    $depth = angi_fn_get_property( 'comment_depth' );

    $props = array(
     'comment_text'            => apply_filters( 'comment_text', get_comment_text( $comment->comment_ID , $args ), $comment, $args ),
     'comment_reply_link_args' => array_merge( $args,
        array(
          'depth'      => $depth,
          'max_depth'  => isset($args['max_depth'] ) ? $args['max_depth'] : '',
          'add_below'  => apply_filters( 'angi_comment_reply_below' , 'div-comment' )
        )
      ),
     'is_current_post_author'   => ( $comment->user_id === $post->post_author ),
     'is_awaiting_moderation'   => '0' == $comment->comment_approved,
    );

    $this -> angi_fn_update( $props );
  }
}