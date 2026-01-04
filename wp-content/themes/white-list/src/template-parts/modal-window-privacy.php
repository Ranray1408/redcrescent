<?php

global $global_options;

$popup_privacy = get_field_value($global_options, 'popup_privacy');
?>

<div class="contacts-popup-privacy">
	<?php
	if (!empty($popup_privacy)) {
		echo '<div class="privacy-popup__content content">' . $popup_privacy . '</div>';
	}
	?>
</div>
