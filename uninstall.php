<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;


$options = $wpdb->get_col(
    $wpdb->prepare(
        "SELECT option_name
         FROM {$wpdb->options}
         WHERE option_name LIKE %s",
        'mhacc_%'
    )
);

foreach ($options as $option) {
    delete_option($option);
}


$wpdb->query(
    "DELETE FROM {$wpdb->options}
     WHERE option_name LIKE '_transient_mhacc_%'
        OR option_name LIKE '_transient_timeout_mhacc_%'"
);

if (is_multisite()) {

    $siteOptions = $wpdb->get_col(
        "SELECT meta_key
         FROM {$wpdb->sitemeta}
         WHERE meta_key LIKE 'mhacc_%'"
    );

    foreach ($siteOptions as $option) {
        delete_site_option($option);
    }

}
