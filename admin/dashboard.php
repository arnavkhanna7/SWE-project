<?php
//session_start();
//
//// 1. Sicherheit-Check: Ist der Nutzer eingeloggt?
//if (!isset($_SESSION['user_id'])) {
//    // Falls nicht, sofort zum Login umleiten
//    header("Location: ../login/login.php");
//    exit();
//}
//?>
<!--<!doctype html>-->
<!--<html lang="de">-->
<!--<head>-->
<!--    <meta charset="utf-8">-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1">-->
<!--    <title>Dashboard – Administration</title>-->
<!--    <link rel="stylesheet" href="../css/styles.css">-->
<!--</head>-->
<!--<body>-->
<!---->
<?php
//// Optional: Header einbinden (Pfad muss angepasst werden, da wir in /admin/ sind)
//include("../komponenten/header.php");
//?>
<!---->
<!--<main id="hauptinhalt">-->
<!--    <section class="login-container" style="max-width: 800px; margin-top: 3rem; text-align: center;">-->
<!---->
<!--        --><?php //if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
<!--            <div style="background-color: #dcfce7; color: #166534; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #86efac;">-->
<!--                ✅ <strong>Login erfolgreich!</strong>-->
<!--            </div>-->
<!--        --><?php //endif; ?>
<!---->
<!--        <h1>Willkommen im Dashboard</h1>-->
<!---->
<!--        <p style="font-size: 1.2rem; margin-bottom: 0.5rem;">-->
<!--            Hallo, <strong>--><?php //echo htmlspecialchars($_SESSION['username']); ?><!--</strong>!-->
<!--        </p>-->
<!--        <p style="color: var(--muted); margin-bottom: 2rem;">-->
<!--            Sie sind angemeldet als: <span style="background: #e0f2fe; color: #0369a1; padding: 0.2rem 0.6rem; border-radius: 4px; font-weight: bold; font-size: 0.9rem;">--><?php //echo htmlspecialchars($_SESSION['role']); ?><!--</span>-->
<!--        </p>-->
<!---->
<!--        <div class="grid" style="margin-bottom: 3rem; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">-->
<!--            <div class="tile" style="cursor: pointer;">-->
<!--                <div class="tile-icon">📄</div>-->
<!--                <div class="tile-title">Inhalte verwalten</div>-->
<!--                <p style="font-size: 0.9rem; color: var(--muted);">Texte und Aufgaben bearbeiten</p>-->
<!--            </div>-->
<!---->
<!--            <div class="tile" style="cursor: pointer;">-->
<!--                <div class="tile-icon">👤</div>-->
<!--                <div class="tile-title">Mein Profil</div>-->
<!--                <p style="font-size: 0.9rem; color: var(--muted);">Passwort ändern</p>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <hr style="margin: 2rem 0; opacity: 0.1;">-->
<!---->
<!--        <div style="display: flex; justify-content: center; align-items: center; gap: 1.5rem;">-->
<!--            <a href="../index.php" class="btn-back" style="font-size: 1rem; text-decoration: none;">-->
<!--                ⬅️ Zur Startseite-->
<!--            </a>-->
<!---->
<!--            <a href="../Controllers/LogoutController.php" style="color: #dc2626; font-weight: bold; text-decoration: none; border: 1px solid #dc2626; padding: 0.8rem 1.2rem; border-radius: 999px; transition: all 0.2s;">-->
<!--                Abmelden-->
<!--            </a>-->
<!--        </div>-->
<!---->
<!--    </section>-->
<!--</main>-->
<!---->
<?php
//include("../komponenten/footer.php");
//?>
<!---->
<!--</body>-->
<!--</html>-->

<?php
session_start();

// 1. Sicherheits-Check: Ist der Nutzer eingeloggt?
if (!isset($_SESSION['user_id'])) {
    // Falls nicht, sofort zum Login umleiten
    header("Location: ../login/login.php");
    exit();
}

// Holen der Rolle zur einfacheren Prüfung
$user_role = $_SESSION['role'] ?? 'teacher'; // Standardmäßig 'teacher', falls Rolle fehlt
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard – Administration</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<?php
// Optional: Header einbinden (Pfad muss angepasst werden, da wir in /admin/ sind)
include("../komponenten/header.php");
?>

<main id="hauptinhalt">
    <section class="login-container" style="max-width: 800px; margin-top: 3rem; text-align: center;">

        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
            <div style="background-color: #dcfce7; color: #166534; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #86efac;">
                ✅ <strong>Login erfolgreich!</strong>
            </div>
        <?php endif; ?>

        <h1>Willkommen im Dashboard</h1>

        <p style="font-size: 1.2rem; margin-bottom: 0.5rem;">
            Hallo, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!
        </p>
        <p style="color: var(--muted); margin-bottom: 2rem;">
            Sie sind angemeldet als: <span style="background: #e0f2fe; color: #0369a1; padding: 0.2rem 0.6rem; border-radius: 4px; font-weight: bold; font-size: 0.9rem;"><?php echo htmlspecialchars($_SESSION['role']); ?></span>
        </p>

        <div class="grid" style="margin-bottom: 3rem; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">

            <?php if ($user_role === 'admin'): ?>
                <a href="fach_manager.php" class="tile" style="cursor: pointer;">
                    <div class="tile-icon">📚</div>
                    <div class="tile-title">Fächer verwalten</div>
                    <p style="font-size: 0.9rem; color: var(--muted);">Fächer hinzufügen/bearbeiten</p>
                </a>
            <?php endif; ?>

            <?php if ($user_role === 'admin' || $user_role === 'lehrer'): ?>
                <a href="uebungs_manager.php" class="tile" style="cursor: pointer;">
                    <div class="tile-icon">📝</div>
                    <div class="tile-title">Übungen verwalten</div>
                    <p style="font-size: 0.9rem; color: var(--muted);">Übungen erstellen & Metadaten</p>
                </a>

                <a href="editor.php" class="tile" style="cursor: pointer;">
                    <div class="tile-icon">✏️</div>
                    <div class="tile-title">Inhalte bearbeiten</div>
                    <p style="font-size: 0.9rem; color: var(--muted);">Erklärungen und Tipps anpassen</p>
                </a>
            <?php endif; ?>

            <a href="#" class="tile" style="cursor: pointer;">
                <div class="tile-icon">👤</div>
                <div class="tile-title">Mein Profil</div>
                <p style="font-size: 0.9rem; color: var(--muted);">Passwort ändern</p>
            </a>
        </div>

        <hr style="margin: 2rem 0; opacity: 0.1;">

        <div style="display: flex; justify-content: center; align-items: center; gap: 1.5rem;">
            <a href="../index.php" class="btn-back" style="font-size: 1rem; text-decoration: none;">
                ⬅️ Zur Startseite
            </a>

            <a href="../Controllers/LogoutController.php" style="color: #dc2626; font-weight: bold; text-decoration: none; border: 1px solid #dc2626; padding: 0.8rem 1.2rem; border-radius: 999px; transition: all 0.2s;">
                Abmelden
            </a>
        </div>

    </section>
</main>

<?php
include("../komponenten/footer.php");
?>

</body>
</html>