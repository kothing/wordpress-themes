<?php
class ANGI_sidebar_model_class extends ANGI_Model {
  private static $sidebar_map = array(
      //id => allowed layout (- b both )
      'right'  => 'r',
      'left'   => 'l'
  );


  /*
  * @override
  * fired before the model properties are parsed
  *
  * return model params array()
  */
  function angi_fn_extend_params( $model = array() ) {
    $screen_layout        = angi_fn_get_layout( angi_fn_get_id() , 'sidebar'  );

    //extract the position
    $position             = substr( $model['id'], 0 ,strpos( $model['id'], '_sidebar' ) );

    if ( ! in_array( $position, array('right', 'left' ) ) )
      return array();

    $global_layout        = angi_fn_get_global_layout();
    $sidebar_layout       = $global_layout[$screen_layout];
    $sidebar_prefix       = self::$sidebar_map[$position];

    //defines the sidebar wrapper class
    $model['element_class']  = $sidebar_layout[ "{$sidebar_prefix}-sidebar" ];

    return parent::angi_fn_extend_params( $model );
  }
}