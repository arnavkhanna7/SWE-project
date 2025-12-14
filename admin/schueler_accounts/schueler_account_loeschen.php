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

$schuelerId = $_POST['id'] ?? null;

if (!$schuelerId) {
    header("Location: index.php");
    exit();
}

// ===============================
// Schüler löschen (nur eigene)
// ===============================
$sql = "DELETE FROM hsgg.users
        WHERE id = :id
          AND teacher_id = :teacher_id
          AND role = 'schueler'";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id'         => $schuelerId,
    ':teacher_id' => $_SESSION['user_id']
]);

header("Location: schueler_verwalten.php?msg=deleted");
exit();
