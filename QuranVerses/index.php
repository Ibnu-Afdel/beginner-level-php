<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Quranic Verse</title>
</head>
<body>
    <?php
    $apiUrl = "https://api.alquran.cloud/v1/ayah/random/en.sahih";

    $response = file_get_contents($apiUrl);

    if ($response !== false) {
        $data = json_decode($response, true);

        if ($data['status'] === "OK") {
            $ayahText = $data['data']['text'];
            $surahName = $data['data']['surah']['englishName'];
            $ayahNumber = $data['data']['numberInSurah'];

            echo "<h1>Random Quranic Verse </h1>";
            echo "<h2>Sahih International Translation</h2>";
            echo "<p><strong>Surah:</strong> $surahName</p>";
            echo "<p><strong>Ayah Number:</strong> $ayahNumber</p>";
            echo "<p><strong>Translation:</strong> $ayahText</p>";
        } else {
            echo "<p>Error: Unable to retrieve the Ayah. Please try again later.</p>";
        }
    } else {
        echo "<p>Error: Unable to connect to the API. Please check your internet connection.</p>";
    }
    ?>
</body>
</html>
