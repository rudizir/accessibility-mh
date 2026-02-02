<?php
namespace MHACC\Admin;
if ( ! defined( "ABSPATH" ) ) exit;

class AdminSettings
{
    public function __construct()
    {
        add_action('admin_init', [$this, 'register']);
    }

    public function register(): void
    {
        register_setting(
            'mhacc_widget_settings',
            'mhacc_widget_options',
            [
                'sanitize_callback' => [self::class, 'sanitize'],
                'default'           => [],
            ]
        );

        do_action('mhacc_widget_register_settings');
    }

    /**
     * Zentrale Sanitization
     *
     * @param array $input
     * @return array
     */
    public static function sanitize($input): array
    {
        $input  = is_array($input) ? $input : [];
        $output = [];

        $output = apply_filters(
            'mhacc_widget_sanitize_settings',
            $output,
            $input
        );

        return is_array($output) ? $output : [];
    }
}
