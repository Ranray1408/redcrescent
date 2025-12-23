<?php

/**
 * Custom shortcodes
 *
 * @package WP-rock/shortcodes
 */

/*
 *  BR SHORTCODE
 */
if (! function_exists('br_shortcode')) {

    /**
     * Shortcode for "br"
     *
     * @param {array}       $atts    - shortcode attributes.
     * @param {string|null} $content - content inside open/close shortcode tags.
     *
     * @return string
     */
    function br_shortcode($atts, $content = null) {
        extract(
            shortcode_atts(
                array(
                    'class' => '',
                ),
                $atts
            )
        );

        $output = '<br class="custom-br ' . $class . '">';

        return $output;
    }

    add_shortcode('br', 'br_shortcode');
}

/*
 *  CURRENT YEAR SHORTCODE
 */

if (! function_exists('current_year_shortcode')) {

    /**
     * Shortcode for "current year"
     *
     * @return string
     */
    function current_year_shortcode() {
        $output = '<span>' . gmdate('Y') . '</span>';
        return $output;
    }

    add_shortcode('current-year', 'current_year_shortcode');
}

/*
 * admin_notes SHORTCODE - you should use this shortcode if you want to add big comment in admin editor,
 * but you don't want to display this info in front area
 */

if (! function_exists('admin_notes_shortcode')) {

    /**
     * Shortcode for "Admin notes inside editor page in admin dashboard".
     *
     * @param {array}       $atts    - shortcode attributes.
     * @param {string|null} $content - content inside open/close shortcode tags.
     *
     * @return string
     */
    function admin_notes_shortcode($atts, $content = null) {
        return '';
    }

    add_shortcode('admin_notes', 'admin_notes_shortcode');
}

/*
 * Content for logged user shortcode
 */

if (! function_exists('logged_user_shortcode')) {

    /**
     * Shortcode for "Logged user". It means that content will be shown only for logged users.
     *
     * @param {array}       $atts    - shortcode attributes.
     * @param {string|null} $content - content inside open/close shortcode tags.
     *
     * @return string
     */
    function logged_user_shortcode($atts, $content = null) {
        if (is_user_logged_in()) {
            return do_shortcode($content);
        }

        return '';
    }

    add_shortcode('logged_user', 'logged_user_shortcode');
}

/*
 *  Popup box shortcode
 */

if (! function_exists('shortcode__boxpopup')) {
    /**
     * Shortcode for "Popup box".
     *
     * @param {array}       $atts    - shortcode attributes.
     * @param {string|null} $content - content inside open/close shortcode tags.
     *
     * @return string
     */
    function shortcode_popup_box($atts, $content = null) {
        extract(
            shortcode_atts(
                array(
                    'box_id'      => '',
                    'box_caption' => '',
                    'put_svg'     => 'false',
                    'svg_src'     => '',
                ),
                $atts
            )
        );

        $output  = '<div id="' . $box_id . '" class="popup">';
        $output .= '<div class="my_overlay js-popup-close"></div>';

        $output .= '<div class="popup-wrapper">';

        $output .= '<div class="js-popup-inner popup-wrapper-inner">';

        $output .= do_shortcode($content);
        $output .= '</div>';
        $output .= '<button
                        data-role="login-close"
                        class="popup-close js-popup-close js-open-popup-activator">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z" fill="#0F1729"/>
                        </svg>
                    </button>';
        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }

    add_shortcode('popup_box', 'shortcode_popup_box');
}
