<?php
session_start(); // <--- WICHTIG
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lernplattform – HSGG</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<div id="page-wrapper">

<?php
include("komponenten/header.php");
include("komponenten/barrierefreiheit.php");
require_once("komponenten/db.php");
?>

<?php
$faecher = query_simple_assoc("SELECT id, name, dir_name, symbol, kachelfarbe FROM hsgg.fach");
?>


<main id="hauptinhalt">
    <section class="hero">
        <h2>Einfach lernen. Mit dem HSGG LernHero.</h2>
        <p>Wähle ein Fach aus, um direkt zu den Materialien und Aufgaben zu gelangen.</p>
    </section>

    <section class="subjects">
        <h2>Fächer</h2>
        <div class="grid">
            <?php
            foreach ($faecher as $f) {
                echo '<a class="tile tile-color-' . htmlspecialchars($f['kachelfarbe']) . '" href="faecher/fach.php?f=' . $f['id'] .'">';
                echo '<div class="tile-icon">' . htmlspecialchars($f['symbol']) . '</div>';
                echo '<div class="tile-title">' . htmlspecialchars($f['name']) . '</div></a>';
            }
            ?>
        </div>
    </section>



    <section class="quick-actions">
        <h2>Schnellzugriff</h2>
        <div class="quick-grid">
            <a class="quick-card" href="#aufgaben"><span class="quick-icon">✅</span><span class="quick-text">Aufgaben</span></a>
            <a class="quick-card" href="#materialien"><span class="quick-icon">📂</span><span class="quick-text">Materialien</span></a>
            <a class="quick-card" href="#termine"><span class="quick-icon">🗓️</span><span class="quick-text">Termine</span></a>
        </div>
    </section>
</main>

<?php
include("komponenten/footer.php");
?>
</div>
</body>
</html>
