<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "herexamen";

// Maak verbinding met de MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer de verbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

echo "Verbinding geslaagd!<br>";

// Test query om de eerste 5 rijen op te halen
$sql = "SELECT * FROM tasks LIMIT 5";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"] . " - Title: " . $row["description"] . " - Deadline: " . $row["due_time"] . "<br>";
    }
} else {
    echo "Geen resultaten gevonden.";
}

$conn->close();
?>