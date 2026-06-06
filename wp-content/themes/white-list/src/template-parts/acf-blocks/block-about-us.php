<?php
$fields = get_fields();

$title = get_field_value($fields, 'title');
$description = get_field_value($fields, 'description');
$block_repeater = get_field_value($fields, 'block_repeater');

$details_btn_text = get_field_value($fields, 'details_btn_text');
$btn_texts = !empty($details_btn_text) ? explode('\\', $details_btn_text) : ['Еще...', 'Закрыть'];
$btn_collapsed_text = $btn_texts[0] ?? 'Еще...';
$btn_expanded_text = $btn_texts[1] ?? 'Закрыть';

?>
<section class="about-us" id="block-about-us">
	<div class="container">
		<?php
		if (!empty($title)) {
			echo '<h2 class="about-us__title">' . $title . '</h2>';
		}
		if (!empty($description)) {
			echo '<div class="about-us__description">
					' . do_shortcode($description) . '
				</div>';
		}
		?>

		<div class="about-us__block-container">
			<?php
			if (!empty($block_repeater)) {

				foreach ($block_repeater as $key => $block) {

					$revers_class = $key % 2 ? 'reverse-block' : '';

					echo '<div class="about-us__block ' . $revers_class . '">';

					if (!empty($block['image'])) {
						echo '<figure class="about-us__block-image">'
							. wp_get_attachment_image($block['image'], [784, 558], false, ['alt' => 'block image', 'decoding' => 'async'])
							. '</figure>';
					}

					echo '<div class="about-us__block-inner">';

					if (!empty($block['title'])) {
						echo '<h3 class="about-us__block-title">' . $block['title'] . '</h3>';
					}

					if (!empty($block['text'])) {
						echo '<div class="about-us__block-text-wrapper js-about-text-wrapper">
								<p class="about-us__block-text">' . $block['text'] . '</p>
								<button class="about-us__block-text-btn js-about-text-btn" type="button" data-text-collapsed="' . esc_attr($btn_collapsed_text) . '" data-text-expanded="' . esc_attr($btn_expanded_text) . '">' . $btn_collapsed_text . '</button>
							</div>';
					}

					echo '</div>';

					echo '</div>';
				}
			}
			?>
		</div>
	</div>
</section>
