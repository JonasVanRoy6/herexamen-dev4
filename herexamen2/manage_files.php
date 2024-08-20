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

// Verwerk het uploadformulier
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"]) && isset($_POST["task_id"])) {
    $taskId = $_POST["task_id"];
    $file = $_FILES["file"];
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($file["name"]);

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        $fileName = $file["name"];
        $sql = "INSERT INTO files (task_id, file_name, file_path) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $taskId, $fileName, $targetFile);

        if ($stmt->execute()) {
            echo "File uploaded and linked successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Verwijder een bestand
if (isset($_GET['delete'])) {
    $fileId = $_GET['delete'];

    // Haal het bestandspad op
    $sql = "SELECT file_path FROM files WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $result = $stmt->get_result();
    $fileRow = $result->fetch_assoc();

    if ($fileRow) {
        $filePath = $fileRow['file_path'];

        // Verwijder het bestand van de server
        if (unlink($filePath)) {
            // Verwijder het bestandspad uit de database
            $sql = "DELETE FROM files WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $fileId);
            $stmt->execute();
            echo "File deleted successfully.";
        } else {
            echo "Error deleting file from server.";
        }
    } else {
        echo "File not found.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Files</title>
</head>

<body>
    <h1>Manage Files</h1>

    <form action="manage_files.php" method="post" enctype="multipart/form-data">
        <label for="task">Select Task:</label>
        <select name="task_id" id="task">
            <?php
            // Verbind met de database
            $conn = new mysqli($servername, $username, $password, $dbname);
            $result = $conn->query("SELECT id, description FROM tasks WHERE status='not_done'");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['description']}</option>";
                }
            }
            $conn->close();
            ?>
        </select>
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>

    <h2>Attached Files</h2>
    <div id="attached-files">
        <?php
        // Toont alle bestanden en geeft de mogelijkheid om ze te verwijderen
        $conn = new mysqli($servername, $username, $password, $dbname);

        $sql = "SELECT f.id, f.file_name, f.file_path, t.description FROM files f
                JOIN tasks t ON f.task_id = t.id
                ORDER BY t.description";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='file-item'>";
                echo "<p><a href='" . htmlspecialchars($row["file_path"]) . "'>" . htmlspecialchars($row["file_name"]) . "</a> (Task: " . htmlspecialchars($row["description"]) . ")</p>";
                echo "<a href='manage_files.php?delete=" . $row["id"] . "' onclick='return confirm(\"Are you sure?\");'>Delete</a>";
                echo "</div>";
            }
        } else {
            echo "No files attached.";
        }

        $conn->close();
        ?>
    </div>
</body>

</html>