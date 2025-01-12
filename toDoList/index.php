<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Todo List</title>
</head>
<body>
    <h1>My PHP Todo List</h1>

    <form method="POST">
        <input type="text" name="task" placeholder="Enter a new task" required>
        <button type="submit" name="add_task">Add Task</button>
    </form>

    <?php
    $tasks = [];

    if (isset($_POST['add_task'])) {
        $task = $_POST['task'];
        if (!empty($task)) {
            $tasks[] = $task;
        }
    }

    if (!empty($tasks)) {
        echo "<h2>Your Todo List:</h2>";
        echo "<ul>";
        foreach ($tasks as $key => $task) {
            echo "<li>$task</li>";
        }
        echo "</ul>";
    }
    ?>
</body>
</html>
