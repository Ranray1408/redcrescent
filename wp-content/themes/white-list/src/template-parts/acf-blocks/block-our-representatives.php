<?php

$fields = get_fields();

$title = get_field_value($fields, 'title');
$cards = get_field_value($fields, 'cards');

?>
<section class="our-representatives" id="block-our-representatives">
	<div class="container">
		<?php if (!empty($title)) : ?>
			<h2 class="our-representatives__title"><?php echo esc_html($title); ?></h2>
		<?php endif; ?>

		<?php if (!empty($cards)) : ?>
			<div class="our-representatives__grid">
				<?php foreach ($cards as $card) : ?>
					<div class="our-representatives__card">
						<div class="our-representatives__card-icon-wrapper">
							<span class="our-representatives__card-icon-circle">
								<?php if (!empty($card['icon'])) : ?>
									<img src="<?php echo esc_url($card['icon']); ?>" alt="<?php echo esc_attr($card['card_title']); ?>" class="our-representatives__card-icon-img">
								<?php endif; ?>
							</span>
						</div>
						<?php if (!empty($card['card_title'])) : ?>
							<h3 class="our-representatives__card-title"><?php echo esc_html($card['card_title']); ?></h3>
						<?php endif; ?>
						<?php if (!empty($card['card_text'])) : ?>
							<div class="our-representatives__card-text">
								<?php echo do_shortcode($card['card_text']); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
