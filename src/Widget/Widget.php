<?php
namespace MHACC\Widget;

class Widget
{


    public function __construct() {
        #add_action('wp_footer', [$this, 'render_widget']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets() {

        $languages  = require MHACC_WIDGET_PATH . 'src/Config/settings-language.php';
        $languages = apply_filters(
            'mhacc_widget_languages',
            $languages
        );
        
        $settings = get_option('mhacc_settings', []);

        if (!empty($settings['widget_active'])) {
        
            wp_enqueue_style(
                'mhacc-widget',
                MHACC_WIDGET_URL . 'assets/css/widget.min.css',
                [],
                filemtime( MHACC_WIDGET_PATH . 'assets/css/widget.min.css' )
            );

            wp_enqueue_script(
                'mhacc-widget',
                MHACC_WIDGET_URL . 'assets/js/widget.min.js',
                [],
                filemtime( MHACC_WIDGET_PATH . 'assets/js/widget.min.js' ),
                true
            );

            /**
             * 📤 Settings ins JS geben
             */
            wp_localize_script(
                'mhacc-widget',
                'MHACC_WIDGET_CONFIG',
                [
                    'settings' => \MHACC\Widget\WidgetSettings::get(),
                    'icons'    => mhacc_load_icons(),
                    'languages' => $languages,
                    'lang'      => $settings['widget_language'] ?? 'en',
                    'isPro'    => defined('MHACC_WIDGET_PRO_ACTIVE') && MHACC_WIDGET_PRO_ACTIVE,
                ]
            );

            /**
             * JS defer
             */
            add_filter('script_loader_tag', function($tag, $handle, $src) {
                if ($handle === 'mhacc-widget') {
                    return '<script src="' . esc_url($src) . '" defer></script>';
                }
                return $tag;
            }, 10, 3);
            
        }
    }
    
}
