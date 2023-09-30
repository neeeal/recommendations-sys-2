const express = require('express');
const app = express();
const port = 3000; // Set your desired port number
const cors = require('cors');
// Middleware to parse JSON data
app.use(express.json());

// Serve your JSON data
const jsonData = require('./recommendations.json');
app.use(cors());
// Define API routes
app.get('/api/data', (req, res) => {
  res.json(jsonData);
});

// Start the server
app.listen(port, () => {
  console.log(`Server is running on port ${port}`);
});
