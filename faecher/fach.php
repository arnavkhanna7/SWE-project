<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Themen – Mathematik</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<?php
include("../komponenten/header.php");
require_once("../komponenten/db.php");
?>
<?php include("../komponenten/barrierefreiheit.php"); ?>

<?php

$fid = $_GET['f'] ?? '1'; //möglicherweise Fehlerseite anzeigen wenn kein Fach selektiert

$uebungen = query_uebungen_fid($fid);
$fach = query_fach_fid($fid);
?>



<main>
    <section class="hero">
        <h2>Übungen in <?php echo htmlspecialchars($fach['name']) ?></h2>
        <p>Hier findest du viele Aufgaben zum Üben. Viel Spaß!</p>
    </section>

    <script>
        function filterExercises() {
            const input = document.getElementById("search-input").value.toLowerCase();
            const cards = document.querySelectorAll("#exercise-grid .tile");

            cards.forEach(card => {
                const text = card.innerText.toLowerCase();
                if (text.includes(input)) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        }
    </script>


    <a href="../index.php" class="btn-back" aria-label="Zurück zur Übersicht">
        ⬅️ Zurück zur Übersicht
    </a>


    <section class="search-bar">
        <label for="search-input">Übungen durchsuchen:</label>
        <input type="text" id="search-input" placeholder="Suchbegriff eingeben..." onkeyup="filterExercises()">
    </section>




    <section class="exercises">
        <div id="exercise-grid" class="grid">
            <?php
            foreach($uebungen as $u) {
                echo '<a href="uebung.php?f=' . $fach['id'] . '&u=' . $u['id'] .  '">';
                echo '<div class="tile tile-color-' . htmlspecialchars($u['kachelfarbe']) . '" data-name="' . htmlspecialchars($u['name']) . '">';
                echo '<div class="tile-icon">' . htmlspecialchars($u['symbol']) . '</div>';
                echo '<div class="tile-title">' . htmlspecialchars($u['name']) . '</div>';
                echo '<p>' . htmlspecialchars($u['beschreibung']) . '</p></div></a>';
            }
            ?>
        </div>
    </section>
</main>

<?php
include("../komponenten/footer.php");
?>
</body>
</html>