<?php

if ( ! class_exists( 'Todo_Task' ) ) {
    class Todo_Task {

        public function __construct() {
            add_action( 'wp_ajax_create_task', array( $this, 'create_task' ) );
            add_action( 'wp_ajax_edit_task', array( $this, 'edit_task' ) );
            add_action( 'wp_ajax_delete_task', array( $this, 'delete_task' ) );
            add_action( 'wp_ajax_load_tasks', array( $this, 'load_tasks' ) );
        }

        public function create_task() {
            check_ajax_referer( 'todo_nonce', 'nonce' );

            $title = sanitize_text_field( $_POST['title'] );
            $content = sanitize_textarea_field( $_POST['content'] );

            $task_id = wp_insert_post( array(
                'post_title' => $title,
                'post_content' => $content,
                'post_type' => 'todo_task',
                'post_status' => 'publish',
            ));

            if ( is_wp_error( $task_id ) ) {
                wp_send_json_error( $task_id->get_error_message() );
            } else {
                wp_send_json_success( array( 'task_id' => $task_id ) );
            }
        }

        public function edit_task() {
            check_ajax_referer( 'todo_nonce', 'nonce' );
        
            $task_id = intval( $_POST['task_id'] );
            $title = sanitize_text_field( $_POST['title'] );
            $content = sanitize_textarea_field( $_POST['content'] );
        
            $result = wp_update_post( array(
                'ID' => $task_id,
                'post_title' => $title,
                'post_content' => $content,
            ));
        
            if ( is_wp_error( $result ) ) {
                wp_send_json_error( $result->get_error_message() );
            } else {
                wp_send_json_success();
            }
        }
        
        public function delete_task() {
            check_ajax_referer( 'todo_nonce', 'nonce' );

            $task_id = intval( $_POST['task_id'] );

            $result = wp_delete_post( $task_id, true );

            if ( is_wp_error( $result ) ) {
                wp_send_json_error( $result->get_error_message() );
            } else {
                wp_send_json_success();
            }
        }

        public function load_tasks() {
            $tasks = get_posts(array(
                'post_type' => 'todo_task',
                'post_status' => 'publish',
                'numberposts' => -1
            ));

            $tasks_data = array();

            foreach ($tasks as $task) {
                $tasks_data[] = array(
                    'id' => $task->ID,
                    'title' => $task->post_title,
                    'content' => $task->post_content
                );
            }

            wp_send_json_success($tasks_data);
        }
    }
}

new Todo_Task();
