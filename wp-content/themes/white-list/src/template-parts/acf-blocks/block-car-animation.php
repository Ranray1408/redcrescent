<?php

$fields = get_fields();

$icons = get_field_value($fields, 'icons');
$background_image = get_field_value($fields, 'background_image')
?>

<div class="car-animation js-car-animation" data-animation-state="enter" style="background-image: url(<?php echo $background_image; ?>)">

	<div class="car-animation__icon-container js-icon-container">
		<?php
		if (!empty($icons)) {
			foreach ($icons as $key => $item) {
				echo '<img class="car-animation__icon js-icon" src="' . $item['icon'] . '" alt="icon">';
			}
		}
		?>
	</div>

	<div class="car-animation__car-wrapper">
		<div class="car-animation__car js-car">
			<div class="car-animation__car-body js-car-body"></div>
			<?php echo get_template_part('src/template-parts/car'); ?>
		</div>
	</div>
</div>
