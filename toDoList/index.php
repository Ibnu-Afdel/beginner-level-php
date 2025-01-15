<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Todo List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
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

    
    if (isset($_GET['delete'])) {
        $deleteIndex = (int)$_GET['delete']; 
        if (isset($tasks[$deleteIndex])) {
            unset($tasks[$deleteIndex]); 
            file_put_contents($file, implode(PHP_EOL, $tasks) . PHP_EOL); // Save updated tasks
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_task'])) {
        $editIndex = (int)$_POST['task_index']; 
        $newTask = trim($_POST['task']); 
        if (isset($tasks[$editIndex]) && !empty($newTask)) {
            $tasks[$editIndex] = $newTask; 
            file_put_contents($file, implode(PHP_EOL, $tasks) . PHP_EOL);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    if (!empty($tasks)) {
        echo "<h2>Your Todo List:</h2>";
        echo "<ul>";
        foreach ($tasks as $key => $task) {
            echo "<li>";
            echo htmlspecialchars($task);
            echo " <a href='?delete=$key'>Delete</a>";
            echo " <a href='?edit=$key' >Edit</a>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No tasks yet. Add your first task above!</p>";
    }

    if (isset($_GET['edit'])) {
        $editIndex = (int)$_GET['edit'];
        if (isset($tasks[$editIndex])) {
            $currentTask = htmlspecialchars($tasks[$editIndex]);
            echo "
            <h2>Edit Task</h2>
            <form action='' method='POST'>
                <input type='hidden' name='task_index' value='$editIndex'>
                <input type='text' name='task' value='$currentTask' required>
                <button type='submit' name='edit_task'>Update Task</button>
            </form>
            ";
        }
    }
    ?>
    </div>
</body>
</html>

