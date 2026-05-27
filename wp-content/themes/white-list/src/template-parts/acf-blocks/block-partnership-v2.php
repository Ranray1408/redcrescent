<?php

$fields = get_fields();

$background_image = get_field_value($fields, 'background_image');
$content = get_field_value($fields, 'content');

?>
<section class="section-direct-dialog" id="section-direct-dialog">
	<div class="container">
		<div class="section-direct-dialog__block" style="background-image: url(<?php echo $background_image; ?>);">
			<?php
			if (!empty($content)) {
				echo '<div class="section-direct-dialog__content-wrapper">
						<div class="section-direct-dialog__content">' . $content . '</div>
					  </div>';
			}
			?>
		</div>
	</div>
</section>
