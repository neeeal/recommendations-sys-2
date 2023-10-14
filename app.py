from flask import Flask, request, jsonify
from flask_cors import CORS  # Import CORS from flask_cors
import pandas as pd
from sklearn.metrics.pairwise import cosine_similarity
from nltk.corpus import stopwords
import nltk
import pymysql

app = Flask(__name__)
CORS(app)  # Enable CORS for your Flask app
# MySQL configuration
db = pymysql.connect(
    host='localhost',
    user='root',
    password='',
    db='movie_sys',
    cursorclass=pymysql.cursors.DictCursor  # Fetch results as dictionaries
)
# Load and preprocess the data
data = pd.read_csv('datasets/imdb_movies.csv')
summary = data['overview'].values

# Preprocess the movie overviews
nltk.download("stopwords")
stop_words = set(stopwords.words("english"))

for n, name in enumerate(summary):
    temp = name.lower().split(" ")
    temp = [''.join([letter for letter in word if letter.isalnum()]) for word in temp]
    temp = [word for word in temp if word not in stop_words]
    temp = ' '.join(temp)
    summary[n] = temp

# Calculate cosine similarity
from sklearn.feature_extraction.text import CountVectorizer

vectorizer = CountVectorizer().fit_transform(summary)
cosine_sim = cosine_similarity(vectorizer)

# Function to get movie recommendations
def get_movie_recommendations(movie_title, similarity_matrix, num_recommendations=10):
    movie_idx = data[data['names'] == movie_title].index[0]
    similar_movies = list(enumerate(similarity_matrix[movie_idx]))
    similar_movies = sorted(similar_movies, key=lambda x: x[1], reverse=True)
    similar_movies = similar_movies[1:num_recommendations + 1]  # Exclude the movie itself
    recommended_movies = []
    for i in similar_movies:
        recommended_movie_title = data['names'].iloc[i[0]]
        movies_descriptions = data['overview'].iloc[i[0]]
        recommended_movies.append({'title': recommended_movie_title, 'description': movies_descriptions})
    return recommended_movies

@app.route('/recommend', methods=['POST'])
def recommend_movies():
    data = request.get_json()
    movie_title = data['movie_title']

    recommendations = get_movie_recommendations(movie_title, cosine_sim)
    
    return jsonify(recommendations)

#try mysql connection
@app.route('/recommend_movies', methods=['GET'])
def recommend():
    try:
        with db.cursor() as cursor:
            # Execute an SQL query to fetch the list of movies
            cursor.execute('SELECT * FROM movies')
            
            # Fetch all the movie records
            data = cursor.fetchall()
            print(data)
    finally:
        db.close()  # Close the database connection

    # Convert the MySQL result to a list of dictionaries
    movies = [{'title': row['title'], 'description': row['summary']} for row in data]

    return jsonify(movies)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)