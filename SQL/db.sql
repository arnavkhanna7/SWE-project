-- 1. Datenbank erstellen (falls noch nicht vorhanden)
CREATE DATABASE IF NOT EXISTS lernplattform_db;
USE lernplattform_db;

-- 2. Tabelle 'users' erstellen
CREATE TABLE IF NOT EXISTS users (
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     username VARCHAR(50) NOT NULL UNIQUE,
                                     password_hash VARCHAR(255) NOT NULL,
                                     role VARCHAR(20) DEFAULT 'teacher', -- z.B. 'admin', 'teacher'
                                     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Benutzer: Admin
-- Passwort: pass123 (als Hash)
INSERT INTO users (username, password_hash, role)
VALUES ('Admin', '$2y$10$abcdefghijklmnopqrstuvwxy1234567890abcdefghijklm', 'admin');
-- HINWEIS: Der Hash oben ist ein Platzhalter.
-- Um "pass123" korrekt zu hashen, nutzen Sie den PHP-Code unten oder ein Online-Tool.
-- Ein echter Hash für "pass123" sieht z.B. so aus:
-- $2y$10$wW55.2... (wird bei jedem Generieren anders aussehen)

UPDATE users
SET password_hash = '$2y$12$EsiqO9YTgJJWUt67spjMa.cEVGQRECeMUFJc4dvD69/QEBZ1D8gla '
WHERE username = 'Admin';

INSERT INTO users (username, password_hash, role)
VALUES ('root', '$2y$12$tV/Yz4psRzznJ9amTsFxZuyAnpj9zUu8/ggZVlV/OLkdwUJsocw2y', 'admin');