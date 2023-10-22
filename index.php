<!DOCTYPE html>
<html>
<head>
    <title>Movie Recommendations</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@2.x.x/dist/alpine.js"></script>
</head>
<body>
    <?php echo(1+1); ?>
    <!-- Fetch and display using Alpine.js -->
    <div x-data="{
        selectedMovie:null,
        movie: null,
        movieTitle: '',
        async getMovies() {
            const response = await fetch('https://flask-production-2296.up.railway.app/movies', {
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
                const response = await fetch('https://flask-production-2296.up.railway.app/movies', {
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
                this.movie = json.data;
                this.selectedMovie=json.movie;
            }
        },
        async search(title){
            const response = await fetch(`https://flask-production-2296.up.railway.app/movies/${title}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });
               
                const json = await response.json();
                this.movie = json;
         
        }
    }" x-init="getMovies()">
        <input x-model="movieTitle" type="text" placeholder="Enter movie title">
        <button @click="search(movieTitle);selectedMovie=null">Search na di maganda</button>

        <template x-if="selectedMovie != null">
            <div>
                <h1 x-text="selectedMovie.title"></h1>
                <p x-text="selectedMovie.movie_id"></p>
                <p ></p>
                <p x-text="selectedMovie.description"></p>
            </div>
        </template>

        <template x-if="selectedMovie != null">
            <h3>More Like <span x-text="selectedMovie.title"></span></h3>
        </template>

        <template x-if="selectedMovie==null">
            <h1>Browse IMDB Movies</h1>
        </template>
        <template x-for="(item, index) in movie" :key="index">
            <div>
                <button x-text="item.title" @click="getRecommendations(item.title, item.movie_id)"></button>
                <p x-text="item.description"></p>
                <p x-text="item.movie_id"></p>
                <p x-text="item.genre"></p>
                <p x-text="item.date"></p>
                <template x-if="selectedMovie!=null">
                    <p x-text="Math.floor(parseFloat(item.score) * 100) + '%'"></p>
                </template>
               
            </div>
        </template>
    </div>
</body>
</html>
