jQuery(document).ready(function($) {
    function loadTasks() {
        $.get(ajaxurl, { action: 'load_tasks' }, function(response) {
            if (response.success) {
                $('#task-list').html('');
                response.data.forEach(function(task) {
                    $('#task-list').append(`
                        <div class="task-item" data-task-id="${task.id}">
                            <div>
                                <strong>${task.title}</strong>
                                <p>${task.content}</p>
                            </div>
                            <div>
                                <button class="edit-task" data-task-id="${task.id}">Editar</button>
                                <button class="delete-task" data-task-id="${task.id}">Excluir</button>
                            </div>
                        </div>
                    `);
                });
            } else {
                alert('Erro ao carregar tarefas: ' + response.data);
            }
        });
    }

    $('#create-task-form').on('submit', function(e) {
        e.preventDefault();

        var taskId = $('#create-task-form button').data('editing');
        var action = taskId ? 'edit_task' : 'create_task';
        var data = {
            task_id: taskId,
            title: $('#task-title').val(),
            content: $('#task-content').val()
        };

        $.post(ajaxurl, {
            action: action,
            nonce: todoAjax.nonce,
            task_id: data.task_id,
            title: data.title,
            content: data.content
        }, function(response) {
            if (response.success) {
                alert('Tarefa ' + (taskId ? 'editada' : 'criada') + ' com sucesso!');
                loadTasks();
                $('#create-task-form button').text('Criar Tarefa').removeData('editing');
                $('#task-title').val('');
                $('#task-content').val('');
            } else {
                alert('Erro: ' + response.data);
            }
        });
    });

    $('#task-list').on('click', '.edit-task', function(e) {
        e.preventDefault();

        var taskId = $(this).data('task-id');
        var taskItem = $(this).closest('.task-item');
        var title = taskItem.find('strong').text();
        var content = taskItem.find('p').text();

        $('#task-title').val(title);
        $('#task-content').val(content);
        $('#create-task-form button').text('Salvar Tarefa').data('editing', taskId);
    });

    $('#task-list').on('click', '.delete-task', function(e) {
        e.preventDefault();

        var taskId = $(this).data('task-id');

        if (confirm('Tem certeza que deseja excluir esta tarefa?')) {
            $.post(ajaxurl, {
                action: 'delete_task',
                nonce: todoAjax.nonce,
                task_id: taskId
            }, function(response) {
                if (response.success) {
                    alert('Tarefa excluída com sucesso!');
                    loadTasks();
                } else {
                    alert('Erro: ' + response.data);
                }
            });
        }
    });

    loadTasks();
});
