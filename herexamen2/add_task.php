<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["list_id"]) && isset($_POST["description"])) {
        $list_id = $_POST["list_id"];
        $description = trim($_POST["description"]);

        $stmt = $conn->prepare("INSERT INTO tasks (list_id, description) VALUES (?, ?)");
        $stmt->bind_param("is", $list_id, $description);

        if ($stmt->execute()) {
            echo "Task added successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>