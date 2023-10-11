<!DOCTYPE html>
<html>
<head>
    <title>Movie Recommendations</title>
     <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
<!-- fetch and display using alpineJS -->
    <!-- initialized and declare variables and functions like getrecommendations with fetch api -->
    <div
        x-data="{
            movie: null,
            movieTitle: '',
        
            getRecommendations() {
                if (this.movieTitle) {
                    fetch('http://127.0.0.1:5000/recommend', {
                        method: 'POST',
                        body: JSON.stringify({ movie_title: this.movieTitle }),
                        headers: {
                            'Content-Type': 'application/json',
                        },
                    })
                    .then((response) => response.json())
                    .then((json) => this.movie = json);
                }
            }
        }" 
        x-init="getRecommendations()">
        <!-- textbox for input movie title -->
        <input x-model="movieTitle" type="text" placeholder="Enter movie titlle">
        <button @click="getRecommendations">Get Reco</button>
        <!-- displaying title and description using for loop -->
        <template x-for="(item, index) in movie" :key="index">
            <div>
                <h4 x-text="item.title"></h4>
                <p x-text="item.description"></p>
            </div>
         </template>
    </div>

<!-- #2.b working frontend for js -->    
<!-- <input type="text" id="movie-title" placeholder="Enter a movie title">
    <button id="get-recommendations">Get Recommendations</button>
    <div id="data">
    </div> -->
<!-- #1 working method for fetching recommendation -->
    <?php
        // if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //     $movie_title = $_POST['movie_title'];

        //     $api_url = 'http://127.0.0.1:5000/recommend';
        //     $request_data = [
        //         'movie_title' => $movie_title,
        //     ];

        //     $ch = curl_init($api_url);
        //     curl_setopt($ch, CURLOPT_POST, true);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_data));
        //     curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     $response = curl_exec($ch);
        //     curl_close($ch);

        //     $recommendations = json_decode($response, true);

        //     if ($recommendations) {
        //         echo "Recommendations:<br>";
        //         foreach ($recommendations as $recommendation) {
        //             echo $recommendation . "<br>";
        //         }
        //     } else {
        //         echo "No recommendations found.";
        //     }
        // }
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
    </script>  -->
</body>
</html>
