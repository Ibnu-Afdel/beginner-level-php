<?php
session_start();

// Initialize score and progress if not set
if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 0;
}

// Fetch Surah names from the API
$surahApiUrl = "https://api.alquran.cloud/v1/surah";
$surahResponse = file_get_contents($surahApiUrl);
$surahData = json_decode($surahResponse, true);

$allSurahNames = [];
if ($surahData['status'] === "OK") {
    foreach ($surahData['data'] as $surah) {
        $allSurahNames[] = $surah['englishName'];
    }
}

// Generate a random Ayah if not already set
if (!isset($_SESSION['current_ayah'])) {
    $_SESSION['current_ayah'] = rand(1, 6236);
}

// Fetch Ayah details
$currentAyah = $_SESSION['current_ayah'];
$apiUrlSahih = "https://api.alquran.cloud/v1/ayah/{$currentAyah}/en.sahih";
$responseSahih = file_get_contents($apiUrlSahih);

if ($responseSahih !== false) {
    $dataSahih = json_decode($responseSahih, true);
    if ($dataSahih['status'] === "OK") {
        $_SESSION['ayah_text'] = $dataSahih['data']['text'];
        $_SESSION['correct_answer'] = $dataSahih['data']['surah']['englishName'];
        $_SESSION['ayah_number'] = $dataSahih['data']['numberInSurah'];
    }
}

// Select 4 random Surahs (including the correct one)
$options = [$_SESSION['correct_answer']];
while (count($options) < 4) {
    $randomSurah = $allSurahNames[array_rand($allSurahNames)];
    if (!in_array($randomSurah, $options)) {
        $options[] = $randomSurah;
    }
}
shuffle($options);

// Handle user guess
if (isset($_POST['submit_guess'])) {
    $userGuess = $_POST['surah_name'];
    if ($userGuess === $_SESSION['correct_answer']) {
        $_SESSION['score'] += 1;
        $resultMessage = "<p class='result-message correct'>Correct! +1 point.</p>";
    } else {
        $resultMessage = "<p class='result-message incorrect'>Incorrect! The correct answer was: <strong>{$_SESSION['correct_answer']}</strong>.</p>";
    }

    // Generate a new Ayah for the next round
    $_SESSION['current_ayah'] = rand(1, 6236);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quran Quiz</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Quran Quiz</h1>
            <p>Test your knowledge of the Quran!</p>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="game-container">
                <h2>Guess the Surah</h2>
                <p><strong>Ayah Text:</strong> <?= $_SESSION['ayah_text']; ?></p>
                <form method="POST">
                    <label for="surah_name">Select Surah:</label>
                    <div class="options">
                        <?php foreach ($options as $option): ?>
                            <label>
                                <input type="radio" name="surah_name" value="<?= $option; ?>" required> <?= $option; ?>
                            </label><br>
                        <?php endforeach; ?>
                    </div>
                    <br>
                    <button type="submit" name="submit_guess">Submit Guess</button>
                </form>
                <?= $resultMessage ?? ''; ?>
                <p class="score"><strong>Your Score:</strong> <?= $_SESSION['score']; ?></p>
            </div>
        </div>
    </main>
</body>
</html>