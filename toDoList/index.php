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
    $file = __DIR__ . '/tasks.txt';

    if (!file_exists($file)) {
        file_put_contents($file, ''); 
    }

    $tasks = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
        $task = trim($_POST['task']); 
        if (!empty($task)) {
            file_put_contents($file, $task . PHP_EOL, FILE_APPEND | LOCK_EX); 
            header("Location: " . $_SERVER['PHP_SELF']); 
            exit;
        }
    }

    if (!empty($tasks)) {
        echo "<h2>Your Todo List:</h2>";
        echo "<ul>";
        foreach ($tasks as $task) {
            echo "<li>" . htmlspecialchars($task) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No tasks yet. Add your first task above!</p>";
    }
    ?>
</body>
</html>

<!-- run the followinf code to make it work.. its not working to create by itself currentlly -->
<!-- chmod 666 tasks.txt -->
