const express = require('express');
const mysql = require('mysql2');

const app = express();
const port = 3000;

// Kết nối MySQL
const db = mysql.createConnection({
  host: 'mysql',
  user: 'root',
  password: 'root',
  database: 'ketqua_xoso'
});

db.connect((err) => {
  if (err) throw err;
  console.log('Connected to MySQL database.');
});

// Endpoint để lấy kết quả xổ số theo ngày
app.get('/ketqua_xoso/:date', (req, res) => {
  const date = req.params.date;

  const query = 'SELECT results FROM ketqua_xoso WHERE date = ?';
  db.query(query, [date], (err, results) => {
    if (err) {
      return res.status(500).json({ error: err.message });
    }
    if (results.length === 0) {
      return res.status(404).json({ message: 'No results found for this date' });
    }
    res.send(results[0].results);
  });
});

app.listen(port, () => {
  console.log(`Server running at http://localhost:${port}`);
});
