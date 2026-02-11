<?php
namespace MHACC\Widget;
if ( ! defined( "ABSPATH" ) ) exit;

class WidgetSettings
{

    public static function get() {

        $groups  = require MHACC_WIDGET_PATH . 'src/Config/settings-definitions.php';
        $groups = apply_filters(
            'mhacc_widget_setting_groups',
            $groups
        );
        uasort($groups, function ($a, $b) {
            return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
        });
        
        $stored      = get_option('mhacc_settings', []);
        

        $jssettings      = [];
        foreach ($groups as $group) {
            

            if ( ($group['type'] ?? '') !== 'feature' ) {
                continue;
            }
            
            $group_id = $group['id'];

            $jssettings[$group_id] = [
                'id'          => $group['id'],
                'title'       => $group['title'] ?? '',
                'description' => $group['description'] ?? '',
                'fields'      => [],
            ];

            foreach ($group['fields'] as $field) {

                $name = $field['name'];

                // Wert: gespeichert → isactive → null
                $value = $stored[$name] ?? ($field['isactive'] ?? null);


                // Nur aktive Features ins JS
                if (!$value) {
                    continue;
                }

                $jssettings[$group_id]['fields'][$name] = [
                    'name'       => $name,
                    'type'       => $field['jstype'] ?? $field['type'],
                    'label'      => $field['label'] ?? '',
                    'icon'       => $field['icon'] ?? '',
                    'jsdefault'  => $field['jsdefault'] ?? '',
                    'jsoptions'  => $field['jsoptions'] ?? '',
                ];

        
                

            }
        }


        
        $jsdesign = [];

        foreach ($groups as $group) {

            if ( ($group['type'] ?? '') !== 'design' ) {
                continue;
            }

            $group_id = $group['id'];

            $jsdesign[$group_id] = [
                'id'          => $group['id'],
                'fields'      => [],
            ];
            foreach ($group['fields'] as $field) {
                $name = $field['name'];
                // Wert: gespeichert → default → null
                $value = $stored[$name] ?? ($field['default'] ?? null);

                $jsdesign[$group_id]['fields'][$name] = [
                    'name'      => $name,
                    'value'     => $value,
                ];
            }
        }

        return [
            'features' => apply_filters(
                'mhacc_widget_js_features',
                $jssettings
            ),

            'design' => apply_filters(
                'mhacc_widget_js_design',
                $jsdesign
            ),
        ];

    }
}
