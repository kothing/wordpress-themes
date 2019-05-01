<?php
class ANGI_btt_arrow_model_class extends ANGI_Model {

  /**
  * @override
  * fired before the model properties are parsed
  *
  * return model preset array()
  */
  function angi_fn_get_preset_model() {
    //set direction class
    $direction = esc_attr( angi_fn_opt( 'tc_back_to_top_position' ) );

    $dir_opposites = array(
      'left'  => 'right',
      'right' => 'left'
    );

    if ( ! array_key_exists( $direction, $dir_opposites ) )
      $direction = 'right';

    $_preset = array (
      'element_class'         => is_rtl() ? $dir_opposites[$direction] : $direction
    );

    return $_preset;
  }

}//end of class