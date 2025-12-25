<?php



$fields = get_fields();

$background_image = get_field_value($fields, 'background_image');
$content = get_field_value($fields, 'content');
$button1 = get_field_value($fields, 'button1');

$button1_title = get_field_value($button1, 'title');
$button1_url = get_field_value($button1, 'url');

$button2 = get_field_value($fields, 'button2');

$button2_title = get_field_value($button2, 'title');
$button2_url = get_field_value($button2, 'url');

$phone_svg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M5 4H9L11 9L8.5 10.5C9.57096 12.6715 11.3285 14.429 13.5 15.5L15 13L20 15V19C20 19.5304 19.7893 20.0391 19.4142 20.4142C19.0391 20.7893 18.5304 21 18 21C14.0993 20.763 10.4202 19.1065 7.65683 16.3432C4.8935 13.5798 3.23705 9.90074 3 6C3 5.46957 3.21071 4.96086 3.58579 4.58579C3.96086 4.21071 4.46957 4 5 4Z" stroke="#F2F2F2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>';
?>
<section class="section-partnership" id="section-partnership">
	<div class="container">
		<div class="section-partnership__block" style="background-image: url(<?php echo $background_image; ?>);">
			<?php
			if (!empty($content)) {
				echo '<div class="section-partnership__content">' . $content . '</div>';
			}

			echo '<div class="section-partnership__button-wrapper">';

			if (!empty($button1_title) && !empty($button1_url)) {
				echo '<a href="' . $button1_url . '" class="section-partnership__button icon-slide-hover-btn">
							<span class="btn-inner"></span>
							<span class="btn-text">' . $button1_title . '</span>
							' . $phone_svg . '
						</a>';
			}

			if (!empty($button2_title) && !empty($button2_url)) {
				echo '<a href="' . $button2_url . '" class="section-partnership__button transparent-btn">
							' . $button2_title . '
						</a>';
			}

			echo '</div>';
			?>
		</div>
	</div>
</section>
