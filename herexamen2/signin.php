<?php
// Zet error reporting aan voor alle fouten
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verbind met de MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "herexamen";

$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer de verbinding
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Debugging: Print de inhoud van $_POST
// Zorg ervoor dat je dit alleen gebruikt voor debugging en verwijder het voor productie
echo '<pre>';
print_r($_POST);
echo '</pre>';

// Debugging: Print de request method
echo '<pre>';
echo 'Request Method: ' . $_SERVER["REQUEST_METHOD"];
echo '</pre>';

// Controleer of het formulier is ingediend en de benodigde gegevens zijn ontvangen
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST["email"]) && isset($_POST["password"])) {
		$email = trim($_POST["email"]);
		$password = trim($_POST["password"]);

		// Debugging: Controleer of de variabelen leeg zijn
		if (empty($email) || empty($password)) {
			echo '<script>alert("Email or password is empty.");</script>';
		} else {
			$salt = "herexamen";
			$password_encrypted = sha1($password . $salt);

			// Bereid de SQL query voor met een voorbereide statement
			$stmt = $conn->prepare("SELECT COUNT(*) as total FROM signup WHERE email = ? AND password = ?");
			if ($stmt === false) {
				die("Prepare failed: " . $conn->error);
			}

			$stmt->bind_param("ss", $email, $password_encrypted);
			$stmt->execute();
			$stmt->bind_result($total);
			$stmt->fetch();
			$stmt->close();
			$conn->close();

			// Controleer of het login succesvol was
			if ($total > 0) {
				echo '<script>alert("Login successful"); window.location.href = "dashboard.php";</script>';
			} else {
				echo '<script>alert("Login failed");</script>';
			}
		}
	} else {
		echo '<script>alert("Please fill all fields.");</script>';
	}
} else {
	echo '<script>alert("Invalid request method.");</script>';
}
?>