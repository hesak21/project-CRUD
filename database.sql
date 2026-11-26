CREATE DATABASE IF NOT EXISTS cafe_request
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cafe_request;

CREATE TABLE IF NOT EXISTS song_requests (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  song_title  VARCHAR(150) NOT NULL,
  artist_name VARCHAR(150) NOT NULL,
  message     VARCHAR(255) DEFAULT NULL,
  cover_url   VARCHAR(255) DEFAULT NULL,
  status      ENUM('Antrean','Diputar','Selesai') NOT NULL DEFAULT 'Antrean',
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_status (status)
) ENGINE=InnoDB;

INSERT INTO song_requests (song_title, artist_name, message, status) VALUES
('Fix You', 'Coldplay', 'Buat yang lagi kangen rumah', 'Diputar'),
('Sampai Jadi Debu', 'Banda Neira', 'Untuk dia yang duduk di pojok', 'Antrean'),
('Lovely Day', 'Bill Withers', NULL, 'Antrean');