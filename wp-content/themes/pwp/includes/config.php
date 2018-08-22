<?php

// Env

$pwp_page_protocol = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
$pwp_page_url      = $pwp_page_protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$pwp_domain        = $pwp_page_protocol . $_SERVER['HTTP_HOST'];

if ( strpos( $pwp_page_url, '?') > -1 ) {
  $pwp_page_url = substr( $pwp_page_url, 0, strpos( $pwp_page_url, '?') );
}

// Globals

define( 'PWP_VERSION', '0.1.1' );
define( 'PWP_DEBUG', true );
define( 'PWP_DEV', ( strpos( $pwp_page_url, '.test') !== false || strpos( $pwp_page_url, 'localhost') !== false ) );
