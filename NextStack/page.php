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
        
        <div class="single-page mx-auto">
            
            <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>

            	<header class="page-header">
            		<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
            	</header><!-- .page-header -->
    
            	<div class="page-content">
            		<?php
            			the_content();
            
            			wp_link_pages( array(
            				'before' => '<div class="page-links">' . __( 'Pages:', 'i_theme' ),
            				'after'  => '</div>',
            			) );
            		?>
            	</div><!-- .page-content -->
    
            	<?php edit_post_link(__('编辑','i_theme'), '<div class="page-footer"><span class="edit-link">', '</span></div>' ); ?>
    
            </article><!-- #page-## -->

            <?php 
                // if ( comments_open() || get_comments_number() ) :
        		// 	comments_template();
                // endif; 
            ?>

        </div>

	</div>
	
<?php get_footer(); ?>