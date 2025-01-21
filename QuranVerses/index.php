<?php
session_start();

if (!isset($_SESSION['current_ayah'])) {
    $_SESSION['current_ayah'] = rand(1, 6236);
}

if (isset($_POST['random'])) {
    $_SESSION['current_ayah'] = rand(1, 6236);
}

if (isset($_POST['next'])) {
    if ($_SESSION['current_ayah'] < 6236) {
        $_SESSION['current_ayah'] += 1;
    }
}

if (isset($_POST['prev'])) {
    if ($_SESSION['current_ayah'] > 1) {
        $_SESSION['current_ayah'] -= 1;
    }
}

$currentAyah = $_SESSION['current_ayah'];

$apiUrlSahih = "https://api.alquran.cloud/v1/ayah/{$currentAyah}/en.sahih";
$apiUrlAmharic = "https://api.alquran.cloud/v1/ayah/{$currentAyah}/am.sadiq";

$responseSahih = file_get_contents($apiUrlSahih);
$responseAmharic = file_get_contents($apiUrlAmharic);

$ayahDetails = '';
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
    <title>Quran Verse</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Quran Verse Explorer</h1>
            <p>Discover random Quranic verses with translations in English and Amharic.</p>
        </div>
    </header>

    <main>
        <div class="container">
            <?= $ayahDetails; ?>
            <div class="action-container">
                <form method="POST">
                    <button type="submit" name="random">Get Random Ayah</button>
                    <button type="submit" name="prev">Previous Ayah</button>
                    <button type="submit" name="next">Next Ayah</button>
                </form>
            </div>
        </div>
    </main>

    <section class="disclaimer">
        <div class="container">
            <p>Please read with caution. If there are any mistakes, feel free to contact me at <strong>your_email@example.com</strong>.</p>
        </div>
    </section>
</body>
</html>
