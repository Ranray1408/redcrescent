<?php

$fields = get_fields();

$title = get_field_value($fields, 'title');
$faq_items = get_field_value($fields, 'faq_items');

$plus_svg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 5V19" stroke="#2F2F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 12H19" stroke="#2F2F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$minus_svg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 12H19" stroke="#2F2F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$plus_minus_svg = $plus_svg . $minus_svg;

?>
<section class="faq" id="block-faq">
	<div class="container">
		<?php if (!empty($title)) : ?>
			<h2 class="faq__title"><?php echo esc_html($title); ?></h2>
		<?php endif; ?>

		<?php if (!empty($faq_items)) : ?>
			<div class="faq__list js-faq-list">
				<?php foreach ($faq_items as $index => $item) :
					$question = get_field_value($item, 'question');
					$answer = get_field_value($item, 'answer');
				?>
					<div class="faq__item js-faq-item">
						<button class="faq__question js-faq-question" type="button" aria-expanded="false">
							<span class="faq__question-text"><?php echo esc_html($question); ?></span>
							<span class="faq__icon" aria-hidden="true">
								<?php echo $plus_minus_svg; ?>
							</span>
						</button>
						<div class="faq__answer js-faq-answer">
							<div class="faq__answer-inner">
								<?php echo do_shortcode($answer); ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
