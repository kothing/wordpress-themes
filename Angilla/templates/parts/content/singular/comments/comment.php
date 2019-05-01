<?php
/**
 * The template for displaying the single comment
 *
 * @package Angilla
 * @since Angilla 1.0.0
 */
?>
<li <?php comment_class() ?> id="comment-<?php comment_ID() ?>" <?php angi_fn_echo('element_attributes') ?>>
  <div id ="div-comment-<?php comment_ID() ?>" class="comment-section clearfix">
    <div class="col-avatar">
      <figure class="comment-avatar">
        <?php echo get_avatar( $comment, 80 ) ?>
      </figure>
    </div>
    <div class="comment-body" role="complementary">
      <header class="comment-meta">
        <div clas="comment-meta-top">
          <div class="comment-author vcard">
            <?php comment_author_link() ?>
            <?php if ( angi_fn_get_property('is_current_post_author') ): ?>
              <span class="small"><?php _e( 'Post author' , 'angilla' ) ?></span>
            <?php endif; ?>
          </div>
          <time class="comment-date comment-metadata" datetime="<?php comment_time() ?>">
            <span><?php comment_date();?>,</span>
            <a class="comment-time comment-link" href="<?php comment_link() ?>"><?php comment_time() ?></a>
          </time>
        </div>
        <?php if ( (bool) $edit_comment_link = get_edit_comment_link() ) {
            angi_fn_edit_button( array( 'class' => 'comment-edit-link', 'link'  => $edit_comment_link, 'text' =>  __( 'Edit comment', 'angilla' ) ) );
        }
        ?>
      </header>
      <div class="comment-content tc-content-inner"><?php comment_text() ?></div>
      <?php if ( angi_fn_get_property( 'is_awaiting_moderation' ) ): ?>
        <p class="comment-awaiting-moderation comment-content"><?php _e( 'Your comment is awaiting moderation.' , 'angilla' ) ?></p>
      <?php endif; ?>
      <?php if ( false != $comment_reply_link = get_comment_reply_link( angi_fn_get_property( 'comment_reply_link_args' ) ) ) :
        echo $comment_reply_link;
      endif ?>
    </div>
  </div>