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


/* Disable WordPress Admin Bar for all users */
add_filter('show_admin_bar', '__return_false');

