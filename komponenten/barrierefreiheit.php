<!-- Floating Button -->
<div id="a11y-floating-btn">
    Ⓐ
</div>


<!-- STOP-Button für Vorlesen -->
<div id="a11y-stop-reading-btn" class="hidden">
    ⛔
</div>



<!-- Hintergrund (grau) -->
<div id="a11y-overlay" class="hidden"></div>

<!-- Einstellungen/Hauptpanel -->
<div id="a11y-menu" class="hidden">

    <h2>Barrierefreiheit</h2>

    <div class="a11y-option" onclick="toggleFontMenu()">
        <span class="a11y-icon">🔤</span>
        <span>Schriftgröße</span>
    </div>
    <!-- versteckter Slider für Schriftgröße -->
    <div id="font-slider-box" class="hidden a11y-submenu">
        <input id="font-slider" type="range" min="80" max="160" step="5" value="100">
        <span id="font-slider-value">100%</span>
    </div>



    <div class="a11y-option" onclick="toggleContrast()">
        <span class="a11y-icon">🎨</span>
        <span>Kontrastmodus</span>
    </div>

    <div class="a11y-option" onclick="toggleBlueFilter()">
        <span class="a11y-icon">🔵</span>
        <span>Blaufilter</span>
    </div>

    <div class="a11y-option" onclick="toggleDarkMode()">
        <span class="a11y-icon">◐</span>
        <span>Dark Mode</span>
    </div>

    <div class="a11y-option" onclick="readPageAloud()">
        <span class="a11y-icon">🎤</span>
        <span>Webseite vorlesen</span>
    </div>

    <div class="a11y-option" onclick="toggleLowSaturation()">
        <span class="a11y-icon">⚫</span>
        <span>Farbschwäche</span>
    </div>

    <div class="a11y-option" onclick="toggleImages()">
        <span class="a11y-icon">🖼️</span>
        <span>Bilder ausblenden</span>
    </div>

    <div class="a11y-option reset" onclick="resetA11y()">
        <span class="a11y-icon">🔄</span>
        <span>Alles zurücksetzen</span>
    </div>

</div>

<script>
    // UI
    const btn = document.getElementById("a11y-floating-btn");
    const menu = document.getElementById("a11y-menu");
    const overlay = document.getElementById("a11y-overlay");

    // Schriftgröße
    const fontBox = document.getElementById("font-slider-box");
    const fontSlider = document.getElementById("font-slider");
    const fontValue = document.getElementById("font-slider-value");

    btn.onclick = () => {
        menu.classList.toggle("hidden");
        overlay.classList.toggle("hidden");
    };

    overlay.onclick = () => {
        menu.classList.add("hidden");
        overlay.classList.add("hidden");
    };

    /* --- Schriftgrößen Menü öffnen --- */
    function toggleFontMenu() {
        fontBox.classList.toggle("hidden");
    }

    /* --- Schriftgröße ändern --- */
    fontSlider.oninput = () => {
        let val = fontSlider.value;

        document.body.style.fontSize = val + "%";
        fontValue.innerText = val + "%";

        sessionStorage.setItem("fontSize", val);
    };

    function loadState() {
        let fz = sessionStorage.getItem("fontSize");
        if (fz) {
            document.body.style.fontSize = fz + "%";
            fontSlider.value = fz;
            fontValue.innerText = fz + "%";
        }
    }
    loadState();

    /* --- Kontrastmodus --- */
    function toggleContrast() {
        document.body.classList.toggle("a11y-contrast");
    }

    /* --- Blaufilter --- */
    function toggleBlueFilter() {
        document.body.classList.toggle("a11y-blue-filter");
    }

    /* --- Dark Mode --- */
    function toggleDarkMode() {
        document.body.classList.toggle("a11y-dark");

        // Zustand speichern
        if (document.body.classList.contains("a11y-dark")) {
            localStorage.setItem("darkmode", "on");
        } else {
            localStorage.setItem("darkmode", "off");
        }
    }
    function loadState() {
        // Schriftgröße aus sessionStorage laden
        let fz = sessionStorage.getItem("fontSize");
        if (fz) {
            document.body.style.fontSize = fz + "%";
            fontSlider.value = fz;
            fontValue.innerText = fz + "%";
        }

        //Darkmode aus localStorage laden
        if (localStorage.getItem("darkmode") === "on") {
            document.body.classList.add("a11y-dark");
        }
    }
    loadState();



    /* --- Webseite vorlesen --- */
    let readingMsg = null; // aktuell laufende Sprachnachricht

    function readPageAloud() {

        // Inhalt, der gelesen werden soll → Nur MAIN
        const text = document.getElementById("hauptinhalt").innerText;

        if (!text.trim()) return;

        // Falls schon am Lesen → abbrechen
        if (speechSynthesis.speaking) return;

        readingMsg = new SpeechSynthesisUtterance(text);
        readingMsg.lang = "de";

        // Stop-Button einblenden
        document.getElementById("a11y-stop-reading-btn").classList.remove("hidden");

        readingMsg.onend = () => {
            // Stop-Button ausblenden wenn fertig
            document.getElementById("a11y-stop-reading-btn").classList.add("hidden");
        };

        speechSynthesis.speak(readingMsg);
    }

    // STOP Button Funktion
    document.getElementById("a11y-stop-reading-btn").onclick = () => {
        speechSynthesis.cancel(); // stoppt alles sofort

        // Button wieder verstecken
        document.getElementById("a11y-stop-reading-btn").classList.add("hidden");
    };



    /* --- Farbschwäche --- */
    function toggleLowSaturation() {
        document.body.classList.toggle("a11y-low-saturation");
    }

    /* --- Bilder ausblenden --- */
    function toggleImages() {
        document.body.classList.toggle("a11y-hide-images");
    }

    /* --- Alles zurücksetzen --- */
    function resetA11y() {
        // Schriftgröße zurücksetzen
        document.body.style.fontSize = "100%";
        sessionStorage.clear();

        // Alle Modi ausschalten
        document.body.classList.remove(
            "a11y-contrast",
            "a11y-blue-filter",
            "a11y-dark",
            "a11y-low-saturation",
            "a11y-hide-images"
        );

        // Darkmode im localStorage zurücksetzen
        localStorage.setItem("darkmode", "off");

        // Schriftregler zurücksetzen
        fontSlider.value = 100;
        fontValue.innerText = "100%";

        // Vorlesen stoppen
        speechSynthesis.cancel();
        // Vorlesen Stop-Button ausblenden
        document.getElementById("a11y-stop-reading-btn").classList.add("hidden");
    }






</script>


