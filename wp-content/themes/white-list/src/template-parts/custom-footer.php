<?php

/**
 * Custom footer template
 *
 * @package WP-rock
 */
global $global_options;

$white_logo = get_field_value($global_options, 'white_logo');
$footer_text_upper_content = get_field_value($global_options, 'footer_text_upper_content');
$footer_text_content = get_field_value($global_options, 'footer_text_content');

$contacts_block = get_field_value($global_options, 'contacts_block');
$ps_text = get_field_value($global_options, 'ps_text');

$footer_link1 = get_field_value($global_options, 'footer_link1');
$footer_link2 = get_field_value($global_options, 'footer_link2');

$contact_us_shortcode = get_field_value($global_options, 'contact_us_shortcode');

$arrow_svg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_86610_1022)">
                    <path d="M5 12H19" stroke="#F2F2F2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13 18L19 12" stroke="#F2F2F2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13 6L19 12" stroke="#F2F2F2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_86610_1022">
                    <rect width="24" height="24" fill="white"/>
                    </clipPath>
                    </defs>
                </svg>';
?>

<footer class="footer">
    <div class="container footer__container">
        <div class="footer__logo-block">
            <?php
            if (!empty($white_logo)) {
                echo '<a href="' . get_site_url() . '" class="footer__logo">
                            <img class="style-svg" src="' . $white_logo . '" alt="logo">
                        </a>';
            }

            if (!empty($footer_text_content)) {
                echo '<div class="footer__text-content">
                            <p class="footer__upper-text">' . $footer_text_upper_content . '</p>
                            ' . do_shortcode($footer_text_content) . '
                        </div>';
            }

            ?>
        </div>
        <div class="footer__contacts-block">
            <?php
            echo !empty($footer_text_upper_content) ? '<p class="footer__upper-text mob">' . $footer_text_upper_content . '</p>' : '';

            if (!empty($contacts_block['title'])) {
                echo '<div class="footer__contacts-block-title">' . $contacts_block['title'] . '</div>';
            }

            if (!empty($contacts_block['email'])) {
                echo '<div class="footer__contacts-block-email">' . $contacts_block['email'] . '</div>';
            }

            if (!empty($contacts_block['phone'])) {

                $phone_title = get_field_value($contacts_block['phone'], 'title');
                $phone_url = get_field_value($contacts_block['phone'], 'url');

                echo '<a href="' . $phone_url . '" class="footer__contacts-block-phone">
                        ' . $phone_title . '
                        </a>';
            }

            if (!empty($contacts_block['socials'])) {
                echo '<div class="footer__socials">';

                foreach ($contacts_block['socials'] as $item) {
                    echo '<a href="' . $item['link'] . '" target="_black" class="footer__socials-item scale-hover-effect">
                                <img src="' . $item['icon'] . '" alt="icon">
                            </a>';
                }

                echo '</div>';
            }

            if (!empty($ps_text)) {
                echo '<p class="footer__ps-text">' . $ps_text . '</p>';
            }
            ?>
        </div>

        <div class="footer__links-block">
            <?php

            $footer_link1_title = get_field_value($footer_link1, 'title');
            $footer_link1_url = get_field_value($footer_link1, 'url');

            if (!empty($footer_link1_title) && !empty($footer_link1_title)) {
                echo '<a href="' . $footer_link1_url . '" class="footer__footer-link icon-slide-hover-btn dark-grey">
                            <span class="btn-inner"></span>
                            <span class="btn-text">' . $footer_link1_title . '</span>
                            ' . $arrow_svg . '
                        </a>';
            }

            $footer_link2_title = get_field_value($footer_link2, 'title');
            $footer_link2_url = get_field_value($footer_link2, 'url');

            if (!empty($footer_link2_title) && !empty($footer_link2_title)) {
                echo '<a href="' . $footer_link2_url . '"
                            class="footer__footer-link primary-btn ' . get_popup_class($footer_link2_url) . '">

                            ' . $footer_link2_title . '
                        </a>';
            }

            ?>
        </div>
        <?php
        if (!empty($ps_text)) {
            echo '<p class="footer__ps-text mob">' . $ps_text . '</p>';
        }
        ?>
    </div>

    </div>
</footer>

<button class="arrow-up js-arrow-up">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g clip-path="url(#clip0_86610_1042)">
            <path d="M6 15L12 9L18 15" stroke="#F2F2F2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </g>
        <defs>
            <clipPath id="clip0_86610_1042">
                <rect width="24" height="24" fill="white" />
            </clipPath>
        </defs>
    </svg>
</button>

<?php
ob_start();
get_template_part('src/template-parts/modal-window');
$html = ob_get_clean();

echo do_shortcode('[popup_box box_id="popup-contacts-modal"]' . $html . '[/popup_box]');

ob_start();
get_template_part('src/template-parts/modal-window-privacy');
$html2 = ob_get_clean();

echo do_shortcode('[popup_box box_id="popup-privacy-modal"]' . $html2 . '[/popup_box]');
?>
