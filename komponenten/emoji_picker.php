<?php
$availableEmojis = [
    '📚', '📖', '📘', '📙', '📕', '📗', '📓', '📔',
    '✏️', '📝', '🖊️', '🖋️', '✒️', '📐', '📏', '📎',
    '🧮', '🔢', '➕', '➖', '✖️', '➗', '∞', 'π',
    '🔬', '🧪', '⚗️', '🧫', '🔭', '🌡️', '⚛️', '🧲',
    '🌍', '🗺️', '🏔️', '🌋', '🏜️', '🏝️', '🌅', '🌄',
    '🏛️', '🗽', '🏟️', '🏰', '💂', '👑', '⚔️', '🛡️',
    '🎨', '🖼️', '🎭', '🎪', '🎤', '🎹', '🎷', '🎺',
    '⚽', '🏀', '🏈', '⚾', '🎾', '🏐', '🏓', '🏸',
    '💻', '🖥️', '⌨️', '🖱️', '💾', '📱', '📲', '🔌',
    '🔧', '⚙️', '🔨', '🛠️', '⛏️', '🔩', '⚒️', '🪛',
    '🧠', '💡', '🔍', '📌', '📍', '🎯', '⭐', '🌈',
    '🏆', '🎖️', '🥇', '🥈', '🥉', '🎓', '📜', '🏅'
];
?>

<script defer>
    document.addEventListener('DOMContentLoaded', function() {
        // Funktion zum Schließen aller Dropdowns
        function closeAllDropdowns() {
            document.querySelectorAll('.emoji-dropdown').forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        }

        // Emoji-Picker für alle vorhandenen Fächer (Tabellenzeilen)
        document.querySelectorAll('.emoji-select-wrapper').forEach(wrapper => {
            const preview = wrapper.querySelector('.emoji-preview');
            const dropdown = wrapper.querySelector('.emoji-dropdown');
            const hiddenInput = wrapper.querySelector('input[name="symbol"]');
            const display = wrapper.querySelector('.emoji-display');

            // Öffnen/Schließen des Dropdowns
            preview.addEventListener('click', function(e) {
                e.stopPropagation();
                closeAllDropdowns();
                dropdown.classList.toggle('active');
            });

            // Emoji auswählen
            const emojiOptions = dropdown.querySelectorAll('.emoji-option');
            emojiOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const emoji = this.getAttribute('data-emoji');
                    hiddenInput.value = emoji;
                    display.innerHTML = emoji;

                    // Markiere ausgewählte Option
                    emojiOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');

                    dropdown.classList.remove('active');
                });
            });

            // Initiale Markierung des ausgewählten Emojis
            const currentEmoji = hiddenInput.value;
            if (currentEmoji) {
                const selectedOption = dropdown.querySelector(`.emoji-option[data-emoji="${currentEmoji}"]`);
                if (selectedOption) {
                    selectedOption.classList.add('selected');
                }
            }
        });

        // Schließe alle Dropdowns beim Klicken außerhalb
        document.addEventListener('click', function() {
            closeAllDropdowns();
        });

        // Verhindere, dass Klicks im Dropdown es schließen
        document.querySelectorAll('.emoji-dropdown').forEach(dropdown => {
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

    });
</script>
