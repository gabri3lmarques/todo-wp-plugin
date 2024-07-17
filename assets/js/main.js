jQuery(document).ready(function($) {
    function ajaxRequest(action, data) {
        data.action = action;
        data.nonce = todoAjax.nonce;

        return $.post(todoAjax.ajax_url, data);
    }

    $('#create-task-form').on('submit', function(e) {
        e.preventDefault();
        
        var data = {
            title: $('#task-title').val(),
            content: $('#task-content').val()
        };

        ajaxRequest('create_task', data).done(function(response) {
            if (response.success) {
                alert('Tarefa criada com sucesso!');
            } else {
                alert('Erro: ' + response.data);
            }
        });
    });

    $('#edit-task-form').on('submit', function(e) {
        e.preventDefault();

        var data = {
            task_id: $('#task-id').val(),
            title: $('#task-title').val(),
            content: $('#task-content').val()
        };

        ajaxRequest('edit_task', data).done(function(response) {
            if (response.success) {
                alert('Tarefa editada com sucesso!');
            } else {
                alert('Erro: ' + response.data);
            }
        });
    });

    $('.delete-task').on('click', function(e) {
        e.preventDefault();

        var taskId = $(this).data('task-id');

        ajaxRequest('delete_task', { task_id: taskId }).done(function(response) {
            if (response.success) {
                alert('Tarefa excluída com sucesso!');
            } else {
                alert('Erro: ' + response.data);
            }
        });
    });
});
