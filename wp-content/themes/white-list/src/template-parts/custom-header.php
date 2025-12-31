<?php

/**
 * Custom header template
 *
 * @package WP-rock
 */

global $global_options;

$logo = get_field_value($global_options, 'logo');
$support_button = get_field_value($global_options, 'support_button');

$support_button_title = get_field_value($support_button, 'title');
$support_button_url = get_field_value($support_button, 'url');
?>

<header class="header js-toggle-menu-elem">
    <div class="container header__container">
        <div class="header__inner-container">

            <?php
            if (!empty($logo)) {
                echo '<a href="' . get_site_url() . '" class="header__logo">
                        <img src="' . $logo . '" alt="logo">
                    </a>';
            }
            ?>

            <nav class="header__nav-wrapper">
                <?php

                echo '<div class="header__logo-wrapper">';

                if (!empty($logo)) {
                    echo '<a href="' . get_site_url() . '" class="header__logo mob">
                                <img src="' . $logo . '" alt="logo">
                            </a>';
                }

                echo '<div class="header__lang-wrapper mob">
                            <a href="#onetap-toolbar" class="header__eye-btn">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 12C10 12.5304 10.2107 13.0391 10.5858 13.4142C10.9609 13.7893 11.4696 14 12 14C12.5304 14 13.0391 13.7893 13.4142 13.4142C13.7893 13.0391 14 12.5304 14 12C14 11.4696 13.7893 10.9609 13.4142 10.5858C13.0391 10.2107 12.5304 10 12 10C11.4696 10 10.9609 10.2107 10.5858 10.5858C10.2107 10.9609 10 11.4696 10 12Z" stroke="#2F2F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M21 12C18.6 16 15.6 18 12 18C8.4 18 5.4 16 3 12C5.4 8 8.4 6 12 6C15.6 6 18.6 8 21 12Z" stroke="#2F2F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>';

                echo get_template_part('src/template-parts/lang-select');

                echo '</div>';

                echo '</div>';

                wp_nav_menu([
                    'menu' => 'Primary Menu ' . pll_current_language(),
                    'echo' => true,
                    'container' => false,
                    'menu_class' => 'header__menu',
                ]);


                if (!empty($support_button_title) && !empty($support_button_url)) {
                    echo '<a href="' . $support_button_url . '" class="header__support-button mob js-toggle-menu-btn primary-btn">
                            ' . $support_button_title . '
                        </a>';
                }

                ?>
            </nav>

            <div class="header__contact-us-wrapper">

                <div class="header__lang-wrapper">
                    <a href="#onetap-toolbar" class="header__eye-btn">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 12C10 12.5304 10.2107 13.0391 10.5858 13.4142C10.9609 13.7893 11.4696 14 12 14C12.5304 14 13.0391 13.7893 13.4142 13.4142C13.7893 13.0391 14 12.5304 14 12C14 11.4696 13.7893 10.9609 13.4142 10.5858C13.0391 10.2107 12.5304 10 12 10C11.4696 10 10.9609 10.2107 10.5858 10.5858C10.2107 10.9609 10 11.4696 10 12Z" stroke="#2F2F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M21 12C18.6 16 15.6 18 12 18C8.4 18 5.4 16 3 12C5.4 8 8.4 6 12 6C15.6 6 18.6 8 21 12Z" stroke="#2F2F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    <?php echo get_template_part('src/template-parts/lang-select'); ?>
                </div>

                <?php
                if (!empty($support_button_title) && !empty($support_button_url)) {
                    echo '<a href="' . $support_button_url . '"
                            class="header__support-button primary-btn ' . get_popup_class($support_button_url) . '">

                            ' . $support_button_title . '
                        </a>';
                }
                ?>

                <button class="header__hamburger red-hover-bg-effect js-toggle-menu-btn square-wrapper">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6H20M4 12H20M4 18H20" stroke="#2F2F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>
