from flask import Flask, request, jsonify
from flask_cors import CORS  # Import CORS from flask_cors
import pandas as pd
from sklearn.metrics.pairwise import cosine_similarity
from nltk.corpus import stopwords
import nltk
import pymysql

app = Flask(__name__)
CORS(app) 

# MySQL configuration
db = pymysql.connect(
    host='localhost',
    user='root',
    password='',
    db='movie_sys',
    cursorclass=pymysql.cursors.DictCursor  
)

# # Load and preprocess the data
with db.cursor() as cursor:
    cursor.execute('SELECT * FROM movies')
    data = cursor.fetchall()

    movie_overviews_orig = [row['overview'] for row in data]
    movie_overviews = [row['overview'] for row in data]
    
    movie_titles = [row['names'] for row in data]  # Change 'names' to 'titl

# Preprocess the movie overviews
    nltk.download("stopwords")
    stop_words = set(stopwords.words("english"))

    for n, name in enumerate(movie_overviews):
        temp = name.lower().split(" ")
        temp = [''.join([letter for letter in word if letter.isalnum()]) for word in temp]
        temp = [word for word in temp if word not in stop_words]
        temp = ' '.join(temp)
        movie_overviews[n] = temp

# Calculate cosine similarity
    from sklearn.feature_extraction.text import CountVectorizer

    vectorizer = CountVectorizer().fit_transform(movie_overviews)
    cosine_sim = cosine_similarity(vectorizer)

@app.route('/', methods=['POST','GET'])
def recommend_mysql_movies():
    if request.method == 'POST':
        data = request.get_json()
        movie_title = data['movie_title']
        
        recommendations = get_movie_recommendations(movie_title, cosine_sim)
        
        return jsonify(recommendations)
    
    if request.method == 'GET':
        
        with db.cursor() as cursor:
            # Execute an SQL query to fetch the list of movies
            cursor.execute('SELECT * FROM movies')
            
            # Fetch all the movie records
            data = cursor.fetchall()

        # Convert the MySQL result to a list of dictionaries
        movies = [{'title': row['names'], 'description': row['overview']} for row in data]

        return jsonify(movies)
            

# Function to get movie recommendations
def get_movie_recommendations(movie_title, similarity_matrix, num_recommendations=20):
    movie_idx = movie_titles.index(movie_title)
    similar_movies = list(enumerate(similarity_matrix[movie_idx]))
    similar_movies = sorted(similar_movies, key=lambda x: x[1], reverse=True)
    similar_movies = similar_movies[1:num_recommendations + 1]  # Exclude the movie itself
    recommended_movies = []
    
    # Calculate a recommendation score based on similarity (cosine similarity)
    for i in similar_movies:
        recommended_movie_title = movie_titles[i[0]]
        movie_description = movie_overviews_orig[i[0]]
        recommended_movies.append({'title': recommended_movie_title, 'description': movie_description, 'score':i[1]})
    return recommended_movies

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)