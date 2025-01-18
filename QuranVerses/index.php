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

if ($responseSahih !== false && $responseAmharic !== false) {
    $dataSahih = json_decode($responseSahih, true);
    $dataAmharic = json_decode($responseAmharic, true);

    if ($dataSahih['status'] === "OK" && $dataAmharic['status'] === "OK") {
        $ayahTextSahih = $dataSahih['data']['text'];
        $surahNameSahih = $dataSahih['data']['surah']['englishName'];
        $ayahNumberSahih = $dataSahih['data']['numberInSurah'];

        $ayahTextAmharic = $dataAmharic['data']['text'];

        echo "<h1>Random Quranic Verse</h1>";
        echo "<h2>Sahih International (English):</h2>";
        echo "<p><strong>Surah:</strong> $surahNameSahih</p>";
        echo "<p><strong>Ayah Number:</strong> $ayahNumberSahih</p>";
        echo "<p><strong>Translation (Sahih International):</strong> $ayahTextSahih</p>";

        echo "<h2>Amharic Translation:</h2>";
        echo "<p><strong>Translation (Amharic):</strong> $ayahTextAmharic</p>";

    } else {
        echo "<p>Error: Unable to retrieve the Ayah. Please try again later.</p>";
    }
} else {
    echo "<p>Error: Unable to connect to the API. Please check your internet connection.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Quranic Verse</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <form method="POST">
        <button type="submit" name="random">Get Random Ayah</button>
        <button type="submit" name="prev">Previous Ayah</button>
        <button type="submit" name="next">Next Ayah</button>
    </form>

</body>
</html>
