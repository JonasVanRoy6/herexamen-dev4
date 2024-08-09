<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>New Todo</title>
    <link rel="stylesheet" href="aanmakentodo.css">
    <style>
        .feedback {
            color: red;
            font-size: small;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>New Todo</h2>
        <form id="todoForm" action="opslaantodo.php" method="post">
            <label for="description">Todo Name</label>
            <input type="text" id="description" name="description" required>

            <label for="due_timeToggle">
                <input type="checkbox" id="due_timeToggle" name="due_timeToggle"> Due Date
            </label>
            <input type="datetime-local" id="due_time" name="due_time" style="display:none;">

            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="not_done">Not Done</option>
                <option value="done">Done</option>
            </select>

            <button type="submit">Save</button>
        </form>

        <!-- Weergave van de feedbackmelding -->
        <?php
        if (isset($_GET['feedback'])) {
            $feedback = htmlspecialchars($_GET['feedback']);
            echo '<div class="feedback">' . $feedback . '</div>';
        }
        ?>
    </div>
    <script>
        document.getElementById('due_timeToggle').addEventListener('change', function () {
            var dueDateInput = document.getElementById('due_time');
            if (this.checked) {
                dueDateInput.style.display = 'block';
            } else {
                dueDateInput.style.display = 'none';
                dueDateInput.value = '';
            }
        });
    </script>
</body>

</html>