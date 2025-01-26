<?php
session_start();

// Initialize score if not set
if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 0;
}

// Generate a random Ayah if not already set
if (!isset($_SESSION['current_ayah'])) {
    $_SESSION['current_ayah'] = rand(1, 6236);
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

// Fetch Ayah details
$currentAyah = $_SESSION['current_ayah'];
$apiUrlSahih = "https://api.alquran.cloud/v1/ayah/{$currentAyah}/en.sahih";
$apiUrlAmharic = "https://api.alquran.cloud/v1/ayah/{$currentAyah}/am.sadiq";

$responseSahih = file_get_contents($apiUrlSahih);
$responseAmharic = file_get_contents($apiUrlAmharic);

$ayahDetails = '';
$options = [];
$correctAnswer = '';
$resultMessage = '';

if ($responseSahih !== false && $responseAmharic !== false) {
    $dataSahih = json_decode($responseSahih, true);
    $dataAmharic = json_decode($responseAmharic, true);

    if ($dataSahih['status'] === "OK" && $dataAmharic['status'] === "OK") {
        $ayahTextSahih = $dataSahih['data']['text'];
        $surahNameSahih = $dataSahih['data']['surah']['englishName'];
        $ayahNumberSahih = $dataSahih['data']['numberInSurah'];

        $ayahTextAmharic = $dataAmharic['data']['text'];

        $ayahDetails = "
            <div class='verse-container'>
                <h2>Sahih International (English)</h2>
                <p><strong>Surah:</strong> $surahNameSahih</p>
                <p><strong>Ayah Number:</strong> $ayahNumberSahih</p>
                <p><strong>Translation:</strong> $ayahTextSahih</p>
            </div>
            <div class='translation-container'>
                <h2>Amharic Translation</h2>
                <p>$ayahTextAmharic</p>
            </div>";

        // Generate 4 options (1 correct, 3 random)
        $options = [$surahNameSahih];
        while (count($options) < 4) {
            $randomSurah = $allSurahNames[array_rand($allSurahNames)];
            if (!in_array($randomSurah, $options)) {
                $options[] = $randomSurah;
            }
        }
        shuffle($options);
        $correctAnswer = $surahNameSahih;

        // Handle user guess
        if (isset($_POST['submit_guess'])) {
            $userGuess = $_POST['surah_name'];
            if ($userGuess === $correctAnswer) {
                $_SESSION['score'] += 1;
                $resultMessage = "<p class='result-message correct'>Correct! +1 point.</p>";
            } else {
                $resultMessage = "<p class='result-message incorrect'>Incorrect! The correct answer was: <strong>$correctAnswer</strong>.</p>";
            }

            // Generate a new Ayah for the next round
            $_SESSION['current_ayah'] = rand(1, 6236);
        }
    } else {
        $ayahDetails = "<p>Error: Unable to retrieve the Ayah. Please try again later.</p>";
    }
} else {
    $ayahDetails = "<p>Error: Unable to connect to the API. Please check your internet connection.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quran Verse Quiz</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Quran Verse Quiz</h1>
            <p>Test your knowledge of the Quran!</p>
        </div>
    </header>

    <main>
        <div class="container">
            <?= $ayahDetails; ?>
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
            <?= $resultMessage; ?>
            <p class="score"><strong>Your Score:</strong> <?= $_SESSION['score']; ?></p>
        </div>
    </main>
</body>
</html>