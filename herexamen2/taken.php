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

// Verkrijg de sorteeroptie uit de URL (standaard op 'due_time_asc' als geen optie is geselecteerd)
$sortOption = isset($_GET['sortOption']) ? $_GET['sortOption'] : 'due_time_asc';

// Bepaal de ORDER BY clausule op basis van de sorteeroptie
switch ($sortOption) {
    case 'description_asc':
        $orderBy = 'description ASC';
        break;
    case 'description_desc':
        $orderBy = 'description DESC';
        break;
    case 'due_time_asc':
        $orderBy = 'CASE WHEN due_time IS NULL OR due_time = \'\' THEN \'9999-12-31\' ELSE due_time END ASC';
        break;
    case 'due_time_desc':
        $orderBy = 'CASE WHEN due_time IS NULL OR due_time = \'\' THEN \'9999-12-31\' ELSE due_time END DESC';
        break;
    default:
        $orderBy = 'CASE WHEN due_time IS NULL OR due_time = \'\' THEN \'9999-12-31\' ELSE due_time END ASC';
}

// SQL-query om taken op te halen en te sorteren
$sql = "SELECT id, description, due_time 
        FROM tasks 
        WHERE status='not_done' 
        ORDER BY $orderBy";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dueTime = $row["due_time"];

        // HTML-output
        echo '<div class="task" data-id="' . $row["id"] . '">';
        echo '<div class="task-header">';
        echo '<img src="images\checkmark.svg.png" alt="Mark as done" class="checkmark" onclick="updateTaskStatus(' . $row["id"] . ', \'done\')">';
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

<script src="script.js"></script>