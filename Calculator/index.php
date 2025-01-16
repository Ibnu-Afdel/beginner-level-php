<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>myPHP | Calculator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>PHP Calculator</h1>
        <form method="POST">
            <label for="num1">Number 1:</label>
            <input type="number" name="num1" id="num1" required>

            <label for="num2">Number 2:</label>
            <input type="number" name="num2" id="num2" required>

            <label for="operation">Select Operation:</label>
            <select name="operation" id="operation">
                <option value="add">Add</option>
                <option value="subtract">Subtract</option>
                <option value="multiply">Multiply</option>
                <option value="divide">Divide</option>
            </select>

            <button name="submit">Calculate</button>
        </form>

        <?php 
        if (isset($_POST["submit"])) {
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
                        echo "<div class='result'>Error: Cannot divide by zero.</div>";
                        return;
                    }
                    $result = $num1 / $num2;
                    break;
                default:
                    echo "<div class='result'>Invalid operation selected.</div>";
                    return;
            }

            echo "<div class='result'>Result: $result</div>";
        } 
        ?>
    </div>
</body>
</html>
