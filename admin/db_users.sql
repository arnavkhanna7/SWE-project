-- 1. Datenbank erstellen hsgg (create_db.sql falls noch nicht vorhanden)
USE hsgg;
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

INSERT INTO hsgg.users (username, password_hash, role)
VALUES ('lehrer', '$2y$12$o.AK4QGXMgQbR8ghEBT.k..VtPoxMgDMrHk5wcpxjtrBUYfOqYZum', 'lehrer');

INSERT INTO hsgg.users (username, password_hash, role)
VALUES ('MatheLehrer', '$2y$12$o.AK4QGXMgQbR8ghEBT.k..VtPoxMgDMrHk5wcpxjtrBUYfOqYZum', 'lehrer');

INSERT INTO hsgg.users (username, password_hash, role)
VALUES ('DeutschLehrer', '$2y$12$o.AK4QGXMgQbR8ghEBT.k..VtPoxMgDMrHk5wcpxjtrBUYfOqYZum', 'lehrer');



ALTER TABLE hsgg.users
    ADD COLUMN fachID INT NULL
        AFTER role;

UPDATE hsgg.users SET fachID = 1 WHERE username = 'MatheLehrer';
UPDATE hsgg.users SET fachID = 2 WHERE username = 'DeutschLehrer';
UPDATE hsgg.users SET fachID = 4 WHERE username = 'EnglischLehrer';


# Theacher id für Schühler accounts in der Tabelle hinzufügen
ALTER TABLE users
    ADD COLUMN teacher_id INT NULL,
    ADD COLUMN klasse VARCHAR(20) NULL;
