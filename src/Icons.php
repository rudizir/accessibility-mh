<?php
if (!defined('ABSPATH')) exit;

/**
 * Lädt alle SVG-Icons aus dem Icons-Ordner
 *
 * @return array ['icon-name' => '<svg>...</svg>']
 */
function mhacc_load_icons(): array {
    static $cache = null;

    if ($cache !== null) {
        return $cache;
    }

    $icon_dir = MHACC_WIDGET_PATH . 'assets/icons/';
    $icons = [];

    if (!is_dir($icon_dir)) {
        return [];
    }

    foreach (glob($icon_dir . '*.svg') as $file) {
        if (!is_file($file)) continue;

        $key = basename($file, '.svg');
        $svg = trim(file_get_contents($file));
        $svg = preg_replace('/<\?xml.*?\?>/', '', $svg);

        $icons[$key] = $svg;
    }

    /**
     * 🔌 Pro / Add-ons können Icons ergänzen oder überschreiben
     */
    $cache = apply_filters('mhacc_widget_icons', $icons);

    return $cache;
}
