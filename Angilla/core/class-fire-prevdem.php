<?php
if ( ! class_exists( 'ANGI_prevdem' ) ) :
  final class ANGI_prevdem {
    function __construct() {
      //FEATURED PAGES DISABLED BY DEFAULT
      add_filter( 'tc_opt_tc_show_featured_pages', '__return_false' );
      //SLIDER DISABLED BY DEFAULT
      add_filter( 'tc_opt_tc_front_slider', '__return_false' );
    }//construct
  }//end of class
endif;

?>