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

    $mhacc_icon_dir = MHACC_WIDGET_PATH . 'assets/icons/';
    $mhacc_icons = [];

    if (!is_dir($mhacc_icon_dir)) {
        return [];
    }

    foreach (glob($mhacc_icon_dir . '*.svg') as $file) {
        if (!is_file($file)) continue;

        $key = basename($file, '.svg');
        $svg = trim(file_get_contents($file));
        $svg = preg_replace('/<\?xml.*?\?>/', '', $svg);

        $mhacc_icons[$key] = $svg;
    }

    $cache = apply_filters('mhacc_widget_icons', $mhacc_icons);

    return $cache;
}
