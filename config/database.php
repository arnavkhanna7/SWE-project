<?php
// config/database.php

// Passen Sie diese Daten an Ihre lokale Umgebung an
$host = '127.0.0.1';
$db   = 'lernplattform_db';
$user = 'root';        // XAMPP Standard: 'root'
$pass = 'root';            // XAMPP Standard: leer lassen
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Bei Fehler Verbindung abbrechen und Fehlermeldung zeigen
    die("Datenbank-Verbindungsfehler: " . $e->getMessage());
}
