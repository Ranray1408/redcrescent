<?php
/**
 * The template for displaying pages
 */
get_header();
?>


<?php


if ( have_posts() ) :
    // Start the loop.
    while ( have_posts() ) :
        the_post();
        add_filter( 'the_content', 'wpautop' );
        the_content();
    endwhile;
endif;


?>


<?php
get_footer();
