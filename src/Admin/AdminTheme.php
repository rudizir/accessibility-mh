<?php
namespace MHACC\Admin;
if ( ! defined( "ABSPATH" ) ) exit;

class AdminTheme
{
    /**
     * Vom User gewählte Einstellung
     * light | dark
     */
    public static function get_mode(): string
    {
        $mode = get_user_meta(
            get_current_user_id(),
            'mhacc_widget_theme_mode',
            true
        );

        if (!in_array($mode, ['light', 'dark'], true)) {
            $mode = 'dark'; // Default, wenn nichts gesetzt
        }

        return $mode;
    }

    /**
     * Effektiver Modus (system aufgelöst)
     * light | dark
     */
    public static function get_effective_mode(): string
    {
        $mode = self::get_mode();

        if ($mode !== 'system') {
            return $mode;
        }

        /**
         * System-Farbe serverseitig nicht zuverlässig ermittelbar
         * → Fallback: dark
         * (JS kann später überschreiben)
         */
        return 'dark';
    }

    /**
     * CSS-Klasse für Admin
     */
    public static function get_css_class(): string
    {
        return 'mhacc-theme-' . self::get_effective_mode();
    }
}
