<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODO APP</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>TODO APP</h1>
            <a href="todoaanmaken.php"> Add</a>
        </div>
        <h2>Not Done</h2>
        <div id="not-done-tasks">
            <?php include 'taken.php'; ?>

        </div>
        <h2>Done</h2>
        <div id="done-tasks">
            <?php include 'takendone.php'; ?>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>