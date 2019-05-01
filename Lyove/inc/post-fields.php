<?php
/**
 * Add a custom field to the article.
 * 文章添加自定义字段面板
 * 
 */

 
/* 
 * 创建需要的字段信息
 */
$new_meta_boxes =
array(
    "field1" => array(
        "name" => "field1",
        "std" => "",
        "title" => "字段1:"
    ),
    "field2" => array(
        "name" => "field2",
        "std" => "",
        "title" => "字段2:"
    )
);


/* 
 * 创建(显示)面板内容的函数
 * 该函数用来显示面板的内容，将作为add_meta_box函数才callback参数调用
 */
function new_meta_boxes() {
    global $post, $new_meta_boxes;
    foreach($new_meta_boxes as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'].'_value', true);
        if($meta_box_value == "")
            $meta_box_value = $meta_box['std'];
        echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
        // 自定义字段标题
        echo'<h4>'.$meta_box['title'].'</h4>';
        // 自定义字段输入框
        echo '<textarea cols="60" rows="3" name="'.$meta_box['name'].'_value" class="custom_field" style="width:100%">'.$meta_box_value.'</textarea><br />';
    }
}


/* 
 * 创建面板
 *
 * add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args ); 
 * $id--面板的的id属性(html)。
 * $title--面板标题
 * $callback--调用的函数
 * $post_type--要在编辑页面创建面板的文章类型，比如post\page..自定义的文章类型等
 * $context--(可选)面板要显示的位置，可以使用normal\advanced\side分别为普通、高级(貌似跟普通效果差不多)、边栏
 * $priority--(可选)显示的优先级，可以使用high\core\default\low 如果设置为high那么它会显示在默认的那些自定义字段、评论、作者什么的前面
 * $callback_args--(可选、数组)要传给那个$callback函数的参数
 */
function create_meta_box() {
    global $theme_name;
    if ( function_exists('add_meta_box') ) {
        add_meta_box( 'extrasdiv', '扩展模块', 'new_meta_boxes', 'post', 'normal', 'high' );
    }
} 
 
 
/* 
 * 保存更新数据
 */ 
function save_postdata( $post_id ) {
    global $post, $new_meta_boxes;
    foreach($new_meta_boxes as $meta_box) {
        if ( !wp_verify_nonce( $_POST[$meta_box['name'].'_noncename'], plugin_basename(__FILE__) ))  {
            return $post_id;
        }
        if ( 'page' == $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_page', $post_id ))
                return $post_id;
        }
        else {
            if ( !current_user_can( 'edit_post', $post_id ))
                return $post_id;
        }
        $data = $_POST[$meta_box['name'].'_value'];
        if(get_post_meta($post_id, $meta_box['name'].'_value') == "")
            add_post_meta($post_id, $meta_box['name'].'_value', $data, true);
        elseif($data != get_post_meta($post_id, $meta_box['name'].'_value', true))
            update_post_meta($post_id, $meta_box['name'].'_value', $data);
        elseif($data == "")
            delete_post_meta($post_id, $meta_box['name'].'_value', get_post_meta($post_id, $meta_box['name'].'_value', true));
    }
}


/* 
 * 触发ction钩子
 */
add_action('admin_menu', 'create_meta_box');
add_action('save_post', 'save_postdata');


/* 
 * 调用
 * $field1 = get_post_meta($post->ID, "field1_value", true);
 * $field2 = get_post_meta($post->ID, "field2_value", true);
 */


?>