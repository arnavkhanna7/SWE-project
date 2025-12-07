-- Installationsskript für die Datenbank des HSGG-LernHero

DROP DATABASE IF EXISTS hsgg;

CREATE DATABASE hsgg
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE hsgg;

CREATE TABLE fach (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(40) NOT NULL,
    dir_name VARCHAR(40) NOT NULL, -- Name des Ordners in dem Fach gespeichert wird
    symbol VARCHAR(10),
    kachelfarbe VARCHAR(20) NOT NULL
);

CREATE TABLE uebung(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(40) NOT NULL,
    file_name VARCHAR(40) NOT NULL, -- Name für Datei die geladen werden muss bzw. PDF benennung i.e. 'algebra' => 'algebra.php'/'algebra_aufgaben.pdf'
    beschreibung VARCHAR(80) NOT NULL,
    symbol VARCHAR(10),
    kachelfarbe VARCHAR(20) NOT NULL,
    explanation_box TEXT NOT NULL,
    tips_box TEXT NOT NULL,
    fachID INT,

    FOREIGN KEY (fachID) REFERENCES fach(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);


-- Startfaecher:

INSERT INTO fach (name, symbol, dir_name, kachelfarbe) VALUES ('Mathe', '➗', 'mathe', 'blue'); -- kachelfarbe wird dann mit vordefinierter CSS Klasse gefärbt
INSERT INTO fach (name, symbol, dir_name, kachelfarbe) VALUES ('Deutsch', '📘', 'deutsch', 'yellow');

INSERT INTO uebung (name, file_name, beschreibung, symbol, kachelfarbe, explanation_box, tips_box, fachID)
VALUES ('Bruchrechnung', 'bruchrechnung', 'Übungen zur Addition und Subtraktion von Brüchen','➗','pink',
        '<h3>➗ Was ist Bruchrechnung?</h3>
        <p>
            Brüche sind Zahlen, die einen Teil eines Ganzen darstellen. Sie bestehen aus einem
            <strong>Zähler</strong> (die Zahl oben) und einem <strong>Nenner</strong> (die Zahl unten).
        </p>

        <div class="example">
            <strong>Beispiel:</strong> Bei dem Bruch <sup>3</sup>&frasl;<sub>4</sub> bedeutet dies:
            <br>• Der Zähler ist <strong>3</strong> (wir haben 3 Teile)
            <br>• Der Nenner ist <strong>4</strong> (das Ganze wurde in 4 Teile geteilt)
            <br>• Also haben wir 3 von 4 gleichen Teilen
        </div>

        <p style="margin-top: 1.5rem;">
            <strong>Wichtige Regeln:</strong>
            <br>✓ Beim Addieren und Subtrahieren müssen die Nenner gleich sein
            <br>✓ Zum Kürzen teilst du Zähler und Nenner durch die gleiche Zahl
            <br>✓ Zum Erweitern multiplizierst du Zähler und Nenner mit der gleichen Zahl
        </p>',
        '<h4>💡 Tipps für erfolgreiches Üben</h4>
        <ul>
            <li>Nimm dir Zeit und arbeite Schritt für Schritt</li>
            <li>Schreibe immer den Rechenweg auf, nicht nur das Ergebnis</li>
            <li>Prüfe am Ende, ob dein Bruch noch gekürzt werden kann</li>
            <li>Bei Fehlern: Schaue dir die Lösung an und versuche zu verstehen, wo der Fehler war</li>
            <li>Übe regelmäßig - auch nur 10 Minuten pro Tag helfen!</li>
        </ul>',
        1);

INSERT INTO uebung (name, file_name, beschreibung, symbol, kachelfarbe, explanation_box, tips_box, fachID)
VALUES ('Geometrie','geometrie', 'Grundlagen zu Flächen und Winkeln','📐','green',
        '<h3>📐 Was ist Geometrie?</h3>
        <p>
            Geometrie beschäftigt sich mit <strong>Formen, Flächen und Winkeln</strong>.
            Wir lernen, wie man Flächen berechnet und wie groß Winkel sind.
        </p>

        <div class="example">
            <strong>Beispiel:</strong> Ein Rechteck hat eine Länge von 5 cm und eine Breite von 3 cm.
            <br>• <strong>Fläche:</strong> Länge · Breite = 5 cm · 3 cm = 15 cm²
            <br>• <strong>Umfang:</strong> 2 · Länge + 2 · Breite = 2 · 5 + 2 · 3 = 16 cm
        </div>

        <p style="margin-top: 1.5rem;">
            <strong>Wichtige Formeln:</strong>
            <br>✓ <strong>Rechteck:</strong> Fläche = Länge · Breite, Umfang = 2 · (Länge + Breite)
            <br>✓ <strong>Quadrat:</strong> Fläche = Seite · Seite, Umfang = 4 · Seite
            <br>✓ <strong>Dreieck:</strong> Alle Winkel zusammen ergeben 180°
        </p>',
        '<h4>💡 Tipps für Geometrie</h4>
        <ul>
            <li>Zeichne immer eine Skizze - das hilft beim Verstehen!</li>
            <li>Schreibe die Formel auf, bevor du rechnest</li>
            <li>Achte auf die Einheiten (cm, cm², m, m²)</li>
            <li>Bei Winkeln: Alle Winkel im Dreieck ergeben zusammen 180°</li>
            <li>Prüfe dein Ergebnis: Ist es realistisch?</li>
        </ul>',
        1);

INSERT INTO uebung (name, file_name, beschreibung, symbol, kachelfarbe, explanation_box, tips_box, fachID)
VALUES ('Algebra', 'algebra', 'Lineare Gleichungen und Variablen','🔢','blue',
        '<h3>🔢 Was ist Algebra?</h3>
        <p>
            Algebra ist das Rechnen mit <strong>Variablen</strong> (Platzhaltern wie x, y, a, b).
            Statt nur mit Zahlen zu rechnen, verwenden wir Buchstaben, die für unbekannte Zahlen stehen.
        </p>

        <div class="example">
            <strong>Beispiel:</strong> Die Gleichung x + 3 = 7 bedeutet:
            <br>• Eine unbekannte Zahl <strong>x</strong> plus 3 ergibt 7
            <br>• Um x zu finden, rechnen wir: x = 7 - 3
            <br>• Also: x = 4
            <br>• <strong>Probe:</strong> 4 + 3 = 7 ✓
        </div>

        <p style="margin-top: 1.5rem;">
            <strong>Wichtige Regeln:</strong>
            <br>✓ Gleiche Variablen kann man zusammenfassen (3x + 2x = 5x)
            <br>✓ Was du auf einer Seite machst, musst du auch auf der anderen Seite machen
            <br>✓ Ziel: Die Variable alleine auf einer Seite haben
        </p>',
        '<h4>💡 Tipps für Algebra</h4>
        <ul>
            <li>Schreibe immer alle Rechenschritte auf, nicht nur das Endergebnis</li>
            <li>Mache eine Probe: Setze dein Ergebnis in die Gleichung ein</li>
            <li>Merke: Was links vom = steht, muss gleich viel wert sein wie rechts</li>
            <li>Erst die Zahl wegrechnen, dann durch die Zahl vor dem x teilen</li>
            <li>Bei Textaufgaben: Überlege zuerst, was x sein soll</li>
        </ul>',
        1);

INSERT INTO uebung (name, file_name, beschreibung, symbol, kachelfarbe, explanation_box, tips_box, fachID)
VALUES ('Textaufgaben','textaufgaben', 'Knifflige Aufgaben zum logischen Denken','📝','yellow',
        '<h3>📝 Was sind Textaufgaben?</h3>
        <p>
            Textaufgaben sind <strong>Rechenaufgaben aus dem Alltag</strong>, die als Text formuliert sind.
            Du musst herausfinden, welche Rechnung gebraucht wird und dann lösen.
        </p>

        <div class="example">
            <strong>Beispiel:</strong> Lisa kauft 3 Hefte für je 2 Euro. Wie viel bezahlt sie insgesamt?
            <br><br>
            <strong>Schritt 1:</strong> Was ist gesucht? → Der Gesamtpreis
            <br><strong>Schritt 2:</strong> Welche Zahlen habe ich? → 3 Hefte, 2 Euro pro Heft
            <br><strong>Schritt 3:</strong> Welche Rechnung? → 3 · 2 Euro
            <br><strong>Schritt 4:</strong> Rechnen → 3 · 2 = 6 Euro
            <br><strong>Schritt 5:</strong> Antwort → Lisa bezahlt 6 Euro.
        </div>

        <p style="margin-top: 1.5rem;">
            <strong>Wichtige Tipps:</strong>
            <br>✓ Lies die Aufgabe zweimal genau durch
            <br>✓ Markiere die wichtigen Zahlen und Wörter
            <br>✓ Überlege: Was ist gesucht?
            <br>✓ Schreibe immer eine vollständige Antwort mit Einheit
        </p>',
        '<h4>💡 Tipps für Textaufgaben</h4>
        <ul>
            <li>Lies die Aufgabe zweimal - einmal schnell, einmal ganz genau</li>
            <li>Unterstreiche oder markiere alle Zahlen in der Aufgabe</li>
            <li>Frage dich: Was soll ich ausrechnen?</li>
            <li>Schreibe die Rechnung UND die Antwort auf (mit Einheit!)</li>
            <li>Überlege: Ist mein Ergebnis sinnvoll?</li>
            <li>Keine Angst vor langen Texten - lies Schritt für Schritt!</li>
        </ul>',
        1);

