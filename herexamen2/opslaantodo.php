<?php
session_start(); // Start de sessie om feedback op te slaan

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connection.php';

$feedback = ''; // Variabele voor feedbackmelding

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $status = $_POST['status'];
    $due_time = isset($_POST['due_time']) ? $_POST['due_time'] : null;

    // Controleer of de beschrijving al bestaat
    $checkSql = "SELECT id FROM tasks WHERE description = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $description);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // Beschrijving bestaat al
        $feedback = "Error: Een taak met deze beschrijving bestaat al.";
    } else {
        // Voeg de nieuwe taak toe
        $sql = "INSERT INTO tasks (description, status, due_time) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $description, $status, $due_time);

        if ($stmt->execute()) {
            $_SESSION['feedback'] = "New record created successfully";
            // Redirect naar dezelfde pagina om de feedback weer te geven
            header("Location: dashboard.php");
            exit();
        } else {
            $feedback = "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
    }

    $checkStmt->close();
}

$conn->close();

// Sla de feedback op in de sessie en verwijder de sessie na gebruik
if (!empty($feedback)) {
    $_SESSION['feedback'] = $feedback;
    header("Location: todoaanmaken.php?feedback=" . urlencode($feedback));
    exit();
}
?>