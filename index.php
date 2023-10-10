<!DOCTYPE html>
<html>
<head>
    <title>Movie Recommendations</title>
</head>
<body>
    <form method="POST" action="">
        <label for="movie-title">Enter Movie Title:</label>
        <input type="text" name="movie_title" id="movie-title" placeholder="Avatar: The Way of Water">
        <input type="submit" name="get-recommendations" value="Get Recommendations">
    </form>
    <div id="data"></div>

<!-- #1 working method for fetching recommendation -->
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
    <!-- #2 working method for fetching recommendation -->
    <!-- <script>
        document.getElementById('get-recommendations').addEventListener('click', function () {
            const movieTitle = document.getElementById('movie-title').value;
            document.getElementById('data').innerHTML = "Loading...";

            fetch('http://127.0.0.1:5000/recommend', {
                method: 'POST',
                body: JSON.stringify({ movie_title: movieTitle }),
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                recommendations=data
                // Render recommendations using PHP in the HTML
                document.getElementById('data').innerHTML = data.length > 0
                    ? "Recommendations:<br>" + data.join("<br>")
                    : "No recommendations found.";
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script> -->
</body>
</html>
