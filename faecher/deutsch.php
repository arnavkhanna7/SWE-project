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
?>

<main>
    <section class="hero">
        <h2>Übungen in Mathematik</h2>
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
            <a href="">
                <div class="tile" data-name="Bruchrechnung">
                    <div class="tile-icon">⬜</div>
                    <div class="tile-title">Platzhalter</div>
                    <p>Übungen zum Platzhalter</p>
                </div>
            </a>


    </section>
</main>

<?php
include("../komponenten/footer.php");
?>
</body>
</html>