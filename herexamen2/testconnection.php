<?php
// Zet error reporting aan voor alle fouten
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Probeer een eenvoudige echo
echo "PHP werkt!";
?>
<!DOCTYPE html>
<html>

<head>
    <title>SignUp and Login</title>
</head>

<body>
    <h1>Formulier</h1>
    <form action="" method="POST">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">SignUp</button>
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "Formulier is verzonden.<br>";
    }
    ?>
</body>

</html>