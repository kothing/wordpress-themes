<?php
/*
 * @Theme Name: NextStack
 */
if ( ! defined( 'ABSPATH' ) ) { 
    die;
} // Cannot access pages directly.

$options[] = array(
    'id' => 'favorites_meta',
    'title' => '图标设置',
    'taxonomy' => 'favorites',
    'data_type' => 'unserialize',
    'fields' => array(
        array(
            'id' => '_term_ico',
            'type' => 'icon',
            'title' => '选择菜单图标',
            'default' => 'fa fa-chrome'
        ),
        array(
            'id' => '_term_order',
            'type' => 'text',
            'title' => '排序',
            'after' =>'数字越大越靠前',
            'default'   => '0',
        ),
        array(
            'type'    => 'notice',
            'content' => '<b><span style="color:red">注意：</span>如果添加新的分类后首页没有显示，请检测“排序”字段有没有值，如果没有，请设置一个值，默认为 0。</b>',
            'class'   => 'info',
        ),
    ),
);
CSFramework_Taxonomy::instance( $options );