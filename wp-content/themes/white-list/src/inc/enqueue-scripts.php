<?php

/**
 * Enqueue scripts and styles.
 *
 * @return void
 */
function site_scripts() {
    $general_style_ver = file_exists(get_stylesheet_directory() . '/style.css')
        ? gmdate('ymd-Gis', filemtime(get_stylesheet_directory() . '/style.css'))
        : null;
    $frontend_css      = get_template_directory() . '/build/css/frontend.css';
    $frontend_js       = get_template_directory() . '/build/js/frontend.js';
    $custom_style_ver  = file_exists($frontend_css) ? gmdate('ymd-Gis', filemtime($frontend_css)) : null;
    $custom_js_ver     = file_exists($frontend_js) ? gmdate('ymd-Gis', filemtime($frontend_js)) : null;



    // Main stylesheet (required by every site).
    wp_enqueue_style('wp-rock-style', get_stylesheet_uri(), array(), $general_style_ver);

    // Optional compiled frontend CSS/JS from build (present after first build).
    if (file_exists($frontend_css)) {
        wp_enqueue_style('styles', get_template_directory_uri() . '/build/css/frontend.css', array('wp-rock-style'), $custom_style_ver);
    }

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    if (file_exists($frontend_js)) {
        wp_enqueue_script('scripts', get_template_directory_uri() . '/build/js/frontend.js', array(), $custom_js_ver, true);


        wp_localize_script('scripts', 'php_vars', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'site_url' => get_site_url(),
        ]);
    }
}

add_action('wp_enqueue_scripts', 'site_scripts');


// Keep admin assets minimal in boilerplate (none by default)
