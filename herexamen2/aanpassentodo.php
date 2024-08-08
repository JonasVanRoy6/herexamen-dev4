<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Todo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Todo</h2>
        <form id="editForm" action="update_todo.php" method="post">
            <input type="hidden" id="todoId" name="id">
            <label for="todoDescription">Todo Description</label>
            <input type="text" id="todoDescription" name="description" required>
            <label for="dueDateToggle">
                <input type="checkbox" id="dueDateToggle" name="dueDateToggle"> Due Date
            </label>
            <input type="datetime-local" id="todoDueTime" name="due_time">
            <button type="submit">Save</button>
        </form>
    </div>
    <script src="script.js"></script>
</body>

</html>