<?php
namespace MHACC\Admin;
if ( ! defined( "ABSPATH" ) ) exit;

use MHACC\Admin\AdminHeader;
use MHACC\Admin\AdminTheme;
use MHACC\Settings\SettingsRenderer;

class Admin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_menu']);
    }

    /* =====================================================
     * MENU
     * ===================================================== */

    public function register_menu()
    {
        add_menu_page(
            'Accessibility Widget',
            'MH-Accessibility',
            'manage_options',
            'mhacc-widget',
            [$this, 'page_welcome'],
            'dashicons-universal-access',
            61
        );

        add_submenu_page(
            'mhacc-widget',
            __('Welcome', 'mh-accessibility'),
            __('Welcome', 'mh-accessibility'),
            'manage_options',
            'mhacc-widget',
            [$this, 'page_welcome']
        );

        add_submenu_page(
            'mhacc-widget',
            __('Modules', 'mh-accessibility'),
            __('Modules', 'mh-accessibility'),
            'manage_options',
            'mhacc-widget-modules',
            [$this, 'page_modules']
        );

        

        // Erweiterungspunkt für Pro
        do_action('mhacc_widget_admin_pages');
    }

    /* =====================================================
     * WELCOME / DESIGN
     * ===================================================== */

    public function page_welcome()
    {
        AdminHeader::render('welcome');

        $groups   = $this->get_groups('design');
        $settings = $this->handle_save($groups);

        $renderer = new SettingsRenderer($groups, $settings);
        ?>

        <div class="wrap <?php echo esc_attr(AdminTheme::get_css_class()); ?>">
            <form method="post">
                <?php wp_nonce_field('mhacc_save_settings', 'mhacc_settings_nonce'); ?>
                <?php $renderer->render(); ?>
            </form>
        </div>

        <?php
    }

    /* =====================================================
     * MODULES / FEATURES
     * ===================================================== */

    public function page_modules()
    {
        AdminHeader::render('modules');

        $groups   = $this->get_groups('feature');
        $settings = $this->handle_save($groups);

        $renderer = new SettingsRenderer($groups, $settings);
        ?>

        <div class="wrap <?php echo esc_attr(AdminTheme::get_css_class()); ?>">
            <form method="post">
                <?php wp_nonce_field('mhacc_save_settings', 'mhacc_settings_nonce'); ?>
                <?php $renderer->render(); ?>
            </form>
        </div>

        <?php
    }


    /* =====================================================
     * HELPERS
     * ===================================================== */

    private function get_groups(string $type): array
    {
        $groups = require MHACC_WIDGET_PATH . 'src/Config/settings-definitions.php';

        $groups = apply_filters('mhacc_widget_setting_groups', $groups);

        uasort($groups, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));

        return array_filter($groups, fn($g) => ($g['type'] ?? '') === $type);
    }

    private function handle_save(array $groups): array
    {
        $settings = get_option('mhacc_settings', []);

        if (
            isset( $_POST['mhacc_settings_nonce'] ) &&
            wp_verify_nonce(
                sanitize_text_field( wp_unslash( $_POST['mhacc_settings_nonce'] ) ),
                'mhacc_save_settings'
            )
        ) {
            $new = $settings;

            $raw_settings = wp_unslash( $_POST['mhacc_settings'] ?? [] );

            foreach ( $raw_settings as $key => $value ) {
                $new[ $key ] = is_array( $value )
                    ? array_map( 'sanitize_text_field', $value )
                    : sanitize_text_field( $value );
            }

            // unchecked toggles = 0
            foreach ($groups as $group) {
                foreach ($group['fields'] as $field) {
                    if (($field['type'] ?? '') === 'toggle') {
                        $name = $field['name'];
                        if (!isset($_POST['mhacc_settings'][$name])) {
                            $new[$name] = 0;
                        }
                    }
                }
            }

            update_option('mhacc_settings', $new);
            echo '<div class="notice notice-success"><p>Settings saved.</p></div>';

            return $new;
        }

        return $settings;
    }
}
