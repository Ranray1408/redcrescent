<?php

global $global_options;

$team_popup_content = get_field_value($global_options, 'team_popup_content');
?>

<div class="team-popup">
    <?php
    if (!empty($team_popup_content)) {
        echo '<div class="team-popup__content content">' . do_shortcode($team_popup_content) . '</div>';
    }
    ?>
</div>
