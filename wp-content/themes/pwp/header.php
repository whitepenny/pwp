<?php
global $header_transparent;

$main_title = get_bloginfo( 'name' );
$tagline = str_ireplace( $main_title, '<strong>'.$main_title.'</strong>', get_bloginfo( 'description' ) );

$scripts_head = get_field( 'scripts_head', 'option' );
$scripts_body = get_field( 'scripts_body', 'option' );

$portal_link = get_field( 'global_client_portal_link', 'option' );
$bill_link = get_field( 'global_bill_pay_link', 'option' );
?><!DOCTYPE html>
<html lang="en" class="no-js">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <?php wp_head(); ?>
    <?php pwp_favicons(); ?>
    <?php echo $scripts_head; ?>
  </head>
  <body <?php body_class( 'fs-grid fs-grid-fluid' ); ?> >
    <?php echo $scripts_body; ?>

    <div class="container">

      <header class="header <?php echo ( $header_transparent ) ? 'flipped' : ''; ?>">
        <a href="<?php echo get_home_url(); ?>" class="header_logo">
          <span class="icon logo"></span>
          <span class="screenreader"><?php echo $main_title; ?></span>
        </a>
        <button type="button" class="header_nav_handle js-swap" data-swap-target=".main_nav" data-swap-linked="main_nav">Menu</button>
      </header>

      <div class="main_nav">
        <?php pwp_main_navigation( 1 ); ?>
      </div>

      <div class="page_wrapper js-mobile_nav_content">
        <main class="main">
