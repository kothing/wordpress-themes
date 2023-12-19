<?php 
/*
 * @Theme Name:NextStack
 * @FilePath: \NextStack\archive.php
 */
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
        <div class="archive">
        	<?php if ( have_posts() ) : ?>
        	
            	<h1 class="category-name text-gray">
                    <i class="icon-io-tag" id="<?php single_cat_title() ?>"></i><?php single_cat_title() ?>
                </h1>
        		<?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                	<header class="post-header">
                		<?php
                			the_title( '<h3 class="post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
                		 ?>
                	</header><!-- .post-header -->
                
                	<div class="post-content">
                		<?php the_excerpt(); ?>
                	</div><!-- .post-content -->
                
                </article><!-- #post-## -->

            <?php endwhile; endif;  ?>
            
        </div>
    
    	<div class="posts-nav">
    	    <?php echo paginate_links(array(
    	        'prev_next'          => 0,
    	        'before_page_number' => '',
    	        'mid_size'           => 2,
    	    ));?>
    	</div>
	</div>

<?php get_footer(); ?>
