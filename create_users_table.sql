-- SQL to create users table for Charitap
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  user_type ENUM('donor','recipient') NOT NULL,
  created_at DATETIME NOT NULL,
  INDEX (email)
);
