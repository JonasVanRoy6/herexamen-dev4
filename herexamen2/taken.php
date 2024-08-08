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

$sql = "SELECT id, description, due_time FROM tasks WHERE status='not_done'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="task" data-id="' . $row["id"] . '">
                <span class="description">' . htmlspecialchars($row["description"]) . ' (Due: ' . htmlspecialchars($row["due_time"]) . ')</span>
                <span class="checkmark" onclick="updateTaskStatus(' . $row["id"] . ', \'done\')">✔️</span>
                <button class="delete-btn" data-id="' . $row["id"] . '">Verwijder</button>';

        // Commentaren ophalen voor deze taak
        $taskId = $row["id"];
        $commentSql = "SELECT comment FROM comments WHERE tasks_id='$taskId'";
        $commentResult = $conn->query($commentSql);

        echo '<div class="comments">';
        if ($commentResult->num_rows > 0) {
            while ($commentRow = $commentResult->fetch_assoc()) {
                echo '<p>' . htmlspecialchars($commentRow["comment"]) . '</p>';
            }
        } else {
            echo '<p>No comments yet.</p>';
        }
        echo '</div>';

        // Inputveld voor nieuwe commentaren
        echo '<textarea class="new-comment"></textarea>
              <button class="add-comment">Voeg commentaar toe</button>
              </div>';
    }
} else {
    echo "No tasks found.";
}

$conn->close();
?>

<script src="script.js"></script>