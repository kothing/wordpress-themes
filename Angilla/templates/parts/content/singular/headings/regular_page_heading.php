<?php
/**
 * The template for displaying the header of a single page
 * In loop
 *
 * @package Angilla
 */
?>
<header class="entry-header <?php angi_fn_echo( 'element_class' ) ?>" <?php angi_fn_echo('element_attributes') ?>>
  <div class="entry-header-inner">
    <?php
    // This hook is used to render the following elements(ordered by priorities) :
    // singular thumbnail
    do_action( '__before_regular_heading_title' );

    if ( get_the_title() ) :

    ?>
    <h1 class="entry-title"><?php the_title() ?></h1>
    <?php

    endif;
    if ( angi_fn_is_registered_or_possible('edit_button') && (bool) $edit_post_link = get_edit_post_link() )
        angi_fn_edit_button( array( 'link'  => $edit_post_link ) );

    // This hook is used to render the following elements(ordered by priorities) :
    // singular thumbnail
    do_action( '__after_regular_heading_title' );

    angi_fn_comment_info( $before = '<div class="header-bottom entry-meta"><div class="post-info"><div class="comment-info">', $after = '</div></div></div>' );
    ?>
  </div>
</header>