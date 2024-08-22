<?php
// Zet error reporting aan voor alle fouten
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verbind met de MySQL database
include 'db_connection.php';

// Controleer of het formulier is ingediend en de benodigde gegevens zijn ontvangen
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST["email"]) && isset($_POST["password"])) {
		$email = trim($_POST["email"]);
		$password = trim($_POST["password"]);

		// Controleer of de variabelen leeg zijn
		if (empty($email) || empty($password)) {
			echo '<script>alert("Email or password is empty.");</script>';
		} else {
			// Bereid de SQL query voor met een voorbereide statement
			$stmt = $conn->prepare("SELECT password FROM signup WHERE email = ?");
			if ($stmt === false) {
				die("Prepare failed: " . $conn->error);
			}

			$stmt->bind_param("s", $email);
			$stmt->execute();
			$stmt->bind_result($hashed_password);
			$stmt->fetch();
			$stmt->close();

			// Controleer of het wachtwoord correct is
			if (password_verify($password, $hashed_password)) {
				echo '<script> window.location.href = "dashboard.php";</script>';
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

$conn->close();
?>