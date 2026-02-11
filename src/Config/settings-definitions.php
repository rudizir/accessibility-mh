<?php
if ( ! defined( "ABSPATH" ) ) exit;

require_once MHACC_WIDGET_PATH . 'src/Icons.php';

$mhacc_icons = mhacc_load_icons();

return [



    'general' => [
        'id' => 'general',
        'order' => 1,
        'title' =>  __('General', 'mh-accessibility'),
        'description' =>  __('General settings for activation, language, and legal notices.', 'mh-accessibility'),
        'type' => 'design',
        'fields' => [

            [
                'type'        => 'toggle',
                'name'        => 'widget_active',
                'label'       => __('Enable Widget', 'mh-accessibility'),
                'description' => __('Enables or disables the accessibility widget.', 'mh-accessibility'),
                'icon'        => ' ',
                'isactive'    => 0,
            ],

            [
                'type'        => 'text',
                'name'        => 'accessibility_statement_url',
                'label'       => __('Accessibility Statement Link', 'mh-accessibility'),
                'description' => __('URL to the official accessibility statement of your website.', 'mh-accessibility'),
                'icon'        => ' ',
                'placeholder' => 'https://example.com/accessibility',
                'default'     => '',
            ],

            [
                'type'        => 'select',
                'name'        => 'widget_language',
                'label'       => __('Language', 'mh-accessibility'),
                'description' => __('Language of the widget for visitors.', 'mh-accessibility'),
                'icon'        => ' ',
                'default'     => 'en',
                'options' => [
                    'en' => [
                        'value' => __('English', 'mh-accessibility'),
                    ],
                    'de' => [
                        'value' => __('German', 'mh-accessibility'),
                    ],
                ],
            ],
        ],
    ],



    'widget_design' => [
        'id' => 'widget_design',
        'order' => 2,
        'title' => __('Widget Design', 'mh-accessibility'),
        'description' => __('Customize the appearance of the widget.', 'mh-accessibility'),
        'type' => 'design',
        'fields' => [

            [
                'type'        => 'iconradios',
                'name'        => 'widget_style',
                'label'       => __('Widget Style', 'mh-accessibility'),
                'description' => __('Visual style of the widget', 'mh-accessibility'),
                'icon'        => ' ',
                'options'     => [
                    'style_1' => [
                        'icon' => $mhacc_icons['icon-style_1'] ?? '',
                    ],
                    'style_2' => [
                        'icon' => $mhacc_icons['icon-style_2'] ?? '',
                    ],
                ],
                'default'     => 'style_1',
            ],

            [
                'type'        => 'select',
                'name'        => 'widget_size',
                'label'       => __('Widget Size', 'mh-accessibility'),
                'description' => __('Size of the widget', 'mh-accessibility'),
                'icon'        => ' ',
                'default'     => 'medium',
                'options'     => [
                    'small' => [
                        'value' => __('Small', 'mh-accessibility'),
                    ],
                    'medium' => [
                        'value' => __('Medium', 'mh-accessibility'),
                    ],
                    'large' => [
                        'value' => __('Large', 'mh-accessibility'),
                    ],
                ],
            ],

            [
                'type'        => 'color',
                'name'        => 'theme_primary_color',
                'label'       => __('Color', 'mh-accessibility'),
                'description' => __('Primary color of the widget', 'mh-accessibility'),
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
            ],

            [
                'type'        => 'color',
                'name'        => 'theme_primary_fontcolor',
                'label'       => __('Font Color', 'mh-accessibility'),
                'description' => __('Text color of the widget', 'mh-accessibility'),
                'icon'        => ' ',
                'default'     => '#ffffff',
                'options'     => '',
            ],

            [
                'type'        => 'number',
                'name'        => 'border_radius',
                'label'       => __('Border Radius', 'mh-accessibility'),
                'description' => __('Corner rounding', 'mh-accessibility'),
                'icon'        => ' ',
                'default'     => '10',
            ],


            'remove_branding' => [
                'type' => 'toggle',
                'name' => 'remove_branding',
                'label' => __('Remove Branding', 'mh-accessibility'),
                'description' => __('Removes branding from the widget', 'mh-accessibility'),
                'icon' => '',
                'isactive' => 0,
            ],

        ],
    ],





    'trigger_design' => [
        'id' => 'trigger_design',
        'order' => 3,
        'title' => __('Trigger Design', 'mh-accessibility'),
        'description' => __('Customize the appearance of the trigger.', 'mh-accessibility'),
        'type' => 'design',
        'fields' => [

            [
                'type'        => 'position',
                'name'        => 'position_x',
                'label'       => __('Trigger Position X', 'mh-accessibility'),
                'description' => __('Horizontal position on the page', 'mh-accessibility'),
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
            ],

            [
                'type'        => 'position',
                'name'        => 'position_y',
                'label'       => __('Trigger Position Y', 'mh-accessibility'),
                'description' => __('Vertical position on the page', 'mh-accessibility'),
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
            ],

            [
                'type'        => 'iconradios',
                'name'        => 'trigger_icon',
                'label'       => __('Trigger Icon', 'mh-accessibility'),
                'description' => __('Choose the icon for the widget trigger', 'mh-accessibility'),
                'icon'        => ' ',
                'options'     => [
                    'icon-1' => [
                        'icon' => $mhacc_icons['icon-1'] ?? '',
                    ],
                    'icon-2' => [
                        'icon' => $mhacc_icons['icon-2'] ?? '',
                    ],
                ],
                'default'     => 'icon-1',
            ],
            
        ],
    ],










    'inhaltsmodule' => [
        'id' => 'inhaltsmodule',
        'order' => 4,
        'title' => __('Content Modules', 'mh-accessibility'),
        'description' => __('Settings for font size, readability, and links.', 'mh-accessibility'),
        'type' => 'feature',
        'fields' => [

            [
                'type' => 'toggle',
                'jstype' => 'slider',
                'jsdefault' => 100,
                'name' => 'font_size',
                'label' => __('Font Size', 'mh-accessibility'),
                'description' => __('Increase or decrease text size', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-readablefont'] ?? '',
                'isactive' => 1,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'slider',
                'jsdefault' => 100,
                'name' => 'line_height',
                'label' => __('Line Height', 'mh-accessibility'),
                'description' => __('Adjust line spacing', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-align'] ?? '',
                'isactive' => 1,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'slider',
                'jsdefault' => 100,
                'name' => 'letter_spacing',
                'label' => __('Letter Spacing', 'mh-accessibility'),
                'description' => __('Adjust spacing between letters', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-spacing'] ?? '',
                'isactive' => 1,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'readable_font',
                'label' => __('Readable Font', 'mh-accessibility'),
                'description' => __('Uses an easy-to-read font', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-readablefont'] ?? '',
                'isactive' => 1,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'highlight_links',
                'label' => __('Highlight Links', 'mh-accessibility'),
                'description' => __('Highlights links on the page', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-links'] ?? '',
                'isactive' => 1,
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
                'label' => __('Text Alignment', 'mh-accessibility'),
                'description' => __('Change text alignment', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-align'] ?? '',
                'isactive' => 1,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'big_cursor',
                'label' => __('Large Cursor', 'mh-accessibility'),
                'description' => __('Makes the cursor larger', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-cursor'] ?? '',
                'isactive' => 1,
            ],

            

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'focus',
                'label' => __('Focus Outline', 'mh-accessibility'),
                'description' => __('Adds an outline around focused elements', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-focus'] ?? '',
                'isactive' => 1,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'stop_autoplay',
                'label' => __('Stop Autoplay', 'mh-accessibility'),
                'description' => __('Stops autoplay for videos and audio', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-stop-autoplay'] ?? '',
                'isactive' => 1,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'highlight_title',
                'label' => __('Highlight Headings', 'mh-accessibility'),
                'description' => __('Highlights page headings', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-highlight-title'] ?? '',
                'isactive' => 1,
            ],
        ],
    ],



    

    'visuelle_module' => [
        'id' => 'visuelle_module',
        'order' => 5,
        'title' => __('Visual Modules', 'mh-accessibility'),
        'description' => __('Color modes and contrast settings.', 'mh-accessibility'),
        'type' => 'feature',
        'fields' => [

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'dark_mode',
                'label' => __('Dark Mode', 'mh-accessibility'),
                'description' => __('Enables dark color scheme', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-dark'] ?? '',
                'isactive' => 1,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'high_contrast',
                'label' => __('High Contrast', 'mh-accessibility'),
                'description' => __('Increases color contrast', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-contrast'] ?? '',
                'isactive' => 1,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'monochrome',
                'label' => __('Monochrome', 'mh-accessibility'),
                'description' => __('Black and white mode', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-mono'] ?? '',
                'isactive' => 1,
            ],

            
            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'invert_color',
                'label' => __('Invert Colors', 'mh-accessibility'),
                'description' => __('Inverts page colors', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-invert-colors'] ?? '',
                'isactive' => 1,
            ],
        ],
    ],







    'orientierung_navigation' => [
        'id' => 'orientierung_navigation',
        'order' => 6,
        'title' => __('Orientation & Navigation', 'mh-accessibility'),
        'description' => __('Reading aids, focus tools, and animation settings.', 'mh-accessibility'),
        'type' => 'feature',
        'fields' => [

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'reading_line',
                'label' => __('Reading Line', 'mh-accessibility'),
                'description' => __('Horizontal reading guide', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-line'] ?? '',
                'isactive' => 1,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'reading_mask',
                'label' => __('Reading Mask', 'mh-accessibility'),
                'description' => __('Highlight a focused reading area', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-mask'] ?? '',
                'isactive' => 1,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'stop_animations',
                'label' => __('Stop Animations', 'mh-accessibility'),
                'description' => __('Stops all animations', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-stop'] ?? '',
                'isactive' => 1,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'hide_images',
                'label' => __('Hide Images', 'mh-accessibility'),
                'description' => __('Hides images on the page', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-hideimages'] ?? '',
                'isactive' => 1,
            ],

            [
                'type' => 'toggle',
                'jstype' => 'toggle',
                'name' => 'keyboard_navigation',
                'label' => __('Keyboard-Navigation', 'mh-accessibility'),
                'description' => __('Keyboard-Navigation', 'mh-accessibility'),
                'icon' => $mhacc_icons['icon-keyboard'] ?? '',
                'isactive' => 1,
            ],

        ],
    ],


];
