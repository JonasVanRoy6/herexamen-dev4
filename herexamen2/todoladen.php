<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "herexamen";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, description, status, due_time FROM tasks";
$result = $conn->query($sql);

$notDoneTodos = "";
$doneTodos = "";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $todo = "<div class='todo-item " . ($row['status'] === 'done' ? "done" : "") . "'>";
        $todo .= "<span class='todo-text' onclick=\"editTodoForm(" . $row['id'] . ", '" . $row['description'] . "', '" . $row['due_time'] . "')\">" . $row['description'] . " (Due: " . $row['due_time'] . ")</span>";
        $todo .= "<form class='edit-form' action='update_todo.php' method='post'>";
        $todo .= "<input type='hidden' name='id' value='" . $row['id'] . "'>";
        $todo .= "<input type='checkbox' name='status' value='done' " . ($row['status'] === 'done' ? "checked" : "") . ">";
        $todo .= "<button type='submit'>✓</button>";
        $todo .= "</form>";
        $todo .= "<form class='delete-form' action='delete_todo.php' method='post' onsubmit='return deleteTodo();'>";
        $todo .= "<input type='hidden' name='id' value='" . $row['id'] . "'>";
        $todo .= "<button type='submit'>🗑</button>";
        $todo .= "</div>";

        if ($row['status'] === 'done') {
            $doneTodos .= $todo;
        } else {
            $notDoneTodos .= $todo;
        }
    }
} else {
    $notDoneTodos = "No todos found";
}

$conn->close();

echo json_encode(['notDone' => $notDoneTodos, 'done' => $doneTodos]);
?>