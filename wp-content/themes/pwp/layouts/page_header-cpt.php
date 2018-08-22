<?php
$cpt = pwp_get_cpt();
$np_options = get_option( 'nestedpages_posttypes' );

$page_id = $np_options[ $cpt ]['post_type_page_assignment_page_id'];

$page_title = get_field( 'page_title', $page_id );
$page_intro = get_field( 'page_intro', $page_id );
$page_type = get_field( 'page_type', $page_id );
$page_image = get_field( 'page_image', $page_id );

if ( empty( $page_title ) ) {
  $page_title = get_the_title( $page_id );
}

pwp_template_part( 'layouts/partial-page_header', array(
  'page_title' => $page_title,
  'page_intro' => $page_intro,
  'page_type' => $page_type,
  'page_image' => $page_image,
) );
