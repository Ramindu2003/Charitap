-- SQL to create events table for Charitap
CREATE TABLE IF NOT EXISTS events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  event_date DATE NOT NULL,
  created_by INT NOT NULL,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (created_by) REFERENCES users(id)
);
