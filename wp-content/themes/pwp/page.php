<?php
global $header_transparent;

$page_image = get_field( 'page_image', $page_id );
$header_transparent = ( ! empty( $page_image ) );

get_header();

if ( have_posts() ) :
  while ( have_posts() ) :
    the_post();
?>
<?php get_template_part( 'layouts/page_header' ); ?>
<?php get_template_part( 'layouts/blocks', 'full_width' ); ?>
<?php
  endwhile;
endif;

get_footer();
