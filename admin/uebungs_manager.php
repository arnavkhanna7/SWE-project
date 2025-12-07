<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lernplattform – HSGG</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/manager.css">
</head>
<body>
<div id="page-wrapper">

    <?php
    include("../komponenten/header.php");
    include("../komponenten/barrierefreiheit.php");
    ?>

    <?php
    require("../komponenten/db.php");
    include("../komponenten/util.php");

    // Alle Übungen laden
    $uebungen = query_simple_assoc("SELECT * FROM uebung");
    $faecher = query_simple_assoc("SELECT * FROM fach");

    $file_name_block_list = [];
    foreach($uebungen as $u) {
        $dir_name_block_list[] = $u['file_name'];
    }

    // Feedback Banner
    $editOperationInfo = null;

    // Neue Übung anlegen
    if (isset($_POST['createNewUebung'])) {
        $name = $_POST["name"];
        $file_name = $_POST["file_name"];
        $beschreibung = $_POST["beschreibung"];
        $symbol = $_POST["symbol"];
        $kachelfarbe = $_POST["kachelfarbe"];
        $fachID = $_POST["fachID"];
        if(!in_array($file_name, $file_name_block_list)) {
            db_insert_new_uebung($fachID, $name, $file_name, $beschreibung, $symbol, $kachelfarbe);
            $editOperationInfo = '<div class="fach-success-banner">🎉 Übung erfolgreich erstellt!</div>';
        } else {
            $editOperationInfo = '<div class="fach-failure-banner">❌ Fehler: Fach konnte nicht erstellt werden!</div>';
        }

    }

    // Übung speichern
    if (isset($_POST['saveUebung'])) {
        $id = $_POST["id"];
        $name = $_POST["name"];
        $file_name = $_POST["file_name"];
        $beschreibung = $_POST["beschreibung"];
        $symbol = $_POST["symbol"];
        $kachelfarbe = $_POST["kachelfarbe"];
        $fachID = $_POST["fachID"];
        db_update_uebung($id, $name, $file_name, $beschreibung, $symbol, $kachelfarbe, $fachID);
        $editOperationInfo = '<div class="fach-success-banner">🎉 Übung erfolgreich gespeichert!</div>';
    }

    // Übung löschen
    if (isset($_POST['deleteUebung'])) {
        $id = $_POST["id"];
        $file_name = $_POST["file_name"];
        $fachID = db_query_uebung_fach($id)['fachID'];
        $fach = query_fach_fid($fachID);
        $dir_name = $fach['dir_name'];
        $path = $_SERVER['DOCUMENT_ROOT'] . "/faecher/" . $dir_name . "/" . $file_name;
        db_delete_uebung($id);
        if(file_exists($path . "_aufgaben.pdf")) {  unlink($path . "_aufgaben.pdf"); }
        if(file_exists($path . "_loesungen.pdf")) {  unlink($path . "_loesungen.pdf"); }
        if(file_exists($path . "_uebung.txt")) {  unlink($path . "_uebung.txt"); }
        $editOperationInfo = '<div class="fach-success-banner">⚠️ Übung erfolgreich gelöscht!</div>';
    }

    // Neu laden wegen möglichen Updates durch BearbeitungenS
    $uebungen = query_simple_assoc("SELECT * FROM uebung");

    if(isset($_POST['saveFiles'])) {
        $id       = $_POST['id'];          // Übungs-ID
        $fachID   = $_POST['fachID'] ?? '';
        $fileName = $_POST['file_name'] ?? '';
        $fachDir  = $_POST['fach_dir'] ?? '';

        // Basis-Pfad für diese Übung
        $basePath = $_SERVER['DOCUMENT_ROOT'] . "/faecher/" . $fachDir . "/" . $fileName;

        // Sicherstellen, dass das Verzeichnis existiert
        $dir = dirname($basePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // Aufgaben-PDF speichern
        if (!empty($_FILES['aufgabe_pdf']['tmp_name'])) {
            $target = $basePath . "_aufgaben.pdf";
            if (move_uploaded_file($_FILES['aufgabe_pdf']['tmp_name'], $target)) {
                $editOperationInfo = '<div class="fach-success-banner">📄 Aufgaben-PDF gespeichert!</div>';
            } else {
                $editOperationInfo = '<div class="fach-failure-banner">❌ Fehler beim Speichern der Aufgaben-PDF!</div>';
            }
        }

        // Lösungs-PDF speichern
        if (!empty($_FILES['loesung_pdf']['tmp_name'])) {
            $target = $basePath . "_loesungen.pdf";
            if (move_uploaded_file($_FILES['loesung_pdf']['tmp_name'], $target)) {
                $editOperationInfo = '<div class="fach-success-banner">📄 Lösungs-PDF gespeichert!</div>';
            } else {
                $editOperationInfo = '<div class="fach-failure-banner">❌ Fehler beim Speichern der Lösungs-PDF!</div>';
            }
        }

        // Textarea-Inhalt speichern
        if (isset($_POST['uebung_text'])) {
            $target = $basePath . "_uebung.txt";
            if (file_put_contents($target, $_POST['uebung_text']) !== false) {
                $editOperationInfo = '<div class="fach-success-banner">📝 Übungstext gespeichert!</div>';
            } else {
                $editOperationInfo = '<div class="fach-failure-banner">❌ Fehler beim Speichern des Übungstextes!</div>';
            }
        }
    }

    ?>


    <main id="hauptinhalt">

        <?php echo $editOperationInfo ?? ''; ?>

        <h1>Übungen verwalten</h1>

        <div class="faecher-explanation-box">
            <ul>
                <li><strong>ID:</strong> Eindeutige Kennung der Übung</li>
                <li><strong>Name:</strong> Anzeigename der Übung</li>
                <li><strong>file_name:</strong> Dateiname der Übung (z. B. PDF oder Arbeitsblatt)</li>
                <li><strong>beschreibung:</strong> Kurzbeschreibung oder Titel der Übung</li>
                <li><strong>symbol:</strong> Emoji oder Icon zur visuellen Kennzeichnung</li>
                <li><strong>kachelfarbe:</strong> Hintergrundfarbe der Übungskachel</li>
                <li><strong>fachID:</strong> Verknüpfung zum zugehörigen Fach (Fremdschlüssel)</li>
            </ul>
            Inhalte werden im Editor hinzugefügt!<br>
            PDFs müssen auch hinzugefügt werden.
        </div>

        <section class="uebung-list">
            <h2>Vorhandene Übungen</h2>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Dateiname</th>
                    <th>Beschreibung</th>
                    <th>Symbol</th>
                    <th>Kachelfarbe</th>
                    <th>Fach</th>
                    <th>Aktionen</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($uebungen as $uebung): ?>
                    <tr>
                        <form action="uebungs_manager.php" method="post">
                            <td><?= htmlspecialchars($uebung['id']) ?></td>
                            <td><input type="text" name="name" value="<?= htmlspecialchars($uebung['name']) ?>" required maxlength="40"></td>
                            <td><input type="text" name="file_name" value="<?= htmlspecialchars($uebung['file_name']) ?>" required disabled maxlength="40"></td>
                            <td><input type="text" name="beschreibung" value="<?= htmlspecialchars($uebung['beschreibung']) ?>" required maxlength="80"></td>
                            <td><input type="text" name="symbol" value="<?= htmlspecialchars($uebung['symbol']) ?>" maxlength="10"></td>
                            <td>
                                <select name="kachelfarbe" required>
                                    <option value="blue"   <?= ($uebung['kachelfarbe'] === 'blue'   ? 'selected' : '') ?>>Blau</option>
                                    <option value="yellow" <?= ($uebung['kachelfarbe'] === 'yellow' ? 'selected' : '') ?>>Gelb</option>
                                    <option value="lila"   <?= ($uebung['kachelfarbe'] === 'lila'   ? 'selected' : '') ?>>Lila</option>
                                    <option value="green"  <?= ($uebung['kachelfarbe'] === 'green'  ? 'selected' : '') ?>>Grün</option>
                                    <option value="pink"   <?= ($uebung['kachelfarbe'] === 'pink'   ? 'selected' : '') ?>>Pink</option>
                                    <option value="red"    <?= ($uebung['kachelfarbe'] === 'red'    ? 'selected' : '') ?>>Rot</option>
                                    <option value="orange" <?= ($uebung['kachelfarbe'] === 'orange' ? 'selected' : '') ?>>Orange</option>
                                </select>
                            </td>
                            <td>
                                <select name="fachID" required>
                                    <?php foreach ($faecher as $fach): ?>
                                        <option value="<?= $fach['id'] ?>" <?= ($uebung['fachID'] == $fach['id'] ? 'selected' : '') ?>>
                                            <?= htmlspecialchars($fach['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="id" value="<?= $uebung['id'] ?>">
                                <input type="hidden" name="file_name" value="<?= $uebung['file_name'] ?>">
                                <button type="submit" name="saveUebung" value="update">Speichern</button>
                                <button type="submit" name="deleteUebung" value="delete" onclick="return confirm('Übung wirklich löschen?')">Löschen</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Eingabemaske -->
        <section class="uebung-add fach-add-lila">
            <h2>Neue Übung hinzufügen</h2>
            <form action="uebungs_manager.php" method="post" class="fach-form">

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="file_name">Dateiname:</label>
                <input type="text" id="file_name" name="file_name" required>

                <label for="beschreibung">Beschreibung:</label>
                <input type="text" id="beschreibung" name="beschreibung" required>

                <label for="symbol">Symbol:</label>
                <input type="text" id="symbol" name="symbol">

                <label for="kachelfarbe">Kachelfarbe:</label>
                <select id="kachelfarbe" name="kachelfarbe" required>
                    <option value="blue">Blau</option>
                    <option value="yellow">Gelb</option>
                    <option value="lila">Lila</option>
                    <option value="green">Grün</option>
                    <option value="pink">Pink</option>
                    <option value="red">Rot</option>
                    <option value="orange">Orange</option>
                </select>

                <label for="fachID">Fach:</label>
                <select id="fachID" name="fachID" required>
                    <?php foreach ($faecher as $fach): ?>
                        <option value="<?= $fach['id'] ?>"><?= htmlspecialchars($fach['name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" name="createNewUebung" value="true">Übung hinzufügen</button>
            </form>
        </section>

        <section class="uebung-files">
            <h2>Übungspdfs und Interaktive Übungsaufgaben verwalten</h2>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Aufgaben‑PDF</th>
                    <th>Lösungs‑PDF</th>
                    <th>Editor (Textarea)</th>
                    <th>Aktionen</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($uebungen as $uebung): ?>
                    <?php
                    $result = array_filter($faecher, fn($f) => $f['id'] == $uebung['fachID']);
                    $gesuchtes_fach = reset($result);
                    $fach_dir = $gesuchtes_fach['dir_name'];
                    $path = $_SERVER['DOCUMENT_ROOT'] . "/faecher/" . $fach_dir . "/" .  $uebung['file_name'];
                    $pdf_loesungen = $path . "_loesungen.pdf";
                    $pdf_aufgaben = $path . "_aufgaben.pdf";
                    ?>
                    <tr>
                        <form action="uebungs_manager.php" method="post" enctype="multipart/form-data">
                            <td><?= htmlspecialchars($uebung['id']) ?></td>
                            <td><?= htmlspecialchars($uebung['name']) ?></td>
                            <td>
                                <input type="file" name="aufgabe.pdf" accept="application/pdf">
                                <?php
                                if(file_exists($pdf_aufgaben)) {
                                    echo "<span> PDF hinterlegt, ersetzen? </span>";
                                } else {
                                    echo "<span> PDF noch nicht hinterlegt! </span>";
                                }
                                ?>
                            </td>
                            <td>
                                <input type="file" name="loesung_pdf" accept="application/pdf">
                                <?php
                                if(file_exists($pdf_loesungen)) {
                                    echo "<span> PDF hinterlegt, ersetzen? </span>";
                                } else {
                                    echo "<span> PDF noch nicht hinterlegt! </span>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $file_path = $path . "_uebung.txt";
                                if (file_exists($file_path)) {
                                    // Inhalt ohne zusätzliche Leerzeichen/Zeilenumbrüche ausgeben
                                    $file_contents = trim(htmlspecialchars(file_get_contents($file_path)));
                                } else {
                                    $file_contents = "";
                                }
                                echo '<textarea name="uebung_text" rows="5" cols="50" placeholder="Datei‑Inhalt bearbeiten...">' . $file_contents . '</textarea>';
                                ?>
                            </td>
                            <td>
                                <input type="hidden" name="id" value="<?= $uebung['id'] ?>">
                                <input type="hidden" name="file_name" value="<?= $uebung['file_name'] ?>">
                                <input type="hidden" name="fachID" value="<?= $uebung['fachID'] ?>">
                                <input type="hidden" name="fach_dir" value="<?= $fach_dir ?>">
                                <button type="submit" name="saveFiles" value="true">Speichern</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>



    </main>


    <?php
    include("../komponenten/footer.php");
    ?>
</div>
</body>
</html>

