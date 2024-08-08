<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "herexamen";

    // Maak verbinding met de MySQL database via MySQLi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Controleer of de verbinding succesvol is
    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }

    // Haal de input op via POST in plaats van JSON
    $todoId = $_POST['tasks_id'] ?? null;
    $comment = $_POST['comment'] ?? null;

    var_dump($todoId, $comment); // Debugging: Toon de ontvangen waarden

    // Controleer of de input geldig is
    if (empty($todoId) || empty($comment)) {
        throw new Exception('Invalid input: tasks_id or comment is missing');
    }

    // Bereid de SQL voor en voer deze uit met MySQLi
    $stmt = $conn->prepare("INSERT INTO comments (tasks_id, comment) VALUES (?, ?)");
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }

    // Bind de parameters en voer de query uit
    $stmt->bind_param("is", $todoId, $comment);
    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }

    // Succesvolle respons
    echo json_encode(['success' => true]);

    // Sluit de statement en de verbinding
    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    // Foutafhandeling met gedetailleerd foutbericht
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>