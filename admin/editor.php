<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lernplattform – HSGG: Editor</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/editor.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
</head>
<body>
<div id="page-wrapper">

    <?php
    include("../komponenten/header.php");
    include("../komponenten/barrierefreiheit.php");
    ?>

    <?php
    require_once("../komponenten/db.php");
    $fach = query_simple_assoc("SELECT * FROM hsgg.fach");



    //=== Laden von Inhalten aus einer Übung ===
    $user_role = $_SESSION['role'] ?? 'guest';
    $user_fach_id = $_SESSION['fachID'] ?? null;

    if(isset($_POST["loadUebungSubmitted"])){
        $fachID    = $_POST['fachID'] ?? null;
        $uebungsID = $_POST['uebungsID'] ?? null;
        $editor_content = query_uebung_fid_uid($fachID, $uebungsID);
        $exp_box = $editor_content["explanation_box"];
        $tips_box = $editor_content["tips_box"];
        echo "<script>
            let explanation_box_content = " . json_encode($exp_box) . ";
            let tips_box_content        = " . json_encode($tips_box) . "; </script>";
        $currently_editing_fach = query_fach_fid($fachID)['name'];
        $currently_editing_uebung = $editor_content['name'];
        $currently_editing = "Übung $currently_editing_uebung aus dem Fach $currently_editing_fach wird editiert";
    } else {
        echo "<script> let explanation_box_content = ''; let tips_box_content = ''; </script>";
        $currently_editing = "Noch keine Übung ausgewählt";
    }

    //=== Speichern von Inhalten aus einer Übung ===
//    if(isset($_POST["saveUebungSubmitted"])){
//        update_editable_contents($_POST['uebungsID'], $_POST['explanation_box_content'], $_POST['tips_box_content']);
//        echo '<div id="success-box">Speichern erfolgreich</div>';
//    }
        if(isset($_POST["saveUebungSubmitted"])){

            $fachID_to_save = $_POST['fachID'] ?? null; // Das Fach, das gespeichert werden soll
            $uebungsID_to_save = $_POST['uebungsID'] ?? null; // Die Übung, die gespeichert werden soll

        // **NEU: ZUGRIFFSPRÜFUNG**
            if ($user_role !== 'admin' && (int)$user_fach_id !== (int)$fachID_to_save) {
            // Wenn der Benutzer kein Admin ist UND die FachID des Benutzers
            // nicht mit der FachID des zu speichernden Inhalts übereinstimmt:
                echo '<div id="success-box" style="background-color: #dc2626; color: white;">❌ Fehler: Keine Berechtigung zum Speichern in diesem Fach.</div>';
            // Wir beenden die Funktion hier, um das Speichern zu verhindern
            } else {
            // Wenn Admin ODER Fach-ID übereinstimmt, speichern:
                update_editable_contents($uebungsID_to_save, $_POST['explanation_box_content'], $_POST['tips_box_content']);
                echo '<div id="success-box">Speichern erfolgreich</div>';
            }
        }
    ?>

    <main id="hauptinhalt">

        <div class="form-row">
            <!-- Fach Dropdown -->
            <label for="fach">Fach auswählen:</label>
            <select id="fach">
                <?php
                foreach($fach as $f) {
                    echo '<option value="'.$f['id'].'">'. $f['name'] .'</option>';
                }
                ?>
            </select>

            <!-- Übung Dropdown -->
            <label for="uebung">Übung auswählen:</label>
            <select id="uebung">
                <?php
                foreach($fach as $f) {
                    $fach_uebungen = query_simple_assoc("SELECT * FROM uebung WHERE fachId = '$f[id]'");
                    echo '<optgroup label="' . $f['name'] . '" data-fach-id="' . $f['id'] . '">';
                    if(count($fach_uebungen) == 0) {
                        echo '<option value="invalid" disabled> Keine Übungen verfügbar </option>';
                    }
                    foreach($fach_uebungen as $uebung) {
                        echo '<option value="'.$uebung['id'].'">'. $uebung['name'] .'</option>';
                    }
                    echo '</optgroup>';
                }
                ?>
            </select>

            <!-- Load Button: Übermittelt Ids von Fach und Übung um Inhalte laden zu können -->
            <form method="post" action="editor.php">
                <input type="submit" name="loadButton" id="loadButton" value="Laden">
                <input type="hidden" id="fachID" name="fachID">
                <input type="hidden" id="uebungsID" name="uebungsID">
                <input type="hidden"  name="loadUebungSubmitted" value="wasSubmitted">
            </form>
        </div>

        <div id="current-uebung-box">
            <span id="current-uebung-label">Aktuelle Übung:</span>
            <span id="current-uebung-name"><?php echo $currently_editing ?></span>

            <form method="post" action="editor.php" id="saveButtonForm">
                <input type="submit" name="saveButton" id="saveButton" value="Speichern">
                <?php
                if(isset($_POST["loadUebungSubmitted"])){
                    echo '<input type="hidden" name="fachID" value="' . $fachID . '">';
                    echo '<input type="hidden" name="uebungsID" value="' . $uebungsID . '">';
                    echo '<input type="hidden"  name="saveUebungSubmitted" value="wasSubmitted">';
                }
                ?>
                <input type="hidden" name="explanation_box_content" id="explanation_box_content_save">
                <input type="hidden" name="tips_box_content" id="tips_box_content_save">
            </form>
        </div>


        <div class="editor-grid">
            <section>
                <h3>Erklärungsbox:</h3>
                <div id="editor-explanation" class="editor-box"></div>
            </section>

            <section>
                <h3>Tips-Box:</h3>
                <div id="editor-tips" class="editor-box"></div>
            </section>
        </div>


        <script>
            let explanationEditor = ClassicEditor
                .create(document.querySelector('#editor-explanation'), {
                    toolbar: [
                        'undo','redo','bold','italic','underline',
                        'link','bulletedList','numberedList','blockQuote','insertTable'
                    ]
                })
                .then(editor => { explanationEditor = editor; explanationEditor.setData(explanation_box_content); })
                .catch(error => { console.error(error); });

            let tipsEditor = ClassicEditor
                .create(document.querySelector('#editor-tips'), {
                    toolbar: [
                        'undo','redo','bold','italic','underline',
                        'link','bulletedList','numberedList','blockQuote','insertTable'
                    ]
                })
                .then(editor => { tipsEditor = editor; tipsEditor.setData(tips_box_content); })
                .catch(error => { console.error(error); });

            // Vor dem Absenden Inhalte in Hidden Inputs schreiben
            document.getElementById("saveButtonForm").addEventListener("submit", function(e) {
                // hole Editor-Inhalte
                const expContent  = explanationEditor.getData();
                const tipsContent = tipsEditor.getData();

                // schreibe sie in die Hidden Inputs
                document.getElementById("explanation_box_content_save").value = expContent;
                document.getElementById("tips_box_content_save").value        = tipsContent;
            });

            const fachSelect = document.getElementById("fach");
            const uebungSelect = document.getElementById("uebung");
            const fachIDInput = document.getElementById("fachID");
            const uebungsIDInput = document.getElementById("uebungsID");

            // Hide all groups initially
            document.querySelectorAll("#uebung optgroup").forEach(group => {
                group.style.display = "none";
            });

            // --- Initial selection: first Fach + first Übung if available ---
            if (fachSelect.options.length > 0) {
                fachSelect.selectedIndex = 0;
                const firstFachId = fachSelect.value;
                fachIDInput.value = firstFachId; // set hidden Fach ID

                document.querySelectorAll("#uebung optgroup").forEach(group => {
                    if (group.getAttribute("data-fach-id") === firstFachId) {
                        if (group.children.length > 0) {
                            group.style.display = "block";
                            // deselect all options first
                            Array.from(group.children).forEach(opt => opt.selected = false);
                            // select the first Übung
                            group.children[0].selected = true;
                            uebungsIDInput.value = group.children[0].value; // set hidden Übung ID
                        } else {
                            group.style.display = "none";
                            uebungsIDInput.value = ""; // no Übung
                        }
                    } else {
                        group.style.display = "none";
                    }
                });
            }

            // --- Toggle groups when Fach changes ---
            fachSelect.addEventListener("change", () => {
                const selectedFachId = fachSelect.value;
                fachIDInput.value = selectedFachId; // update hidden Fach ID

                document.querySelectorAll("#uebung optgroup").forEach(group => {
                    if (group.getAttribute("data-fach-id") === selectedFachId) {
                        group.style.display = "block";
                        if (group.children.length > 0) {
                            // deselect all options first
                            Array.from(group.children).forEach(opt => opt.selected = false);
                            // select the first Übung of this group
                            group.children[0].selected = true;
                            uebungsIDInput.value = group.children[0].value; // update hidden Übung ID
                        } else {
                            group.style.display = "none";
                            uebungsIDInput.value = ""; // no Übung
                        }
                    } else {
                        group.style.display = "none";
                        // also deselect any options in hidden groups
                        Array.from(group.children).forEach(opt => opt.selected = false);
                    }
                });
            });

            // --- Update hidden input when Übung changes ---
            uebungSelect.addEventListener("change", () => {
                uebungsIDInput.value = uebungSelect.value;
            });
        </script>

    </main>


    <!-- Info box -->
    <section class="katex-hinweis" aria-labelledby="katex-hinweis-titel" role="note">
        <h2 id="katex-hinweis-titel">Formeleingabe mit LaTeX</h2>
        <p>
            Geben Sie Formeln in der Form <span class="latex-inline">\( \dots \)</span> für Inline‑Darstellung
            oder <span class="latex-inline">

            \[ \dots \]

        </span> für Block‑Darstellung ein.
        </p>

        <div class="katex-beispiele" aria-label="KaTeX-Beispiele">
            <h3>Beispiele</h3>

            <ul>
                <li><strong>Inline:</strong>
                    <span class="render-me">\( E = mc^2 \)</span>
                </li>
                <li><strong>Bruch:</strong>
                    <span class="render-me">\( \frac{a}{b} + \frac{c}{d} \)</span>
                </li>
                <li><strong>Wurzel:</strong>
                    <span class="render-me">\( \sqrt{a^2 + b^2} \)</span>
                </li>
                <li><strong>Summenzeichen:</strong>
                    <span class="render-me">\( \sum_{i=1}^{n} i = \frac{n(n+1)}{2} \)</span>
                </li>
                <li><strong>Blockgleichung:</strong>
                    <span class="render-me">

                    \[ \int_{0}^{\infty} e^{-x} \, dx = 1 \]

                </span>
                </li>
                <li><strong>Vektoren/Matrizen:</strong>
                    <span class="render-me">\( \vec{v} = \begin{bmatrix} x \\ y \\ z \end{bmatrix} \)</span>
                </li>
            </ul>
        </div>

        <p class="katex-hinweis-tip">
            Tipp: Verwenden Sie doppelte Backslashes in HTML‑Attributen/Strings (z.&nbsp;B. <code>\\alpha</code>), wenn ein einzelner Backslash sonst als Escape interpretiert wird.
        </p>
    </section>

    <?php
    include("../komponenten/footer.php");
    ?>
</div>
</body>
</html>

