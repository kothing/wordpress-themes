<?php
class ANGI_social_block_model_class extends ANGI_Model {
  /*
  * @override
  * fired before the model properties are parsed
  *
  * return model params array()
  */
  function angi_fn_extend_params( $model = array() ) {
    $_socials = angi_fn_get_social_networks( $output_type = 'array' );
    if ( ! empty( $_socials  ) )
      $model[ 'socials' ] = array_map( 'angi_fn_li_wrap', $_socials );

    return parent::angi_fn_extend_params( $model );
  }

}