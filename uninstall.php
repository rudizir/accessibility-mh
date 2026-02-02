<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

// Lösche alle Optionen, die mit mhacc_ beginnen
$mhacc_options = $wpdb->get_col(
    $wpdb->prepare(
        "SELECT option_name
         FROM {$wpdb->options}
         WHERE option_name LIKE %s",
        'mhacc_%'
    )
);

foreach ($mhacc_options as $mhacc_option) {
    delete_option($mhacc_option);
    wp_cache_delete($mhacc_option, 'options'); // Cache leeren
}


$wpdb->query(
    $wpdb->prepare(
        "
        DELETE FROM {$wpdb->options}
        WHERE option_name LIKE %s
           OR option_name LIKE %s
        ",
        '_transient_mhacc_%',
        '_transient_timeout_mhacc_%'
    )
);


// Multisite
if (is_multisite()) {
    $mhacc_siteOptions = $wpdb->get_col(
        $wpdb->prepare(
            "SELECT meta_key
             FROM {$wpdb->sitemeta}
             WHERE meta_key LIKE %s",
            'mhacc_%'
        )
    );

    foreach ($mhacc_siteOptions as $mhacc_option) {
        delete_site_option($mhacc_option);
        wp_cache_delete($mhacc_option, 'site-options'); // Cache leeren
    }
}
