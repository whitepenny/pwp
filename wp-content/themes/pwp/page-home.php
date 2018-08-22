<?php
/*
Template Name: Home
*/

global $header_transparent;

$header_transparent = true;

get_header();

if ( have_posts() ) :
  while ( have_posts() ) :
    the_post();
?>
<?php get_template_part( 'layouts/page_header', 'home' ); ?>
<?php get_template_part( 'layouts/blocks', 'full_width' ); ?>
<?php
  endwhile;
endif;

get_footer();
