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
$options = [$_SESSION['correct_answer']]; // Always include the correct answer
while (count($options) < 4) {
    $randomSurah = $allSurahNames[array_rand($allSurahNames)];
    if (!in_array($randomSurah, $options)) {
        $options[] = $randomSurah;
    }
}
shuffle($options);

// Handle user guess
if (isset($_POST['surah_name'])) {
    $userGuess = $_POST['surah_name'];

    // Store previous Ayah details for validation
    $previousAyahText = $_SESSION['ayah_text'];
    $previousCorrectAnswer = $_SESSION['correct_answer'];
    $previousAyahNumber = $_SESSION['ayah_number'];

    // Validate the user's guess against the previous Ayah
    if ($userGuess === $previousCorrectAnswer) {
        $_SESSION['score'] += 1;
        $resultMessage = "
            <p class='result-message correct'>
                Correct! +1 point.<br>
                <strong>Your Answer:</strong> $userGuess<br>
                <strong>Correct Answer:</strong> $previousCorrectAnswer (Ayah $previousAyahNumber)<br>
                <strong>Ayah Text:</strong> $previousAyahText
            </p>";
    } else {
        $resultMessage = "
            <p class='result-message incorrect'>
                Incorrect!<br>
                <strong>Your Answer:</strong> $userGuess<br>
                <strong>Correct Answer:</strong> $previousCorrectAnswer (Ayah $previousAyahNumber)<br>
                <strong>Ayah Text:</strong> $previousAyahText
            </p>";
    }

    // Generate a new Ayah for the next round
    $_SESSION['current_ayah'] = rand(1, 6236);

    // Fetch new Ayah details
    $newAyah = $_SESSION['current_ayah'];
    $newApiUrlSahih = "https://api.alquran.cloud/v1/ayah/{$newAyah}/en.sahih";
    $newResponseSahih = file_get_contents($newApiUrlSahih);

    if ($newResponseSahih !== false) {
        $newDataSahih = json_decode($newResponseSahih, true);
        if ($newDataSahih['status'] === "OK") {
            $_SESSION['ayah_text'] = $newDataSahih['data']['text'];
            $_SESSION['correct_answer'] = $newDataSahih['data']['surah']['englishName'];
            $_SESSION['ayah_number'] = $newDataSahih['data']['numberInSurah'];
        }
    }

    // Regenerate options for the new Ayah
    $options = [$_SESSION['correct_answer']]; // Always include the correct answer
    while (count($options) < 4) {
        $randomSurah = $allSurahNames[array_rand($allSurahNames)];
        if (!in_array($randomSurah, $options)) {
            $options[] = $randomSurah;
        }
    }
    shuffle($options);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quran Quiz</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Auto-submit the form when a choice is selected
        function autoSubmit() {
            document.getElementById('quizForm').submit();
        }
    </script>
</head>
<body>
    <header>
        <div class="container">
            <h1>Quran Quiz</h1>
            <p>Test your knowledge of the Quran!</p>
            <a href="index.php" class="back-button">Back to Home</a>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="game-container">
                <h2>Guess the Surah</h2>
                <p><strong>Ayah Text:</strong> <?= $_SESSION['ayah_text']; ?></p>
                <form method="POST" id="quizForm">
                    <label for="surah_name">Select Surah:</label>
                    <div class="options">
                        <?php foreach ($options as $option): ?>
                            <label>
                                <input type="radio" name="surah_name" value="<?= $option; ?>" required onclick="autoSubmit()"> <?= $option; ?>
                            </label><br>
                        <?php endforeach; ?>
                    </div>
                </form>
                <?= $resultMessage ?? ''; ?>
                <p class="score"><strong>Your Score:</strong> <?= $_SESSION['score']; ?></p>
            </div>
        </div>
    </main>
</body>
</html>