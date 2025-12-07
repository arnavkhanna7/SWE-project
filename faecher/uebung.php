<?php
require_once("../komponenten/db.php");

$fid = $_GET['f'] ?? '1'; //möglicherweise Fehlerseite anzeigen wenn kein Fach selektiert
$uid = $_GET['u'] ?? '1';

$fach = query_fach_fid($fid);
$uebung = query_uebung_fid_uid($fid, $uid);

$pdf_aufgaben = $fach['name'] . '/' . $uebung['file_name'] . '_aufgaben.pdf';
$pdf_loesungen = $fach['name'] . '/' . $uebung['file_name'] . '_loesungen.pdf';
?>

<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $uebung['name'] ?></title>
    <link rel="stylesheet" href="../../../../css/styles.css">
    <link rel="stylesheet" href="../../../../css/aufgaben.css">
    <!-- KaTeX CSS & JS über CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
</head>
<body>
<?php
include $_SERVER["DOCUMENT_ROOT"] . "/komponenten/header.php";
include $_SERVER["DOCUMENT_ROOT"] . "/komponenten/barrierefreiheit.php";
?>

<main>
    <section class="hero">
        <h2> <?php echo htmlspecialchars($uebung['name']) ?> </h2>
        <p> <?php echo htmlspecialchars($uebung['beschreibung']) ?> </p>
    </section>

    <?php
    echo '<a href="fach.php?f=' . htmlspecialchars($fid) . '" class="btn-back"> 📚 Zurück zu ' . htmlspecialchars($fach['name']) . '</a>';
    ?>

    <!-- Erklärung -->
    <?php
    echo '<div class="explanation-box explanation-box-' . $uebung['kachelfarbe'] . '">';
    echo $uebung['explanation_box'];
    echo '</div>';
    ?>


    <!-- Aufgabenblatt -->
    <div class="pdf-container">
        <div class="pdf-header">
            <div class="pdf-icon">📝</div>
            <div class="pdf-info">
                <h3>Aufgabenblatt</h3>
                <p>Lade das Aufgabenblatt herunter und löse die Aufgaben auf einem Blatt Papier.</p>
            </div>
        </div>

        <?php
        echo '<embed src="' . $pdf_aufgaben . '" type="application/pdf" class="pdf-embed">'
        ?>

        <div style="text-align: center;">
            <?php
            echo '<a href="' . $pdf_aufgaben . '" download class="btn-download btn-download-' . $uebung['kachelfarbe'] . '">';
            echo '⬇️ Aufgabenblatt herunterladen </a>';
            ?>
            <button onclick="toggleSolution()" class="btn-show-solution" id="solutionBtn">
                👁️ Lösungen anzeigen
            </button>
        </div>
    </div>

    <!-- Lösungsblatt (zunächst versteckt) -->
    <div class="solution-section" id="solutionSection">
        <div class="pdf-container" style="border: 3px solid #4CAF50;">
            <div class="pdf-header">
                <div class="pdf-icon">✅</div>
                <div class="pdf-info">
                    <h3>Lösungsblatt</h3>
                    <p>Vergleiche deine Lösungen mit den richtigen Antworten. Achte auf den Rechenweg!</p>
                </div>
            </div>

            <?php
            echo '<embed src="' . $pdf_loesungen . '" type="application/pdf" class="pdf-embed">'
            ?>

            <div style="text-align: center;">

                <?php
                echo '<a href="' . $pdf_loesungen . '" download class="btn-download btn-download-' . $uebung['kachelfarbe'] . '">';
                echo '⬇️ Lösungsblatt herunterladen </a>';
                ?>

            </div>
        </div>
    </div>

    <!-- Tipps -->
    <?php
    echo '<div class="tips-box tips-box-' . $uebung['kachelfarbe'] . '">';
    echo $uebung['tips_box'];
    echo '</div>';
    ?>
</main>

<script>
    function toggleSolution() {
        const solutionSection = document.getElementById('solutionSection');
        const btn = document.getElementById('solutionBtn');

        if (solutionSection.classList.contains('visible')) {
            solutionSection.classList.remove('visible');
            btn.textContent = '👁️ Lösungen anzeigen';
            btn.style.background = 'linear-gradient(135deg, #4CAF50 0%, #45a049 100%)';
        } else {
            solutionSection.classList.add('visible');
            btn.textContent = '🙈 Lösungen verstecken';
            btn.style.background = 'linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%)';

            // Smooth scroll to solution
            solutionSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Render KaTeX (Formeln) in Explanation-Box und Tips-Box
    function renderMathForBoxes() {
        const boxes = document.querySelectorAll('.explanation-box, .tips-box');

        boxes.forEach(box => {
            if (!box) return;

            renderMathInElement(box, {
                delimiters: [
                    { left: "\\(", right: "\\)", display: false },
                    { left: "\\[", right: "\\]", display: true }
                ],
                throwOnError: false
            });
        });
    }

    // Render immediately after the DOM is ready
    document.addEventListener("DOMContentLoaded", renderMathForBoxes);
</script>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/komponenten/footer.php"; ?>
</body>
</html>
