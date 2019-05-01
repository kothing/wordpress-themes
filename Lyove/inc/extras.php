<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Lyove
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function lyove_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
    
	if ( is_front_page() && 'slider' == get_theme_mod( 'featured_layout' ) ) {
		$classes[] = 'slider';
	} elseif ( is_front_page() ) {
		$classes[] = 'grid';
	}   

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class for content sidebar alignment.
	if ( ! is_active_sidebar( 'sidebar-1' ) ||
		'only-content' === get_theme_mod( 'lyove_layout', 'content-sidebar' ) ) :
		$classes[] = 'only-content';
	elseif ( 'sidebar-content' === get_theme_mod( 'lyove_layout', 'content-sidebar' ) ) :
		$classes[] = 'sidebar-content';
	else :
		$classes[] = 'content-sidebar';
	endif;

	// Adds a class for fixed main navigation.
	if ( get_theme_mod( 'lyove_sticky_main_menu', true ) ) {
		$classes[] = 'fixed-nav';
	}

	return $classes;
}
add_filter( 'body_class', 'lyove_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function lyove_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', bloginfo( 'pingback_url' ), '">';
	}
}
add_action( 'wp_head', 'lyove_pingback_header' );

/**
 * Change Read more text.
 *
 * Change excerpt read more link text based on custom text entered in
 * theme customizer.
 *
 * @return string
 */
function lyove_excerpt_teaser() {
	$url  = esc_url( get_permalink() );
	$text = __( 'Continue reading', 'lyove' );
	$title = get_the_title();

	if ( 0 === strlen( $title ) ) :
		$screen_reader = '';
	else :
		$screen_reader = sprintf( '<span class="screen-reader-text">%s</span>', $title );
	endif;

	$excerpt_teaser = sprintf( '<p><a class="more-link" href="%1$s">%2$s %3$s</a></p>', $url, $text, $screen_reader );
	return $excerpt_teaser;
}
add_filter( 'excerpt_more', 'lyove_excerpt_teaser' );

/**
 * Enqueue inline link color to 'head'
 */
function lyove_link_color() {
	$output = '';
	$color = get_theme_mod( 'lyove_theme_color', '' );

	// Escape $color. Output only if $color is a 3 or 6 digit hex color (with #).
	if ( '' !== $color && preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		$output .= sprintf( '
			button:hover,
			input:hover[type="button"],
			input:hover[type="reset"],
			input:hover[type="submit"] {
				background-color: %1$s;
				border-color: %1$s;
			}

			button:focus,
			input:focus[type="button"],
			input:focus[type="reset"],
			input:focus[type="submit"] {
				background-color: %1$s;
				border-color: %1$s;
			}

			input:focus,
			textarea:focus {
				border-color: %1$s;
			}

			a {
				color: %1$s;
			}

			.nav-menu a:hover,
			.nav-menu a:focus {
				color: %1$s;
			}

			.nav-next a:hover,
			.nav-previous a:hover,
			.nav-next a:focus,
			.nav-previous a:focus {
				color: %1$s;
			}

			.widget-title > span {
				border-bottom: 2px solid %1$s;
			}', $color
		);
	}
	if ( '' !== $output ) {
		wp_add_inline_style( 'lyove-style', $output );
	}
}
add_action( 'wp_enqueue_scripts', 'lyove_link_color', 50 );

//开启友情链接
add_filter('pre_option_link_manager_enabled','__return_true');

/*
 * 屏蔽widgets
 */
function remove_dashboard_widgets() {
    // Globalize the metaboxes array, this holds all the widgets for wp-admin
    global $wp_meta_boxes;
    
    // 以下这一行代码将删除 "快速发布" 模块
    // unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    
    // 以下这一行代码将删除 "引入链接" 模块
    //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    
    // 以下这一行代码将删除 "插件" 模块
    //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    
    // 以下这一行代码将删除 "近期评论" 模块
    //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    
    // 以下这一行代码将删除 "近期草稿" 模块
    //unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
    
    // 以下这一行代码将删除 "WordPress 开发日志" 模块
    //unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    
    // 以下这一行代码将删除 "其它 WordPress 新闻" 模块
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    
    // 以下这一行代码将删除 "概况" 模块
    // unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );


/*
* 网站关键字
*/
function lyove_web_keywords() {
    global $s, $post;
    $keywords = '';
	//如果是文章页，关键词则是：标签+分类ID
    if (is_single ()) {
        if (get_the_tags ( $post->ID )) {
            foreach ( get_the_tags ( $post->ID ) as $tag )
            $keywords .= $tag->name . ', ';
        }
        foreach ( get_the_category ( $post->ID ) as $category )
        $keywords .= $category->cat_name . ', ';
        $keywords = substr_replace ( $keywords, '', - 2 );
    } elseif ( is_home () || is_front_page() ) {
        $keywords = get_bloginfo( 'description', 'display' );
    } elseif ( is_category ()) {
        $keywords = get_bloginfo ( 'name' ). ',' .single_tag_title ( '', false );
    } elseif ( is_tag () ) {
        $keywords = get_bloginfo ( 'name' ). ',' .single_cat_title ( '', false );
    } elseif ( is_search () ) {
        $keywords = get_bloginfo ( 'name' ). ',' .esc_html ( $s, 1 );
    } else {
        $keywords = trim ( wp_title ( 'Lyove', false ) );
    }
    
    echo "<meta name=\"keywords\" content=\"$keywords\" />\n";
}
add_action ( 'wp_head', 'lyove_web_keywords' ); 


/**
 * Function to show the footer info, copyright information.
 */
function lyove_footer_info() {
	printf( __( 'Theme by %1$s. Powered by %2$s', 'lyove' ), '<a href="http://www.missra.com/" target="_blank" title="Lyove">Lyove</a>', '<a href="http://wordpress.org/" target="_blank" title="WordPress.org">WordPress</a>' );
}



