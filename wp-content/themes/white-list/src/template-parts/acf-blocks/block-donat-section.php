<?php
$fields = get_fields();
$title = get_field_value($fields, 'title');
$description = get_field_value($fields, 'description');
$icons = get_field_value($fields, 'icons');
$block_bg = get_field_value($fields, 'block_bg');

$form_title = get_field_value($fields, 'form_title');

$form_fields = $fields['form_fields'] ?? null;

$period_monthly = get_field_value($form_fields, 'period_monthly');
$period_one_time = get_field_value($form_fields, 'period_one_time');

$sum_text_title = get_field_value($form_fields, 'sum_text_title');

$price1 = get_field_value($form_fields, 'price1');
$price2 = get_field_value($form_fields, 'price2');
$price3 = get_field_value($form_fields, 'price3');

$custom_price_text = get_field_value($form_fields, 'custom_price_text');
$name_text = get_field_value($form_fields, 'name_text');
$second_name_text = get_field_value($form_fields, 'second_name_text');
$email_text = get_field_value($form_fields, 'email_text');
$phone_text = get_field_value($form_fields, 'phone_text');
$date_text = get_field_value($form_fields, 'date_text');

$checkbox_text = get_field_value($form_fields, 'checkbox_text');
$submit_text = get_field_value($form_fields, 'submit_text');

$form_text = [
	'form_title' => $form_title ?? '',
	'period_monthly' => $period_monthly ?? '',
	'period_one_time' => $period_one_time ?? '',
	'sum_text_title' => $sum_text_title ?? '',
	'price1' => $price1 ?? '',
	'price2' => $price2 ?? '',
	'price3' => $price3 ?? '',
	'custom_price_text' => $custom_price_text ?? '',
	'name_text' => $name_text ?? '',
	'second_name_text' => $second_name_text ?? '',
	'email_text' => $email_text ?? '',
	'phone_text' => $phone_text ?? '',
	'date_text' => $date_text ?? '',
	'checkbox_text' => $checkbox_text ?? '',
	'submit_text' => $submit_text ?? '',
]
?>
<section class="donat-section">
	<div class="container">
		<div class="donat-block" style="background-image: url(<?php echo $block_bg; ?>);">

			<div class="donat-block__left-wrapper white-text" style="background-image: url(<?php echo $block_bg; ?>);">
				<?php
				if (!empty($title)) {
					echo '<h1 class="donat-block__title">' . $title . '</h1>';
				}

				if (!empty($description)) {
					echo '<div class="donat-block__description">' . $description . '</div>';
				}

				if (!empty($icons)) {
					echo '<div class="donat-block__icons-wrapper">';
					foreach ($icons as $icon) {
						if (empty($icon['icon']) || empty($icon['sum']) || empty($icon['text'])) continue;

						echo '<div class="donat-block__icon-item">
								<div class="donat-block__icon-img">
									<img src="' . $icon['icon'] . '" alt="icon">
								</div>
								<div class="donat-block__icon-item-sum-inner">

									<p class="donat-block__icon-item-sum">' . $icon['sum'] . '</p>
									<p class="donat-block__icon-item-text">' . $icon['text'] . '</p>

								</div>
							</div>';
					}
					echo '</div>';
				}
				?>
			</div>

			<div class="donat-block__right-wrapper">

				<form class="donat-block__form js-donation-form" method="POST">
					<h3 class="donat-block__form-title"><?php echo $form_text['form_title']; ?></h3>

					<div class="donat-block__form-radio-period-wrapper">
						<label class="donat-block__form-radio-period-item border-hover-effect-pink">
							<input checked type="radio" name="pay-period" value="monthly">
							<span><?php echo $form_text['period_monthly']; ?></span>
						</label>
						<label class="donat-block__form-radio-period-item border-hover-effect-pink">
							<input type="radio" name="pay-period" value="one-time">
							<span><?php echo $form_text['period_one_time']; ?></span>
						</label>
					</div>

					<div class="donat-block__form-fields-wrapper">
						<p class="donat-block__form-fields-title"><?php echo $form_text['sum_text_title']; ?></p>
						<div class="donat-block__form-radio-sum-wrapper">
							<?php
							$donat_array = [
								$form_text['price1'] . ' ₸',
								$form_text['price2'] . ' ₸',
								$form_text['price3'] . ' ₸',
							];

							foreach ($donat_array as $key => $val) {
								$checked = ($key === 0) ? 'checked' : '';
								$clear_price = preg_replace('/[^0-9]/', '', $val);

								echo '<label class="donat-block__form-radio-sum-item">
										<input type="radio" name="pay-sum" ' . $checked . ' value="' . trim($clear_price) . '">
										<span class="border-hover-effect-dark">' . $val . '</span>
									</label>';
							}
							?>
							<label class="donat-block__form-radio-custom-sum-item">
								<input name="custom-pay-sum" type="number"
									placeholder="<?php echo $form_text['custom_price_text']; ?> ₸">
							</label>
						</div>

						<?php
						$form_fields = [
							[
								'validate' => 'js-validate-name',
								'placeholder' => $form_text['name_text'],
								'name' => 'first-name',
								'required' => 'required'
							],
							[
								'validate' => 'js-validate-name',
								'placeholder' => $form_text['second_name_text'],
								'name' => 'last-name',
								'required' => 'required'
							],
							[
								'validate' => 'js-validate-email',
								'placeholder' => $form_text['email_text'],
								'name' => 'email',
								'required' => 'required'
							],
							[
								'validate' => 'js-validate-phone',
								'placeholder' => $form_text['phone_text'],
								'name' => 'phone',
								'required' => 'required'
							],
						];
						?>
						<div class="donat-block__form-personal-info">
							<?php
							foreach ($form_fields as $key => $field) {
								echo '<label class="donat-block__input-wrapper" for="id-' . $field['name'] . '">

										<input class="' . $field['validate'] . '"
											id="id-' . $field['name'] . '"
											type="text"
											name="' . $field['name'] . '"
											placeholder="' . $field['placeholder'] . '" ' . $field['required'] . '>
										</label>';
							}
							?>
							<label class="donat-block__input-wrapper date" for="id-birth-date">
								<span class="js-date-placeholder date-placeholder"><?php echo $form_text['date_text']; ?></span>
								<input
									type="date"
									id="id-birth-date"
									name="birth-date"
									/>
							</label>
						</div>

						<label class="donat-block__checkbox checkbox">
							<input checked type="checkbox" name="offer">
							<span class="checkbox-box"></span>
							<?php echo $form_text['checkbox_text']; ?>
						</label>
					</div>
					<button id="payButton" type="submit" class="donat-block__submit-btn icon-slide-hover-btn">
						<span class="btn-inner"></span>
						<span class="btn-text"><?php echo $form_text['submit_text']; ?></span>
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<g clip-path="url(#clip0_86582_72)">
								<path d="M5 12H19" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
								<path d="M13 18L19 12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
								<path d="M13 6L19 12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
							</g>
							<defs>
								<clipPath id="clip0_86582_72">
									<rect width="24" height="24" fill="white" />
								</clipPath>
							</defs>
						</svg>
					</button>
				</form>
			</div>
		</div>
	</div>
</section>
