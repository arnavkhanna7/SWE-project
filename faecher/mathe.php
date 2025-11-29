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
            <div class="tile tile-color-pink" data-name="Bruchrechnung">
                <div class="tile-icon">➗</div>
                <div class="tile-title">Bruchrechnung</div>
                <p>Übungen zur Addition und Subtraktion von Brüchen.</p>
            </div>
            </a>

            <div class="tile tile-color-yellow" data-name="Geometrie">
                <div class="tile-icon">📐</div>
                <div class="tile-title">Geometrie</div>
                <p>Grundlagen zu Flächen und Winkeln.</p>
            </div>

            <div class="tile tile-color-blue" data-name="Algebra">
                <div class="tile-icon">🔢</div>
                <div class="tile-title">Algebra</div>
                <p>Lineare Gleichungen und Variablen.</p>
            </div>

            <div class="tile tile-color-green" data-name="Textaufgaben">
                <div class="tile-icon">📝</div>
                <div class="tile-title">Textaufgaben</div>
                <p>Knifflige Aufgaben zum logischen Denken.</p>
            </div>
        </div>
    </section>
</main>

<?php
include("../komponenten/footer.php");
?>
</body>
</html>