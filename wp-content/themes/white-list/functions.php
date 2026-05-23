<?php
define('THEME_URI', get_template_directory_uri());
define('THEME_DIR', get_template_directory());
define('STYLE_URI', get_stylesheet_uri());
define('STYLE_DIR', get_stylesheet_directory());
define('ASSETS_CSS', THEME_URI . '/assets/public/css/');
define('ASSETS_JS', THEME_URI . '/assets/public/js/');

require THEME_DIR . '/src/inc/custom-posts-type.php';
require THEME_DIR . '/src/inc/custom-taxonomies.php';
require THEME_DIR . '/src/inc/custom-shortcodes.php';
require THEME_DIR . '/src/inc/class-acf-blocks.php';
require THEME_DIR . '/src/inc/initial-setup.php';
require THEME_DIR . '/src/inc/enqueue-scripts.php';


/**
 * Populate hide_footer_menu_items select with footer menu items
 */
add_filter('acf/load_field/name=hide_footer_menu_items', function ($field) {
    $choices = [];
    $menus = wp_get_nav_menus();

    foreach ($menus as $menu) {
        if (strpos($menu->name, 'Footer menu') !== 0) {
            continue;
        }

        $items = wp_get_nav_menu_items($menu->term_id);

        if (!empty($items)) {
            foreach ($items as $item) {
                $language_label = str_replace('Footer menu', '', $menu->name);
                $choices[$item->ID] = $item->title . ' (' . strtoupper($language_label) . ')';
            }
        }
    }

    if (!empty($choices)) {
        $field['choices'] = $choices;
    }

    return $field;
});

/**
 * Filter out hidden footer menu items on pages
 */
add_filter('wp_nav_menu_objects', function ($items, $args) {
    if (!is_page()) {
        return $items;
    }

    $hide_ids = get_field('hide_footer_menu_items', get_the_ID());

    if (empty($hide_ids)) {
        return $items;
    }

    return array_filter($items, function ($item) use ($hide_ids) {
        return !in_array($item->ID, (array) $hide_ids);
    });
}, 10, 2);

/* Disable WordPress Admin Bar for all users */
add_filter('show_admin_bar', '__return_false');

