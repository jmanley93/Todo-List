<?php
/*
 * Plugin Name:       todo-list
 * Description:       Display and add to do list items
 * Version:           1.0
 * Author:            Jack Manley
 */

//Register scripts to use
function func_load_vuescripts() {
    wp_enqueue_script('vue', 'https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js', [], '2.5.17');
    wp_enqueue_script('todo-list-script', plugin_dir_url( __FILE__ ) . 'Assets/todo-list.js', [], '1.0', true);
    wp_enqueue_style('todo-list-styles', plugin_dir_url( __FILE__ ) . 'Assets/todo-list.css', [], '1.0');
}

add_action('wp_enqueue_scripts', 'func_load_vuescripts');

//Add shortcode to WordPress
function add_todo_list(){
 
  wp_enqueue_script('vue');
  wp_enqueue_script( 'wp-api' );
  wp_enqueue_script('todo-list-script');
  wp_enqueue_style('todo-list-styles');

  $str= "<div id='mount'></div>";
  return $str;

}

add_shortcode( 'todoList', 'add_todo_list' );

//Add custom post type to WordPress
function custom_todo_post() {
    $labels = array(
        'name'               => _x( 'todo', 'post type general name' ),
        'singular_name'      => _x( 'todo', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'todo' ),
        'add_new_item'       => __( 'Add New todo' ),
        'edit_item'          => __( 'Edit todo' ),
        'new_item'           => __( 'New todo' ),
        'all_items'          => __( 'All todo' ),
        'view_item'          => __( 'View todo' ),
        'search_items'       => __( 'Search todo' ),
        'not_found'          => __( 'No todo found' ),
        'not_found_in_trash' => __( 'No todo found in the Trash' ), 
        'menu_name'          => 'todo'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Holds todo list data',
        'public'        => true,
        'menu_position' => 5,
        'has_archive'   => true,
        'show_in_rest'  => true,
      );
    register_post_type( 'todo', $args ); 
  }
  
 add_action( 'init', 'custom_todo_post' );

 //Add custom fields to WordPress
 function facebook_add_user_data() {
  register_rest_field(
    "todo", "tododescription",
      ["get_callback" => 'get_custom_field',
      "update_callback" => 'set_custom_field']
    );
  register_rest_field(
    "todo", "todostatus",
      ["get_callback" => 'get_custom_field',
      "update_callback" => 'set_custom_field']
    );
  register_rest_field(
    "todo", "tododate",
      ["get_callback" => 'get_custom_field',
      "update_callback" => 'set_custom_field']
    );
  }


  function get_custom_field ($user, $field_name, $request, $object_type) {
    return get_user_meta($user["id"], $field_name, TRUE);
  }

  function set_custom_field ($value, $user, $field_name, $request, $object_type) {
    update_user_meta($user->ID, $field_name, $value);
  }

  add_action( 'rest_api_init', 'facebook_add_user_data' );



?>