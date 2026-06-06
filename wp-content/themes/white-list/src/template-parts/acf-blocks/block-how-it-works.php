<?php

$fields = get_fields();

$subtitle = get_field_value($fields, 'subtitle');
$title = get_field_value($fields, 'title');
$description = get_field_value($fields, 'description');
$info_cards = get_field_value($fields, 'info_cards');
$block_image = get_field_value($fields, 'block_image');
?>

<section class="how-it-works" id="block-how-it-works">
    <div class="container">

        <div class="how-it-works__inner">
            <div class="how-it-works__content">
                <?php if (!empty($title)) : ?>
                    <h2 class="how-it-works__title">
                        <?php echo esc_html($title); ?>
                    </h2>
                <?php endif; ?>

                <?php if (!empty($description)) : ?>
                    <div class="how-it-works__description">
                        <?php echo do_shortcode($description); ?>
                    </div>
                <?php endif; ?>

                <div class="how-it-works__cards">
                    <?php if (!empty($info_cards)) : ?>
                        <?php foreach ($info_cards as $card) : ?>

                            <?php if (empty($card['card_title']) && empty($card['card_text'])) continue; ?>

                            <div class="how-it-works__card">
                                <div class="how-it-works__card-icon-circle">
                                    <?php if (!empty($card['icon'])) : ?>
                                        <img src="<?php echo esc_url($card['icon']); ?>" alt="<?php echo esc_attr($card['card_title']); ?>" class="how-it-works__card-icon-img">
                                    <?php endif; ?>
                                </div>


                                <div class="how-it-works__card-inner">
                                    <?php if (!empty($card['card_title'])) : ?>
                                        <h6 class="how-it-works__card-title">
                                            <?php echo esc_html($card['card_title']); ?>
                                        </h6>
                                    <?php endif; ?>

                                    <?php if (!empty($card['card_text'])) : ?>
                                        <div class="how-it-works__card-text">
                                            <?php echo do_shortcode($card['card_text']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

			<div class="how-it-works__col-image">
				<?php if (!empty($block_image)) :
					echo wp_get_attachment_image($block_image, 'full', false, ['class' => 'how-it-works__img', 'loading' => 'lazy', 'alt' => '', 'decoding' => 'async']);
				endif; ?>
			</div>
        </div>
    </div>
</section>
