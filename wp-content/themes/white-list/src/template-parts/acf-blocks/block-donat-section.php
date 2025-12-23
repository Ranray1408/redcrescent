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

			<div class="donat-block__left-wrapper white-text">
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
								<p class="donat-block__icon-item-sum">' . $icon['sum'] . '</p>
								<p class="donat-block__icon-item-text">' . $icon['text'] . '</p>
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
						<label class="donat-block__form-radio-period-item">
							<input checked type="radio" name="pay-period" value="monthly">
							<span>Ежемесячно</span>
						</label>
						<label class="donat-block__form-radio-period-item">
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
								$clear_price = preg_replace('/[^0-9]/', '' ,$val);

								echo '<label class="donat-block__form-radio-sum-item">
										<input type="radio" name="pay-sum" ' . $checked . ' value="' . trim($clear_price) . '">
										<span>' . $val . '</span>
									</label>';
							}
							?>
							<label class="donat-block__form-radio-custom-sum-item">
								<input name="custom-pay-sum" type="text" placeholder="Другая сумма ₸">
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
								'placeholder' => 'E-mail *',
								'name' => 'email',
								'required' => 'required'
							],
						];
						?>
						<div class="donat-block__form-personal-info">
							<?php
							foreach ($form_fields as $field) {
								echo '<label class="donat-block__input-wrapper" for="id-' . $field['name'] . '">
								<span>' . $field['placeholder'] . '

								<input class="' . $field['validate'] . '"
									id="id-' . $field['name'] . '"
									type="text"
									name="' . $field['name'] . '"
									placeholder="' . $field['placeholder'] . '">
									' . $field['required'] . '
								</label>';
							}
							?>
							<label class="donat-block__input-wrapper" for="id-birth-date">
								<span>Дата рождения</span>
								<input
									type="date"
									id="id-birth-date"
									name="birth-date"
									placeholder="Дата рождения" />
							</label>
						</div>

						<label class="donat-block__checkbox checkbox">
							<input checked type="checkbox" name="offer">
							<span></span>
							<p>Я согласен(а) с условиями <a class="js-open-popup-activator" href="#oferta-popup">Публичной Оферты</a></p>
						</label>
					</div>
					<button type="submit" class="donat-block__submit-btn primary-btn">
						<span>Помочь сейчас</span>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<path d="M12 19.7625L10.9125 18.7725C7.05 15.27 4.5 12.9525 4.5 10.125C4.5 7.8075 6.315 6 8.625 6C9.93 6 11.1825 6.6075 12 7.56C12.8175 6.6075 14.07 6 15.375 6C17.685 6 19.5 7.8075 19.5 10.125C19.5 12.9525 16.95 15.27 13.0875 18.7725L12 19.7625Z" stroke="white" stroke-width="2" stroke-linejoin="round" />
						</svg>
					</button>
				</form>
			</div>
		</div>
	</div>
	<button id="payButton">payButton</button>
</section>
