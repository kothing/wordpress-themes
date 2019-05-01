<?php
/**
 * The template for displaying the header of a post in a post list
 * In ANGI loop
 *
 * @package Angilla
 */
?>
<header class="entry-header <?php angi_fn_echo( 'element_class' ) ?>" <?php angi_fn_echo('element_attributes') ?>>
  <div class="entry-header-inner <?php angi_fn_echo( 'entry_title_class' ) ?>">
    <?php
      if ( angi_fn_get_property( 'has_header_format_icon' ) ): ?>
        <div class="post-type__icon"><i class="icn-format"></i></div>
    <?php
      endif;//has_header_format_icon

      if ( angi_fn_is_registered_or_possible('post_metas') && $cat = angi_fn_get_property( 'cat_list', 'post_metas', array( 'limit'  => angi_fn_get_property('cat_limit') ) ) ) : ?>
        <div class="tax__container post-info entry-meta">
          <?php echo $cat ?>
        </div>
    <?php
      endif; //post_metas

      if ( angi_fn_get_property( 'the_title' ) ): ?>

    <h2 class="entry-title">
      <a class="angi-title" href="<?php the_permalink() ?>" title="<?php the_title_attribute( array( 'before' => __('Permalink to ', 'angilla') ) ) ?>" rel="bookmark"><?php angi_fn_echo( 'the_title' ) ?></a>
    </h2>
    <?php

      endif;//the_title

      angi_fn_comment_info( array( 'before' => '<div class="post-info">', 'after' => '</div>') );

      if ( angi_fn_is_registered_or_possible('edit_button') && (bool) $edit_post_link = get_edit_post_link() )
        angi_fn_edit_button( array( 'link'  => $edit_post_link ) );
    ?>
  </div>
</header>