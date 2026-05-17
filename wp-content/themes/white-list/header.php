<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;700&display=swap&subset=cyrillic-ext" rel="stylesheet">
    <?php wp_head(); ?>
</head>

<body <?php body_class(get_field('hide_primary_menu') ? 'header--menu-hidden' : ''); ?>>
    <?php echo esc_html(get_template_part('src/template-parts/custom', 'header')); ?>
