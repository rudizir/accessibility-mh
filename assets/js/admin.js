
document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-theme]');
    if (!btn) return;

    fetch(ajaxurl, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            action: 'mhacc_widget_set_theme',
            mode: btn.dataset.theme,
            _ajax_nonce: MHACC_WIDGET_THEME.nonce
        })
    }).then(() => location.reload());
});


document.addEventListener('DOMContentLoaded', () => {

    // 🔹 Hilfsfunktion: passende Textfarbe berechnen
    function getTextColorForBackground(hex) {
        if (!hex || hex.length < 7) return '#000000';

        const r = parseInt(hex.substr(1, 2), 16);
        const g = parseInt(hex.substr(3, 2), 16);
        const b = parseInt(hex.substr(5, 2), 16);

        const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
        return luminance > 0.5 ? '#000000' : '#ffffff';
    }

    // 🔹 Referenz auf das Schriftfarben-Feld (global, eindeutig)
    const fontColorField = document.querySelector(
        'input[name="mhacc_settings[theme_primary_fontcolor]"]'
    );
    const fontColorPicker = fontColorField
        ? fontColorField.closest('.mhacc-color-field')?.querySelector('.mhacc-color-picker')
        : null;

    document.querySelectorAll('.mhacc-color-field').forEach(field => {

        const textInput   = field.querySelector('.mhacc-color-input');
        const colorPicker = field.querySelector('.mhacc-color-picker');
        const radios      = field.querySelectorAll('.mhacc-color-swatch input[type="radio"]');

        if (!textInput || !colorPicker) return;

        const isPrimaryColor =
            textInput.name === 'mhacc_settings[theme_primary_color]';

        /**
         * 🔁 alles synchron setzen
         */
        const setColor = (value, autoFont = false) => {
            if (!value) return;

            textInput.value = value;
            colorPicker.value = value;

            radios.forEach(radio => {
                radio.checked = radio.value.toLowerCase() === value.toLowerCase();
            });

            // 🔥 Auto-Schriftfarbe nur bei Primärfarbe
            if (autoFont && isPrimaryColor && fontColorField) {
                const fontColor = getTextColorForBackground(value);

                fontColorField.value = fontColor;
                if (fontColorPicker) {
                    fontColorPicker.value = fontColor;
                }
            }
        };

        /**
         * TEXT → alles
         */
        textInput.addEventListener('input', () => {
            const value = textInput.value.trim();
            if (/^#([0-9A-F]{3}){1,2}$/i.test(value)) {
                setColor(value, true);
            }
        });

        /**
         * PICKER → alles
         */
        colorPicker.addEventListener('input', () => {
            setColor(colorPicker.value, true);
        });

        /**
         * PRESET → alles
         */
        radios.forEach(radio => {
            radio.addEventListener('change', () => {
                if (radio.checked) {
                    setColor(radio.value, true);
                }
            });
        });

    });

});
