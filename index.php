<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Recommendations</title>
</head>
<body>
    <div>
        <form method="POST" action="">
            <label for="movie-title">Enter Movie Title:</label>
            <input type="text" name="movie_title" id="movie-title" placeholder="Avatar: The Way of Water">
            <input type="submit" name="get-recommendations" value="Get Recommendations">
        </form>
    </div>
    <div id="data">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $movie_title = $_POST['movie_title'];

            $api_url = 'http://127.0.0.1:5000/recommend';
            $request_data = [
                'movie_title' => $movie_title,
            ];

            $ch = curl_init($api_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $recommendations = json_decode($response, true);

            if ($recommendations) {
                echo "Recommendations:<br>";
                foreach ($recommendations as $recommendation) {
                    echo $recommendation . "<br>";
                }
            } else {
                echo "No recommendations found.";
            }
        }
        ?>
    </div>
</body>
</html>
