<?php
// Zet error reporting aan voor alle fouten
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Variabele voor het statusbericht
$status_message = '';

// Verwerk de formulierinvoer
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "herexamen";

	// Maak verbinding met de MySQL database
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Controleer de verbinding
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Ontvang de waarden van het formulier
	if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["password"])) {
		$name = $_POST["name"];
		$email = $_POST["email"];
		$password = $_POST["password"];
		$salt = "herexamen";
		$password_encrypted = sha1($password . $salt);

		// Bereid de SQL query voor
		$stmt = $conn->prepare("INSERT INTO signup (name, email, password) VALUES (?, ?, ?)");
		if ($stmt === false) {
			die("Prepare failed: " . $conn->error);
		}
		$stmt->bind_param("sss", $name, $email, $password_encrypted);

		// Voer de query uit en controleer of deze is geslaagd
		if ($stmt->execute() === TRUE) {
			header("Location: index.html");
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