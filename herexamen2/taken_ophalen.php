<?php
include 'db_connection.php';
echo "Taken_ophalen.php wordt uitgevoerd.<br>";

// Controleer of de sorteeroptie is doorgegeven en valideren
$valid_sort_options = [
    'description_asc',
    'description_desc',
    'due_time_asc',
    'due_time_desc'
];
$sort_option = isset($_GET['sortOption']) && in_array($_GET['sortOption'], $valid_sort_options) ? $_GET['sortOption'] : 'description_asc';

echo "Ontvangen sorteeroptie: " . htmlspecialchars($sort_option) . "<br>";

// Sorteeroptie verwerken
switch ($sort_option) {
    case "description_asc":
        $sort_query = "ORDER BY description ASC";
        break;
    case "description_desc":
        $sort_query = "ORDER BY description DESC";
        break;
    case "due_time_asc":
        $sort_query = "ORDER BY due_time ASC";
        break;
    case "due_time_desc":
        $sort_query = "ORDER BY due_time DESC";
        break;
    default:
        $sort_query = "ORDER BY description ASC";
}

echo "Ingebouwde query: " . htmlspecialchars($sort_query) . "<br>";

// SQL-query uitvoeren
$sql = "SELECT * FROM tasks $sort_query";
$result = $conn->query($sql);

if ($result === false) {
    die("Fout bij uitvoeren van query: " . htmlspecialchars($conn->error));
}

// Verwerken van de resultaten
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Voorbeeld van hoe je resultaten kunt tonen
        echo "ID: " . htmlspecialchars($row['id']) . " - Beschrijving: " . htmlspecialchars($row['description']) . " - Deadline: " . htmlspecialchars($row['due_time']) . "<br>";
    }
} else {
    echo "Geen resultaten gevonden.";
}

// Sluit de verbinding
$conn->close();
?>