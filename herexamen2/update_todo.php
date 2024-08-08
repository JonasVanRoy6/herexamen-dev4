<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "herexamen";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['id'];
$description = $_POST['description'];
$dueTime = $_POST['due_time'];
$status = isset($_POST['status']) ? 'done' : 'not_done';

$stmt = $conn->prepare("UPDATE tasks SET description = ?, due_time = ?, status = ? WHERE id = ?");
$stmt->bind_param("sssi", $description, $dueTime, $status, $id);

if ($stmt->execute()) {
    echo "Todo updated successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>