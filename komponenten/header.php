<?php
// WICHTIG: Setzen Sie hier den Namen des Projektordners!
// Wenn Ihre URL: http://localhost/meinprojekt/index.php ist, muss es 'meinprojekt' sein.
$PROJECT_ROOT = '';
// Wenn Ihre URL: http://localhost/index.php ist, muss es leer sein: $PROJECT_ROOT = '';

// Überprüfen, ob Session gestartet ist (für Username-Anzeige)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$username = $_SESSION['username'] ?? null;
?>
<header class="site-header">
    <a href="<?php echo $PROJECT_ROOT; ?>/index.php" class="brand" aria-label="Zur Startseite">
        <span class="brand-icon" aria-hidden="true">🎓</span>
        <h1 class="brand-title">Lernplattform der HSGG</h1>
    </a>

    <nav class="site-actions" aria-label="Hauptaktionen">
        <?php if ($username): ?>
            <div class="user-menu">
                <span class="user-greeting">Hallo, <strong><?php echo htmlspecialchars($username); ?></strong></span>

                <a href="<?php echo $PROJECT_ROOT; ?>/admin/dashboard.php" class="btn btn-sm">Dashboard</a>
                <a href="<?php echo $PROJECT_ROOT; ?>/Controllers/LogoutController.php" class="btn-logout" title="Abmelden" aria-label="Abmelden">
                    🚪
                </a>
            </div>
        <?php else: ?>
            <a class="btn btn-login" href="<?php echo $PROJECT_ROOT; ?>/login/login.php">
                <span aria-hidden="true">🔐</span> Login
            </a>
        <?php endif; ?>
    </nav>
</header>