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

// Huidige datum
$currentDate = new DateTime();

// SQL-query om taken op te halen en te sorteren op vervaldatum
$sql = "SELECT id, description, due_time 
        FROM tasks 
        WHERE status='not_done' 
        ORDER BY 
            CASE 
                WHEN due_time IS NULL OR due_time = '' THEN '9999-12-31' 
                ELSE due_time 
            END ASC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dueTime = $row["due_time"];

        // HTML-output
        echo '<div class="task" data-id="' . $row["id"] . '">';
        echo '<div class="task-header">';
        echo '<span class="checkmark" onclick="updateTaskStatus(' . $row["id"] . ', \'done\')">✔️</span>';
        echo '<span class="description">' . htmlspecialchars($row["description"]) . '</span>';

        if (!empty($dueTime) && DateTime::createFromFormat('Y-m-d H:i:s', $dueTime) !== false) {
            // Bereken het aantal resterende dagen als er een vervaldatum is
            $dueDate = new DateTime($dueTime);
            $interval = $currentDate->diff($dueDate);
            $daysRemaining = $interval->format('%r%a'); // `%r` geeft het teken van de resterende dagen (positief/negatief)

            echo '<span class="due-date" style="display:none;">' . htmlspecialchars($dueTime) . '</span>
                  <span class="remaining-days">';

            if ($daysRemaining > 0) {
                echo '(Nog ' . $daysRemaining . ' dagen)';
            } elseif ($daysRemaining == 0) {
                echo '(Vandaag)';
            } elseif ($daysRemaining < 70000) {
                echo '(Geen datum)';
            } else {
                echo '(' . abs($daysRemaining) . ' dagen verstreken)';
            }

            echo '</span>';
        } else {
            // Geen vervaldatum opgegeven of onjuist formaat
            echo '<span class="due-date" style="display:none;">Geen vervaldatum opgegeven</span>';
        }

        echo '<button class="delete-btn" data-id="' . $row["id"] . '">Verwijder</button>';
        echo '</div>'; // sluit .task-header

        // Commentaren ophalen voor deze taak
        $taskId = $row["id"];
        $commentSql = "SELECT comment, id FROM comments WHERE tasks_id='$taskId'";
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

        // Knop om commentaarsectie te tonen
        echo '<button class="show-comment-btn" onclick="toggleCommentSection(' . $row["id"] . ')">Voeg commentaar toe</button>';

        // Verborgen commentaarsectie
        echo '<div class="new-comment-section" id="comment-section-' . $row["id"] . '">
                <textarea class="new-comment"></textarea>
                <input type="hidden" name="tasks_id" class="task-id" value="' . $row['id'] . '">
                <button class="add-comment">Voeg commentaar toe</button>
              </div>';

        echo '</div>'; // sluit .task
    }
} else {
    echo "No tasks found.";
}

$conn->close();
?>

<script>
    function toggleCommentSection(taskId) {
        var commentSection = document.getElementById('comment-section-' + taskId);
        if (commentSection.style.display === "none" || commentSection.style.display === "") {
            commentSection.style.display = "block";
        } else {
            commentSection.style.display = "none";
        }
    }
</script>