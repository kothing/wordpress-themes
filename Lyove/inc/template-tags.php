<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Lyove
 */

if ( ! function_exists( 'lyove_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function lyove_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			_x( 'on %s', 'post date', 'lyove' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
			_x( 'Written by %s', 'post author', 'lyove' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span><span class="posted-on">' . $posted_on . '</span>';
		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				__( 'Edit Post%s', 'lyove' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;


/**
 * Single previous next links
 */
if ( ! function_exists( 'lyove_post_navigation' ) ) :
	
	function lyove_post_navigation() {
		$prevPost = get_previous_post();
		$nextPost = get_next_post();
		?>
		<nav class="navigation post-navigation">
            <div class="nav-links">
            <?php	
            if ( !empty( $prevPost ) ) {
                $prevthumbnail = get_the_post_thumbnail( $prevPost->ID, 'lyove-small-width' );
                if( empty( $prevthumbnail ) ) {
                    $prevthumbnail = '<img src="' . get_stylesheet_directory_uri() . '/assets/images/blank_img.png"/>';
                }
                ?>
                <div class="nav-previous">
                    <span class="nav-post-icon prev-icon"><?php previous_post_link('%link', '<i class="genericon genericon-leftarrow" aria-hidden="true"></i>'); ?></span>
                    <div class="nav-post-thumb prev-thumbnail">
                        <?php previous_post_link('%link', $prevthumbnail ); ?>
                    </div>
                    <div class="nav-post-title prev-title">
                        <?php previous_post_link('%link' ); ?>
                    </div>	
                </div>
            <?php } else { ?>
                <div class="nav-previous">
                    <div class="nav-post-title prev-title"><?php _e('No more','lyove'); ?></div>	
                </div>
            <?php }
            if ( !empty( $nextPost ) ) { 
                $nextthumbnail = get_the_post_thumbnail( $nextPost->ID, 'lyove-small-width' );
                if( empty( $nextthumbnail ) ) {
                    $nextthumbnail = '<img src="' . get_stylesheet_directory_uri() . '/assets/images/blank_img.png"/>';
                }
                ?>
                <div class="nav-next">
                    <span class="nav-post-icon next-icon"><?php next_post_link('%link', '<i class="genericon genericon-rightarrow" aria-hidden="true"></i>'); ?></span>
                    <div class="nav-post-thumb next-thumbnail">
                        <?php next_post_link('%link', $nextthumbnail ); ?>
                    </div>
                    <div class="nav-post-title next-title">
                        <?php next_post_link('%link' ); ?>
                    </div>	
                </div>
            
            <?php } else { ?>
                <div class="nav-next">
                    <div class="nav-post-title next-title"><?php _e('No more','lyove'); ?></div>	
                </div>
            <?php } ?>
            </div>
		</nav>
		<?php
	}
endif;


if ( ! function_exists( 'lyove_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function lyove_entry_footer() {
        /* translators: used between list items */
        $categories_list = get_the_category_list( __( ', ', 'lyove' ) );
        if ( $categories_list && lyove_categorized_blog() && !is_category() && !is_archive() ) {
            //printf( '<span class="cat-links">' . __( 'Posted in %1$s', 'lyove' ) . '</span>', $categories_list );
            printf( '<span class="cat-links"><i class="genericon genericon-category"></i>' . __( '%1$s', 'lyove' ) . '</span>', $categories_list );
        }

        /* translators: used between list items */
        $tags_list = get_the_tag_list( '', __( ' ', 'lyove' ) );
        if ( $tags_list ) {
            //printf( '<span class="tags-links">' . __( 'Tagged %1$s', 'lyove' ) . '</span>', $tags_list );
            printf( '<span class="tags-links"><i class="genericon genericon-tag"></i>' . __( '%1$s', 'lyove' ) . '</span>', $tags_list );
        }
        
        /* comments */
        if ( comments_open() ) {
            printf( '<span class="comments-link"><i class="genericon genericon-comment"></i><a href="%s#comments" rel="nofollow" title="%s">%s</a></span>', get_the_permalink(), the_title_attribute('echo=0'), get_comments_number() );
        }
	}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function lyove_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'lyove_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'lyove_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so lyove_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so lyove_categorized_blog should return false.
		return false;
	}
}

if ( ! function_exists( 'lyove_the_custom_logo' ) ) :
	/**
	 * Displays the optional custom logo.
	 *
	 * Does nothing if the custom logo is not available.
	 */
	function lyove_the_custom_logo() {
		if ( function_exists( 'the_custom_logo' ) ) {
			the_custom_logo();
		}
	}
endif;

if ( ! function_exists( 'lyove_the_custom_header' ) ) :
	/**
	 * Displays the custom header.
	 *
	 * Does nothing if the custom header is not available.
	 */
	function lyove_the_custom_header() {
		if ( get_header_image() && is_front_page() ) {
			printf( '<div id="wp-custom-header" class="wp-custom-header">%s</div>', get_header_image_tag() );
		}
	}
endif;

if ( ! function_exists( 'lyove_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 *
	 * Create your own lyove_post_thumbnail() function to override in a child theme.
	 */
	function lyove_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			if ( 1 !== get_theme_mod( 'lyove_no_thumbnail_onpost', '' ) ) : ?>
				<figure class="post-thumbnail">
					<?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ), 'class' => 'aligncenter thumbnails' ) ); ?>
				</figure><!-- .post-thumbnail -->
		<?php endif;
		else : ?>
            <figure class="post-thumbnail">
                <a href="<?php the_permalink(); ?>" aria-hidden="true">
                    <?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ), 'class' => 'aligncenter thumbnails' ) ); ?>
                </a>
            </figure>
		<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'lyove_footer_widgets' ) ) :
	/**
	 * Display footer widgets.
	 *
	 * @return void
	 * @since  0.0.1
	 */
	function lyove_footer_widgets() {
		/**
		 * Identify total number of active footer widgets (widget area in-use).
		 * Maximum three widget area supported in footer.
		 */
		$total = 0; // Count active footer widgets area.
		$i = 0; while ( $i < 3 ) {
			$i++;
			if ( is_active_sidebar( 'footer-' . $i ) ) :
				$total++ ;
			endif;
		}
		/**
		 *
		 * Display all active footer widgets area.
		 * Do not display footer-widget areas if they are not in use (footer-widget
		 * area does not contain any widgets).
		 */
		if ( 0 !== $total ) {
			printf( '<div class="footer-widgets"><div class="wrapper">' );
			$i = 0; while ( $i < 3 ) {
				$i++;
				if ( is_active_sidebar( 'footer-' . $i ) ) :
					printf( '<div class="footer-widget footer-widget-count-%s">', absint( $total ) );
					dynamic_sidebar( 'footer-' . $i );
					printf( '</div>' );
				endif;
			}
			printf( '</div></div><!--/# footer-widgets-->' );
		}
	}
endif;

/**
 * Flush out the transients used in lyove_categorized_blog.
 */
function lyove_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'lyove_categories' );
}
add_action( 'edit_category', 'lyove_category_transient_flusher' );
add_action( 'save_post',     'lyove_category_transient_flusher' );
