<?php
$fields = get_fields();

$title = get_field_value($fields, 'title');
$description = get_field_value($fields, 'description');

$report_button1 = get_field_value($fields, 'report_button1');
$report_button2 = get_field_value($fields, 'report_button2');

$report_list = get_field_value($fields, 'report_list');
$result_blocks = get_field_value($fields, 'result_blocks');

$paper_clip = '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M19.9996 9.33312L11.3329 17.9998C10.8025 18.5302 10.5045 19.2496 10.5045 19.9998C10.5045 20.7499 10.8025 21.4694 11.3329 21.9998C11.8634 22.5302 12.5828 22.8282 13.3329 22.8282C14.0831 22.8282 14.8025 22.5302 15.3329 21.9998L23.9996 13.3331C25.0605 12.2723 25.6565 10.8334 25.6565 9.33312C25.6565 7.83283 25.0605 6.39399 23.9996 5.33312C22.9387 4.27226 21.4999 3.67627 19.9996 3.67627C18.4993 3.67627 17.0605 4.27226 15.9996 5.33312L7.33294 13.9998C5.74164 15.5911 4.84766 17.7494 4.84766 19.9998C4.84766 22.2502 5.74164 24.4085 7.33294 25.9998C8.92424 27.5911 11.0825 28.4851 13.3329 28.4851C15.5834 28.4851 17.7416 27.5911 19.3329 25.9998L27.9996 17.3331" stroke="#727272" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>';

$download_icons = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M4 17V19C4 19.5304 4.21071 20.0391 4.58579 20.4142C4.96086 20.7893 5.46957 21 6 21H18C18.5304 21 19.0391 20.7893 19.4142 20.4142C19.7893 20.0391 20 19.5304 20 19V17M7 11L12 16M12 16L17 11M12 16V4" stroke="#F2F2F2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>';

$arrow_icons = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<g clip-path="url(#clip0_86610_694)">
						<path d="M5 12H19" stroke="#2F2F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M13 18L19 12" stroke="#2F2F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M13 6L19 12" stroke="#2F2F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</g>
					<defs>
					<clipPath id="clip0_86610_694">
					<rect width="24" height="24" fill="white"/>
					</clipPath>
					</defs>
				</svg>';

?>
<section class="block-results" id="block-results">
	<div class="container">
		<div class="block-results__inner">

			<div class="block-results__title-wrapper">
				<?php
				if (!empty($title)) {
					echo '<h2 class="block-results__title">' . $title . '</h2>';
				}

				if (!empty($description)) {
					echo '<div class="block-results__description">' . $description . '</div>';
				}
				?>
			</div>

			<div class="block-results__report-wrapper">
				<?php
				$report_button1_title = get_field_value($report_button1, 'title');
				$report_button1_url = get_field_value($report_button1, 'url');

				if (!empty($report_button1_title) && !empty($report_button1_url)) {
					echo '<a href="' . $report_button1_url . '" class="block-results__report-button icon-slide-hover-btn">
								<span class="btn-inner"></span>
								<span class="btn-text">' . $report_button1_title . '</span>
								' . $download_icons . '
							</a>';
				}

				$report_button2_title = get_field_value($report_button2, 'title');
				$report_button2_url = get_field_value($report_button2, 'url');

				if (!empty($report_button2_title) && !empty($report_button2_url)) {
					echo '<a href="' . $report_button2_url . '" class="block-results__report-button grey-bg icon-slide-hover-btn">
								<span class="btn-inner"></span>
								<span class="btn-text">' . $report_button2_title . '</span>
								' . $arrow_icons . '
							</a>';
				}

				?>
				<div class="block-results__repot-list">
					<?php
					if (!empty($report_list)) {
						foreach ($report_list as $item) {
							echo '<div class="block-results__report-item">
									' . $paper_clip . '
									<p>' . ($item['text'] ?? '') . '</p>
								</div>';
						}
					}
					?>
				</div>
			</div>
		</div>

		<div class="block-results__achievements-wrapper">
			<?php
			if (!empty($result_blocks)) {
				foreach ($result_blocks as $item) {
					if (empty($item['number']) && empty($item['text'])) continue;

					echo '<div class="block-results__achievements-item">
								<p class="block-results__achievements-number">' . $item['number'] . '</p>
								<p class="block-results__achievements-text">' . $item['text'] . '</p>
							</div>';
				}
			}
			?>
		</div>
	</div>
	<div class="animation-pulse"></div>
</section>
