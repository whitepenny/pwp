<?php
$name = get_the_title();
$position = get_field( 'bio_position' );
$image = get_field( 'bio_header_image' );

pwp_template_part( 'layouts/partial-page_header', array(
  'page_type' => 'team',
  'page_title' => $name,
  'page_subtitle' => $position,
  'page_image' => $image,
) );
