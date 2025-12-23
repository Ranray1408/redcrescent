<?php
/**
 * Register our custom post types
 *
 * @package WP-rock
 * @since 4.4.0
 */

function register_cpt(): void
{
    foreach ( glob( get_template_directory() . '/src/inc/custom-post-types/*.php' ) as $file ) {
        require $file;
    }
}


add_action( 'init', 'register_cpt' );