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
    $faecher = query_simple_assoc("SELECT * FROM hsgg.fach");

    $dir_name_block_list = [];
    foreach($faecher as $f) {
        $dir_name_block_list[] = $f['dir_name'];
    }

    $editOperationInfo = null;
    if(isset($_POST['createNewFach'])) {
        $name = $_POST["name"];
        $dir_name = $_POST["dir_name"];
        $symbol = $_POST["symbol"];
        $kachelfarbe = $_POST["kachelfarbe"];
        if(!in_array($dir_name, $dir_name_block_list)) {
            db_insert_new_fach($name, $dir_name, $symbol, $kachelfarbe);
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/faecher/' . $dir_name);
            $editOperationInfo = '<div class="fach-success-banner">🎉 Fach erfolgreich erstellt!</div>';
        } else {
            $editOperationInfo = '<div class="fach-failure-banner">❌ Fehler: Fach konnte nicht erstellt werden!</div>';
        }

    }

    if(isset($_POST['saveFach'])) {
        $id = $_POST["id"];
        $name = $_POST["name"];
        $dir_name = $_POST["dir_name"];
        $symbol = $_POST["symbol"];
        $kachelfarbe = $_POST["kachelfarbe"];
        db_update_fach($id, $name, $dir_name, $symbol, $kachelfarbe);
        $editOperationInfo = '<div class="fach-success-banner">🎉 Fach erfolgreich gespeichert!</div>';
    }

    if(isset($_POST['deleteFach'])) {
        $id = $_POST["id"];
        $dir_name = $_POST["dir_name"];
        db_delete_fach($id);
        deleteDirectory($_SERVER['DOCUMENT_ROOT'] . '/faecher/' . $dir_name);
        $editOperationInfo = '<div class="fach-success-banner">⚠️ Fach erfolgreich gelöscht!</div>';
    }

    //Neu laden um neue Daten aus ausgeführter Operation zu übernehmen
    $faecher = query_simple_assoc("SELECT * FROM hsgg.fach");
    ?>

    <main id="hauptinhalt">

        <?php
        echo $editOperationInfo ?? '';
        ?>

        <h1>Fächer bearbeiten</h1>

        <div class="faecher-explanation-box">
            <h3>Erklärung der Felder</h3>
            <ul>
                <li><strong>Name:</strong> Der sichtbare Titel des Fachs, wie er in der Lernplattform angezeigt wird.</li>
                <li><strong>dir_name:</strong> Der technische Verzeichnisname. Unter faecher/&lt;dir_name&gt;. Relevant für die Technische Administration.</li>
                <li><strong>Symbol:</strong> Ein Icon oder Emoji, das das Fach visuell kennzeichnet.</li>
                <li><strong>Kachelfarbe:</strong> Die Hintergrundfarbe der Fach‑Kachel. Wähle eine vordefinierte Designfarbe z.B. Blau, Lila, Grün).</li>
            </ul>
        </div>

        <section class="fach-list">
            <h2>Vorhandene Fächer</h2>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>dir_name</th>
                    <th>Symbol</th>
                    <th>Kachelfarbe</th>
                    <th>Aktionen</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($faecher as $fach): ?>
                    <tr>
                        <form action="fach_manager.php" method="post">
                            <td><?= htmlspecialchars($fach['id']) ?></td>
                            <td>
                                <input type="text" name="name" value="<?= htmlspecialchars($fach['name']) ?>" required maxlength="40">
                            </td>
                            <td>
                                <input type="text" name="dir_name" value="<?= htmlspecialchars($fach['dir_name']) ?>" disabled maxlength="40">
                            </td>
                            <td>
                                <input type="text" name="symbol" value="<?= htmlspecialchars($fach['symbol']) ?>" required maxlength="10">
                            </td>
                            <td>
                                <select name="kachelfarbe" class="color-select" required>
                                    <option value="blue"   <?= ($fach['kachelfarbe'] === 'blue'   ? 'selected' : '') ?>>Blau</option>
                                    <option value="yellow" <?= ($fach['kachelfarbe'] === 'yellow' ? 'selected' : '') ?>>Gelb</option>
                                    <option value="lila"   <?= ($fach['kachelfarbe'] === 'lila'   ? 'selected' : '') ?>>Lila</option>
                                    <option value="green"  <?= ($fach['kachelfarbe'] === 'green'  ? 'selected' : '') ?>>Grün</option>
                                    <option value="pink"   <?= ($fach['kachelfarbe'] === 'pink'   ? 'selected' : '') ?>>Pink</option>
                                    <option value="red"    <?= ($fach['kachelfarbe'] === 'red'    ? 'selected' : '') ?>>Rot</option>
                                    <option value="orange" <?= ($fach['kachelfarbe'] === 'orange' ? 'selected' : '') ?>>Orange</option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="id" value="<?= $fach['id'] ?>">
                                <input type="hidden" name="dir_name" value="<?= $fach['dir_name'] ?>">
                                <button type="submit" name="saveFach" value="update">Speichern</button>
                                <button type="submit" name="deleteFach" value="delete" onclick="return confirm('Fach wirklich löschen?')">Löschen</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Eingabemaske -->
        <section class="fach-add fach-add-lila">
            <h2>Neues Fach hinzufügen</h2>
            <form action="fach_manager.php" method="post" class="fach-form">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required maxlength="40">

                <label for="dir_name">Verzeichnisname (dir_name) dar nicht '/' enthalten und muss einzigartig sein:</label>
                <input type="text" id="dir_name" name="dir_name" required maxlength="40">

                <label for="symbol">Symbol (Emoji oder Icon):</label>
                <input type="text" id="symbol" name="symbol" placeholder="z. B. 📘 oder ➗" required maxlength="10">

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

                <button type="submit" name="createNewFach" value="true">Fach hinzufügen</button>
            </form>
        </section>

    </main>

    <?php
    include("../komponenten/footer.php");
    ?>
</div>
</body>
</html>
