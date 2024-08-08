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

document.addEventListener('DOMContentLoaded', function() {
    // Event delegation
    document.body.addEventListener('click', function(event) {
        if (event.target && event.target.matches('.add-comment')) {
            console.log("Button clicked");

            const taskElement = event.target.closest('.task');

            if (!taskElement) {
                console.error("Task element not found");
                return;
            }

            const taskIdElement = taskElement.querySelector('.task-id');
            const commentElement = taskElement.querySelector('.new-comment');

            if (!taskIdElement || !commentElement) {
                console.error("Required elements (task-id or new-comment) not found");
                return;
            }

            const taskId = taskIdElement.value;
            const comment = commentElement.value.trim();

            console.log("Task ID:", taskId);
            console.log("Comment:", comment);

            if (comment === '') {
                alert('Commentaar kan niet leeg zijn.');
                return;
            }

            fetch('add_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json'
                },
                body: new URLSearchParams({
                    'tasks_id': taskId,
                    'comment': comment
                }).toString()
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response from server:', data);
                if (data.success) {
                    alert('Comment added successfully!');
                    const commentsDiv = taskElement.querySelector('.comments');
                    if (commentsDiv) {
                        const newComment = document.createElement('p');
                        newComment.textContent = comment;
                        commentsDiv.appendChild(newComment);
                    } else {
                        console.error("Comments container not found");
                    }

                    commentElement.value = '';
                } else {
                    alert('Failed to add comment: ' + data.message);
                }
            })
            
        }
    });
});

