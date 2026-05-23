<?php
global $global_options;

$share_title = get_field_value($global_options, 'donation_share_title');
$share_subtitle = get_field_value($global_options, 'donation_share_subtitle');
?>

<div class="donation-success-popup">
    <?php if (!empty($share_title)) : ?>
        <p class="donation-success-popup__title"><?php echo esc_html($share_title); ?></p>
    <?php endif; ?>
    <?php if (!empty($share_subtitle)) : ?>
        <p class="donation-success-popup__subtitle"><?php echo esc_html($share_subtitle); ?></p>
    <?php endif; ?>
    <div class="donation-success-popup__socials">
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="donation-success-popup__social-link" rel="noopener">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M18 2H15C13.6739 2 12.4021 2.52678 11.4645 3.46447C10.5268 4.40215 10 5.67392 10 7V10H7V14H10V22H14V14H17L18 10H14V7C14 6.73478 14.1054 6.48043 14.2929 6.29289C14.4804 6.10536 14.7348 6 15 6H18V2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Facebook
        </a>
        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode($share_title); ?>" target="_blank" class="donation-success-popup__social-link" rel="noopener">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M4 4L11.733 13.686M4 20L14.168 11.21M18 4L10.267 13.686M20 20L9.832 11.21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            X (Twitter)
        </a>
        <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($share_title . ' ' . get_permalink()); ?>" target="_blank" class="donation-success-popup__social-link" rel="noopener">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M21 11.5C21.0034 13.0699 20.6451 14.619 19.9547 16.0207C19.2643 17.4224 18.2618 18.6356 17.0264 19.557C15.7909 20.4784 14.3608 21.0803 12.8461 21.3076C11.3314 21.535 9.78089 21.3805 8.34 20.86L3 22L4.14 16.66C3.55844 15.403 3.31052 14.0217 3.41871 12.6476C3.52689 11.2735 3.98787 9.94882 4.75888 8.79884C5.52989 7.64887 6.58692 6.71333 7.83199 6.08072C9.07705 5.44812 10.4673 5.1399 11.87 5.18499H12.5C14.4913 5.28314 16.3676 6.15591 17.7759 7.61498C19.1841 9.07405 19.9907 11.0071 20 13.02V11.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            WhatsApp
        </a>
        <a href="https://t.me/share/url?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode($share_title); ?>" target="_blank" class="donation-success-popup__social-link" rel="noopener">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M21 5L2 12.5L9 15.5L19 8L13 17.5L18 21L21 5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Telegram
        </a>
    </div>
</div>
