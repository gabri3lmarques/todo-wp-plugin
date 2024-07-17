<?php
/*
Plugin Name: To-do Plugin
Description: Plugin de lista de tarefas para WordPress.
Version: 1.0
Author: Gabriel Marques
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-todo-plugin.php';

function run_todo_plugin() {
    $plugin = new Todo_Plugin();
    $plugin->run();
}

run_todo_plugin();
