<?php

if ( is_home() ) {
  $page_id = get_option( 'page_for_posts' );
} else {
  $page_id = get_the_ID();
}

$page_title = get_field( 'page_title', $page_id );
$page_subtitle = get_field( 'page_subtitle', $page_id );
$page_intro = get_field( 'page_intro', $page_id );
$page_type = get_field( 'page_type', $page_id );
$page_image = get_field( 'page_image', $page_id );
$page_video = get_field( 'page_video', $page_id );
$page_button = get_field( 'page_button', $page_id );

if ( empty( $page_title ) && ! is_home() ) {
  $page_title = get_the_title( $page_id );
}

pwp_template_part( 'layouts/partial-page_header', array(
  'page_title' => $page_title,
  'page_subtitle' => $page_subtitle,
  'page_intro' => $page_intro,
  'page_type' => $page_type,
  'page_image' => $page_image,
  'page_video' => $page_video,
  'page_button' => $page_button,
) );
