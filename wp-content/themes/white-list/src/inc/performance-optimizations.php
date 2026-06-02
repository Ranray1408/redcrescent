<?php

/**
 * Frontend-only performance optimizations.
 * All hooks are guarded by is_admin() to avoid breaking the admin panel.
 */

if (!is_admin()) :

/**
 * Move jQuery to footer to unblock render
 */
add_action('wp_enqueue_scripts', function () {
    if (!empty($GLOBALS['wp_scripts']->registered['jquery'])) {
        $GLOBALS['wp_scripts']->add_data('jquery', 'group', 1);
    }
    if (!empty($GLOBALS['wp_scripts']->registered['jquery-core'])) {
        $GLOBALS['wp_scripts']->add_data('jquery-core', 'group', 1);
    }
    if (!empty($GLOBALS['wp_scripts']->registered['jquery-migrate'])) {
        $GLOBALS['wp_scripts']->add_data('jquery-migrate', 'group', 1);
    }
});

/**
 * Load frontend.css asynchronously (preload + media="print" onload swap)
 */
add_filter('style_loader_tag', function ($tag, $handle) {
    if ('styles' === $handle) {
        $tag = str_replace(
            "media='all'",
            "media='print' onload=\"this.media='all'\"",
            $tag
        );
    }
    return $tag;
}, 10, 2);

/**
 * Defer non-critical scripts
 */
add_filter('script_loader_tag', function ($tag, $handle) {
    $skip = ['scripts', 'admin-bar', 'comment-reply'];
    if (!in_array($handle, $skip) && preg_match('/<script /', $tag)) {
        $tag = str_replace('<script ', '<script defer ', $tag);
    }
    return $tag;
}, 10, 2);

/**
 * Preconnect + preload for faster font and external resource loading
 */
add_action('wp_head', function () {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://widget.tiptoppay.kz">' . "\n";

    echo '<link rel="preload" as="font" href="' . THEME_URI . '/src/fonts/Muller-Regular.woff2" crossorigin>' . "\n";
    echo '<link rel="preload" as="font" href="' . THEME_URI . '/src/fonts/Muller-Medium.woff2" crossorigin>' . "\n";
    echo '<link rel="preload" as="font" href="' . THEME_URI . '/src/fonts/Muller-Bold.woff2" crossorigin>' . "\n";
    echo '<link rel="preload" as="font" href="' . THEME_URI . '/src/fonts/Gilroy-Regular.woff2" crossorigin>' . "\n";
    echo '<link rel="preload" as="font" href="' . THEME_URI . '/src/fonts/Gilroy-Bold.woff2" crossorigin>' . "\n";
}, 1);

endif;
