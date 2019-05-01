<?php
class ANGI_trackpingback_model_class extends ANGI_Model {

  //bools
  public $ping_number = 0;

  /*
  * Prepare template for comments
  *
  */
  function angi_fn_setup_late_properties() {
    global $comment;

    $_pn = $this->ping_number;
    $_pn++;

    $this -> angi_fn_set_property( 'ping_number', $_pn );

  }
}