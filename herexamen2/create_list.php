<?php
// Zet error reporting aan voor alle fouten
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'todolist.php';  // Zorg ervoor dat je de klasse laadt

$status_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connection.php';

    if (isset($_POST["name"]) && isset($_POST["description"])) {
        try {
            $list = new TodoList($_POST["name"], $_POST["description"]);
            $stmt = $conn->prepare("INSERT INTO lists (name, description) VALUES (?, ?)");
            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }
            $name = $list->getName();
            $description = $list->getDescription();
            $stmt->bind_param("ss", $name, $description);

            if ($stmt->execute() === TRUE) {
                $status_message = 'List successfully created!';
            } else {
                $status_message = 'Error creating list: ' . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            $status_message = $e->getMessage();
        }
    } else {
        $status_message = 'Please fill in all fields.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create List</title>
</head>

<body>
    <h1>Create a New List</h1>
    <form method="POST" action="create_list.php">
        <label for="name">List Name:</label><br>
        <input type="text" id="name" name="name"><br><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description"></textarea><br><br>
        <input type="submit" value="Create List">
    </form>
    <p><?php echo $status_message; ?></p>
</body>

</html>