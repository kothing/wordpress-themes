<?php
/*
 * @Theme Name:NextStack
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
} ?>
<?php if( io_get_option('links') ) : ?>
<div class="friend-links">
    <h4 class="text-gray mb-4">
        <i class="fa fa-bookmark" id="friendlink" style="margin-right:10px"></i><?php _e('友情链接','i_theme') ?>
    </h4>
    <div class="panel">
        <?php wp_list_bookmarks('title_li=&before=&after=&categorize=0&show_images=0&orderby=rating&order=DESC&category='.get_option('link_f_cat')); ?>
    </div> 
</div>
<?php endif; ?> 