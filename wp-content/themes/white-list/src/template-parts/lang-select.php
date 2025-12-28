<?php
$languages = pll_the_languages([
    'raw'           => 1,     // return array instead of echo
    'hide_current'  => 0,     // current language still included
]);

$current_lang = pll_current_language();
?>

<div class="lang-select">
    <input type="checkbox" id="lang-toggle" class="lang-toggle">

    <label for="lang-toggle" class="lang-current">
        <span><?php echo esc_html($current_lang); ?></span>
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 9L12 15L18 9" stroke="#2F2F2F" stroke-width="2"/>
        </svg>
    </label>

    <ul class="lang-options">
        <?php foreach ($languages as $lang): ?>
            <?php if ($lang['slug'] === $current_lang) continue; ?>

			<?php  if ($lang['slug'] === 'kk') continue; // hard exclude Temp decision ?>
            <li>
                <a href="<?php echo esc_url($lang['url']); ?>">
                    <?php echo esc_html($lang['slug']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
