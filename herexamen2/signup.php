<?php
// Zet error reporting aan voor alle fouten
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Variabele voor het statusbericht
$status_message = '';

// Verwerk de formulierinvoer
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	include 'db_connection.php';

	// Ontvang de waarden van het formulier
	if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["password"])) {
		$name = trim($_POST["name"]);
		$email = trim($_POST["email"]);
		$password = trim($_POST["password"]);

		// Hash het wachtwoord met bcrypt
		$password_hashed = password_hash($password, PASSWORD_BCRYPT);

		// Bereid de SQL query voor
		$stmt = $conn->prepare("INSERT INTO signup (name, email, password) VALUES (?, ?, ?)");
		if ($stmt === false) {
			die("Prepare failed: " . $conn->error);
		}
		$stmt->bind_param("sss", $name, $email, $password_hashed);

		// Voer de query uit en controleer of deze is geslaagd
		if ($stmt->execute() === TRUE) {
			header("Location: index.php");
			$status_message = 'Account succesvol aangemaakt!';
		} else {
			$status_message = 'Fout bij aanmaken account: ' . $stmt->error;
		}

		// Sluit de statement en de verbinding
		$stmt->close();
		$conn->close();
	} else {
		$status_message = 'Vul alle velden in.';
	}
}
?>