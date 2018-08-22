<?php
get_header();

if ( have_posts() ) :
  while ( have_posts() ) :
    the_post();
?>
<?php get_template_part( 'layouts/page_header' ); ?>
<?php
  $video = get_field( 'video' );
  $image = get_field( 'image' );
  $content = get_field( 'content' );

  pwp_template_part( 'layouts/partial-media_element', array(
    'video' => $video,
    'image' => $image,
    'content' => $content,
  ) );
?>
<?php get_template_part( 'layouts/blocks', 'full_width' ); ?>
<?php
  endwhile;
endif;

get_footer();
