<?php
namespace MHACC\Widget;
if ( ! defined( "ABSPATH" ) ) exit;

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
                true,
                [ 'strategy' => 'defer' ],
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
                ]
            );

            
        }

        
    }
    

}
