<?php

global $global_options;

$popup_title = get_field_value($global_options, 'popup_title');
$popup_note = get_field_value($global_options, 'popup_note');
$email = get_field_value($global_options, 'email');

$popup_phone = get_field_value($global_options, 'popup_phone');

$popup_phone_title = get_field_value($popup_phone, 'title');
$popup_phone_url = get_field_value($popup_phone, 'url');


$popup_socials = get_field_value($global_options, 'popup_socials');

$mail_svg = '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
				<g clip-path="url(#clip0_86413_1468)">
				<path d="M4 9.33335C4 8.62611 4.28095 7.94783 4.78105 7.44774C5.28115 6.94764 5.95942 6.66669 6.66667 6.66669H25.3333C26.0406 6.66669 26.7189 6.94764 27.219 7.44774C27.719 7.94783 28 8.62611 28 9.33335V22.6667C28 23.3739 27.719 24.0522 27.219 24.5523C26.7189 25.0524 26.0406 25.3334 25.3333 25.3334H6.66667C5.95942 25.3334 5.28115 25.0524 4.78105 24.5523C4.28095 24.0522 4 23.3739 4 22.6667V9.33335Z" stroke="#E3000F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				<path d="M4 9.33331L16 17.3333L28 9.33331" stroke="#E3000F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</g>
				<defs>
				<clipPath id="clip0_86413_1468">
				<rect width="32" height="32" fill="white"/>
				</clipPath>
				</defs>
			</svg>';


$phone_svg = '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M6.66667 5.33331H12L14.6667 12L11.3333 14C12.7613 16.8954 15.1046 19.2387 18 20.6666L20 17.3333L26.6667 20V25.3333C26.6667 26.0406 26.3857 26.7188 25.8856 27.2189C25.3855 27.719 24.7072 28 24 28C18.799 27.6839 13.8935 25.4753 10.2091 21.7909C6.52467 18.1064 4.31607 13.201 4 7.99998C4 7.29274 4.28095 6.61446 4.78105 6.11436C5.28115 5.61426 5.95942 5.33331 6.66667 5.33331Z" stroke="#E3000F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>';
?>

<div class="contacts-popup">
	<?php
	if (!empty($popup_title)) {
		echo '<h3 class="contacts-popup__title">' . $popup_title . '</h3>';
	}

	if (!empty($popup_note)) {
		echo '<p class="contacts-popup__note">' . $popup_note . '</p>';
	}

	if (!empty($email)) {
		echo '<a href="mailto:' . $email . '" class="contacts-popup__email">
					' . $mail_svg . '
					' . $email . '
				</a>';
	}

	if (!empty($popup_phone_title) && !empty($popup_phone_url)) {

		echo '<a href="' . $popup_phone_url . '" class="contacts-popup__phone">
					' . $phone_svg . '
					' . $popup_phone_title . '
				</a>';
	}

	if (!empty($popup_socials)) {

		echo '<div class="contacts-popup__social-list">';

		foreach ($popup_socials as $item) {

			if (empty($item['link']) || empty($item['icon'])) continue;

			echo '<a href="' . $item['link'] . '" class="contacts-popup__social-item scale-hover-effect">
						<img src="' . $item['icon'] . '" alt="icon">
					</a>';
		}

		echo '</div>';
	}
	?>
</div>
