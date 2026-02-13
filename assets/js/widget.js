(function (window) {
    "use strict";

    const AVAILABLE_LANGUAGES = MHACC_WIDGET_CONFIG.languages || {};

    function cacheTextBaseValues() {
        document.querySelectorAll(MHACC_TEXT_ELEMENTS).forEach(el => {
            if (MHACC.isInsidePanel(el)) return;
            if (!el.dataset.mhaccFontBase) {
                el.dataset.mhaccFontBase = parseFloat(window.getComputedStyle(el).fontSize);
            }
            if (!el.dataset.mhaccLineBase) {
                const lh = window.getComputedStyle(el).lineHeight;
                el.dataset.mhaccLineBase = lh === "normal" 
                    ? parseFloat(window.getComputedStyle(el).fontSize) * 1.2
                    : parseFloat(lh);
            }
            if (!el.dataset.mhaccSpacingBase) {
                const ls = window.getComputedStyle(el).letterSpacing;
                el.dataset.mhaccSpacingBase = ls !== "normal" ? parseFloat(ls) : 0;
            }
        });
    }



    /**
     * Globaler Namespace
     */
    const MHACC = {
        config: window.MHACC_WIDGET_CONFIG || {},
        design: {},
        features: {},

        /**
         * Init-Pipeline
         */
        init() {
            this.loadDesign();
            this.loadFeatures();
            this.loadUIState();
            injectBaseStyles();
            this.ui.mount();
            this.applyUIState();
            this.featuresRenderer.render(this.features);
            MHACC.applyTranslations();
            this.dispatchReady();
            

            this.applyFeatures();
            this.applyFeatureEffects(); 
            cacheTextBaseValues();

            this.keyboard.init();
        },
        loadDesign() {
            this.design = this.config.settings?.design || {};
            //console.log("[MHACC] Design geladen:", this.design);
        },

        loadFeatures() {
            const defaults = this.config.settings?.features || {};
            const user     = MHACC.storage.load();

            this.features = structuredClone(defaults);

            Object.entries(this.features).forEach(([groupId, group]) => {
                Object.entries(group.fields || {}).forEach(([key, feature]) => {
                    // Immer Defaultwert setzen
                    feature.value = feature.value ?? feature.default ?? 0;

                    // Dann Userwert überschreiben, falls vorhanden
                    if (user[key] !== undefined) {
                        feature.value = user[key];
                    }
                });
            });

            //console.log("[MHACC] Features geladen & gemerged:", this.features);
        },

        dispatchReady() {
            document.dispatchEvent(
                new CustomEvent("mhacc:ready", { detail: this })
            );
        }
    };  


    const ICONS = MHACC.config.icons || {};
    //console.log(ICONS);



    MHACC.debugState = function () {
        console.table(
            Object.values(MHACC.features).flatMap(g =>
                Object.entries(g.fields).map(([k, f]) => ({
                    feature: k,
                    value: f.value
                }))
            )
        );
    };



    // --- LOCALSTORAGE ---
    MHACC.storage = {
        KEY: "mhacc_widget_user_settings",

        /**
         * Alle gespeicherten User-Settings laden
         */
        load() {
            try {
                return JSON.parse(localStorage.getItem(this.KEY)) || {};
            } catch (e) {
                //console.warn("[MHACC] LocalStorage defekt, reset");
                return {};
            }
        },

        /**
         * Komplett speichern
         */
        save(data) {
            localStorage.setItem(this.KEY, JSON.stringify(data));
        },

        /**
         * Einzelnes Setting setzen
         */
        set(key, value) {
            const data = this.load();
            data[key] = value;
            this.save(data);
        },

        /**
         * Einzelnes Setting holen
         */
        get(key, fallback = null) {
            return this.load()?.[key] ?? fallback;
        },

        /**
         * Einzelnes Setting löschen
         */
        remove(key) {
            const data = this.load();
            delete data[key];
            this.save(data);
        },

        /**
         * Alles löschen (RESET)
         */
        clear() {
            localStorage.removeItem(this.KEY);
            //console.log("[MHACC] User settings gelöscht");
        }
    };

    // --- RESETLOCALSTORAGE ---
    document.addEventListener("click", (e) => {
        const btn = e.target.closest('[data-action="mhacc_storage_reset"]');
        if (!btn) return;

        console.log('RESET CLICK');

        // 1️⃣ Speicher löschen
        MHACC.storage.clear();

        // 2️⃣ Feature-Werte auf Default zurücksetzen
        Object.values(MHACC.features).forEach(group => {
            if (!group?.fields) return;
            Object.entries(group.fields).forEach(([key, feature]) => {
                if (feature.type === "toggle") feature.value = feature.default ?? false;
                if (feature.type === "slider") feature.value = feature.jsdefault ?? 100;
                if (feature.type === "select") feature.value = feature.jsdefault ?? null;
            });
        });

        // 3️⃣ Feature-Effekte zurücksetzen
        MHACC.featureEffects.font_size(100);
        MHACC.featureEffects.line_height(100);
        MHACC.featureEffects.letter_spacing(100);

        // 4️⃣ Alle Buttons zurücksetzen
        document.querySelectorAll(".mhacc-feature-btn, .mhacc-feature-btn-select, .select-data-wrap-item").forEach(btn => {
            btn.classList.remove("is-active");
        });



        // 5️⃣ Slider-UI zurücksetzen
        document.querySelectorAll(".mhacc-feature-btn-slider").forEach(slider => {
            const valueEl = slider.querySelector(".mhacc-slider-value");
            if (valueEl) valueEl.textContent = 100;
        });

        // 6️⃣ Body-Klassen bereinigen
        document.body.className = document.body.className
            .split(" ")
            .filter(c => !c.startsWith("mhacc--"))
            .join(" ");

        // 7️⃣ Inline-Styles entfernen
        MHACC.removeFontSize();
        MHACC.removeLineHeight();
        MHACC.removeLetterSpacing();

        // 8️⃣ Features erneut anwenden, damit nur die Defaults gesetzt werden
        MHACC.applyFeatures();
        MHACC.applyFeatureEffects();

        // 9️⃣ Reset Event dispatchen
        document.dispatchEvent(new CustomEvent("mhacc:reset"));
    });



    // --- STYLES ---
    function injectBaseStyles() {

        if (document.getElementById("mhacc-widget-styles")) return;

        const style = document.createElement("style");
        style.id = "mhacc-widget-styles";
        style.setAttribute("data-layer", "free");

        style.textContent = `
            :root {
                --mhacc_primarycolor:${MHACC.design.widget_design.fields.theme_primary_color.value};
                --mhacc_fontcolor:${MHACC.design.widget_design.fields.theme_primary_fontcolor.value};
            }
            @keyframes triggerPulse {
                0% {
                    box-shadow: 0 0 0 0 ${MHACC.design.widget_design.fields.theme_primary_color.value}90;
                }
                70% {
                    box-shadow: 0 0 0 14px ${MHACC.design.widget_design.fields.theme_primary_color.value}00;
                }
                100% {
                    box-shadow: 0 0 0 0 ${MHACC.design.widget_design.fields.theme_primary_color.value}00;
                }
            }
            #mhacc-panel {
                border-radius: ${MHACC.design.widget_design.fields.border_radius.value}px;
            }
            #mhacc-panel[data-size="large"]{
                ${MHACC.design.trigger_design.fields.position_x.value.position}: 0;
                bottom:0;
            }
            .mhacc-panel-group {
                border-radius: ${MHACC.design.widget_design.fields.border_radius.value}px;
            }
            #mhacc-trigger[data-positionx="right"]{
               right:${MHACC.design.trigger_design.fields.position_x.value.value}${MHACC.design.trigger_design.fields.position_x.value.unit};
            }
            #mhacc-trigger[data-positionx="left"]{
                left:${MHACC.design.trigger_design.fields.position_x.value.value}${MHACC.design.trigger_design.fields.position_x.value.unit};
            }
            /* TRIGGER Y-POSITION */
            #mhacc-trigger[data-positiony="top"]{
                top:${MHACC.design.trigger_design.fields.position_y.value.value}${MHACC.design.trigger_design.fields.position_y.value.unit};
            }
            #mhacc-trigger[data-positiony="bottom"]{
                bottom:${MHACC.design.trigger_design.fields.position_y.value.value}${MHACC.design.trigger_design.fields.position_y.value.unit};
            }
        `;

        document.head.appendChild(style);
    }





    MHACC.i18n = {
        lang: MHACC_WIDGET_CONFIG.lang || "en",

        get dict() {
            return (
                MHACC_WIDGET_CONFIG.languages?.[this.lang]?.translations ||
                MHACC_WIDGET_CONFIG.languages?.en?.translations ||
                {}
            );
        },

        t(key, fallback = "") {
            return this.dict[key] ?? fallback ?? key;
        },

        tGroup(groupId, prop, fallback = "") {
            return this.dict.groups?.[groupId]?.[prop]
                ?? fallback
                ?? `${groupId}.${prop}`;
        }
    };


    // --- BUILD UI  ---
    MHACC.ui = {

        mount() {
            if (!document.getElementById("mhacc-panel")) {
                document.body.appendChild(this.buildPanel());
                this.bindPanelActions();
            }

            if (!document.getElementById("mhacc-trigger")) {
                document.body.appendChild(this.buildTrigger());
            }
        },

        buildPanel() {
            const panel = document.createElement("nav");
            panel.id = "mhacc-panel";
            /* === DATA ATTRIBUTES === */
            const fields = MHACC.design?.widget_design?.fields || {};
            const triggerfields = MHACC.design?.trigger_design?.fields || {};

            panel.dataset.style = fields.widget_style?.value || "style_1";
            panel.dataset.size = fields.widget_size?.value || "medium";
            panel.dataset.mode = fields.theme_mode?.value || "light";
            panel.dataset.positionx = triggerfields.position_x?.value?.position || "right";
                

            const langMenue = document.createElement("div");
            langMenue.classList.add("mhacc-langmenu-wrap");

            if ( AVAILABLE_LANGUAGES && typeof AVAILABLE_LANGUAGES === "object" ) { 

                //console.log(AVAILABLE_LANGUAGES);

                Object.entries(AVAILABLE_LANGUAGES).forEach(([key, lang]) => {
                    const langBtn = document.createElement("button");

                    langBtn.type = "button";
                    langBtn.classList.add("mhacc-langmenu-item");
                    langBtn.dataset.lang = key;
                    langBtn.dataset.action = 'change_lang';

                    //console.log(key);
                    //console.log(lang.title);

                    // Titel aus PHP
                    langBtn.textContent = lang.title;

                    // optional: aktive Sprache markieren
                    if (key === MHACC.uiState.lang) {
                        langBtn.classList.add("is-active");
                    }

                    langMenue.appendChild(langBtn);
                });
            }
            //console.log(langMenue);


            panel.innerHTML = `
                <div id="mhacc-panel-header">
                    <div id="mhacc-panel-header-first">
                        
                            <strong id="mhacc-panel-title" data-i18n="title" >${MHACC.i18n.t("title")}</strong>

                        
                            <button aria-label="Open Languages Menue" id="btn_mhacc_toogle_lang" data-action="toogle_lang">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"></path>
                                </svg>
                                <div id="lang_items"></div>
                            </button>
                
                            
                            <button aria-label="Open User Options" data-action="toogle_user_options">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                                </svg>
                            </button>

                            <button id="mhacc-help-btn" aria-label="Hilfe und Informationen öffnen" data-action="open_help" title="Hilfe / Tastaturbefehle">
                                <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="8" cy="8" r="7" stroke="currentColor" fill="none"/>
                                    <path d="M6.5 6.2c0-1 .8-1.6 1.6-1.6.9 0 1.7.6 1.7 1.6 0 .9-.6 1.3-1.2 1.7-.5.3-.8.7-.8 1.2v.3" 
                                        stroke="currentColor" fill="none" stroke-linecap="round"/>
                                    <circle cx="8" cy="11.3" r=".8" fill="currentColor"/>
                                </svg>
                            </button>

                            <button aria-label="Widget close" data-action="close">${ICONS["icon-close"]}</button>
                        
                    </div>
                    <div id="user_options">

                        <div id="set_panel_light_dark" class="user_option_wrap">
                            <button type="button" role="button" aria-label="Change Widgetstyle to light" data-action="change_mode" data-value="light"> ${ICONS["icon-sun"]} </button>
                            <button type="button" role="button" aria-label="Change Widgetstyle to dark" data-action="change_mode" data-value="dark"> ${ICONS["icon-moon"]} </button>
                        </div>

                        <div id="set_panel_left_right" class="user_option_wrap">
                            <button type="button" role="button" aria-label="Change Widgetposition to left" data-action="change_widget_positionx" data-value="left"> ${ICONS["icon-position-left"]} </button>
                            <button type="button" role="button" aria-label="Change Widgetposition to right" data-action="change_widget_positionx" data-value="right"> ${ICONS["icon-position-right"]} </button>
                        </div>

                        <div id="set_panel_size" class="user_option_wrap">
                            <button type="button" role="button" aria-label="Change Widgetsize to small" data-action="change_widget_size" data-value="small"> ${ICONS["icon-widget-small"]} </button>
                            <button type="button" role="button" aria-label="Change Widgetsize to medium" data-action="change_widget_size" data-value="medium"> ${ICONS["icon-widget-medium"]} </button>
                            <button type="button" role="button" aria-label="Change Widgetsize to large" data-action="change_widget_size" data-value="large"> ${ICONS["icon-widget-large"]} </button>
                        </div>

                        <div id="set_panel_style" class="user_option_wrap">
                            <button type="button" role="button" aria-label="Change Widgetstyle to gridview" data-action="change_widget_style" data-value="style_1"> ${ICONS["icon-style_1"]} </button>
                            <button type="button" role="button" aria-label="Change Widgetstyle to listview" data-action="change_widget_style" data-value="style_2"> ${ICONS["icon-style_2"]} </button>
                        </div>

                    </div>
                </div>

                <div id="mhacc-panel-features"></div>

                <div id="mhacc-panel-footer">
                    
                    <div id="mhacc-misc-wrap">
                        <button id="mhacc-misc" data-action="mhacc_storage_reset" type="button" role="button" aria-label="Widget Reset"><span id="mhacc-misc-text" data-i18n="misc">${MHACC.i18n.t("misc")}</span></button>
                    </div>

                    <div id="mhacc-panel-footer-last">
                        ${
                        MHACC.design.general.fields.accessibility_statement_url.value !== ''
                            ? `<a id="mhacc_accessibility_link" href="${MHACC.design.general.fields.accessibility_statement_url.value}" rel="noopener">
                                <span id="mhacc-statement" data-i18n="statement">${MHACC.i18n.t("statement")}</span>
                            </a>`
                            : ''
                        }
                        ${
                            Number(MHACC?.design?.widget_design?.fields?.show_branding?.value) === 1
                                ? `
                                    <span id="mhacc_pbt">
                                        <a href="https://mh-accessibility.de" target="_blank" rel="noopener">
                                            MH-ACCESSIBILITY.DE
                                        </a>
                                    </span>
                                `
                                : ''
                        }
                    </div>
                </div>
            `;
            
            //console.log(MHACC.design.widget_design.fields);

            const langContainer = panel.querySelector("#lang_items");
            if (langContainer && langMenue) {
                langContainer.appendChild(langMenue);
            }

            return panel;
        },

        buildTrigger() {
            const trigger = document.createElement("button");
            trigger.id = "mhacc-trigger";

            /* === DATA ATTRIBUTES === */
            const fields = MHACC.design?.widget_design?.fields || {};
            const triggerfields = MHACC.design?.trigger_design?.fields || {};
            trigger.setAttribute("aria-label", "Open accessibility menu");
            trigger.setAttribute("aria-expanded", "false");
            trigger.setAttribute("aria-controls", "mhacc-panel");

            trigger.title = "Open accessibility menu";
            trigger.type = "button";
            trigger.role = "button";

            trigger.dataset.style = triggerfields.trigger_icon_style?.value || "style_2";
            trigger.dataset.size = triggerfields.trigger_icon_size?.value || "medium";
            trigger.dataset.positionx = triggerfields.position_x?.value?.position || "right";
            trigger.dataset.positiony = triggerfields.position_y?.value?.position || "bottom";

            /* === ICON === */
            const iconName = triggerfields.trigger_icon?.value || "icon-1";
            const iconSVG = ICONS[iconName] || ICONS["icon-1"];
            trigger.innerHTML = `
                <span class="mhacc-trigger-icon" aria-hidden="true">
                    ${iconSVG}
                </span>
            `;

            const panel = document.getElementById("mhacc-panel");

            function togglePanel() {
                if (!panel) return;
                const isOpen = panel.classList.toggle("is-open");
                trigger.setAttribute("aria-expanded", isOpen);
            }

            // Click
            trigger.addEventListener("click", togglePanel);

            // Enter / Space
            trigger.addEventListener("keydown", (e) => {
                if (e.key === "Enter" || e.key === " " || e.key === "Spacebar") {
                    e.preventDefault();
                    togglePanel();
                }
            });

            return trigger;
        }


    };




    MHACC.toggleFeature = function (groupId, key, btn = null) {
        const feature = MHACC.features?.[groupId]?.fields?.[key];
        if (!feature || feature.type !== "toggle") return;

        const newValue = !feature.value;

        // ===== NORMALES FEATURE =====
        feature.value = newValue;
        MHACC.storage.set(key, newValue);

        // UI Button Status
        if (btn) {
            btn.classList.toggle("is-active", feature.value);
            btn.setAttribute("aria-pressed", feature.value ? "true" : "false");
        }

        document.dispatchEvent(
            new CustomEvent("mhacc:feature", {
                detail: { group: groupId, key, value: feature.value }
            })
        );
    };

    /* 🔑 WICHTIG:
    Core-Referenz für Pro sichern (einmalig)
    */
    if (!MHACC._toggleFeatureBase) {
        MHACC._toggleFeatureBase = MHACC.toggleFeature;
    }





    MHACC.changeSlider = function (groupId, key, delta, valueEl) {
        const feature = MHACC.features?.[groupId]?.fields?.[key];
        if (!feature || feature.type !== "slider") return;

        const min = feature.jsmin ?? 0;
        const max = feature.jsmax ?? 200;

        let newValue = feature.value + delta;
        newValue = Math.min(max, Math.max(min, newValue));

        feature.value = newValue;
        MHACC.storage.set(key, newValue);

        if (valueEl) {
            valueEl.textContent = newValue;
        }

        document.dispatchEvent(
            new CustomEvent("mhacc:feature", {
                detail: { group: groupId, key, value: newValue }
            })
        );
    };

    MHACC.selectFeature = function (groupId, key, value, btn) {
        const feature = MHACC.features?.[groupId]?.fields?.[key];
        if (!feature || feature.type !== "select") return;

        feature.value = value;
        MHACC.storage.set(key, value);

        // UI reset
        btn.querySelectorAll(".select-data-wrap-item")
            .forEach(el => el.classList.remove("is-active"));

        btn.querySelector(`[data-select="${value}"]`)
            ?.classList.add("is-active");

        document.dispatchEvent(
            new CustomEvent("mhacc:feature", {
                detail: { group: groupId, key, value }
            })
        );
    };


    // Event für Button-Klick (nicht nur einzelne Option)
    MHACC.cycleSelectFeature = function(groupId, key) {
        const feature = MHACC.features?.[groupId]?.fields?.[key];
        if (!feature || feature.type !== "select") return;

        const options = feature.jsoptions || [];
        if (!options.length) return;

        const currentIndex = options.indexOf(feature.value);

        let newValue;
        if (currentIndex === -1) {
            // Kein Wert aktiv → erster Wert
            newValue = options[0];
        } else if (currentIndex === options.length - 1) {
            // Letzter Wert aktiv → danach keiner
            newValue = null;
        } else {
            // Normaler Fortschritt
            newValue = options[currentIndex + 1];
        }

        feature.value = newValue;
        MHACC.storage.set(key, newValue);

        // UI aktualisieren
        const btn = document.querySelector(`.mhacc-feature-btn-select[data-group="${groupId}"]`);
        if (btn) {
            btn.querySelectorAll(".select-data-wrap-item")
                .forEach(el => el.classList.remove("is-active"));
            if (newValue !== null) {
                const activeSpan = btn.querySelector(`.select-data-wrap-item[data-select="${newValue}"]`);
                activeSpan?.classList.add("is-active");
            }
        }

        // Feature-Event feuern
        document.dispatchEvent(new CustomEvent("mhacc:feature", {
            detail: { group: groupId, key, value: newValue }
        }));
    };


    MHACC.applyFeatures = function () {
        const body = document.body;

        // alte mhacc-- Klassen entfernen
        body.className = body.className
            .split(" ")
            .filter(c => !c.startsWith("mhacc--"))
            .join(" ");

        Object.values(MHACC.features).forEach(group => {
            if (!group?.fields) return;

            Object.entries(group.fields).forEach(([key, feature]) => {
                if (feature.type === "toggle" && feature.value === true) {
                    body.classList.add(`mhacc--${key}`);
                }
                if (feature.type === "slider") {
                    body.classList.add(`mhacc--${key}-${feature.value}`);
                }
                if (feature.type === "select") {
                    body.classList.add(`mhacc--${key}-${feature.value}`);
                }
            });
        });
    };




    MHACC.uiState = {
        mode: "light",
        size: "medium",
        style: "style_1",
        positionx: "right",
        lang: "en"
    };

    MHACC.storage.KEY_FEATURES = "mhacc_widget_user_settings";
    MHACC.storage.KEY_UI       = "mhacc_widget_ui_settings";

    MHACC.loadUIState = function () {
        const stored = JSON.parse(
            localStorage.getItem(MHACC.storage.KEY_UI) || "{}"
        );

        MHACC.uiState = {
            mode: stored.mode ?? MHACC.design.widget_design.fields.theme_mode.value,
            size: stored.size ?? MHACC.design.widget_design.fields.widget_size.value,
            style: stored.style ?? MHACC.design.widget_design.fields.widget_style.value,
            positionx: stored.positionx ?? MHACC.design.trigger_design.fields.position_x.value.position,
            lang: stored.lang ?? MHACC.i18n.lang
        };

        // 🔑 HIER der entscheidende Teil
        MHACC.i18n.lang = MHACC.uiState.lang;
    };


    MHACC.applyUIState = function () {
        const panel = document.getElementById("mhacc-panel");
        const trigger = document.getElementById("mhacc-trigger");
        if (!panel) return;

        // data-attribute setzen
        panel.dataset.mode      = MHACC.uiState.mode;
        panel.dataset.size      = MHACC.uiState.size;
        panel.dataset.style     = MHACC.uiState.style;
        panel.dataset.positionx = MHACC.uiState.positionx;
        if (trigger) {
            trigger.dataset.positionx = MHACC.uiState.positionx;
        }

        // 🔹 Mapping von Actions zu uiState Keys
        const actionMap = {
            change_mode: "mode",
            change_widget_size: "size",
            change_widget_style: "style",
            change_widget_positionx: "positionx"
        };

        // 🔹 aktive Header-Buttons setzen
        Object.entries(actionMap).forEach(([action, stateKey]) => {
            panel.querySelectorAll(`[data-action="${action}"]`)
                .forEach(btn => {
                    btn.classList.toggle(
                        "is-active",
                        btn.dataset.value === MHACC.uiState[stateKey]
                    );
                });
        });
    };


    MHACC.saveUIState = function () {
        localStorage.setItem(
            MHACC.storage.KEY_UI,
            JSON.stringify(MHACC.uiState)
        );
    };








    // =======================
    // Text-Elemente (global)
    // =======================
    const MHACC_TEXT_ELEMENTS = `
    p, span, a, li,
    h1, h2, h3, h4, h5, h6,
    label, small, strong, em,
    blockquote, figcaption,
    td, th,
    button, input, textarea,
    summary, legend
    `;


    /* =======================
    OVERLAY ELEMENTS
    ======================= */

    const mhaccReadingLine = document.createElement("div");
    mhaccReadingLine.id = "mhacc-reading-line";
    document.body.appendChild(mhaccReadingLine);

    const mhaccReadingMask = document.createElement("div");
    mhaccReadingMask.id = "mhacc-reading-mask";
    document.body.appendChild(mhaccReadingMask);

    


    /* =======================
    MOUSE TRACKING
    ======================= */

    document.addEventListener("mousemove", e => {

        /* --- Reading Line --- */
        if (document.body.classList.contains("mhacc--reading-line")) {
            mhaccReadingLine.style.display = "block";
            mhaccReadingLine.style.top = e.clientY + "px";
        } else {
            mhaccReadingLine.style.display = "none";
        }

        /* --- Reading Mask --- */
        if (document.body.classList.contains("mhacc--reading-mask")) {
            mhaccReadingMask.style.display = "block";
            const maskHeight = 160;
            mhaccReadingMask.style.top = (e.clientY - maskHeight / 2) + "px";
        } else {
            mhaccReadingMask.style.display = "none";
        }

    });



    // =======================
    // Helpers
    // =======================
    MHACC.isInsidePanel = function (el) {
        return el.closest("#mhacc-panel");
    };

    MHACC.isIgnoredTextElement = function (el) {
        return el.closest("svg, i, .icon");
    };


    MHACC.removeFontSize = function () {
        document.querySelectorAll(MHACC_TEXT_ELEMENTS).forEach(el => {
            if (MHACC.isInsidePanel(el)) return;
            if (el.dataset.mhaccFontBase) {
                el.style.setProperty("font-size", el.dataset.mhaccFontBase + "px", "important");
            } else {
                el.style.removeProperty("font-size");
            }
        });
    };

    MHACC.removeLineHeight = function () {
        document.querySelectorAll(MHACC_TEXT_ELEMENTS).forEach(el => {
            if (MHACC.isInsidePanel(el)) return;
            if (el.dataset.mhaccLineBase) {
                el.style.setProperty("line-height", el.dataset.mhaccLineBase + "px", "important");
            } else {
                el.style.removeProperty("line-height");
            }
        });
    };

    MHACC.removeLetterSpacing = function () {
        document.querySelectorAll(MHACC_TEXT_ELEMENTS).forEach(el => {
            if (MHACC.isInsidePanel(el)) return;
            if (el.dataset.mhaccSpacingBase) {
                el.style.setProperty("letter-spacing", el.dataset.mhaccSpacingBase + "px", "important");
            } else {
                el.style.removeProperty("letter-spacing");
            }
        });
    };





    MHACC.enableBigCursor = function () {
        const svg = `
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
        <path fill="black" stroke="white" stroke-width="3"
                d="M2.4 2.4 L43.2 14.4 L31.2 24 L45.6 38.4 L38.4 45.6 L24 31.2 L14.4 43.2 Z"/>
        </svg>`;

        const encoded = encodeURIComponent(svg)
            .replace(/'/g, "%27")
            .replace(/"/g, "%22");

        document.body.style.cursor =
            `url("data:image/svg+xml,${encoded}") 0 0, default`;
    };

    MHACC.disableBigCursor = function () {
        document.body.style.cursor = "";
    };







    MHACC.featureEffects = {


        /* ========= READING LINE ========= */
        reading_line(enabled) {
            document.body.classList.toggle("mhacc--reading-line", enabled);
        },

        /* ========= READING MASK ========= */
        reading_mask(enabled) {
            document.body.classList.toggle("mhacc--reading-mask", enabled);
        },


        /* ========= STOP ANIMATIONS ========= */
        stop_animations(enabled) {
            document.body.classList.toggle("mhacc--stop-animations", enabled);

            document.querySelectorAll("svg").forEach(svg => {
                if (!svg) return;

                if (enabled && typeof svg.pauseAnimations === "function") {
                    svg.pauseAnimations();
                }

                if (!enabled && typeof svg.unpauseAnimations === "function") {
                    svg.unpauseAnimations();
                }
            });
        },


        big_cursor(enabled) {
            document.body.classList.toggle("mhacc--big-cursor", enabled);

            if (enabled) {
                MHACC.enableBigCursor();
            } else {
                MHACC.disableBigCursor();
            }
        },

        
        /* =======================
        FONT SIZE
        value = Prozent (z.B. 100, 110, 120)
        ======================= */
        font_size(value) {
            if (!value || value === 100) {
                MHACC.removeFontSize();
                return;
            }

            const scale = value / 100;

            document.querySelectorAll(MHACC_TEXT_ELEMENTS).forEach(el => {
                if (MHACC.isInsidePanel(el)) return;
                if (MHACC.isIgnoredTextElement(el)) return;

                const computed = window.getComputedStyle(el);
                const fontSize = parseFloat(computed.fontSize);
                if (!fontSize || isNaN(fontSize)) return;

                if (!el.dataset.mhaccFontBase) {
                    el.dataset.mhaccFontBase = fontSize;
                }

                el.style.setProperty(
                    "font-size",
                    (el.dataset.mhaccFontBase * scale) + "px",
                    "important"
                );
            });
        },

        /* =======================
        LINE HEIGHT
        ======================= */
        line_height(value) {
            if (!value || value === 100) {
                MHACC.removeLineHeight();
                return;
            }

            const scale = value / 100;

            document.querySelectorAll(MHACC_TEXT_ELEMENTS).forEach(el => {
                if (MHACC.isInsidePanel(el)) return;
                if (MHACC.isIgnoredTextElement(el)) return;

                const computed = window.getComputedStyle(el);
                let baseLineHeight;

                if (computed.lineHeight === "normal") {
                    const fontSize = parseFloat(computed.fontSize);
                    if (!fontSize) return;
                    baseLineHeight = fontSize * 1.2;
                } else {
                    baseLineHeight = parseFloat(computed.lineHeight);
                }

                if (!baseLineHeight || isNaN(baseLineHeight)) return;

                if (!el.dataset.mhaccLineBase) {
                    el.dataset.mhaccLineBase = baseLineHeight;
                }

                el.style.setProperty(
                    "line-height",
                    (el.dataset.mhaccLineBase * scale) + "px",
                    "important"
                );
            });
        },

        /* =======================
        LETTER SPACING
        ======================= */
        letter_spacing(value) {
            if (!value || value === 100) {
                MHACC.removeLetterSpacing();
                return;
            }

            const scale = (value - 100) / 100;

            document.querySelectorAll(MHACC_TEXT_ELEMENTS).forEach(el => {
                if (MHACC.isInsidePanel(el)) return;
                if (MHACC.isIgnoredTextElement(el)) return;

                const computed = window.getComputedStyle(el);
                const fontSize = parseFloat(computed.fontSize);
                if (!fontSize || isNaN(fontSize)) return;

                const baseSpacing =
                    computed.letterSpacing !== "normal"
                        ? parseFloat(computed.letterSpacing)
                        : 0;

                if (!el.dataset.mhaccSpacingBase) {
                    el.dataset.mhaccSpacingBase = baseSpacing;
                }

                const delta = fontSize * 0.05 * scale;

                el.style.setProperty(
                    "letter-spacing",
                    (parseFloat(el.dataset.mhaccSpacingBase) + delta).toFixed(2) + "px",
                    "important"
                );
            });
        },


        /* 
        tts
        text_zoom
        reading_line
        reading_mask

        */

    };




    MHACC.applyFeatureEffects = function () {

        Object.values(MHACC.features).forEach(group => {
            if (!group || !group.fields) return;
            Object.entries(group.fields).forEach(([key, feature]) => {

                const effect = MHACC.featureEffects[key];
                
                if (!effect) return;

                //console.log(effect);
                effect(feature.value);
            });
        });

    };


        






    MHACC.ui.bindPanelActions = function () {

        const panel = document.getElementById("mhacc-panel");
        if (!panel) return;

        const userOptions = panel.querySelector("#user_options");
        const langItems   = panel.querySelector("#lang_items");

        panel.addEventListener("click", (e) => {
            const btn = e.target.closest("[data-action]");
            if (!btn || !panel.contains(btn)) return;

            const action = btn.dataset.action;
            const value  = btn.dataset.value;
            const lang   = btn.dataset.lang;

            switch (action) {

                /* ===============================
                PANEL HEADER
                =============================== */

                case "toogle_user_options":
                    userOptions?.classList.toggle("is-open");
                    langItems?.classList.remove("is-open");
                    break;

                case "toogle_lang":
                    langItems?.classList.toggle("is-open");
                    userOptions?.classList.remove("is-open");
                    break;

                case "open_help":
                    const help_panel = document.getElementById("mhacc-help-panel");
                    if (help_panel && help_panel.style.display === "flex") {
                        help_panel.style.display = "none";
                    } else {
                        MHACC.help.open();
                    }
                    break;

                case "close":
                    panel.classList.remove("is-open");
                    userOptions?.classList.remove("is-open");
                    langItems?.classList.remove("is-open");
                    // Trigger auf "geschlossen" setzen
                    const triggerBtn = document.getElementById("mhacc-trigger");
                    if (triggerBtn) {
                        triggerBtn.setAttribute("aria-expanded", "false");
                    }                    
                    break;





                /* ===============================
                FEATURE BUTTONS
                =============================== */

                case "toggle_feature": {
                    const featureKey = btn.dataset.feature;
                    const groupId    = btn.dataset.group;

                    MHACC.toggleFeature(groupId, featureKey, btn);
                    break;
                }

                case "slider_change": {
                    const groupId = btn.dataset.group;
                    const key     = btn.dataset.feature;
                    const delta   = Number(btn.dataset.delta);
                    const valueEl = btn.closest(".mhacc-feature-btn-slider") ?.querySelector(".mhacc-slider-value");

                    MHACC.changeSlider(groupId, key, delta, valueEl);
                    break;
                }
                
                case "select_option": {
                    const groupId = btn.dataset.group;
                    const key     = btn.dataset.feature;
                    const value   = btn.dataset.select;
                    const wrapper = btn.closest(".mhacc-feature-btn-select");

                    MHACC.selectFeature(groupId, key, value, wrapper);
                    break;
                }

                case "cycle_select": {
                    const groupId = btn.dataset.group;
                    const key     = btn.dataset.feature;
                    MHACC.cycleSelectFeature(groupId, key);
                    break;
                }

                case "change_mode": {
                    MHACC.uiState.mode = value;
                    MHACC.saveUIState();
                    MHACC.applyUIState();
                    break;
                }
                case "change_widget_size":
                    MHACC.uiState.size = value;
                    MHACC.saveUIState();
                    MHACC.applyUIState();
                    break;

                case "change_widget_style":
                    MHACC.uiState.style = value;
                    MHACC.saveUIState();
                    MHACC.applyUIState();
                    break;

                case "change_widget_positionx":
                    MHACC.uiState.positionx = value;
                    MHACC.saveUIState();
                    MHACC.applyUIState();
                    break;

                case "change_lang": {
                    MHACC.uiState.lang = lang;
                    MHACC.i18n.lang = lang;
                    MHACC.saveUIState();
                    MHACC.applyTranslations();

                    panel.querySelectorAll(".mhacc-langmenu-item")
                        .forEach(b => b.classList.toggle("is-active", b.dataset.lang === lang));
                    break;
                }


            }
        });

        // --- Klick außerhalb → Panel schließen ---
        document.addEventListener("click", (e) => {
            const trigger = document.getElementById("mhacc-trigger");

            // Klick außerhalb Panel UND außerhalb Trigger
            if (!panel.contains(e.target) && e.target !== trigger && !trigger.contains(e.target)) {
                // Panels / Menüs schließen
                userOptions?.classList.remove("is-open");
                langItems?.classList.remove("is-open");

                // Panel schließen, falls offen
                if (panel.classList.contains("is-open")) {
                    panel.classList.remove("is-open");

                    // Trigger-Button Status zurücksetzen
                    trigger.classList.remove("is-active");
                    trigger.setAttribute("aria-expanded", "false");

                    // Optional: Event dispatchen
                    document.dispatchEvent(new CustomEvent("mhacc:panelClosed"));
                }
            }
        });


    };



    

MHACC.help = {
    open() {
        let panel = document.getElementById("mhacc-help-panel");
        if (!panel) {
            panel = document.createElement("div");
            panel.id = "mhacc-help-panel";
            panel.className = "mhacc-panel mhacc-help";

            const title = MHACC.i18n.t("shortcuttitle", "Shortcuts");

            panel.innerHTML = `
                <div id="mhacc-help-header">
                    <h2>${title}</h2>
                    <button id="mhacc-help-close" aria-label="Close help panel">
                        <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                            <g fill="currentColor" fill-rule="nonzero">
                                <path d="M1.084 1.791a.5.5 0 1 1 .707-.707l13.125 13.125a.5.5 0 0 1-.707.707L1.084 1.791Z"></path>
                                <path d="M14.916 1.791a.5.5 0 1 0-.707-.707L1.084 14.209a.5.5 0 0 0 .707.707L14.916 1.791Z"></path>
                            </g>
                        </svg>
                    </button>
                </div>

                <!-- HINWEIS ZUR TASTATURNAVIGATION -->
                <div id="mhacc-help-notice" class="mhacc-help-notice" role="note" aria-live="polite"></div>

                <div id="mhacc-help-table-wrap">
                    <table id="mhacc-help-table">
                        <tbody></tbody>
                    </table>
                </div>
            `;
            document.body.appendChild(panel);

            document.getElementById("mhacc-help-close")
                .addEventListener("click", () => panel.remove());
        }



        // ==============================================
        // HINWEIS-TEXT JE NACH STATUS
        // ==============================================
        const notice = panel.querySelector("#mhacc-help-notice");

        const isActive = document.body.classList.contains("mhacc--keyboard_navigation");
        notice.innerHTML = isActive
            ? MHACC.i18n.t(
                "shortcutnote_active",
                "✓ Tastaturnavigation ist aktiv."
            )
            : MHACC.i18n.t(
                "shortcutnote_inactive",
                "Diese Shortcuts funktionieren nur bei aktivierter Tastaturnavigation (Alt + K)."
            );


        // ==============================================
        // TABELLE AUFBAUEN
        // ==============================================
        const tbody = panel.querySelector("tbody");
        tbody.innerHTML = "";

        const hotkeys = MHACC.i18n.dict?.hotkeys || {};
        if (!Object.keys(hotkeys).length) {
            const row = document.createElement("tr");
            const cell = document.createElement("td");
            cell.colSpan = 2;
            cell.textContent = MHACC.i18n.t("no_shortcuts","No shortcuts defined for this language.");
            row.appendChild(cell);
            tbody.appendChild(row);
        } else {
            const translateKey = k => {
                const map = { alt: "Alt", ctrl: "Strg", shift: "Shift", enter: "Enter", tab: "Tab", esc: "Esc", space: "Space" };
                return map[k.toLowerCase()] || k.toUpperCase();
            };

            for (const [shortcut, description] of Object.entries(hotkeys)) {
                const row = document.createElement("tr");
                const keyCell = document.createElement("td");
                const descCell = document.createElement("td");

                const keys = shortcut.split("_").map(translateKey);
                keyCell.innerHTML = keys.map(k => `<kbd>${k}</kbd>`).join(" + ");

                // Hotkey-Beschreibung ist bereits die Übersetzung
                descCell.textContent = description;

                row.appendChild(keyCell);
                row.appendChild(descCell);
                tbody.appendChild(row);
            }
        }

        panel.style.display = "flex";
        panel.focus();
    }
};





    MHACC.keyboard = {

        init() {
            this.bindGlobalKeys();
            this.bindPanelKeys();
        },


        triggerSlider(group, feature, delta) {

            const btn = document.querySelector(
                `.mhacc-slider-btn[data-feature="${feature}"]` +
                `[data-group="${group}"][data-delta="${delta}"]`
            );

            btn?.dispatchEvent(
                new MouseEvent("click", {
                    bubbles: true,
                    cancelable: true,
                    view: window
                })
            );
        },



        triggerToggle(selector) {
            const btn = document.querySelector(selector);
            btn?.dispatchEvent(
                new MouseEvent("click", {
                    bubbles: true,
                    cancelable: true,
                    view: window
                })
            );
        },

        isEnabled() {
            return document.body.classList.contains("mhacc--keyboard_navigation");
        },

        getFocusable(panel) {
            return [
                ...panel.querySelectorAll(
                    'button, [href], input, select, textarea, [tabindex="0"]'
                )
            ].filter(el => !el.disabled && el.offsetParent !== null);
        },

        // ==========================
        // GLOBALE KEYS
        // ==========================
        bindGlobalKeys() {
            document.addEventListener("keydown", (e) => {
                
                
                const panel   = document.getElementById("mhacc-panel");
                const trigger = document.getElementById("mhacc-trigger");
        
                // ==========================================
                // ALT + K → Keyboard Navigation toggeln
                // ==========================================
                if (e.altKey && e.code === "KeyK") {
                    e.preventDefault();
                    this.triggerToggle(
                        '.mhacc-feature-btn[data-feature="keyboard_navigation"]' +
                        '[data-group="orientierung_navigation"]'
                    );
                    return;
                }


                if (!this.isEnabled()) return;


                // ALT + 9 → Hilfe/Info öffnen/close toggle
                if (e.altKey && e.code === "Digit9") {
                    e.preventDefault();
                    const panel = document.getElementById("mhacc-help-panel");
                    if (panel && panel.style.display === "flex") {
                        panel.style.display = "none"; // Panel schließen
                    } else {
                        MHACC.help.open(); // Panel öffnen
                    }
                    return;
                }


                
                // ==========================================
                // CTRL + SHIFT + A → Fokus auf Widget
                // ==========================================
                if (e.ctrlKey && e.shiftKey && e.code === "KeyA") {
                    e.preventDefault();
                    const panel   = document.getElementById("mhacc-panel");
                    const trigger = document.getElementById("mhacc-trigger");
                    // Öffnen falls zu
                    if (!panel?.classList.contains("is-open")) {
                        trigger?.click();
                    }
                    setTimeout(() => this.focusFirst(), 50);
                    return;
                }
                // ==========================================
                // ALT + N → Navigation
                // ==========================================
                if (e.altKey && e.code === "KeyN") {
                    e.preventDefault();

                    const nav = document.querySelector("nav, #nav, #navigation");

                    if (nav) {
                        nav.setAttribute("tabindex", "-1");
                        nav.focus();
                        nav.scrollIntoView({ behavior: "smooth" });
                    }

                    return;
                }     

                // ==========================================
                // ALT + S → Skip to Content
                // ==========================================
                if (e.altKey && e.code === "KeyS") {
                    e.preventDefault();

                    const main = document.querySelector("main");

                    if (main) {
                        main.setAttribute("tabindex", "-1");
                        main.focus();
                        main.scrollIntoView({ behavior: "smooth" });
                    }

                    return;
                }      
                // ==========================================
                // ALT + ← / → → Browser Back/Forward
                // ==========================================
                if (e.altKey && e.code === "ArrowLeft") {
                    e.preventDefault();
                    history.back();
                    return;
                }

                if (e.altKey && e.code === "ArrowRight") {
                    e.preventDefault();
                    history.forward();
                    return;
                }




                // --------------------------------------------
                // WIDGET ÖFFNEN
                // Alt + 1
                // --------------------------------------------
                if (e.altKey && e.code === "Digit1") {
                    e.preventDefault();
                    trigger?.click();
                    setTimeout(() => {this.focusFirst();}, 50);
                    return;
                }

                // ==========================================
                // ALT + 0 → Widget Reset
                // ==========================================
                if (e.altKey && e.code === "Digit0") {
                    e.preventDefault();

                    const resetBtn = document.getElementById("mhacc-misc");

                    resetBtn?.dispatchEvent(
                        new MouseEvent("click", {
                            bubbles: true,
                            cancelable: true,
                            view: window
                        })
                    );

                    return;
                }






                const toggles = {
                    KeyL: ['inhaltsmodule', 'highlight_links'],
                    KeyH: ['inhaltsmodule', 'highlight_title'],
                    KeyF: ['inhaltsmodule', 'focus'],
                    KeyV: ['inhaltsmodule', 'stop_autoplay'],
                    KeyP: ['inhaltsmodule', 'tts'],
                    KeyD: ['visuelle_module', 'dark_mode'],
                    KeyC: ['visuelle_module', 'high_contrast'],
                    KeyR: ['orientierung_navigation', 'reading_line'],
                    KeyI: ['orientierung_navigation', 'hide_images'],
                    KeyO: ['orientierung_navigation', 'stop_animations']
                };
                if (e.altKey && toggles[e.code]) {
                    e.preventDefault();
                    const [group, feature] = toggles[e.code];
                    this.triggerToggle(
                    `.mhacc-feature-btn[data-feature="${feature}"][data-group="${group}"]`
                    );
                    return;
                }

                // ==========================================
                // ALT + B → Farbschwäche-Filter durchschalten
                // ==========================================
                if (e.altKey && e.code === "KeyB") {
                    e.preventDefault();

                    const btn = document.querySelector(
                        '.mhacc-feature-btn-select' +
                        '[data-feature="blind_filters"]' +
                        '[data-group="visuelle_module"]'
                    );

                    btn?.click();
                    return;
                }



                // ==========================================
                // ALT + T → Schrift größer
                // ==========================================
                if (e.altKey && e.code === "KeyT") {
                    e.preventDefault();

                    // Alle 3 Features synchron +
                    this.triggerSlider("inhaltsmodule", "font_size", 10);
                    this.triggerSlider("inhaltsmodule", "line_height", 10);
                    this.triggerSlider("inhaltsmodule", "letter_spacing", 10);

                    return;
                }

                // ==========================================
                // ALT + G → Schrift kleiner
                // ==========================================
                if (e.altKey && e.code === "KeyG") {
                    e.preventDefault();

                    // Alle 3 Features synchron -
                    this.triggerSlider("inhaltsmodule", "font_size", -10);
                    this.triggerSlider("inhaltsmodule", "line_height", -10);
                    this.triggerSlider("inhaltsmodule", "letter_spacing", -10);

                    return;
                }

                


                // -------------------------------
                // SPRUNG ZU NÄCHSTEM ELEMENT (vorwärts/rückwärts)
                // -------------------------------
                const jumpMap = {
                    "h": "h1,h2,h3,h4,h5,h6",
                    "a": "a[href]",
                    "l": "ul,ol",
                    "b": "button",
                    "f": "input,textarea,select",
                    "i": "img"
                };

                const key = e.key.toLowerCase();
                const selector = jumpMap[key];
                if (!selector) return;

                // --- Ignoriere Hotkeys, wenn gerade in einem Formularfeld getippt wird ---
                const activeTag = document.activeElement.tagName.toLowerCase();
                const typingFields = ["input", "textarea", "select"];
                if (typingFields.includes(activeTag)) return;

                e.preventDefault();

                // Alle sichtbaren Elemente des Typs
                const elements = Array.from(document.querySelectorAll(selector))
                    .filter(el => el.offsetParent !== null);

                if (!elements.length) return;

                const current = document.activeElement;
                let next = null;

                // Prüfen, ob Shift gedrückt wurde → rückwärts
                const backward = e.shiftKey;

                // Iteration über Elemente je nach Richtung
                if (!backward) {
                    // Vorwärts: nächstes Element nach Fokus
                    for (const el of elements) {
                        if (current.compareDocumentPosition(el) & Node.DOCUMENT_POSITION_FOLLOWING) {
                            next = el;
                            break;
                        }
                    }
                } else {
                    // Rückwärts: letztes Element vor Fokus
                    for (let i = elements.length - 1; i >= 0; i--) {
                        const el = elements[i];
                        if (current.compareDocumentPosition(el) & Node.DOCUMENT_POSITION_PRECEDING) {
                            next = el;
                            break;
                        }
                    }
                }

                // Wrap-around, wenn nichts gefunden wird
                if (!next) next = backward ? elements[elements.length - 1] : elements[0];

                // Fokussierbar machen, falls nötig
                const tag = next.tagName.toLowerCase();
                if (!["a","button","input","select","textarea"].includes(tag)) {
                    if (!next.hasAttribute("tabindex")) next.setAttribute("tabindex", "0");
                }

                // Scrollen + Fokus
                next.scrollIntoView({ behavior: "smooth", block: "center" });
                next.focus({ preventScroll: true });





                // --------------------------------------------
                // ESC → schließen
                // --------------------------------------------
                if (e.key === "Escape" && panel?.classList.contains("is-open")) {
                    e.preventDefault();
                    this.closePanel();
                }

            });
        },

        // ==========================
        // PANEL NAVIGATION
        // ==========================
        bindPanelKeys() {
            const panel = document.getElementById("mhacc-panel");
            if (!panel) return;

            panel.addEventListener("keydown", (e) => {

                if (!this.isEnabled()) return;

                const focusable = this.getFocusable(panel);
                const current   = document.activeElement;
                const index     = focusable.indexOf(current);

                // ---- TAB FOCUS TRAP ----
                if (e.key === "Tab") {
                    if (e.shiftKey) {
                        if (index === 0) {
                            e.preventDefault();
                            focusable[focusable.length - 1]?.focus();
                        }
                    } else {
                        if (index === focusable.length - 1) {
                            e.preventDefault();
                            focusable[0]?.focus();
                        }
                    }
                    return;
                }

                // ---- FEATURE BEDIENUNG ----
                const btn = current.closest("[data-action]");
                if (!btn) return;

                const action = btn.dataset.action;

                switch (action) {

                    case "toggle_feature":
                        if (["Enter", " "].includes(e.key)) {
                            e.preventDefault();
                            btn.click();
                        }
                        break;

                    case "slider_change":
                        this.handleSliderKeys(e, btn);
                        break;

                    case "cycle_select":
                        if (["Enter", " "].includes(e.key)) {
                            e.preventDefault();
                            btn.click();
                        }

                        if (["ArrowRight", "ArrowLeft"].includes(e.key)) {
                            e.preventDefault();
                            btn.click();
                        }
                        break;
                }

            });
        },

        // ==========================
        // SLIDER STEUERUNG
        // ==========================
        handleSliderKeys(e, btn) {

            const wrapper = btn.closest(".mhacc-feature-btn-slider");
            if (!wrapper) return;

            const minus = wrapper.querySelector('[data-delta="-10"]');
            const plus  = wrapper.querySelector('[data-delta="10"]');

            switch (e.key) {

                case "ArrowLeft":
                case "ArrowDown":
                    e.preventDefault();
                    minus?.click();
                    break;

                case "ArrowRight":
                case "ArrowUp":
                    e.preventDefault();
                    plus?.click();
                    break;

                case "Home":
                    e.preventDefault();
                    this.setSliderToMin(wrapper);
                    break;

                case "End":
                    e.preventDefault();
                    this.setSliderToMax(wrapper);
                    break;
            }
        },

        setSliderToMin(wrapper) {
            const minus = wrapper.querySelector('[data-delta="-10"]');
            for (let i = 0; i < 20; i++) minus?.click();
        },

        setSliderToMax(wrapper) {
            const plus = wrapper.querySelector('[data-delta="10"]');
            for (let i = 0; i < 20; i++) plus?.click();
        },

        // ==========================
        // FOKUS MANAGEMENT
        // ==========================
        focusFirst() {
            const panel = document.getElementById("mhacc-panel");
            if (!panel) return;

            const focusable = this.getFocusable(panel);
            focusable[0]?.focus();
        },

        closePanel() {
            const panel   = document.getElementById("mhacc-panel");
            const trigger = document.getElementById("mhacc-trigger");

            panel?.classList.remove("is-open");
            trigger?.setAttribute("aria-expanded", "false");
            trigger?.focus();
        }
    };















    // LIVE TRANSLATION
    MHACC.applyTranslations = function () {

        const panel = document.getElementById("mhacc-panel");
        if (!panel) return;

        /* === einfache Keys === */
        panel.querySelectorAll("[data-i18n]").forEach(el => {
            const key = el.dataset.i18n;
            el.textContent = MHACC.i18n.t(key, el.textContent);
        });

        /* === Gruppen Keys === */
        panel.querySelectorAll("[data-i18n-group]").forEach(el => {
            const group = el.dataset.i18nGroup;
            const prop  = el.dataset.i18nProp;
            el.textContent = MHACC.i18n.tGroup(group, prop, el.textContent);
        });

        /* === aria / title === */
        panel.querySelectorAll("[data-feature]").forEach(btn => {
            const key = btn.dataset.feature;
            const label = MHACC.i18n.t(key);
            if (label) {
                btn.setAttribute("aria-label", label);
                btn.setAttribute("title", label);
            }
        });



        /* === Hotkeys in Help-Panel === */
        const helpPanel = document.getElementById("mhacc-help-panel");
        if (helpPanel) {
            const tbody = helpPanel.querySelector("tbody");
            if (tbody) {
                tbody.innerHTML = "";
                const hotkeys = MHACC.i18n.dict?.hotkeys || {};
                const translateKey = k => {
                    const map = { alt: "Alt", ctrl: "Strg", shift: "Shift", enter: "Enter", tab: "Tab", esc: "Esc", space: "Space" };
                    return map[k.toLowerCase()] || k.toUpperCase();
                };

                if (!Object.keys(hotkeys).length) {
                    const row = document.createElement("tr");
                    const cell = document.createElement("td");
                    cell.colSpan = 2;
                    cell.textContent = MHACC.i18n.t("no_shortcuts","No shortcuts defined for this language.");
                    row.appendChild(cell);
                    tbody.appendChild(row);
                } else {
                    for (const [shortcut, description] of Object.entries(hotkeys)) {
                        const row = document.createElement("tr");
                        const keyCell = document.createElement("td");
                        const descCell = document.createElement("td");

                        const keys = shortcut.split("_").map(translateKey);
                        keyCell.innerHTML = keys.map(k => `<kbd>${k}</kbd>`).join(" + ");

                        descCell.textContent = description;

                        row.appendChild(keyCell);
                        row.appendChild(descCell);
                        tbody.appendChild(row);
                    }
                }

                // Überschriften im Help-Panel
                const header = helpPanel.querySelector("#mhacc-help-header h2");
                if (header) {
                    header.textContent = MHACC.i18n.t("shortcuttitle","Shortcuts");
                }
                const thead = helpPanel.querySelector("thead");
                if (thead) {
                    thead.querySelectorAll("th")[0].textContent = MHACC.i18n.t("shortcut","Shortcut");
                    thead.querySelectorAll("th")[1].textContent = MHACC.i18n.t("description","Beschreibung");
                }
            }
        }


        /* === aktuelle Sprache im Header === */
        const langLabel = panel.querySelector(".mhacc-lang-label");
        if (langLabel) {
            langLabel.textContent =
                MHACC_WIDGET_CONFIG.languages?.[MHACC.i18n.lang]?.title
                || MHACC.i18n.lang;
        }
    };




    // --- FEATURES ---
    MHACC.featuresRenderer = {
        render(groups) {
            const container = document.getElementById("mhacc-panel-features");
            if (!container || !groups) return;

            container.innerHTML = ""; // alles vorher leeren

            Object.values(groups).forEach(group => {
                if (!group || !group.fields) return;

                /* === GROUP WRAPPER === */
                const groupEl = document.createElement("section");
                groupEl.className = "mhacc-panel-group";
                groupEl.id = `mhacc-group-${group.id}`;

                /* === GROUP HEADER === */
                const header = document.createElement("div");
                header.className = "mhacc-panel-group-header";


                if (group.title) {

                    const title = document.createElement("p");
                    
                    title.className = "mhacc-group-title";
                    
                    title.setAttribute("data-i18n-group", group.id);
                    title.setAttribute("data-i18n-prop", "title");

                    title.textContent = MHACC.i18n.tGroup(group.id, "title");

                    header.appendChild(title);
                }

                if (group.description) {

                    const desc = document.createElement("p");

                    desc.className = "mhacc-group-description";

                    desc.setAttribute("data-i18n-group", group.id);
                    desc.setAttribute("data-i18n-prop", "description");

                    desc.textContent = MHACC.i18n.tGroup(group.id, "description");
                    
                    header.appendChild(desc);
                }

                /* === FEATURES CONTAINER === */
                const featuresEl = document.createElement("div");
                featuresEl.className = "mhacc-panel-group-features";

                Object.entries(group.fields).forEach(([key, feature]) => {
                    if (!feature) return;

                    
                    
                    if (feature.type === "toggle") {

                        const btn = document.createElement("button");

                        btn.type = "button";
                        btn.className = "mhacc-feature-btn";
                        btn.dataset.action = "toggle_feature";
                        btn.dataset.feature = key;
                        btn.dataset.group = group.id;
                        btn.role = "button";
                        btn.ariaLabel = feature.label || key;
                        btn.setAttribute("aria-pressed", feature.value ? "true" : "false");
                        btn.title = feature.label || key;
                        
                        if (feature.value) {
                            btn.classList.add("is-active");
                        }
                        
                        if (key.startsWith("profile_")) {
                            btn.classList.add("mhacc-profile-btn");
                        }

                        const iconSpan = document.createElement("span");
                        iconSpan.className = "mhacc-btn-icon";
                        iconSpan.innerHTML = feature.icon;

                        const labelSpan = document.createElement("span");

                        labelSpan.className = "mhacc-btn-label";
                        labelSpan.dataset.i18n = key;
                        labelSpan.textContent = MHACC.i18n.t(key, feature.label);

                        const fakeToggle = document.createElement("span");
                        fakeToggle.classList.add("fake-toggle");


                        btn.appendChild(iconSpan);
                        btn.appendChild(labelSpan);
                        btn.appendChild(fakeToggle);


                        featuresEl.appendChild(btn);
                    }

                    


                    else if(feature.type == 'slider'){
                        
                        //console.log(feature);
                        
                        const sliderWrapper = document.createElement("div");
                        sliderWrapper.className = "mhacc-feature-btn-slider";


                        // --- Icon ---
                        const iconSpan = document.createElement("span");
                        iconSpan.classList.add("mhacc-btn-icon");
                        iconSpan.innerHTML = feature.icon;

                        // --- Label ---
                        const labelSpan = document.createElement("span");
                        labelSpan.className = "mhacc-btn-label";
                        labelSpan.dataset.i18n = key;
                        labelSpan.textContent = MHACC.i18n.t(key, feature.label);





                        // Wrapper für Buttons + Value
                        const itemsWrapper = document.createElement("span");
                        itemsWrapper.classList.add("mhacc-slider-items");


                        // Value-Span
                        const valueSpan = document.createElement("span");
                        valueSpan.setAttribute("aria-live", "polite");
                        valueSpan.classList.add("mhacc-slider-value");

                        // Minus-Button
                        const minusBtn = document.createElement("button");
                        minusBtn.type = "button";
                        minusBtn.setAttribute("role", "button");
                        minusBtn.setAttribute("aria-label", feature.label+" reduce");
                        minusBtn.classList.add("mhacc-slider-btn");
                        //minusBtn.dataset.feature = key + "_minus";
                        minusBtn.textContent = "−";
                        // NEW
                        minusBtn.dataset.action = "slider_change";
                        minusBtn.dataset.feature = key;
                        minusBtn.dataset.group = group.id;
                        minusBtn.dataset.delta = -10;
                        

                        // Plus-Button
                        const plusBtn = document.createElement("button");
                        plusBtn.type = "button";
                        plusBtn.setAttribute("role", "button");
                        plusBtn.setAttribute("aria-label", feature.label+" enlarge");
                        plusBtn.classList.add("mhacc-slider-btn");
                        //plusBtn.dataset.feature = key + "_plus";
                        plusBtn.textContent = "+";
                        // NEW
                        plusBtn.dataset.action = "slider_change";
                        plusBtn.dataset.feature = key;
                        plusBtn.dataset.group = group.id;
                        plusBtn.dataset.delta = 10;

                        // Buttons & Value in itemsWrapper
                        itemsWrapper.appendChild(minusBtn);
                        itemsWrapper.appendChild(valueSpan);
                        itemsWrapper.appendChild(plusBtn);

                        sliderWrapper.appendChild(iconSpan);
                        sliderWrapper.appendChild(labelSpan);
                        sliderWrapper.appendChild(itemsWrapper);


                        // Slider-Wert: gespeicherter Wert oder jsdefault
                        let sliderValue = MHACC.storage.get(key, feature.jsdefault ?? 0);
                        feature.value = sliderValue;
                        valueSpan.textContent = sliderValue;


                        featuresEl.appendChild(sliderWrapper);
                    }

                    else if(feature.type == 'select'){
                        
                        //console.log(feature);
                        
                        const btn = document.createElement("button");
                        btn.type = "button";
                        btn.className = "mhacc-feature-btn-select";
                        //btn.dataset.action = key;

                        btn.dataset.action = "cycle_select"; // statt select_option
                        btn.dataset.feature = key;
                        btn.dataset.group = group.id;
 
                        btn.ariaLabel = feature.label || key;
                        btn.title = feature.label || key;
                        btn.role = "button";

                        // --- Icon ---
                        const iconSpan = document.createElement("span");
                        iconSpan.classList.add("mhacc-btn-icon");
                        iconSpan.innerHTML = feature.icon;
                        btn.appendChild(iconSpan);

                        // --- Label ---
                        const labelSpan = document.createElement("span");
                        labelSpan.className = "mhacc-btn-label";
                        labelSpan.dataset.i18n = key;
                        labelSpan.textContent = MHACC.i18n.t(key, feature.label);

                        btn.appendChild(labelSpan);


                        

                        // --- Options Wrapper ---
                        const optionsWrap = document.createElement("span");
                        optionsWrap.classList.add("select-data-wrap");

                        if (Array.isArray(feature.jsoptions)) {
                            feature.jsoptions.forEach(opt => {
                                const optSpan = document.createElement("span");
                                // NEW
                                if (opt === feature.value) {
                                    optSpan.classList.add("is-active");
                                }
                                optSpan.classList.add("select-data-wrap-item");
                                optSpan.dataset.select = opt;
                                //optSpan.dataset.action = key+'_'+opt; 

                                // NEW
                                optSpan.dataset.action = "select_option";
                                optSpan.dataset.feature = key;
                                optSpan.dataset.group = group.id;
                                optSpan.dataset.select = opt;
                                                                
                                optionsWrap.appendChild(optSpan);
                            });
                        }

                        btn.appendChild(optionsWrap);

                        
                        featuresEl.appendChild(btn);
                    }





                });

                groupEl.appendChild(header);
                groupEl.appendChild(featuresEl);
                container.appendChild(groupEl); // direkt in #mhacc-panel-features
            });
        }
    };





    // 🔌 global verfügbar machen
    window.MHACC = MHACC;


    // DOM Ready → Init starten
    document.addEventListener("DOMContentLoaded", () => {
        MHACC.init();
    });

    document.addEventListener("mhacc:feature", () => {
        MHACC.applyFeatures();
        MHACC.applyFeatureEffects();
    });

    // SPECIAL CLICK keyboard_navigation
    document.addEventListener("mhacc:feature", (e) => {
        const { key, value } = e.detail;

        if (key === "keyboard_navigation" && value === true) {
            const helpBtn = document.getElementById("mhacc-help-btn");
            if (!helpBtn) return;

            // Animation neu starten
            helpBtn.classList.remove("animate-pulse");
            void helpBtn.offsetWidth; // reflow erzwingen
            helpBtn.classList.add("animate-pulse");

            // Klasse nach Ende entfernen (optional)
            helpBtn.addEventListener("animationend", () => {
                helpBtn.classList.remove("animate-pulse");
            }, { once: true });
        }
    });



})(window);
