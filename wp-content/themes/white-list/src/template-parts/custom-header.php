<?php

/**
 * Custom header template
 *
 * @package WP-rock
 */

global $global_options;

$logo = get_field_value($global_options, 'logo');
$phone = get_field_value($global_options, 'phone');
$whatsapp = get_field_value($global_options, 'whatsapp');
$contact_us = get_field_value($global_options, 'contact_us');

$phone_title = get_field_value($phone, 'title');
$phone_url = get_field_value($phone, 'url');

$phone_svg = '<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 1H7L9 6L6.5 7.5C7.57096 9.67153 9.32847 11.429 11.5 12.5L13 10L18 12V16C18 16.5304 17.7893 17.0391 17.4142 17.4142C17.0391 17.7893 16.5304 18 16 18C12.0993 17.763 8.42015 16.1065 5.65683 13.3432C2.8935 10.5798 1.23705 6.90074 1 3C1 2.46957 1.21071 1.96086 1.58579 1.58579C1.96086 1.21071 2.46957 1 3 1Z" stroke="#2F2F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>';

$whatsapp_url = get_field_value($whatsapp, 'url');

$whatsapp_svg = ' <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 24C18.6274 24 24 18.6274 24 12C24 5.37258 18.6274 0 12 0C5.37258 0 0 5.37258 0 12C0 18.6274 5.37258 24 12 24Z" fill="#25D366"/>
                    <path d="M12.3945 4.39999C14.3034 4.40082 16.0952 5.14399 17.4424 6.49277C18.7895 7.84143 19.531 9.63411 19.5303 11.5406C19.5287 15.474 16.3287 18.6748 12.3945 18.6764H12.3916C11.1974 18.6759 10.0236 18.3767 8.98145 17.8082L5.19922 18.8004L6.21191 15.1021C5.58749 14.02 5.25825 12.7924 5.25879 11.5348C5.26055 7.6007 8.46166 4.40008 12.3945 4.39999ZM12.3975 5.60507C9.12605 5.60507 6.46516 8.26575 6.46387 11.5357C6.46345 12.6563 6.77651 13.7478 7.37012 14.692L7.51172 14.9166L6.91211 17.1051L9.15723 16.5162L9.37402 16.6441C10.2846 17.1845 11.3284 17.4709 12.3926 17.4713H12.3945C15.6633 17.4713 18.3237 14.8107 18.3252 11.5406C18.3258 9.95588 17.7096 8.46538 16.5898 7.34433C15.4702 6.22349 13.9814 5.60572 12.3975 5.60507ZM10.21 8.24667C10.3194 8.25217 10.4666 8.20467 10.6113 8.55234C10.7599 8.90939 11.1166 9.78731 11.1611 9.87656C11.2056 9.96565 11.2349 10.0693 11.1758 10.1881C11.1164 10.3069 11.0871 10.382 10.998 10.4859C10.9089 10.59 10.8107 10.7185 10.7305 10.7984C10.6412 10.8873 10.5485 10.9833 10.6523 11.1617C10.7563 11.3402 11.1137 11.9243 11.6436 12.3971C12.3246 13.0045 12.8998 13.1926 13.0781 13.2818C13.2561 13.3709 13.36 13.3566 13.4639 13.2379C13.5678 13.1189 13.9094 12.7172 14.0283 12.5387C14.1472 12.3602 14.2663 12.3894 14.4297 12.4488C14.593 12.5083 15.468 12.9391 15.6475 13.0289C15.8258 13.1182 15.9447 13.1635 15.9893 13.2379C16.0338 13.3124 16.0342 13.6692 15.8857 14.0855C15.7371 14.5018 15.0253 14.8818 14.6826 14.9332C14.3756 14.9791 13.9865 14.9985 13.5596 14.8629C13.3006 14.7807 12.9683 14.6706 12.543 14.4869C10.7544 13.7146 9.58621 11.9136 9.49707 11.7945C9.4076 11.6751 8.76875 10.8279 8.76855 9.95078C8.76855 9.074 9.22878 8.64225 9.39258 8.46347C9.55588 8.28511 9.74929 8.23991 9.86816 8.23984C9.98695 8.23984 10.1061 8.24146 10.21 8.24667Z" fill="#FDFDFD"/>
                    </svg>';

$contact_title = get_field_value($contact_us, 'title');
$contact_url = get_field_value($contact_us, 'url');
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

                if (!empty($logo)) {
                    echo '<a href="' . get_site_url() . '" class="header__logo mob">
                                <img src="' . $logo . '" alt="logo">
                            </a>';
                }

                wp_nav_menu([
                    'menu' => 'Primary Menu',
                    'echo' => true,
                    'container' => false,
                    'menu_class' => 'header__menu',
                ]);



                echo '<div class="header__contact-us-container mob">';
                if ($phone_title && $phone_url) {
                    echo ' <a href="' . $phone_url . '" class="header__phone mob">
                               ' . $phone_title . '
                            </a>';
                }

                echo '<div class="header__contact-us-wrapper">';


                if ($phone_title && $phone_url) {
                    echo ' <a href="' . $phone_url . '" class="header__phone">
                            <div class="square-wrapper">
                                ' . $phone_svg . '
                            </div>
                        </a>';
                }

                if (!empty($whatsapp_url)) {
                    echo '<a href="' . $whatsapp_url . '" class="header__whatsapp">
                            <div class="square-wrapper">
                                ' . $whatsapp_svg . '
                            </div>
                        </a>';
                }

                if ($contact_title && $contact_url) {
                    echo '<a href="' . $contact_url . '" class="header__contact_us primary-btn mob js-open-popup-activator">
                            ' . $contact_title . '
                        </a>';
                }

                echo '</div>';
                ?>
            </nav>

            <div class="header__contact-us-wrapper">
                <?php


                if ($phone_title && $phone_url) {
                    echo ' <a href="' . $phone_url . '" class="header__phone">
                            <span>' . $phone_title . '</span>

                            <div class="square-wrapper">
                                ' . $phone_svg . '
                            </div>
                        </a>';
                }

                if (!empty($whatsapp_url)) {
                    echo '<a href="' . $whatsapp_url . '" class="header__whatsapp">
                            <div class="square-wrapper">
                                ' . $whatsapp_svg . '
                            </div>
                        </a>';
                }

                if ($contact_title && $contact_url) {
                    echo '<a href="' . $contact_url . '" class="header__contact_us primary-btn js-open-popup-activator">
                            ' . $contact_title . '
                        </a>';
                }
                ?>
                <button class="header__hamburger js-toggle-menu-btn square-wrapper">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6H20M4 12H20M4 18H20" stroke="#2F2F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>
