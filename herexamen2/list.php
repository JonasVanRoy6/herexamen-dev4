<form method="POST" action="add_task.php">
    <label for="list_id">Select List:</label>
    <select id="list_id" name="list_id">
        <?php
        include 'db_connection.php';

        $sql = "SELECT id, name FROM lists";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
            }
        }

        $conn->close();
        ?>
    </select>
    <br><br>
    <label for="description">Task Description:</label><br>
    <textarea id="description" name="description"></textarea><br><br>
    <input type="submit" value="Add Task">
</form>