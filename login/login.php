<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login – Administration</title>
    <link rel="stylesheet"
          href="../css/styles.css">
</head>
<body>
<?php
// Include the shared header component
include("../komponenten/header.php");
?>

<main id="hauptinhalt">
    <section class="hero">
        <h2>Login für Lehrkräfte</h2>
        <p>Bitte melden Sie sich an, um Inhalte zu bearbeiten und neue Fächer hinzuzufügen.</p>
    </section>

    <section class="login-form" style="max-width: 400px; margin: 2rem auto; padding: 2rem; background: var(--card); border-radius: var(--radius); box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <form action="../Controllers/LoginController.php" method="POST">
            <div style="margin-bottom: 1.5rem;">
                <label for="username" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Benutzername</label>
                <input type="text" id="username" name="username" required
                       style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ccc; font-size: 1rem;">
            </div>

            <div style="margin-bottom: 2rem;">
                <label for="password" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Passwort</label>
                <input type="password" id="password" name="password" required
                       style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid #ccc; font-size: 1rem;">
            </div>

            <button type="submit" class="btn-login"
                    style="width: 100%; padding: 1rem; border: none; cursor: pointer; background: var(--accent); color: white; border-radius: 999px; font-weight: 700;">
                Anmelden
            </button>
        </form>

        <p style="text-align: center; margin-top: 1.5rem;"><a href="../index.php" style="color: var(--accent);">Zurück zur Startseite</a></p>
    </section>
</main>

<?php
include("../komponenten/footer.php");
?>
</body>
</html>