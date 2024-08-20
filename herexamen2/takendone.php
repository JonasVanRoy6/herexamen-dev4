<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "herexamen";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, description, due_time FROM tasks WHERE status='done'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="task done" data-id="' . $row["id"] . '">
        <span class="description">' . htmlspecialchars($row["description"]) . ' (Due: ' . htmlspecialchars($row["due_time"]) . ')</span>
        <img src="images\checkmark.svg.png" alt="Mark as not done" class="checkmark" onclick="updateTaskStatus(' . $row["id"] . ', \'not_done\')">
        <button class="delete-btn" data-id="' . $row["id"] . '">Verwijder</button>
      </div>';

    }
} else {
    echo "No tasks found.";
}

$conn->close();
?>

<script src="script.js"></script>