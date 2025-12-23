 <?php
$languages = pll_languages_list([
	'fields' => 'slug'
]);

$current_lang = pll_current_language();
?>

 <div class="lang-select">
 	<input type="checkbox" id="lang-toggle" class="lang-toggle">

 	<label for="lang-toggle" class="lang-current">
 		<span><?php echo esc_html($current_lang); ?></span>
 		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
 			<g clip-path="url(#clip0_86546_1161)">
 				<path d="M6 9L12 15L18 9" stroke="#2F2F2F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
 			</g>
 			<defs>
 				<clipPath id="clip0_86546_1161">
 					<rect width="24" height="24" fill="white" />
 				</clipPath>
 			</defs>
 		</svg>
 	</label>

 	<ul class="lang-options">
 		<?php foreach ($languages as $lang): ?>
 			<li>
 				<a href="<?php echo esc_url(pll_home_url($lang)); ?>">
 					<?php echo esc_html($lang); ?>
 				</a>
 			</li>
 		<?php endforeach; ?>
 	</ul>
 </div>
