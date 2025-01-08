<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>myPHP | Calculator</title>
</head>
<body>
<h1>php Calculator</h1>

<form action="" method="POST">
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
    <button type="submit" name="submit">Calculate</button>
</form>

</body>
</html>
