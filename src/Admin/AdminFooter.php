<?php
namespace MHACC\Admin;
if ( ! defined( "ABSPATH" ) ) exit;

class AdminFooter
{
    public function __construct()
    {
        add_filter('admin_footer_text', [$this, 'render_footer_left']);
        add_filter('update_footer', [$this, 'render_footer_right'], 11);
    }

    /**
     * Prüft, ob wir uns auf einer MHACC-Adminseite befinden
     */
    private function is_plugin_page(): bool
    {
        if (!function_exists('get_current_screen')) {
            return false;
        }

        $screen = get_current_screen();
        return $screen && str_contains($screen->id, 'mhacc-widget');
    }

    /**
     * Linker Footer-Text
     */
    public function render_footer_left(string $text): string
    {
        if (!$this->is_plugin_page()) {
            return $text;
        }
        
        return sprintf(
            '<span id="footer-thankyou">
                %s <a href="%s" target="_blank" rel="noopener noreferrer" class="mhacc-rating-link" aria-label="five stars">★★★★★</a>. 
            </span>',
            esc_html__('If you like MH Accessibility, please give us a', 'mh-accessibility'),
            esc_url(MHACC_LINK_TO_RATINGS)
        ) . ' ' . esc_html__('Thank you in advance.', 'mh-accessibility');
        
    }

    /**
     * Rechter Footer-Text (Version)
     */
    public function render_footer_right(string $text): string
    {
        if (!$this->is_plugin_page()) {
            return $text;
        }

        return sprintf(
            /* translators: %s: plugin version number */
            esc_html__('MH-Accessibility Version %s', 'mh-accessibility'),
            esc_html(MHACC_VERSION)
        );
    }
}
