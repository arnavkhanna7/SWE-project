<?php
session_start(); // Wichtig: Session starten, um den Login-Status zu merken

// Datenbank-Konfiguration einbinden (Pfad geht eins hoch '..' in den config-Ordner)
require_once __DIR__ . '/../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Eingaben bereinigen
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Einfache Validierung
    if (empty($username) || empty($password)) {
        header("Location: ../login/login.php?error=empty");
        exit();
    }

    // 1. Benutzer in der Datenbank suchen
    // Wir nutzen Prepared Statements gegen SQL-Injections
    /** @var PDO $pdo */
    $stmt = $pdo->prepare("SELECT id, username, password_hash, role FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    // 2. Passwort prüfen (Hash vergleichen)
    if ($user && password_verify($password, $user['password_hash'])) {

        // --- LOGIN ERFOLGREICH ---
        // Session-Variablen setzen
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Weiterleitung zum Dashboard (Erstellen wir gleich in Schritt 4)
        header("Location: ../admin/dashboard.php?msg=success");
        exit();

    } else {

        // --- LOGIN FEHLGESCHLAGEN ---
        header("Location: ../login/login.php?error=failed");
        exit();
    }

} else {
    // Direkter Aufruf ohne POST verhindern
    header("Location: ../login/login.php");
    exit();
}