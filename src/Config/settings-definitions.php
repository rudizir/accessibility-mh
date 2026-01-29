<?php
if (!defined('ABSPATH')) exit;

require_once MHACC_WIDGET_PATH . 'src/Icons.php';

$icons = mhacc_load_icons();

return [



    'general' => [
        'id' => 'general',
        'order' => 1,
        'title' =>  __('General', 'mhacc'),
        'description' =>  __('General settings for activation, language, and legal notices.', 'mhacc'),
        'pro' => false,
        'type' => 'design',
        'fields' => [

            [
                'type'        => 'toggle',
                'name'        => 'widget_active',
                'label'       => __('Enable Widget', 'mhacc'),
                'description' => __('Enables or disables the accessibility widget.', 'mhacc'),
                'icon'        => ' ',
                'isactive'    => 0,
                'pro'         => false,
            ],

            [
                'type'        => 'text',
                'name'        => 'accessibility_statement_url',
                'label'       => __('Accessibility Statement Link', 'mhacc'),
                'description' => __('URL to the official accessibility statement of your website.', 'mhacc'),
                'icon'        => ' ',
                'placeholder' => 'https://example.com/accessibility',
                'default'     => '',
                'pro'         => false,
            ],

            [
                'type'        => 'select',
                'name'        => 'widget_language',
                'label'       => __('Language', 'mhacc'),
                'description' => __('Language of the widget for visitors.', 'mhacc'),
                'icon'        => ' ',
                'default'     => 'en',
                'options' => [
                    'en' => [
                        'value' => __('English', 'mhacc'),
                        'pro'   => false,
                    ],
                    'de' => [
                        'value' => __('German', 'mhacc'),
                        'pro'   => false,
                    ],
                    'es' => [
                        'value' => __('Spanish', 'mhacc'),
                        'pro'   => true,
                    ],
                    'fr' => [
                        'value' => __('French', 'mhacc'),
                        'pro'   => true,
                    ],
                    'it' => [
                        'value' => __('Italian', 'mhacc'),
                        'pro'   => true,
                    ],
                    'pt' => [
                        'value' => __('Portuguese', 'mhacc'),
                        'pro'   => true,
                    ],
                    'ru' => [
                        'value' => __('Russian', 'mhacc'),
                        'pro'   => true,
                    ],
                    'zh' => [
                        'value' => __('Chinese (Mandarin)', 'mhacc'),
                        'pro'   => true,
                    ],
                    'ar' => [
                        'value' => __('Arabic', 'mhacc'),
                        'pro'   => true,
                    ],
                    'hi' => [
                        'value' => __('Hindi', 'mhacc'),
                        'pro'   => true,
                    ],
                    'pl' => [
                        'value' => __('Polish', 'mhacc'),
                        'pro'   => true,
                    ],
                    'nl' => [
                        'value' => __('Dutch', 'mhacc'),
                        'pro'   => true,
                    ],
                    'tr' => [
                        'value' => __('Turkish', 'mhacc'),
                        'pro'   => true,
                    ],
                    'ja' => [
                        'value' => __('Japanese', 'mhacc'),
                        'pro'   => true,
                    ],
                ],
                'pro'         => false,
            ],
        ],
    ],



    'widget_design' => [
        'id' => 'widget_design',
        'order' => 2,
        'title' => __('Widget Design', 'mhacc'),
        'description' => __('Customize the appearance of the widget.', 'mhacc'),
        'pro' => false,
        'type' => 'design',
        'fields' => [

            [
                'type'        => 'iconradios',
                'name'        => 'widget_style',
                'label'       => __('Widget Style', 'mhacc'),
                'description' => __('Visual style of the widget', 'mhacc'),
                'icon'        => ' ',
                'options'     => [
                    'style_1' => [
                        'icon' => $icons['icon-style_1'] ?? '',
                        'pro'   => false,
                    ],
                    'style_2' => [
                        'icon' => $icons['icon-style_2'] ?? '',
                        'pro'   => false,
                    ],
                ],
                'default'     => 'style_1',
                'pro' => false,
            ],

            [
                'type'        => 'select',
                'name'        => 'widget_size',
                'label'       => __('Widget Size', 'mhacc'),
                'description' => __('Size of the widget', 'mhacc'),
                'icon'        => ' ',
                'default'     => 'medium',
                'options'     => [
                    'small' => [
                        'value' => __('Small', 'mhacc'),
                        'pro'   => false,
                    ],
                    'medium' => [
                        'value' => __('Medium', 'mhacc'),
                        'pro'   => false,
                    ],
                    'large' => [
                        'value' => __('Large', 'mhacc'),
                        'pro'   => false,
                    ],
                ],
                'pro' => false,
            ],

            [
                'type'        => 'color',
                'name'        => 'theme_primary_color',
                'label'       => __('Color', 'mhacc'),
                'description' => __('Primary color of the widget', 'mhacc'),
                'icon'        => ' ',
                'default'     => '#0000ff',
                'options'     => [
                    '#0000ff',
                    '#dab064',
                    '#ffc107',
                    '#da006f',
                    '#97da4d',
                    '#6f00da',
                    '#cfda76',
                ],
                'pro' => false,
            ],

            [
                'type'        => 'color',
                'name'        => 'theme_primary_fontcolor',
                'label'       => __('Font Color', 'mhacc'),
                'description' => __('Text color of the widget', 'mhacc'),
                'icon'        => ' ',
                'default'     => '#ffffff',
                'options'     => '',
                'pro' => false,
            ],

            [
                'type'        => 'number',
                'name'        => 'border_radius',
                'label'       => __('Border Radius', 'mhacc'),
                'description' => __('Corner rounding', 'mhacc'),
                'icon'        => ' ',
                'default'     => '10',
                'pro' => false,
            ],

            [
                'type'        => 'iconradios',
                'name'        => 'theme_mode',
                'label'       => __('Light / Dark Mode', 'mhacc'),
                'description' => __('Widget appearance', 'mhacc'),
                'icon'        => ' ',
                'options'     => [
                    'light' => [
                        'icon' => $icons['icon-sun'] ?? '',
                        'pro'   => false,
                    ],
                    'dark' => [
                        'icon' => $icons['icon-moon'] ?? '',
                        'pro'   => true,
                    ],
                ],
                'default'     => 'light',
                'pro' => true,
            ],

            [
                'type' => 'toggle',
                'name' => 'remove_branding',
                'label' => __('Remove Branding', 'mhacc'),
                'description' => __('Removes branding from the widget', 'mhacc'),
                'icon' => '',
                'isactive' => 0,
                'pro' => true,
            ],
        ],
    ],





    'trigger_design' => [
        'id' => 'trigger_design',
        'order' => 3,
        'title' => __('Trigger Design', 'mhacc'),
        'description' => __('Customize the appearance of the trigger.', 'mhacc'),
        'pro' => false,
        'type' => 'design',
        'fields' => [

            [
                'type'        => 'position',
                'name'        => 'position_x',
                'label'       => __('Trigger Position X', 'mhacc'),
                'description' => __('Horizontal position on the page', 'mhacc'),
                'icon'        => ' ',
                'options'     => [
                    'value' => [
                        'type' => 'number',
                        'default' => '2',
                    ],
                    'unit' => [
                        'type' => 'select',
                        'default' => 'rem',
                        'options' => ['rem', 'px'],
                    ],
                    'position' => [
                        'type' => 'select',
                        'default' => 'right',
                        'options' => ['left', 'right'],
                    ],
                ],
                'pro' => false,
            ],

            [
                'type'        => 'position',
                'name'        => 'position_y',
                'label'       => __('Trigger Position Y', 'mhacc'),
                'description' => __('Vertical position on the page', 'mhacc'),
                'icon'        => ' ',
                'options'     => [
                    'value' => [
                        'type' => 'number',
                        'default' => '2',
                    ],
                    'unit' => [
                        'type' => 'select',
                        'default' => 'rem',
                        'options' => ['rem', 'px'],
                    ],
                    'position' => [
                        'type' => 'select',
                        'default' => 'bottom',
                        'options' => ['bottom', 'top'],
                    ],
                ],
                'pro' => false,
            ],

            [
                'type'        => 'iconradios',
                'name'        => 'trigger_icon',
                'label'       => __('Trigger Icon', 'mhacc'),
                'description' => __('Choose the icon for the widget trigger', 'mhacc'),
                'icon'        => ' ',
                'options'     => [
                    'icon-1' => [
                        'icon' => $icons['icon-1'] ?? '',
                        'pro'   => false,
                    ],
                    'icon-2' => [
                        'icon' => $icons['icon-2'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-3' => [
                        'icon' => $icons['icon-3'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-4' => [
                        'icon' => $icons['icon-4'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-5' => [
                        'icon' => $icons['icon-5'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-6' => [
                        'icon' => $icons['icon-6'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-7' => [
                        'icon' => $icons['icon-7'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-8' => [
                        'icon' => $icons['icon-8'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-9' => [
                        'icon' => $icons['icon-9'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-10' => [
                        'icon' => $icons['icon-10'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-11' => [
                        'icon' => $icons['icon-11'] ?? '',
                        'pro'   => true,
                    ],
                ],
                'default'     => 'icon-1',
                'pro' => true,
            ],

            [
                'type'        => 'select',
                'name'        => 'trigger_icon_size',
                'label'       => __('Trigger Icon Size', 'mhacc'),
                'description' => __('Size of the trigger icon', 'mhacc'),
                'icon'        => ' ',
                'options'     => [
                    'small' => [
                        'value' => __('Small', 'mhacc'),
                        'pro'   => true,
                    ],
                    'medium' => [
                        'value' => __('Medium', 'mhacc'),
                        'pro'   => true,
                    ],
                    'large' => [
                        'value' => __('Large', 'mhacc'),
                        'pro'   => true,
                    ],
                ],
                'default'     => 'medium',
                'pro' => true,
            ],

            [
                'type'        => 'select',
                'name'        => 'trigger_icon_style',
                'label'       => __('Trigger Icon Style', 'mhacc'),
                'description' => __('Visual style of the trigger icon', 'mhacc'),
                'icon'        => ' ',
                'options'     => [
                    'style_1' => [
                        'value' => __('Round', 'mhacc'),
                        'pro'   => true,
                    ],
                    'style_2' => [
                        'value' => __('Semi-Round', 'mhacc'),
                        'pro'   => true,
                    ],
                    'style_3' => [
                        'value' => __('Square', 'mhacc'),
                        'pro'   => true,
                    ],
                ],
                'default'     => 'style-1',
                'pro' => true,
            ],
        ],
    ],










    'inhaltsmodule' => [
        'id' => 'inhaltsmodule',
        'order' => 4,
        'title' => __('Content Modules', 'mhacc'),
        'description' => __('Settings for font size, readability, and links.', 'mhacc'),
        'pro' => false,
        'type' => 'feature',
        'fields' => [

            [
                'type' => 'toggle',
                'jstype' => 'slider',
                'jsdefault' => 100,
                'name' => 'font_size',
                'label' => __('Font Size', 'mhacc'),
                'description' => __('Increase or decrease text size', 'mhacc'),
                'icon' => $icons['icon-readablefont'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'slider',
                'jsdefault' => 100,
                'name' => 'line_height',
                'label' => __('Line Height', 'mhacc'),
                'description' => __('Adjust line spacing', 'mhacc'),
                'icon' => $icons['icon-align'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'slider',
                'jsdefault' => 100,
                'name' => 'letter_spacing',
                'label' => __('Letter Spacing', 'mhacc'),
                'description' => __('Adjust spacing between letters', 'mhacc'),
                'icon' => $icons['icon-spacing'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'readable_font',
                'label' => __('Readable Font', 'mhacc'),
                'description' => __('Uses an easy-to-read font', 'mhacc'),
                'icon' => $icons['icon-readablefont'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'highlight_links',
                'label' => __('Highlight Links', 'mhacc'),
                'description' => __('Highlights links on the page', 'mhacc'),
                'icon' => $icons['icon-links'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'select',
                'jsoptions' => [
                    'left',
                    'center',
                    'right',
                    'justify'
                ],
                'name' => 'text_align',
                'label' => __('Text Alignment', 'mhacc'),
                'description' => __('Change text alignment', 'mhacc'),
                'icon' => $icons['icon-align'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'big_cursor',
                'label' => __('Large Cursor', 'mhacc'),
                'description' => __('Makes the cursor larger', 'mhacc'),
                'icon' => $icons['icon-cursor'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'tts',
                'label' => __('Text-to-Speech', 'mhacc'),
                'description' => __('Reads text aloud', 'mhacc'),
                'icon' => $icons['icon-speaker'] ?? '',
                'isactive' => 0,
                'pro' => true,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'text_zoom',
                'label' => __('Cursor Reading Aid', 'mhacc'),
                'description' => __('Zooms text near the cursor', 'mhacc'),
                'icon' => $icons['icon-zoom'] ?? '',
                'isactive' => 0,
                'pro' => true,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'dyslexia_font_pro',
                'label' => __('Dyslexia-Friendly Fonts', 'mhacc'),
                'description' => __('Improves readability for dyslexia', 'mhacc'),
                'icon' => $icons['icon-dyslexia'] ?? '',
                'isactive' => 0,
                'pro' => true,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'smart_spacing',
                'label' => __('Smart Spacing', 'mhacc'),
                'description' => __('Optimizes text spacing', 'mhacc'),
                'icon' => $icons['icon-spacing'] ?? '',
                'isactive' => 0,
                'pro' => true,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'focus',
                'label' => __('Focus Outline', 'mhacc'),
                'description' => __('Adds an outline around focused elements', 'mhacc'),
                'icon' => $icons['icon-focus'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'stop_autoplay',
                'label' => __('Stop Autoplay', 'mhacc'),
                'description' => __('Stops autoplay for videos and audio', 'mhacc'),
                'icon' => $icons['icon-stop-autoplay'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'highlight_title',
                'label' => __('Highlight Headings', 'mhacc'),
                'description' => __('Highlights page headings', 'mhacc'),
                'icon' => $icons['icon-highlight-title'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],
        ],
    ],




    'visuelle_module' => [
        'id' => 'visuelle_module',
        'order' => 5,
        'title' => __('Visual Modules', 'mhacc'),
        'description' => __('Color modes and contrast settings.', 'mhacc'),
        'pro' => false,
        'type' => 'feature',
        'fields' => [

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'dark_mode',
                'label' => __('Dark Mode', 'mhacc'),
                'description' => __('Enables dark color scheme', 'mhacc'),
                'icon' => $icons['icon-dark'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'high_contrast',
                'label' => __('High Contrast', 'mhacc'),
                'description' => __('Increases color contrast', 'mhacc'),
                'icon' => $icons['icon-contrast'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'monochrome',
                'label' => __('Monochrome', 'mhacc'),
                'description' => __('Black and white mode', 'mhacc'),
                'icon' => $icons['icon-mono'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'select',
                'jsoptions' => [
                    'protanopia',
                    'protanomaly',
                    'deuteranopia',
                    'deuteranomaly',
                    'tritanopia'
                ],
                'name' => 'blind_filters',
                'label' => __('Color Vision Filters', 'mhacc'),
                'description' => __('Adjust colors for color vision deficiencies', 'mhacc'),
                'icon' => $icons['icon-colorblind'] ?? '',
                'isactive' => 0,
                'pro' => true,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'intelligent_contrast',
                'label' => __('Intelligent Contrast', 'mhacc'),
                'description' => __('Automatically adjusts contrast', 'mhacc'),
                'icon' => $icons['icon-contrast'] ?? '',
                'isactive' => 0,
                'pro' => true,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'saturation',
                'label' => __('Saturation', 'mhacc'),
                'description' => __('Adjusts color saturation', 'mhacc'),
                'icon' => $icons['icon-saturation'] ?? '',
                'isactive' => 0,
                'pro' => true,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'invert_color',
                'label' => __('Invert Colors', 'mhacc'),
                'description' => __('Inverts page colors', 'mhacc'),
                'icon' => $icons['icon-invert-colors'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],
        ],
    ],




    'orientierung_navigation' => [
        'id' => 'orientierung_navigation',
        'order' => 6,
        'title' => __('Orientation & Navigation', 'mhacc'),
        'description' => __('Reading aids, focus tools, and animation settings.', 'mhacc'),
        'pro' => false,
        'type' => 'feature',
        'fields' => [

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'reading_line',
                'label' => __('Reading Line', 'mhacc'),
                'description' => __('Horizontal reading guide', 'mhacc'),
                'icon' => $icons['icon-line'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'reading_mask',
                'label' => __('Reading Mask', 'mhacc'),
                'description' => __('Highlight a focused reading area', 'mhacc'),
                'icon' => $icons['icon-mask'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'stop_animations',
                'label' => __('Stop Animations', 'mhacc'),
                'description' => __('Stops all animations', 'mhacc'),
                'icon' => $icons['icon-stop'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'hide_images',
                'label' => __('Hide Images', 'mhacc'),
                'description' => __('Hides images on the page', 'mhacc'),
                'icon' => $icons['icon-hideimages'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],
        ],
    ],


];
