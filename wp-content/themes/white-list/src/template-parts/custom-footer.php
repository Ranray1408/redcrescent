<?php

/**
 * Custom footer template
 *
 * @package WP-rock
 */
global $global_options;

$footer_logo = get_field_value($global_options, 'footer_logo');
$footer_note_block = get_field_value($global_options, 'footer_note_block');
$presintation_btn = get_field_value($global_options, 'presintation_btn');
$footer_developers_note = get_field_value($global_options, 'footer_developers_note');

$footer_middle_text_block = get_field_value($global_options, 'footer_middle_text_block');

$footer_menu_title = get_field_value($global_options, 'footer_menu_title');

$footer_contacts_title = get_field_value($global_options, 'footer_contacts_title');
$footer_location = get_field_value($global_options, 'footer_location');
$email = get_field_value($global_options, 'email');
$work_schedule = get_field_value($global_options, 'work_schedule');
$phone = get_field_value($global_options, 'phone');
$socials_list = get_field_value($global_options, 'socials_list');

$ps_text = get_field_value($global_options, 'ps_text');


$popup_form_shortcode = get_field_value($global_options, 'popup_form_shortcode');
$contact_us_shortcode = get_field_value($global_options, 'contact_us_shortcode');
?>

<footer class="footer">
    <div class="container">
        <div class="small-container footer__inner-container">
            <div class="footer__logo-block">
                <?php
                if (!empty($footer_logo)) {
                    echo '<a href="' . get_site_url() . '" class="footer__logo">
                            <img class="style-svg" src="' . $footer_logo . '" alt="logo">
                        </a>';
                }

                if (!empty($footer_note_block)) {
                    echo '<div class="footer__note-block">
                            ' . do_shortcode($footer_note_block) . '
                        </div>';
                }

                $presintation_btn_title = get_field_value($presintation_btn, 'title');
                $presintation_btn_url = get_field_value($presintation_btn, 'url');

                if (!empty($presintation_btn_title) && !empty($presintation_btn_url)) {
                    echo '<a href="' . $presintation_btn_url . '" class="footer__presintation-btn grey-btn">
                            ' . $presintation_btn_title . '
                        </a>';
                }

                if (!empty($footer_developers_note)) {
                    echo '<div class="footer__developers-note">
                            ' . do_shortcode($footer_developers_note) . '
                        </div>';
                }
                ?>
            </div>
            <div class="footer__middle-text-block">
                <?php
                if (!empty($footer_middle_text_block)) {
                    echo do_shortcode($footer_middle_text_block);
                }
                ?>
            </div>

            <div class="footer__menu-wrapper">
                <?php
                if (!empty($footer_menu_title)) {
                    echo '<p class="footer__block-title">
                            ' . $footer_menu_title . '
                        </p>';
                }

                wp_nav_menu([
                    'menu'       => 'Footer menu',
                    'echo'       => true,
                    'container'  => false,
                    'menu_class' => 'footer__menu',
                ]);

                if (!empty($footer_developers_note)) {
                    echo '<div class="footer__developers-note mob">
                            ' . do_shortcode($footer_developers_note) . '
                        </div>';
                }
                ?>
            </div>

            <div class="footer__contacts-us-block">
                <?php
                if (!empty($footer_contacts_title)) {
                    echo '<p class="footer__block-title">
                            ' . $footer_contacts_title . '
                        </p>';
                }

                if (!empty($footer_location)) {
                    echo '<p class="footer__footer-location">
                            ' . $footer_location . '
                        </p>';
                }

                if (!empty($email)) {
                    echo '<p class="footer__email">
                            ' . $email . '
                        </p>';
                }

                if (!empty($work_schedule)) {
                    echo '<p class="footer__work-schedule">
                            ' . $work_schedule . '
                        </p>';
                }

                $phone_title = get_field_value($phone, 'title');
                $phone_url = get_field_value($phone, 'url');

                if (!empty($phone_title) && !empty($phone_url)) {
                    echo '<a href="' . $phone_url . '" class="footer__phone">
                            ' . $phone_title . '
                        </a>';
                }

                if (!empty($socials_list)) {
                    echo '<div class="footer__socials-wrapper">';

                    foreach ($socials_list as $item) {
                        if (empty($item['icon']) && empty($item['link']['url'])) continue;

                        echo '<a href="' . $item['link']['url'] . '" target="_blank" class="footer__social-item bg-hover-effect">
                                <img src="' . $item['icon'] . '" alt="icon">
                            </a>';
                    }

                    echo '</div>';
                }
                ?>
            </div>
        </div>
        <div class="small-container">
            <?php
            if (!empty($ps_text)) {
                echo '<div class="footer__ps-text">' . $ps_text . '</div>';
            }
            ?>
        </div>
    </div>
</footer>

<?php

echo do_shortcode('[popup_box box_id="popup-form-shorcode"]' . do_shortcode($popup_form_shortcode) . '[/popup_box]');
echo do_shortcode('[popup_box box_id="popup-contact-us-form-shorcode"]' . do_shortcode($contact_us_shortcode) . '[/popup_box]');
?>
