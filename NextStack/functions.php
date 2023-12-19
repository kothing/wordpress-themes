<?php
if ( ! defined( 'ABSPATH' ) ) { 
	exit; 
}

date_default_timezone_set('Asia/Shanghai');
require get_template_directory() . '/inc/inc.php';

// 登录页面的LOGO链接为首页链接
add_filter('login_headerurl',function() {
	return get_bloginfo('url');
});

// 登陆界面logo的title为博客副标题
add_filter('login_headertext',function() {
	return get_bloginfo( 'description' );
});

// WordPress 5.0+移除 block-library CSS
add_action( 'wp_enqueue_scripts', 'remove_block_library_css', 100 );
function remove_block_library_css() {
	wp_dequeue_style( 'wp-block-library' );
}



function remove_dashboard_widgets() {
    // Globalize the metaboxes array, this holds all the widgets for wp-admin
    global $wp_meta_boxes;

    // // 删除 "概况" 模块
    // unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);

    // 删除 "WordPress活动及新闻" 模块
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);

    // 删除 "其它 WordPress 新闻" 模块
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);

    // 删除 "快速发布" 模块
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);

    // 删除 "站点健康状况" 模块
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health']);

    // // 删除 "近期评论" 模块
    // unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);

    // // 删除 "近期草稿" 模块
    // unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
}

add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );