<?php
/**
 * Plugin Name: Accessibility MH
 * Plugin URI: https://www.mh-accessibility.de/
 * Description: Adds a customisable Accessibility widget to the website and enables various accessibility features.
 * Version: 1.5.8
 * Author: MH-ACCESSIBILITY Team
 * Author URI: https://www.mh-accessibility.de/about-us
 * Text Domain: accessibility-mh
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'MHACC_WIDGET_PATH', plugin_dir_path( __FILE__ ) );
define( 'MHACC_WIDGET_URL', plugin_dir_url( __FILE__ ) );
define( 'MHACC_LINK_TO_PRO', 'https://www.mh-accessibility.de/preise/');

define( 'MHACC_LINK_TO_RATINGS', 'https://www.mh-accessibility.de/');

define( 'MHACC_VERSION', '1.5.8');



spl_autoload_register(function ($class) {

    if (strpos($class, 'MHACC\\') !== 0) {
        return;
    }

    $base_dir = MHACC_WIDGET_PATH . 'src/';

    $relative_class = substr($class, strlen('MHACC\\'));
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (is_readable($file)) {
        require $file;
    }
});




add_action( 'admin_enqueue_scripts', function( $hook ) {

    if (
        $hook !== 'toplevel_page_mhacc-widget' && strpos( $hook, 'mhacc-widget' ) === false
    ) {
        return;
    }

    wp_enqueue_style(
        'mhacc-widget-admin',
        MHACC_WIDGET_URL . 'assets/css/admin.css',
        [],
        filemtime( MHACC_WIDGET_PATH . 'assets/css/admin.css' )
    );
    
    
    wp_enqueue_script(
        'mhacc-widget-admin-theme',
        MHACC_WIDGET_URL . 'assets/js/admin.js',
        [],
        filemtime( MHACC_WIDGET_PATH . 'assets/js/admin.js' ),
        true
    );


    wp_localize_script(
        'mhacc-widget-admin-theme',
        'MHACC_WIDGET_THEME',
        ['nonce' => wp_create_nonce('mhacc_widget_theme')]
    );
    

});



add_action('wp_ajax_mhacc_widget_set_theme', function() {
    check_ajax_referer('mhacc_widget_theme');

    if (!current_user_can('manage_options')) {
        wp_send_json_error();
    }

    $mode = sanitize_text_field( wp_unslash( $_POST['mode'] ?? '' ) );
    if (!in_array($mode, ['system','light','dark'], true)) {
        wp_send_json_error();
    }

    update_user_meta(get_current_user_id(), 'mhacc_widget_theme_mode', $mode);
    wp_send_json_success();
});



add_action('plugins_loaded', function () {

    if (class_exists(MHACC\Widget\Widget::class)) {
        new MHACC\Widget\Widget();
    }

    if (is_admin()) {
        class_exists(MHACC\Admin\Admin::class) && new MHACC\Admin\Admin();
        class_exists(MHACC\Admin\AdminTheme::class) && new MHACC\Admin\AdminTheme();
        class_exists(MHACC\Admin\AdminSettings::class) && new MHACC\Admin\AdminSettings();
        class_exists(MHACC\Admin\AdminFooter::class) && new MHACC\Admin\AdminFooter();
    }

    do_action('mhacc_loaded');
});


function mhacc_get_admin_theme_mode(): string {

    $mode = get_user_meta(get_current_user_id(), 'mhacc_widget_theme_mode', true);

    if (!in_array($mode, ['light', 'dark'], true)) {
        return 'dark';
    }

    return $mode;
}




add_filter('admin_body_class', function ($classes) {
    $mode = mhacc_get_admin_theme_mode();

    // alte Klassen sicher entfernen
    $classes = preg_replace('/\b(light|dark)\b/', '', $classes);

    $classes .= ' ' . esc_attr($mode);

    return trim($classes);
});



