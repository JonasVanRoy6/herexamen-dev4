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

if (isset($_GET['id'])) {
    $fileId = $_GET['id'];

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