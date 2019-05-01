<?php
function angi_fn_register_social_links_module( $args ) {
    $defaults = array(
        'setting_id' => '',

        'base_url_path' => '',//PC_AC_BASE_URL/inc/angi-modules/social-links/
        'version' => '',

        'option_value' => array(), //<= will be used for the dynamic registration

        'setting' => array(),
        'control' => array(),
        'section' => array(), //array( 'id' => '', 'label' => '' ),

        'sanitize_callback' => '',
        'validate_callback' => ''
    );
    $args = wp_parse_args( $args, $defaults );

    if ( ! isset( $GLOBALS['angi_base_fmk_namespace'] ) ) {
        error_log( __FUNCTION__ . ' => global angi_base_fmk not set' );
        return;
    }

    $anginamespace = $GLOBALS['angi_base_fmk_namespace'];
    //angi_fn\angi_register_dynamic_module
    $ANGI_Fmk_Base_fn = $anginamespace . 'ANGI_Fmk_Base';
    if ( ! function_exists( $ANGI_Fmk_Base_fn) ) {
        error_log( __FUNCTION__ . ' => Namespace problem => ' . $ANGI_Fmk_Base_fn );
        return;
    }


    $ANGI_Fmk_Base_fn() -> angi_pre_register_dynamic_setting( array(
        'setting_id' => $args['setting_id'],
        'module_type' => 'angi_social_module',
        'option_value' => ! is_array( $args['option_value'] ) ? array() : $args['option_value'],

        'setting' => $args['setting'],

        'section' => $args['section'],

        'control' => $args['control']
    ));

    // angi_fn\angi_register_dynamic_module()
    $ANGI_Fmk_Base_fn() -> angi_pre_register_dynamic_module( array(

        'dynamic_registration' => true,
        'module_type' => 'angi_social_module',

        'customizer_assets' => array(
            'control_js' => array(
                // handle + params for wp_enqueue_script()
                // @see https://developer.wordpress.org/reference/functions/wp_enqueue_script/
                'angi-social-links-module' => array(
                    'src' => sprintf(
                        '%1$s/assets/js/%2$s',
                        $args['base_url_path'],
                        '_2_7_socials_module.js'
                    ),
                    'deps' => array('customize-controls' , 'jquery', 'underscore'),
                    'ver' => ( defined('WP_DEBUG') && true === WP_DEBUG ) ? time() : $args['version'],
                    'in_footer' => true
                )
            ),
            'localized_control_js' => array(
                'deps' => 'angi-customizer-fmk',
                'global_var_name' => 'socialModuleLocalized',
                'params' => array(
                    //Social Module
                    'defaultSocialColor' => 'rgb(90,90,90)',
                    'defaultSocialSize'  => 14,
                    'i18n' => array(
                        'Rss' => __('Rss', 'angilla'),
                        'Select a social icon' => __('Select a social icon', 'angilla'),
                        'Follow us on' => __('Follow us on', 'angilla'),
                        'Done !' => __('Done !', 'angilla'),
                        'New Social Link created ! Scroll down to edit it.' => __('New Social Link created ! Scroll down to edit it.', 'angilla'),
                    )
                    //option value for dynamic registration
                )
            )
        ),

        'tmpl' => array(
            'pre-item' => array(
                'social-icon' => array(
                    'input_type'  => 'select',
                    'title'       => __('Select an icon', 'angilla')
                ),
                'social-link'  => array(
                    'input_type'  => 'text',
                    'title'       => __('Social link url', 'angilla'),
                    'notice_after'      => __('Enter the full url of your social profile (must be valid url).', 'angilla'),
                    'placeholder' => __('http://...,mailto:...,...', 'angilla')
                )
            ),
            'mod-opt' => array(
                'social-size' => array(
                    'input_type'  => 'number',
                    'title'       => __('Size in px', 'angilla'),
                    'step'        => 1,
                    'min'         => 5,
                    'transport' => 'postMessage'
                )
            ),
            'item-inputs' => array(
                'social-icon' => array(
                    'input_type'  => 'select',
                    'title'       => __('Social icon', 'angilla')
                ),
                'social-link'  => array(
                    'input_type'  => 'text',
                    'title'       => __('Social link', 'angilla'),
                    'notice_after'      => __('Enter the full url of your social profile (must be valid url).', 'angilla'),
                    'placeholder' => __('http://...,mailto:...,...', 'angilla')
                ),
                'title'  => array(
                    'input_type'  => 'text',
                    'title'       => __('Title', 'angilla'),
                    'notice_after'      => __('This is the text displayed on mouse over.', 'angilla'),
                ),
                'social-color'  => array(
                    'input_type'  => 'color',
                    'title'       => sprintf( '%1$s <i>%2$s %3$s</i>', __('Icon color', 'angilla'), __('default:', 'angilla'), 'rgba(255,255,255,0.7)' ),
                    'notice_after'      => __('Set a unique color for your icon.', 'angilla'),
                    'transport' => 'postMessage'
                ),
                'social-target' => array(
                    'input_type'  => 'check',
                    'title'       => __('Link target', 'angilla'),
                    'notice_after'      => __('Check this option to open the link in a another tab of the browser.', 'angilla'),
                    'width-100'   => true
                )
            )
        )
    ));
}//ac_register_social_links_module()





/////////////////////////////////////////////////////////////////
// SANITIZATION
/***
* Social Module sanitization/validation
**/
function angi_fn_sanitize_callback__angi_social_module( $socials ) {
  // error_log( 'IN SANITIZATION CALLBACK' );
  // error_log( print_r( $socials, true ));
  if ( empty( $socials ) )
    return array();

  //sanitize urls and titles for the db
  foreach ( $socials as $index => &$social ) {
    if ( ! is_array( $social ) || ! ( array_key_exists( 'social-link', $social) &&  array_key_exists( 'title', $social) ) )
      continue;

    $social['social-link']  = esc_url_raw( $social['social-link'] );
    $social['title']        = esc_attr( $social['title'] );
  }
  return $socials;
}

function angi_fn_validate_callback__angi_social_module( $validity, $socials ) {
  // error_log( 'IN VALIDATION CALLBACK' );
  // error_log( print_r( $socials, true ));
  $ids_malformed_url = array();
  $malformed_message = __( 'An error occurred: malformed social links', 'angilla');

  if ( empty( $socials ) )
    return array();

  //validate urls
  foreach ( $socials as $index => $item_or_modopt ) {
    if ( ! is_array( $item_or_modopt ) )
      return new WP_Error( 'required', $malformed_message );

    //should be an item or a mod opt
    if ( ! array_key_exists( 'is_mod_opt', $item_or_modopt ) && ! array_key_exists( 'id', $item_or_modopt ) )
      return new WP_Error( 'required', $malformed_message );

    //if modopt case, skip
    if ( array_key_exists( 'is_mod_opt', $item_or_modopt ) )
      continue;

    if ( $item_or_modopt['social-link'] != esc_url_raw( $item_or_modopt['social-link'] ) )
      array_push( $ids_malformed_url, $item_or_modopt[ 'id' ] );
  }

  if ( empty( $ids_malformed_url) )
    return null;

  return new WP_Error( 'required', __( 'Please fill the social link inputs with a valid URLs', 'angilla' ), $ids_malformed_url );
}

