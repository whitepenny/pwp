<?php

$video = get_sub_field( 'video' );
$image = get_sub_field( 'image' );
$content = get_sub_field( 'content' );

pwp_template_part( 'layouts/partial-media_element', array(
  'video' => $video,
  'image' => $image,
  'content' => $content,
) );
