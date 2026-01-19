-- ========================================
-- Student Management System Database Setup
-- ========================================

-- Create Database
CREATE DATABASE IF NOT EXISTS gestion_etudiants;
USE gestion_etudiants;

-- Create Students Table
CREATE TABLE IF NOT EXISTS etudiants (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique student identifier',
    nom VARCHAR(100) NOT NULL COMMENT 'Student last name',
    prenom VARCHAR(100) NOT NULL COMMENT 'Student first name',
    email VARCHAR(100) NOT NULL UNIQUE COMMENT 'Student email address (must be unique)',
    filiere VARCHAR(100) NOT NULL COMMENT 'Field of study',
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Date and time when student was registered',
    INDEX idx_email (email) COMMENT 'Index for faster email lookups',
    INDEX idx_nom (nom) COMMENT 'Index for faster name searches'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Table containing all student information';

-- Sample Data (Optional - can be deleted or modified)
INSERT INTO etudiants (nom, prenom, email, filiere) VALUES
('Boucher', 'Ahmed', 'ahmed.boucher@email.com', 'Computer Science'),
('Mariem', 'Sophie', 'sophie.mariem@email.com', 'Business'),
('Durand', 'amin', 'mohammed.amin@email.com', 'Engineering'),
('Dubois', 'Marie', 'marie.dubois@email.com', 'Medicine'),
('Moreau', 'Pierre', 'pierre.moreau@email.com', 'Law');
