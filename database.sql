  CREATE DATABASE IF NOT EXISTS db_summarecon;
  USE db_summarecon;

  -- Tabel untuk otentikasi administrator
  CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );

  -- Tabel data unit perumahan
  CREATE TABLE IF NOT EXISTS housing_units (
    id INT AUTO_INCREMENT PRIMARY KEY,
  nama_unit VARCHAR(100) NOT NULL,
  tipe VARCHAR(50) NOT NULL,
  harga DECIMAL(15, 2) NOT NULL,
  status ENUM('Tersedia', 'Terjual') DEFAULT 'Tersedia',
  gambar VARCHAR(255) NOT NULL,
  deskripsi TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );

  -- Tabel pesan kontak dari customer
  CREATE TABLE IF NOT EXISTS contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  telepon VARCHAR(20) NOT NULL,
  pesan TEXT NOT NULL,
  tanggal_kirim TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );

  -- Reset data default
  TRUNCATE TABLE users;
  TRUNCATE TABLE housing_units;

  -- Insert user default admin (password: admin123)
  INSERT INTO users (username, password) VALUES
  ('admin', '$2y$12$85V5YPcdBr1j5V3msr.x.OhRHedC0ISD1WxylH8M8ifJt.igKVa9q');

  -- Insert unit perumahan default
  INSERT INTO housing_units (nama_unit, tipe, harga, status, gambar, deskripsi) VALUES
  ('Onyx Residence', 'Contemporary Tropical', 1850000000.00, 'Tersedia', 'onyx.jpg', 'Smart Home | Cross Ventilation. Hunian modern dengan ventilasi udara menyilang yang alami.'),
  ('Green Crystal Residence', 'Garden House', 2250000000.00, 'Tersedia', 'green-crystal.jpg', 'Green Area | Elite Living. Nuansa taman yang asri dan hijau untuk gaya hidup elit.'),
  ('The Morizen', 'Japanese Style', 3500000000.00, 'Tersedia', 'the-morizen.jpg', 'Authentic Japan | Premium Quality. Kemewahan arsitektur Jepang autentik dengan spesifikasi material premium.');
