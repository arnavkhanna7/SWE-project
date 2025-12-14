<?php
session_start();

// ===============================
// 1. Zugriffsschutz: nur Lehrer
// ===============================
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'lehrer') {
    header("Location: ../../login/login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';

// ===============================
// 2. Eigene Schüler laden
// ===============================
$sql = "SELECT id, username, klasse
        FROM hsgg.users
        WHERE role = 'schueler'
          AND teacher_id = :teacher_id
        ORDER BY username";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':teacher_id' => $_SESSION['user_id']
]);

$schueler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Schüler verwalten</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>

    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/schueler_acc_verwaltung.css">

<body>

<?php include("../../komponenten/header.php"); ?>

<main id="hauptinhalt">
    <section class="login-container" style="max-width: 900px; margin-top: 3rem;">

        <h1 style="text-align:center;">👩‍🎓 Schüler verwalten</h1>

        <!-- ===============================
             Erfolgsmeldungen
        =============================== -->
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'created'): ?>
            <div style="background:#dcfce7; color:#166534; padding:1rem; border-radius:8px; margin:1.5rem 0;">
                ✅ Schüler wurde erfolgreich angelegt
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
            <div style="background:#fee2e2; color:#991b1b; padding:1rem; border-radius:8px; margin:1.5rem 0;">
                🗑 Schüler wurde gelöscht
            </div>
        <?php endif; ?>

        <!-- ===============================
            Zurück zum Dashboard Button
       =============================== -->
        <div style="margin-top: 2.5rem;">
            <a href="../dashboard.php" class="btn-back">⬅️ Zurück zum Dashboard</a>
            <br>
            <br>
            <br>
        </div>

        <!-- ===============================
             Schüler anlegen
        =============================== -->
        <div class="card">
            <h2 class="card-title">➕ Schüler anlegen</h2>

            <form method="post" action="schueler_account_anlegen.php" class="form-vertical">
                <label>
                    Benutzername
                    <input type="text" name="username" placeholder="z.B. max.mustermann" required>
                </label>

                <label>
                    Passwort
                    <input type="password" name="password" placeholder="Startpasswort" required>
                </label>

                <label>
                    Klasse
                    <input type="text" name="klasse" placeholder="z.B. 5B" required>
                </label>

                <button type="submit" class="btn-create">
                    ➕ Schüler erstellen
                </button>

            </form>
        </div>

        <!-- ===============================
             Wenn Schühler bereits existiert
        =============================== -->

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'exists'): ?>
            <div style="background:#fef3c7; color:#92400e; padding:1rem; border-radius:8px; margin:1.5rem 0;">
                ⚠️ Benutzername existiert bereits. Bitte einen anderen wählen.
            </div>
        <?php endif; ?>



        <!-- ===============================
             Schülerliste
        =============================== -->
        <h2>📋 Meine Schüler</h2>

        <?php if (count($schueler) === 0): ?>
            <p style="color: var(--muted);">Noch keine Schüler angelegt.</p>
        <?php else: ?>
            <ul style="list-style:none; padding:0;">
                <?php foreach ($schueler as $s): ?>
                    <li style="display:flex; justify-content:space-between; align-items:center; padding:0.8rem; border-bottom:1px solid #eee;">
                        <div>
                            <strong><?= htmlspecialchars($s['username']) ?></strong>
                            <span style="color:var(--muted);">
                            (<?= htmlspecialchars($s['klasse']) ?>)
                        </span>
                        </div>

                        <form method="post" action="schueler_account_loeschen.php"
                              onsubmit="return confirm('Schüler wirklich löschen?');">
                            <input type="hidden" name="id" value="<?= $s['id'] ?>">
                            <button type="submit"
                                    style="color:#dc2626; background:none; border:none; cursor:pointer;">
                                🗑 Löschen
                            </button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>


    </section>
</main>

<?php include("../../komponenten/footer.php"); ?>

</body>
</html>



