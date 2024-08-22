<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connection.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
} else {
    echo "error";
}

$conn->close();
?>