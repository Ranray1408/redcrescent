<?php

/**
 * Create Theme General Settings
 *
 * @package acf/settings
 */

if (function_exists('acf_add_options_page')) {

    $parent = acf_add_options_page(
        array(
            'page_title' => 'Theme General Settings',
            'menu_title' => 'Общие настройки',
            'menu_slug'  => 'theme-general-settings',
            'post_id'    => 'theme-general-settings',
            'capability' => 'edit_posts',
            'redirect'   => false,
        )
    );
}

// Specify which menu location will be used in theme.
register_nav_menus(
    array(
        'primary_menu' => __('Primary Menu', 'wp-rock'),
        'footer_menu'  => __('Footer Menu', 'wp-rock'),
    )
);

/**
 * Initial setup actions for site
 *
 * @package WP-rock
 */

/*Collect all ACF option fields to global variable. */
global $global_options;

if (function_exists('get_fields')) {
    if (function_exists('pll_current_language')) {
        // @codingStandardsIgnoreStart
        $locale         = get_locale();
        // @codingStandardsIgnoreEnd
        $global_options = get_fields('theme-general-settings_' . $locale);
    } else {
        $global_options = get_fields('theme-general-settings');
    }
}

/**
 * Helper: safely get value from array.
 * Business logic: provide a simple accessor used in templates to avoid notices.
 *
 * @param {array}  $data_arr - Array to check and return data.
 * @param {string} $key      - key that should be found in array.
 *
 * @return mixed|null
 */
function get_field_value($data_arr, $key) {
    return (isset($data_arr[$key])) ? $data_arr[$key] : null;
}

// Add title tag support.
add_theme_support('title-tag');

// Add default posts and comments RSS feed links to head.
add_theme_support('automatic-feed-links');

// Enable support for Post Thumbnails on posts and pages.
add_theme_support('post-thumbnails');

// Add shortcode support in text widgets.
add_filter('widget_text', 'do_shortcode');

// Set  Revisions Config to Zero.
add_filter('wp_revisions_to_keep', '__return_zero');
