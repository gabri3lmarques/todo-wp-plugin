<?php

if ( ! class_exists( 'Todo_Plugin' ) ) {
    class Todo_Plugin {

        public function __construct() {
            $this->includes();
        }

        private function includes() {
            require_once plugin_dir_path( __FILE__ ) . 'class-todo-task.php';
        }

        public function run() {
            add_action( 'init', array( $this, 'register_post_type' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        }

        public function register_post_type() {
            register_post_type( 'todo_task', array(
                'labels' => array(
                    'name' => 'Tarefas',
                    'singular_name' => 'Tarefa',
                ),
                'public' => true,
                'has_archive' => true,
                'supports' => array( 'title', 'editor' ),
                'show_ui'            => false, 
                'show_in_menu'       => false
            ));
        }

        public function enqueue_scripts() {
            wp_enqueue_style( 'todo-style', plugin_dir_url( __FILE__ ) . '../assets/css/style.css' );
            wp_enqueue_script( 'todo-script', plugin_dir_url( __FILE__ ) . '../assets/js/main.min.js', array( 'jquery' ), null, true );
        }

        public function add_admin_menu() {
            add_menu_page(
                'To-do List',      // Título da página
                'To-do List',      // Texto do menu
                'manage_options',  // Capacidade necessária
                'todo-list',       // Slug da página
                array( $this, 'admin_page' ), // Função callback
                'dashicons-list-view' // Ícone do menu
            );
        }

        public function admin_page() {
            ?>
            <div class="wrap">
                <h1>To-do List</h1>
                <form id="create-task-form">
                    <input type="text" id="task-title" placeholder="Título da Tarefa" required>
                    <textarea id="task-content" placeholder="Descrição da Tarefa" required></textarea>
                    <button type="submit">Criar Tarefa</button>
                </form>
                <div id="task-list">
                    <!-- Lista de tarefas será carregada aqui -->
                </div>
            </div>
            <?php
        }

        public function enqueue_admin_scripts($hook) {
            if ($hook != 'toplevel_page_todo-list') {
                return;
            }

            wp_enqueue_style('todo-admin-style', plugin_dir_url(__FILE__) . '../assets/css/admin.css');
            wp_enqueue_script('todo-admin-script', plugin_dir_url(__FILE__) . '../assets/js/admin.js', array('jquery'), null, true);
            wp_localize_script('todo-admin-script', 'todoAjax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('todo_nonce')
            ));
        }
    }
}
