<?php

$fields = get_fields();

$title = get_field_value($fields, 'title');
$button = get_field_value($fields, 'button');

global $global_options;

$team_members = get_field_value($global_options, 'team_members');

$no_photo_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024"><path fill="#000000" d="M628.736 528.896A416 416 0 0 1 928 928H96a415.872 415.872 0 0 1 299.264-399.104L512 704l116.736-175.104zM720 304a208 208 0 1 1-416 0 208 208 0 0 1 416 0z"/></svg>';

?>
<section class="our-team" id="block-our-team">
    <div class="container">
        <?php if (!empty($title)) : ?>
            <h2 class="our-team__title"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if (!empty($team_members)) : ?>
            <div class="our-team__grid">
                <?php foreach ($team_members as $member) : ?>
                    <?php
                    $photo = get_field_value($member, 'photo');
                    $name = get_field_value($member, 'name');
                    $team_id = get_field_value($member, 'team_id');
                    ?>
                    <div class="our-team__card">
                        <div class="our-team__photo-wrapper">
							<?php if (!empty($photo)) :
								echo wp_get_attachment_image($photo, 'full', false, ['class' => 'our-team__photo', 'alt' => esc_attr($name), 'decoding' => 'async']);
							else : ?>
                                <span class="our-team__photo-placeholder">
                                    <?php echo $no_photo_svg; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($name)) : ?>
                            <p class="our-team__name"><?php echo esc_html($name); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($team_id)) : ?>
                            <p class="our-team__id"><?php echo esc_html($team_id); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php
        $button_url = get_field_value($button, 'url');
        $button_title = get_field_value($button, 'title');
        if (!empty($button_url) && !empty($button_title)) :
        ?>
            <div class="our-team__button-wrapper">
                <a href="<?php echo esc_url($button_url); ?>" class="our-team__button primary-btn <?php echo esc_attr(get_popup_class($button_url)); ?>">
                    <?php echo esc_html($button_title); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
