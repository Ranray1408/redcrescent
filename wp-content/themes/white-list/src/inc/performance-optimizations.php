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
    wp_deregister_script('jquery');
    wp_register_script('jquery', includes_url('/js/jquery/jquery.min.js'), [], '3.7.1', true);
    wp_enqueue_script('jquery');
}, 100);

add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-rock-style');
}, 100);

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

    echo '<link rel="preload" as="style" href="' . get_template_directory_uri() . '/build/css/frontend.css">' . "\n";
}, 1);

endif;
