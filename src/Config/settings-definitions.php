<?php
if ( ! defined( "ABSPATH" ) ) exit;

require_once MHACC_WIDGET_PATH . 'src/Icons.php';

$mhacc_icons = mhacc_load_icons();

return [



    'general' => [
        'id' => 'general',
        'order' => 1,
        'title' =>  __('General', 'accessibility-mh'),
        'description' =>  __('General settings for activation, language, and legal notices.', 'accessibility-mh'),
        'pro' => false,
        'type' => 'design',
        'fields' => [

            [
                'type'        => 'toggle',
                'name'        => 'widget_active',
                'label'       => __('Enable Widget', 'accessibility-mh'),
                'description' => __('Enables or disables the accessibility widget.', 'accessibility-mh'),
                'icon'        => ' ',
                'isactive'    => 0,
                'pro'         => false,
            ],

            [
                'type'        => 'text',
                'name'        => 'accessibility_statement_url',
                'label'       => __('Accessibility Statement Link', 'accessibility-mh'),
                'description' => __('URL to the official accessibility statement of your website.', 'accessibility-mh'),
                'icon'        => ' ',
                'placeholder' => 'https://example.com/accessibility',
                'default'     => '',
                'pro'         => false,
            ],

            [
                'type'        => 'select',
                'name'        => 'widget_language',
                'label'       => __('Language', 'accessibility-mh'),
                'description' => __('Language of the widget for visitors.', 'accessibility-mh'),
                'icon'        => ' ',
                'default'     => 'en',
                'options' => [
                    'en' => [
                        'value' => __('English', 'accessibility-mh'),
                        'pro'   => false,
                    ],
                    'de' => [
                        'value' => __('German', 'accessibility-mh'),
                        'pro'   => false,
                    ],
                    'es' => [
                        'value' => __('Spanish', 'accessibility-mh'),
                        'pro'   => true,
                    ],
                    'fr' => [
                        'value' => __('French', 'accessibility-mh'),
                        'pro'   => true,
                    ],
                    'it' => [
                        'value' => __('Italian', 'accessibility-mh'),
                        'pro'   => true,
                    ],
                    'pt' => [
                        'value' => __('Portuguese', 'accessibility-mh'),
                        'pro'   => true,
                    ],
                    'ru' => [
                        'value' => __('Russian', 'accessibility-mh'),
                        'pro'   => true,
                    ],
                    'zh' => [
                        'value' => __('Chinese (Mandarin)', 'accessibility-mh'),
                        'pro'   => true,
                    ],
                    'ar' => [
                        'value' => __('Arabic', 'accessibility-mh'),
                        'pro'   => true,
                    ],
                    'hi' => [
                        'value' => __('Hindi', 'accessibility-mh'),
                        'pro'   => true,
                    ],
                    'pl' => [
                        'value' => __('Polish', 'accessibility-mh'),
                        'pro'   => true,
                    ],
                    'nl' => [
                        'value' => __('Dutch', 'accessibility-mh'),
                        'pro'   => true,
                    ],
                    'tr' => [
                        'value' => __('Turkish', 'accessibility-mh'),
                        'pro'   => true,
                    ],
                    'ja' => [
                        'value' => __('Japanese', 'accessibility-mh'),
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
        'title' => __('Widget Design', 'accessibility-mh'),
        'description' => __('Customize the appearance of the widget.', 'accessibility-mh'),
        'pro' => false,
        'type' => 'design',
        'fields' => [

            [
                'type'        => 'iconradios',
                'name'        => 'widget_style',
                'label'       => __('Widget Style', 'accessibility-mh'),
                'description' => __('Visual style of the widget', 'accessibility-mh'),
                'icon'        => ' ',
                'options'     => [
                    'style_1' => [
                        'icon' => $mhacc_icons['icon-style_1'] ?? '',
                        'pro'   => false,
                    ],
                    'style_2' => [
                        'icon' => $mhacc_icons['icon-style_2'] ?? '',
                        'pro'   => false,
                    ],
                ],
                'default'     => 'style_1',
                'pro' => false,
            ],

            [
                'type'        => 'select',
                'name'        => 'widget_size',
                'label'       => __('Widget Size', 'accessibility-mh'),
                'description' => __('Size of the widget', 'accessibility-mh'),
                'icon'        => ' ',
                'default'     => 'medium',
                'options'     => [
                    'small' => [
                        'value' => __('Small', 'accessibility-mh'),
                        'pro'   => false,
                    ],
                    'medium' => [
                        'value' => __('Medium', 'accessibility-mh'),
                        'pro'   => false,
                    ],
                    'large' => [
                        'value' => __('Large', 'accessibility-mh'),
                        'pro'   => false,
                    ],
                ],
                'pro' => false,
            ],

            [
                'type'        => 'color',
                'name'        => 'theme_primary_color',
                'label'       => __('Color', 'accessibility-mh'),
                'description' => __('Primary color of the widget', 'accessibility-mh'),
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
                'label'       => __('Font Color', 'accessibility-mh'),
                'description' => __('Text color of the widget', 'accessibility-mh'),
                'icon'        => ' ',
                'default'     => '#ffffff',
                'options'     => '',
                'pro' => false,
            ],

            [
                'type'        => 'number',
                'name'        => 'border_radius',
                'label'       => __('Border Radius', 'accessibility-mh'),
                'description' => __('Corner rounding', 'accessibility-mh'),
                'icon'        => ' ',
                'default'     => '10',
                'pro' => false,
            ],

            [
                'type'        => 'iconradios',
                'name'        => 'theme_mode',
                'label'       => __('Light / Dark Mode', 'accessibility-mh'),
                'description' => __('Widget appearance', 'accessibility-mh'),
                'icon'        => ' ',
                'options'     => [
                    'light' => [
                        'icon' => $mhacc_icons['icon-sun'] ?? '',
                        'pro'   => false,
                    ],
                    'dark' => [
                        'icon' => $mhacc_icons['icon-moon'] ?? '',
                        'pro'   => true,
                    ],
                ],
                'default'     => 'light',
                'pro' => true,
            ],

            [
                'type' => 'toggle',
                'name' => 'remove_branding',
                'label' => __('Remove Branding', 'accessibility-mh'),
                'description' => __('Removes branding from the widget', 'accessibility-mh'),
                'icon' => '',
                'isactive' => 0,
                'pro' => true,
            ],
        ],
    ],





    'trigger_design' => [
        'id' => 'trigger_design',
        'order' => 3,
        'title' => __('Trigger Design', 'accessibility-mh'),
        'description' => __('Customize the appearance of the trigger.', 'accessibility-mh'),
        'pro' => false,
        'type' => 'design',
        'fields' => [

            [
                'type'        => 'position',
                'name'        => 'position_x',
                'label'       => __('Trigger Position X', 'accessibility-mh'),
                'description' => __('Horizontal position on the page', 'accessibility-mh'),
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
                'label'       => __('Trigger Position Y', 'accessibility-mh'),
                'description' => __('Vertical position on the page', 'accessibility-mh'),
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
                'label'       => __('Trigger Icon', 'accessibility-mh'),
                'description' => __('Choose the icon for the widget trigger', 'accessibility-mh'),
                'icon'        => ' ',
                'options'     => [
                    'icon-1' => [
                        'icon' => $mhacc_icons['icon-1'] ?? '',
                        'pro'   => false,
                    ],
                    'icon-2' => [
                        'icon' => $mhacc_icons['icon-2'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-3' => [
                        'icon' => $mhacc_icons['icon-3'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-4' => [
                        'icon' => $mhacc_icons['icon-4'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-5' => [
                        'icon' => $mhacc_icons['icon-5'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-6' => [
                        'icon' => $mhacc_icons['icon-6'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-7' => [
                        'icon' => $mhacc_icons['icon-7'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-8' => [
                        'icon' => $mhacc_icons['icon-8'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-9' => [
                        'icon' => $mhacc_icons['icon-9'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-10' => [
                        'icon' => $mhacc_icons['icon-10'] ?? '',
                        'pro'   => true,
                    ],
                    'icon-11' => [
                        'icon' => $mhacc_icons['icon-11'] ?? '',
                        'pro'   => true,
                    ],
                ],
                'default'     => 'icon-1',
                'pro' => true,
            ],

            [
                'type'        => 'select',
                'name'        => 'trigger_icon_size',
                'label'       => __('Trigger Icon Size', 'accessibility-mh'),
                'description' => __('Size of the trigger icon', 'accessibility-mh'),
                'icon'        => ' ',
                'options'     => [
                    'small' => [
                        'value' => __('Small', 'accessibility-mh'),
                        'pro'   => true,
                    ],
                    'medium' => [
                        'value' => __('Medium', 'accessibility-mh'),
                        'pro'   => true,
                    ],
                    'large' => [
                        'value' => __('Large', 'accessibility-mh'),
                        'pro'   => true,
                    ],
                ],
                'default'     => 'medium',
                'pro' => true,
            ],

            [
                'type'        => 'select',
                'name'        => 'trigger_icon_style',
                'label'       => __('Trigger Icon Style', 'accessibility-mh'),
                'description' => __('Visual style of the trigger icon', 'accessibility-mh'),
                'icon'        => ' ',
                'options'     => [
                    'style_1' => [
                        'value' => __('Round', 'accessibility-mh'),
                        'pro'   => true,
                    ],
                    'style_2' => [
                        'value' => __('Semi-Round', 'accessibility-mh'),
                        'pro'   => true,
                    ],
                    'style_3' => [
                        'value' => __('Square', 'accessibility-mh'),
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
        'title' => __('Content Modules', 'accessibility-mh'),
        'description' => __('Settings for font size, readability, and links.', 'accessibility-mh'),
        'pro' => false,
        'type' => 'feature',
        'fields' => [

            [
                'type' => 'toggle',
                'jstype' => 'slider',
                'jsdefault' => 100,
                'name' => 'font_size',
                'label' => __('Font Size', 'accessibility-mh'),
                'description' => __('Increase or decrease text size', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-readablefont'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'slider',
                'jsdefault' => 100,
                'name' => 'line_height',
                'label' => __('Line Height', 'accessibility-mh'),
                'description' => __('Adjust line spacing', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-align'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'slider',
                'jsdefault' => 100,
                'name' => 'letter_spacing',
                'label' => __('Letter Spacing', 'accessibility-mh'),
                'description' => __('Adjust spacing between letters', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-spacing'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'readable_font',
                'label' => __('Readable Font', 'accessibility-mh'),
                'description' => __('Uses an easy-to-read font', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-readablefont'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'highlight_links',
                'label' => __('Highlight Links', 'accessibility-mh'),
                'description' => __('Highlights links on the page', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-links'] ?? '',
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
                'label' => __('Text Alignment', 'accessibility-mh'),
                'description' => __('Change text alignment', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-align'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'big_cursor',
                'label' => __('Large Cursor', 'accessibility-mh'),
                'description' => __('Makes the cursor larger', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-cursor'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'tts',
                'label' => __('Text-to-Speech', 'accessibility-mh'),
                'description' => __('Reads text aloud', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-speaker'] ?? '',
                'isactive' => 0,
                'pro' => true,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'text_zoom',
                'label' => __('Cursor Reading Aid', 'accessibility-mh'),
                'description' => __('Zooms text near the cursor', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-zoom'] ?? '',
                'isactive' => 0,
                'pro' => true,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'dyslexia_font_pro',
                'label' => __('Dyslexia-Friendly Fonts', 'accessibility-mh'),
                'description' => __('Improves readability for dyslexia', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-dyslexia'] ?? '',
                'isactive' => 0,
                'pro' => true,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'smart_spacing',
                'label' => __('Smart Spacing', 'accessibility-mh'),
                'description' => __('Optimizes text spacing', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-spacing'] ?? '',
                'isactive' => 0,
                'pro' => true,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'focus',
                'label' => __('Focus Outline', 'accessibility-mh'),
                'description' => __('Adds an outline around focused elements', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-focus'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'stop_autoplay',
                'label' => __('Stop Autoplay', 'accessibility-mh'),
                'description' => __('Stops autoplay for videos and audio', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-stop-autoplay'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'highlight_title',
                'label' => __('Highlight Headings', 'accessibility-mh'),
                'description' => __('Highlights page headings', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-highlight-title'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],
        ],
    ],




    'visuelle_module' => [
        'id' => 'visuelle_module',
        'order' => 5,
        'title' => __('Visual Modules', 'accessibility-mh'),
        'description' => __('Color modes and contrast settings.', 'accessibility-mh'),
        'pro' => false,
        'type' => 'feature',
        'fields' => [

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'dark_mode',
                'label' => __('Dark Mode', 'accessibility-mh'),
                'description' => __('Enables dark color scheme', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-dark'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'high_contrast',
                'label' => __('High Contrast', 'accessibility-mh'),
                'description' => __('Increases color contrast', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-contrast'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'monochrome',
                'label' => __('Monochrome', 'accessibility-mh'),
                'description' => __('Black and white mode', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-mono'] ?? '',
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
                'label' => __('Color Vision Filters', 'accessibility-mh'),
                'description' => __('Adjust colors for color vision deficiencies', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-colorblind'] ?? '',
                'isactive' => 0,
                'pro' => true,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'intelligent_contrast',
                'label' => __('Intelligent Contrast', 'accessibility-mh'),
                'description' => __('Automatically adjusts contrast', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-contrast'] ?? '',
                'isactive' => 0,
                'pro' => true,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'saturation',
                'label' => __('Saturation', 'accessibility-mh'),
                'description' => __('Adjusts color saturation', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-saturation'] ?? '',
                'isactive' => 0,
                'pro' => true,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'invert_color',
                'label' => __('Invert Colors', 'accessibility-mh'),
                'description' => __('Inverts page colors', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-invert-colors'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],
        ],
    ],




    'orientierung_navigation' => [
        'id' => 'orientierung_navigation',
        'order' => 6,
        'title' => __('Orientation & Navigation', 'accessibility-mh'),
        'description' => __('Reading aids, focus tools, and animation settings.', 'accessibility-mh'),
        'pro' => false,
        'type' => 'feature',
        'fields' => [

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'reading_line',
                'label' => __('Reading Line', 'accessibility-mh'),
                'description' => __('Horizontal reading guide', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-line'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'reading_mask',
                'label' => __('Reading Mask', 'accessibility-mh'),
                'description' => __('Highlight a focused reading area', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-mask'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'stop_animations',
                'label' => __('Stop Animations', 'accessibility-mh'),
                'description' => __('Stops all animations', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-stop'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'hide_images',
                'label' => __('Hide Images', 'accessibility-mh'),
                'description' => __('Hides images on the page', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-hideimages'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'keyboard_navigation',
                'label' => __('Keyboard-Navigation', 'accessibility-mh'),
                'description' => __('Keyboard-Navigation', 'accessibility-mh'),
                'icon' => $mhacc_icons['icon-readablefont'] ?? '',
                'isactive' => 1,
                'pro' => false,
            ],

        ],
    ],


];
