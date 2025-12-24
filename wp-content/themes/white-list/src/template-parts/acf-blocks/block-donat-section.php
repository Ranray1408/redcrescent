<?php
$fields = get_fields();
$title = get_field_value($fields, 'title');
$description = get_field_value($fields, 'description');
$icons = get_field_value($fields, 'icons');
$block_bg = get_field_value($fields, 'block_bg');

$form_title = get_field_value($fields, 'form_title');

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
					<h3 class="donat-block__form-title">Сделать пожертвование</h3>

					<div class="donat-block__form-radio-period-wrapper">
						<label class="donat-block__form-radio-period-item border-hover-effect-pink">
							<input checked type="radio" name="pay-period" value="monthly">
							<span>Ежемесячно</span>
						</label>
						<label class="donat-block__form-radio-period-item border-hover-effect-pink">
							<input type="radio" name="pay-period" value="one-time">
							<span>Разово</span>
						</label>
					</div>

					<div class="donat-block__form-fields-wrapper">
						<p class="donat-block__form-fields-title">Сумма пожертвования:</p>
						<div class="donat-block__form-radio-sum-wrapper">
							<?php
							$donat_array = [
								'12 000 ₸',
								'7 000 ₸',
								'5 000 ₸',
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
								<input name="custom-pay-sum" type="number" placeholder="Другая сумма ₸">
							</label>
						</div>

						<?php
						$form_fields = [
							[
								'validate' => 'js-validate-name',
								'placeholder' => 'Имя *',
								'name' => 'first-name',
								'required' => 'required'
							],
							[
								'validate' => 'js-validate-name',
								'placeholder' => 'Фамилия *',
								'name' => 'last-name',
								'required' => 'required'
							],
							[
								'validate' => 'js-validate-email',
								'placeholder' => 'e-mail *',
								'name' => 'email',
								'required' => 'required'
							],
							[
								'validate' => 'js-validate-phone',
								'placeholder' => 'Телефон *',
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
							<label class="donat-block__input-wrapper" for="id-birth-date">
								<input
									type="date"
									id="id-birth-date"
									name="birth-date"
									placeholder="Дата рождения" />
							</label>
						</div>

						<label class="donat-block__checkbox checkbox">
							<input checked type="checkbox" name="offer">
							<span class="checkbox-box"></span>
							Я согласен(а) с условиями
							<a class="js-open-popup-activator" href="#oferta-popup">
								Публичной Оферты
							</a>
							и
							<a class="js-open-popup-activator" href="#oferta-popup">
								Соглашение о сборе и обработке персональных данных
							</a>
						</label>
					</div>
					<button id="payButton" type="submit" class="donat-block__submit-btn icon-slide-hover-btn">
						<span class="btn-inner"></span>
						<span class="btn-text">Помочь сейчас</span>
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
