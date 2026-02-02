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
            'MH Accessibility',
            'manage_options',
            'mhacc-widget',
            [$this, 'page_welcome'],
            'dashicons-universal-access',
            61
        );

        add_submenu_page(
            'mhacc-widget',
            __('Welcome', 'accessibility-mh'),
            __('Welcome', 'accessibility-mh'),
            'manage_options',
            'mhacc-widget',
            [$this, 'page_welcome']
        );

        add_submenu_page(
            'mhacc-widget',
            __('Modules', 'accessibility-mh'),
            __('Modules', 'accessibility-mh'),
            'manage_options',
            'mhacc-widget-modules',
            [$this, 'page_modules']
        );

        
        // PRO CTAs (nur wenn Pro nicht aktiv)
        if (!defined('MHACC_WIDGET_PRO_ACTIVE')) {
            add_submenu_page(
                'mhacc-widget',
                __('Statement', 'accessibility-mh'),
                __('Statement', 'accessibility-mh'),
                'manage_options',
                'mhacc-widget-statement',
                [$this, 'page_getpro_statement']
            );

            add_submenu_page(
                'mhacc-widget',
                __('Analyser', 'accessibility-mh'),
                __('Analyser', 'accessibility-mh'),
                'manage_options',
                'mhacc-widget-analyser',
                [$this, 'page_getpro_analyser']
            );
        }

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
     * GET PRO PAGES
     * ===================================================== */

    public function page_getpro_analyser()
    {
        AdminHeader::render('analyser');
        $this->render_pro_cta('Analyser');
    }

    public function page_getpro_statement()
    {
        AdminHeader::render('statement');
        $this->render_pro_cta('Statement');
    }

    private function render_pro_cta(string $title)
    {
        ?>
        <div class="wrap <?php echo esc_attr(AdminTheme::get_css_class()); ?>">
            <div class="boxed container_medium">

                <svg style="color:var(--mhacc-primary);" width="50" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <g fill="currentColor">
                        <path d="M2.881,59.625v25.604l22.175,12.803L47.23,85.229V59.625L25.056,46.822L2.881,59.625z M37.778,79.772l-12.722,7.345l-12.723-7.345v-14.69l12.723-7.346l12.722,7.346V79.772z"></path>
                        <path d="M72.556,15.759L50.381,2.957L28.206,15.76v25.605l22.175,12.803l22.175-12.803V15.759z M63.104,35.907l-12.723,7.346l-12.722-7.346v-14.69l12.722-7.346l12.723,7.346V35.907z"></path>
                        <path d="M75.707,46.822L53.531,59.625v25.604l22.175,12.803l22.175-12.803V59.625L75.707,46.822z M88.429,79.772l-12.723,7.345l-12.723-7.345v-14.69l12.724-7.346l12.722,7.346V79.772z"></path>
                    </g>
                </svg>                
                
                <h1><?php echo esc_html($title); ?></h1>
                <p><?php esc_html_e('This is a pro feature. Please upgrade to MH-ACCESSIBILITY Pro to use this feature', 'accessibility-mh'); ?></p>
                <p>
                    <a href="<?php echo esc_url(MHACC_LINK_TO_PRO); ?>"
                       target="_blank"
                       class="button button-primary">
                        <?php esc_html_e('Upgrade To Pro', 'accessibility-mh'); ?>
                    </a>
                </p>
                
            </div>
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
