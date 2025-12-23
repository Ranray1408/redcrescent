<?php

get_header();

global $global_options;

$page_404_content = get_field_value($global_options, 'page_404_content');
$back_buttons = get_field_value($global_options, 'back_buttons');
?>

<div class="page404">
	<div class="container page404__container">
		<?php
		if (!empty($page_404_content)) {
			echo '<div class="single-content">' . $page_404_content . '</div>';
		}

		if (!empty($back_buttons)) {

			echo '<div class="back-button-wrapper">';

			foreach ($back_buttons as $button) {

				$button_url = get_field_value($button['link'], 'url');
				$button_title = get_field_value($button['link'], 'title');

				if (!empty($button_url) && !empty($button_url)) {
					echo '<a href="' . $button_url . '" class="back-button-wrapper__button white-color-hover-effect">
							' . $button_title . '
							</a>';
				}
			}

			echo '</div>';
		}
		?>
	</div>
</div>


<?php get_footer();
