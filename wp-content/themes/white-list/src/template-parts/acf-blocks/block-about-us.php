<?php



$fields = get_fields();

$title = get_field_value($fields, 'title');
$description = get_field_value($fields, 'description');
$block_repeater = get_field_value($fields, 'block_repeater');

?>
<section class="about-us">
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
						echo '<figure class="about-us__block-image">
									<img src="' . $block['image'] . '" alt="block image">
								</figure>';
					}

					echo '<div class="about-us__block-inner">';

					if (!empty($block['title'])) {
						echo '<h3 class="about-us__block-title">' . $block['title'] . '</h3>';
					}

					if (!empty($block['text'])) {
						echo '<p class="about-us__block-text">' . $block['text'] . '</p>';
					}

					echo '</div>';

					echo '</div>';
				}
			}
			?>
		</div>
	</div>
</section>
