<?php
/*
 * @Theme Name: NextStack
 */
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

$options[] = array(
    'id' => 'site_meta',
    'title' => '网址链接属性',
    'post_type' => 'site',
    'data_type' => 'unserialize',
    'context' => 'normal',
    'priority' => 'high',
    'sections'  => array(
        array(
            'name'   => 'section_4',
            'fields' => array(
                array(
                    'id'    => '_visible',
                    'type'  => 'switcher',
                    'title' => '管理员可见',
                    'label' => '如果开启，将只有登录管理员账号后才会显示',
                ),
                array(
                    "id" => "_site_link",
                    "type"=>"text",
                    "title" => "输入网址链接，",
                    'after' =>'需包含 http(s)://<br><span style="font-weight: normal;color: crimson;margin-top: 10px;display: block;">注意：“网址”和“公众号二维码”两者可同时填写，但是至少填一项。</span>',
                ),
            
                array(
                    "id" => "_site_sescribe",
                    "type"=>"text",
                    "title" => "描述",
                ),
            
                array(
                    "id" => "_site_order",
                    "std" => "0",
                    "title" => "网址排序数值越大越靠前",
                    "type"=>"text"
                ),
            
                array(
                    "id" => "_thumbnail",
                    "type"=>"image",
                    "title" => "添加图标地址，调用自定义图标",
                    'add_title' => '添加图标',
                ),
            
                array(
                    "id" => "_wechat_qr",
                    "type"=>"image",
                    "title" => "添加公众号二维码",
                    'add_title' => '添加二维码',
                ),
            ),
        ),

    ),
);
CSFramework_Metabox::instance( $options );