<?php
/**
 * Remove unnecessary.
 *
 * @package Angilla
 */

//前端页面移除Head多余信息
remove_action( 'wp_head', 'feed_links', 2 ); 							// 去除文章feed
remove_action( 'wp_head', 'rsd_link' ); 								// 针对Blog的远程离线编辑器接口
remove_action( 'wp_head', 'wlwmanifest_link' ); 						// Windows Live Writer接口
remove_action( 'wp_head', 'wp_generator' ); 							// 移除版本号
remove_action( 'wp_head', 'index_rel_link');							// 当前文章的索引
remove_action( 'wp_head', 'feed_links_extra', 3);						// 额外的feed,例如category, tag页
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );	// 移除<link>中rel=pre
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );				// 移除<link>中rel=shortlink
remove_action( 'wp_head', 'rel_canonical' );							// 移除<link>中rel='canonical'
remove_action( 'wp_head', 'rsd_link');									// 移除head中的rel="EditURI"
remove_action( 'wp_head', 'wlwmanifest_link');							// 移除head中的rel="wlwmanifest"
remove_action( 'wp_head', 'rsd_link');									// rsd_link移除XML-RPC
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );				// 移除wp-json链接
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );		// 移除oEmbed discovery
remove_filter( 'the_content', 'wptexturize');							// 禁用半角符号自动转换为全角



//前端页面移除JS和CSS版本号
function mipress_remove_script_style_version( $src ) {
	if( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}
add_filter( 'style_loader_src', 'mipress_remove_script_style_version', 999 );
add_filter( 'script_loader_src', 'mipress_remove_script_style_version', 999 );


//移除DNS预获取（dns-prefetch）
function mipress_remove_dns_prefetch( $hints, $relation_type ) {
    if ( 'dns-prefetch' === $relation_type ) {
        return array_diff( wp_dependencies_unique_hosts(), $hints );
    }
    return $hints;
}
add_filter( 'wp_resource_hints', 'mipress_remove_dns_prefetch', 10, 2 );


/**
 * 禁用Emoji表情
 */
function mipress_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'mipress_disable_emojis_tinymce' );
}
add_action( 'init', 'mipress_disable_emojis' );

/**
 * 禁用编辑器表情
 */
function mipress_disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}


/*
 * 禁用embeds
*/
function disable_embeds_init() {
    /* @var WP $wp */
    global $wp;
 
    // Remove the embed query var.
    $wp->public_query_vars = array_diff( $wp->public_query_vars, array(
        'embed',
    ) );
 
    // Remove the REST API endpoint.
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );
 
    // Turn off
    add_filter( 'embed_oembed_discover', '__return_false' );
 
    // Don't filter oEmbed results.
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
 
    // Remove oEmbed discovery links.
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
 
    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );
}
 
add_action( 'init', 'disable_embeds_init', 9999 );

/**
 * 移除编辑器 wpembed 插件
 */
function disable_embeds_tiny_mce_plugin( $plugins ) {
    return array_diff( $plugins, array( 'wpembed' ) );
}



?>