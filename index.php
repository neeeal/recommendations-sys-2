<!DOCTYPE html>
<html>
<head>
    <title>Movie Recommendations</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@2.x.x/dist/alpine.js"></script>
</head>
<body>
    <!-- Fetch and display using Alpine.js -->
    <div x-data="{
        movie: null,
        movieTitle: '',
        async getMovies() {
            const response = await fetch('http://127.0.0.1:5000/', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            });
            const json = await response.json();
            this.movie = json;
        },
        async getRecommendations(title,id) {
            console.log(id,'fds', title)
            if (title) {
                const response = await fetch('http://127.0.0.1:5000/', {
                    method: 'POST',
                    body: JSON.stringify({ 
                        movie_title: title,
                        movie_id:  id
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });
               
                const json = await response.json();
                console.log(json[1].data)
                this.movie = json[1].data;
            }
        }
    }" x-init="getMovies()">
        <!-- Textbox for input movie title -->
        <input x-model="movieTitle" type="text" placeholder="Enter movie title">
        <button @click="getRecommendations(movieTitle)">Get Reco</button>
        <!-- Display title, description, genre, and date using a for loop -->
        <template x-for="(item, index) in movie" :key="index">
            <div>
                <button x-text="item.title" @click="getRecommendations(item.title, item.movie_id)"></button>
                <p x-text="item.description"></p>
                <p x-text="item.movie_id"></p>
                <p x-text="item.genre"></p>
                <p x-text="item.date"></p>
                <p x-text="item.score"></p>
            </div>
        </template>
    </div>
</body>
</html>
