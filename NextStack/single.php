<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>


<?php 
$categories= get_categories(array(
  'taxonomy'     => 'favorites',
  'meta_key'     => '_term_order',
  'orderby'      => 'meta_value_num',
  'order'        => 'desc',
  'hide_empty'   => 0,
  )
); 
include( 'templates/sidebar.php' );
?>
<div class="main-content">
    
    <?php include( 'templates/navbar.php' ); ?>
    
    <div class="container">

    	<div class="single-post mx-auto">
            
            <?php while( have_posts() ): the_post(); ?> 
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            	<header class="post-header">
            		<?php the_title( '<h1 class="post-title">', '</h1>' ); ?>
            	</header><!-- .post-header -->
            
            	<div class="post-content">
            		<?php
            			the_content( sprintf(
            				esc_html__( 'Continue reading %s', 'i_theme' ),
            				the_title( '<span class="screen-reader-text">', '</span>', false )
            			) );
            
            			wp_link_pages( array(
            				'before' => '<div class="page-links">' . __( 'Pages:', 'i_theme' ),
            				'after'  => '</div>',
            			) );
            		?>
            	</div><!-- .entry-content -->
            	<?php edit_post_link(__('编辑','i_theme'), '<div class="post-footer"><span class="edit-link">', '</span></div>' ); ?>
            </article><!-- #post-## -->
            
            
            <?php endwhile; ?>  
            
            <?php 
                // if ( comments_open() || get_comments_number() ) :
    			// 	comments_template();
                // endif; 
            ?>
    	</div>
	</div>
<?php get_footer(); ?>