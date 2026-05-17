<?php

$fields = get_fields();

$title = get_field_value($fields, 'title');
$content = get_field_value($fields, 'content');

?>
<section class="text-section" id="block-text-section">
    <div class="container">
        <?php if (!empty($title)) : ?>
            <h2 class="text-section__title"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if (!empty($content)) : ?>
            <div class="text-section__content">
                <?php echo do_shortcode($content); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
