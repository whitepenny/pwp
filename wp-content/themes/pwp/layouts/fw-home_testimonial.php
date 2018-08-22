<?php

$quote = get_sub_field( 'quote' );
$content = get_sub_field( 'content' );
$author = get_sub_field( 'author' );
$image = get_sub_field( 'image' );

pwp_template_part( 'layouts/partial-testimonial', array(
  'quote' => $quote,
  'content' => $content,
  'author' => $author,
  'image' => $image,
) );
