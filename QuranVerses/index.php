<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Quote Generator</title>
</head>
<body>
    <?php
    $quotes = [
        ["quote" => "The only way to do great work is to love what you do.", "author" => "Steve Jobs"],
        ["quote" => "Success is not the key to happiness. Happiness is the key to success.", "author" => "Albert Schweitzer"],
        ["quote" => "Don't watch the clock; do what it does. Keep going.", "author" => "Sam Levenson"],
        ["quote" => "Keep your face always toward the sunshine—and shadows will fall behind you.", "author" => "Walt Whitman"],
        ["quote" => "Believe you can and you're halfway there.", "author" => "Theodore Roosevelt"]
    ];

    $randomIndex = array_rand($quotes);
    $randomQuote = $quotes[$randomIndex];
    ?>
    <div>
        <div>"<?php echo $randomQuote['quote']; ?>"</div>
        <div>- <?php echo $randomQuote['author']; ?></div>
    </div>
    <form method="post">
        <button type="submit">Get Random Quote</button>
    </form>
</body>
</html>
