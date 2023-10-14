# Movie Recommendation System using php and flask

This is a simple movie recommendation system using Flask, PHP, and a Flask-based API. Users can input a movie title, and the system will provide movie recommendations based on cosine similarity between movie overviews.

## Getting Started

Python version: Python 3.11.5

Follow these steps to clone and run the application on your local machine.

1. `` git clone https://github.com/KimberlyPangilinan/recommendations-sys.git 
      cd recommendations-sys
    ``
2. `` pip install flask flask-cors scikit-learn nltk pandas `` or  `pip install -r requirements.txt`
3.  Navigate to the recommendations-system directory. `` python app.py ``

    The Flask application will start on http://127.0.0.1:5000.

2. Start the PHP Application (Using XAMPP)

    Move the index.php file to your XAMPP's web root directory (e.g., C:\xampp\htdocs\recommendations-sys or your folder path).

    Import imdb_movies.sql to your database named movie_sys

    Start your XAMPP server.

    Open a web browser and access the PHP application at http://localhost/recommendations-sys/.

    Enter a movie title, click "Get Recommendations," and the app will display movie recommendations.
