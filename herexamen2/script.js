function updateTaskStatus(taskId, status) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "statustaken.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            location.reload();
        }
    };
    xhr.send("task_id=" + taskId + "&status=" + status);
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            fetch('delete_todo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + id
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    const taskElement = document.querySelector(`.task[data-id="${id}"]`);
                    if (taskElement) {
                        taskElement.remove();
                    } else {
                        console.error('Task element not found');
                    }
                } else {
                    console.error('Failed to delete task');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});

document.querySelectorAll('.add-comment').forEach(button => {
    button.addEventListener('click', function() {
        const taskElement = this.closest('.task');
        const taskId = taskElement.dataset.id;  // Haal het tasks_id op uit data-id attribuut
        const comment = taskElement.querySelector('.new-comment').value;

        // Debugging: Controleer of taskId en comment correct worden opgehaald
        console.log("Task ID:", taskId);
        console.log("Comment:", comment);

        fetch('add_comment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `tasks_id=${encodeURIComponent(taskId)}&comment=${encodeURIComponent(comment)}`
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response from server:', data);
            if (data.success) {
                alert('Comment added successfully!');
            } else {
                alert('Failed to add comment: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to add comment');
        });
    });
});

