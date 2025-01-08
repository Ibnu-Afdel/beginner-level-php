<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>myPHP | Calculator</title>
</head>
<body>
<h1>php Calculator</h1>

<form  method="POST">
    <label for="num1">Number 1:</label>
    <input type="number" name="num1" id="num1" required><br><br>

    <label for="num2">Number 2:</label>
    <input type="number" name="num2" id="num2" required><br><br>

    <label for="operation">Select Operation:</label>
    <select name="operation" id="operation">
        <option value="add">Add</option>
        <option value="subtract">Subtract</option>
        <option value="multiply">Multiply</option>
        <option value="divide">Divide</option>
    </select><br><br>
    <button name="submit">Calculate</button>
</form>

<?php if (isset($_POST["submit"])) {
    // var_dump($_POST);
    $num1 = $_POST["num1"];
    $num2 = $_POST["num2"];
    $operation = $_POST["operation"];

    $result = 0;

    switch ($operation) {
        case "add":
            $result = $num1 + $num2;
            break;
        case "subtract":
            $result = $num1 - $num2;
            break;
        case "multiply":
            $result = $num1 * $num2;
            break;
        case "divide":
            if ($num2 == 0) {
                echo "Error: Cant divide by zero.";
                return;
            }
            $result = $num1 / $num2;
            break;
        default:
            echo "Invalid operation selected.";
            return;
    }

    echo "<h2>Result: $result</h2>";
} ?>
</body>
</html>
