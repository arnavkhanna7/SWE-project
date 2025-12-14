<?php
session_start();

// ===============================
// Zugriffsschutz
// ===============================
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'lehrer') {
    header("Location: ../../login/login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// ===============================
// Eingaben prüfen
// ===============================
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$klasse   = trim($_POST['klasse'] ?? '');

if ($username === '' || $password === '' || $klasse === '') {
    header("Location: index.php?msg=error");
    exit();
}

// ===============================
// WennSchüler bereits existiert (Fehlermeldung)
// ===============================
// Prüfen, ob Username schon existiert
$checkSql = "SELECT id FROM hsgg.users WHERE username = :username";
$checkStmt = $pdo->prepare($checkSql);
$checkStmt->execute([':username' => $username]);

if ($checkStmt->fetch()) {
    // Username existiert bereits
    header("Location: schueler_verwalten.php?msg=exists");
    exit();
}


// ===============================
// Schüler anlegen
// ===============================
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO hsgg.users
        (username, password_hash, role, teacher_id, fachID, klasse)
        VALUES
        (:username, :password_hash, 'schueler', :teacher_id, :fachID, :klasse)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':username'       => $username,
    ':password_hash'  => $passwordHash,
    ':teacher_id'     => $_SESSION['user_id'],
    ':fachID'         => $_SESSION['fachID'],
    ':klasse'         => $klasse
]);

header("Location: schueler_verwalten.php?msg=created");
exit();


