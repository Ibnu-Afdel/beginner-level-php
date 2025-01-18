<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Quranic Verse (Sahih International & Amharic)</title>
</head>
<body>
    <?php
    $randomAyah = rand(1, 6236);

    $apiUrlSahih = "https://api.alquran.cloud/v1/ayah/{$randomAyah}/en.sahih";

    $apiUrlAmharic = "https://api.alquran.cloud/v1/ayah/{$randomAyah}/am.sadiq";

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
            $surahNameAmharic = $dataAmharic['data']['surah']['englishName'];
            $ayahNumberAmharic = $dataAmharic['data']['numberInSurah'];

            echo "<h1>Random Quranic Verse</h1>";
            echo "<h2>Sahih International (English):</h2>";
            echo "<p><strong>Surah:</strong> $surahNameSahih</p>";
            echo "<p><strong>Ayah Number:</strong> $ayahNumberSahih</p>";
            echo "<p><strong>Translation (Sahih International):</strong> $ayahTextSahih</p>";

            echo "<h2>Amharic Translation:</h2>";
            echo "<p><strong>Surah:</strong> $surahNameAmharic</p>";
            echo "<p><strong>Ayah Number:</strong> $ayahNumberAmharic</p>";
            echo "<p><strong>Translation (Amharic):</strong> $ayahTextAmharic</p>";
        } else {
            echo "<p>Error: Unable to retrieve the Ayah. Please try again later.</p>";
        }
    } else {
        echo "<p>Error: Unable to connect to the API. Please check your internet connection.</p>";
    }
    ?>
</body>
</html>
