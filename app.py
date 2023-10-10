from flask import Flask, request, jsonify
from flask_cors import CORS  # Import CORS from flask_cors
import pandas as pd
from sklearn.metrics.pairwise import cosine_similarity
from nltk.corpus import stopwords
import nltk

app = Flask(__name__)
CORS(app)  # Enable CORS for your Flask app

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
    recommended_movies = [data['names'].iloc[i[0]] for i in similar_movies]
    return recommended_movies

@app.route('/recommend', methods=['POST'])
def recommend_movies():
    data = request.get_json()
    movie_title = data['movie_title']

    recommendations = get_movie_recommendations(movie_title, cosine_sim)
    
    return jsonify(recommendations)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
