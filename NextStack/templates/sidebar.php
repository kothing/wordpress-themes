<?php
/*
 * @Theme Name:NextStack
 */
if ( ! defined( 'ABSPATH' ) ) { 
    exit; 
} ?>
<aside class="sidebar toggle-sidebar uncollapsed">
    <div class="sidebar-inner">
        <div class="site-logo">
            <div class="logo">
                <a href="<?php bloginfo('url') ?>" class="logo-expanded">
                    <img src="<?php echo io_get_option('logo_normal') ?>" height="40" alt="<?php bloginfo('name') ?>" />
                </a>
                <a href="<?php bloginfo('url') ?>" class="logo-collapsed">
                    <img src="<?php echo io_get_option('logo_small') ?>" height="40" alt="<?php bloginfo('name') ?>">
                </a>
            </div>
            <div class="mobile-menu-toggle visible-xs">
                <a href="#" data-toggle="mobile-menu">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
        </div>
        <ul class="main-menu">
        <?php
        foreach($categories as $category) {
            if($category->category_parent == 0){
                $children = get_categories(array(
                    'taxonomy'   => 'favorites',
                    'meta_key'   => '_term_order',
                    'orderby'    => 'meta_value_num',
                    'order'      => 'desc',
                    'child_of'   => $category->term_id,
                    'hide_empty' => 0)
                );
                if(empty($children)){ ?>
                <li class="menu-item">
                    <a href="<?php if (is_home() || is_front_page()): ?><?php else: echo home_url() ?>/<?php endif; ?>#term-<?php echo $category->term_id;?>" class="item-link smooth">
                        <i class="<?php echo get_term_meta($category->term_id, '_term_ico',true) ?> fa-fw icon-lg"></i>
                        <span class="cat-name"><?php echo $category->name; ?></span>
                    </a>
                </li> 
                <?php } else { ?>
                <li class="menu-item">
                    <a href="#">
                        <i class="<?php echo get_term_meta($category->term_id, '_term_ico',true) ?> fa-fw icon-lg"></i>
                        <span class="cat-name"><?php echo $category->name; ?></span>
                    </a>
                    <ul>
                        <?php foreach ($children as $mid) { ?>

                        <li class="sub-menu-item">
                            <a href="<?php if (is_home() || is_front_page()): ?><?php else: echo home_url() ?>/<?php endif; ?>#term-<?php  echo $mid->term_id ;?>" class="item-link smooth"><?php echo $mid->name; ?></a>
                        </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php }
            }
        }
        ?> 
        </ul>
        <ul class="bottom-menu">
            <?php
                if(function_exists('wp_nav_menu')) {
                    wp_nav_menu( array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'nav_main',) ); 
                }
            ?>
        </ul>
    </div>
</aside>